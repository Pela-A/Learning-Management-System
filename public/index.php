<?php
//includes at top
include __DIR__ . '/../model/UsersDB.php';
include __DIR__ . '/../model/OrganizationsDB.php';
include __DIR__ . '/../include/functions.php';

//initialize error variable
$error = "";
$action = "";

if(isset($_GET['action'])){
    $action = filter_input(INPUT_GET, 'action');
}

//logging in form post
if (isset($_POST['login'])) {
    //no need to validate input
    $userArray = array('username' => filter_input(INPUT_POST, 'username'), 'password' => filter_input(INPUT_POST, 'password') );
    $user = new UserDB($userArray);

    //array result of logging in
    $admin = $user->login();

    var_dump($admin);
    //if a result was found redirect
    if(count($admin)>0){
        //start session with user object stored in session variable
        session_start();
        
        //currently storing entire user in session variable !!!!!!!!!!!!!!!!!!!
        $_SESSION['userID']=$admin['userID'];
        
        header('Location: ../private/landingPage.php');
    }else{
        //unset session variables and give error
        session_unset(); 
        $error = "Incorrect Username or Password!";
    }

}else{
    $userArray = array('username' => '', 'password' => '');
    $user = new UserDB($userArray);
}
//creating org
if(isset($_POST['create'])){

    $firstName = filter_input(INPUT_POST, 'firstName');
    $lastName = filter_input(INPUT_POST, 'lastName');
    $phoneNum = filter_input(INPUT_POST, 'phoneNum');
    $email = filter_input(INPUT_POST, 'email');
    $birthdate = filter_input(INPUT_POST, 'birthdate');
    $gender = filter_input(INPUT_POST, 'gender');
    $newUser = filter_input(INPUT_POST, 'newUser');
    $newPass = filter_input(INPUT_POST, 'newPass');

    $error = verifyUserInformation($firstName,$lastName,$phoneNum,$email,$birthdate,$gender,$newUser,$newPass);
    
    //org information
    $orgName = filter_input(INPUT_POST, 'orgName');
    $address = filter_input(INPUT_POST, 'address');
    $city = filter_input(INPUT_POST, 'city');
    $state = filter_input(INPUT_POST, 'state');
    $zipCode = filter_input(INPUT_POST, 'zipCode');

    $enterOrgCode = "";

    if($orgName == ""){
        $error .= "<li>Please enter an organization name!";
    }

    //ASK SCOTT ABOUT THE ABILITY TO USE AN API FOR SELECTING ADDRESS, CITY, STATE, ZIPCODE ***********************

    //If no errors, create organization and assign user to Org as OrgAdmin
    if($error == ""){
        

        
        //we want to create orgCodes until the code is not already in the database
        //this means we need to pull all orgCodes
        $tempObj = new OrganizationDB();
        $codes = $tempObj->getAllOrgCodes();
        do {
            //random orgCode creation of length 20
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < 20; $i++) {
                $randomString .= $characters[random_int(0, $charactersLength - 1)];
            }

            //search for random string using binary search
            //check at end if another loop needs to happen. if we return zero that means we found that org code in db.
        } while (binarySearch($codes, $randomString));

        $orgArray = array('orgName' => $orgName, 'orgAddress' => $address, 'orgCity' => $city, 'orgState' => $state, 'orgZip' => $zipCode, 'orgCode' => $randomString );
        $organization = new OrganizationDB($orgArray);


        //code here to create organization.

        //newID represents last inserted record (created organization)
        //#### ASK SCOTT IF IT SOMEHOW RETURNS ZERO WHAT TO DO.
        $newID = $organization->createOrganization();

        //create USER Object and add to data base
        $makeUser = new UserDB(array('orgID'=>$newID, 'firstName' => $firstName, 'lastName' => $lastName, 'phoneNumber' => $phoneNum, 'email' => $email, 'birthdate' => $birthdate, 'gender' => $gender, 'letterDate' => date('Y-m-d'), 'username' => $newUser, 'password' => $newPass, 'isOrgAdmin' => 1, 'isVerified' => 1));

        session_start();
        $_SESSION['userID']=$makeUser->createUser();

        //redirect to landing page
        header('Location: ../private/landingPage.php');

    }

//if trying to join organization.
}elseif(isset($_POST['join'])){

    //account information
    $firstName = filter_input(INPUT_POST, 'firstName');
    $lastName = filter_input(INPUT_POST, 'lastName');
    $phoneNum = filter_input(INPUT_POST, 'phoneNum');
    $email = filter_input(INPUT_POST, 'email');
    $birthdate = filter_input(INPUT_POST, 'birthdate');
    $gender = filter_input(INPUT_POST, 'gender');
    $newUser = filter_input(INPUT_POST, 'newUser');
    $newPass = filter_input(INPUT_POST, 'newPass');

    $orgName = "";
    $address = "";
    $city = "";
    $state = "";
    $zipCode = "";

    $error = verifyUserInformation($firstName,$lastName,$phoneNum,$email,$birthdate,$gender,$newUser,$newPass);

    $enterOrgCode = filter_input(INPUT_POST, 'orgCode');


    echo("got here");
    $tempObj = new OrganizationDB(array('orgCode' => $enterOrgCode));
    if(binarySearch($tempObj->getAllOrgCodes(), $enterOrgCode)){
        $joinID = $tempObj -> getOrgID();
        var_dump ($joinID);
        $makeUser = new UserDB(array('orgID'=>$joinID, 'firstName' => $firstName, 'lastName' => $lastName, 'phoneNumber' => $phoneNum, 'email' => $email, 'birthdate' => $birthdate, 'gender' => $gender, 'letterDate' => date('Y-m-d'), 'username' => $newUser, 'password' => $newPass, 'isOrgAdmin' => 0, 'isVerified' => 0));
        session_start();
        $_SESSION['userID']=$makeUser->createUser();

        //redirect to landing page
    
        header('Location: ../private/landingPage.php');
    }
    else{
        $error .= "<li>There is no organization with that Code!";
    }
    
    //if the org code is in the database we should join the user on that orgID
    //this means we must grab the orgID
    


//first time loading to site
}else{
    $firstName = "";
    $lastName = "";
    $phoneNum = "";
    $email = "";
    $birthdate = "";
    $gender = "";
    $newUser = "";
    $newPass = "";
    $orgName = "";
    $address = "";
    $city = "";
    $state = "";
    $zipCode = "";
    $enterOrgCode = "";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Home Page</title>
</head>
<body>

    <?php if($action == ''): ?>
        <h2>Login Form</h2>
        <form name="login_form" method="post">
            <!--FORM-->

            <div class="row justify-content-center">

                <div class="col-sm text-center">
                    <div class="label">
                        <label>Username:</label>
                    </div>
                    <div>
                        <input type="text" name="username" value="<?= $user->getUsername(); ?>" />
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">

                <div class="col-sm text-center">
                    <div class="label">
                        <label>Password:</label>
                    </div>
                    <div>
                        <input type="password" name="password" value="<?= $user->getPassword(); ?>" />
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">

                <div class="col-sm text-center">
                    <div>
                        &nbsp;
                    </div>
                    <div>
                        <input type="submit" name="login" value="Login" />
                    </div>
                </div>
            </div>

            <?php if($error != ""):?>
                <div class="row justify-content-center">

                    <div class="col-sm text-center">
                        <div class="error">
                            <?php echo($error); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            
        </form>

        <a href="index.php?action=createOrg">Register Organization</a>
        <a href="index.php?action=joinOrg">Join Organization</a>

    <?php elseif($action == 'createOrg'): ?>
        <h2>Create Organization Form</h2>
        <form name="create_org_form" method="post">
            <h3>Enter Your Information</h3>

            <div class="row">
                <label>First Name:</label>
                <input type="text" name="firstName" value="<?=$firstName?>">
            </div>

            <div class="row">
                <label>Last Name:</label>
                <input type="text" name="lastName" value="<?=$lastName?>">
            </div>

            <div class="row">
                <label>Phone Number:</label>
                <input type="text" name="phoneNum" value="<?=$phoneNum?>">
            </div>

            <div class="row">
                <label>Email:</label>
                <input type="text" name="email" value="<?=$email?>">
            </div>
            
            <div class="row">
                <label>Birthday:</label>
                <input type="date" name="birthdate" value="<?=$birthdate?>">
            </div>
            
            <div class="row">
                <label>Gender:</label>
                <input type="radio" value="Male" name="gender" <?php if($gender=="Male") echo('checked');?>> Male
                <input type="radio" value="Female" name="gender"<?php if($gender=="Female") echo('checked');?>> Female
                <br />
            </div>
            
            <div class="row">
                <label>Create Username:</label>
                <input type="text" name="newUser" value="<?=$newUser?>">
            </div>
            
            <div class="row">
                <label>Create Password:</label>
                <input type="text" name="newPass" value="<?=$newPass?>">
            </div>
            
            <h3>Enter Organization Information</h3>

            <div class="row">
                <label>Organization Name</label>
                <input type="text" name="orgName" value="<?=$orgName?>">
            </div>
            
            <div class="row">
                <label>Address</label>
                <input type="text" name="address" value="<?=$address?>">
            </div>
            
            <div class="row">
                <label>City</label>
                <input type="text" name="city" value="<?=$city?>">
            </div>
            
            <div class="row">
                <label>State</label>
                <select class="form-control text-secondary col-md-4" style="height: 40px;" type="text" name="state" required>
                    <option value="">State</option>
                    <option value="AL">Alabama</option>
                    <option value="AK">Alaska</option>
                    <option value="AZ">Arizona</option>
                    <option value="AR">Arkansas</option>
                    <option value="CA">California</option>
                    <option value="CO">Colorado</option>
                    <option value="CT">Connecticut</option>
                    <option value="DE">Delaware</option>
                    <option value="FL">Florida</option>
                    <option value="GA">Georgia</option>
                    <option value="HI">Hawaii</option>
                    <option value="ID">Idaho</option>
                    <option value="IL">Illinois</option>
                    <option value="IN">Indiana</option>
                    <option value="IA">Iowa</option>
                    <option value="KS">Kansas</option>
                    <option value="KY">Kentucky</option>
                    <option value="LA">Louisiana</option>
                    <option value="ME">Maine</option>
                    <option value="MD">Maryland</option>
                    <option value="MA">Massachusetts</option>
                    <option value="MI">Michigan</option>
                    <option value="MN">Minnesota</option>
                    <option value="MS">Mississippi</option>
                    <option value="MO">Missouri</option>
                    <option value="MT">Montana</option>
                    <option value="NE">Nebraska</option>
                    <option value="NV">Nevada</option>
                    <option value="NH">New Hampshire</option>
                    <option value="NJ">New Jersey</option>
                    <option value="NM">New Mexico</option>
                    <option value="NY">New York</option>
                    <option value="NC">North Carolina</option>
                    <option value="ND">North Dakota</option>
                    <option value="OH">Ohio</option>
                    <option value="OK">Oklahoma</option>
                    <option value="OR">Oregon</option>
                    <option value="PA">Pennsylvania</option>
                    <option value="RI">Rhode Island</option>
                    <option value="SC">South Carolina</option>
                    <option value="SD">South Dakota</option>
                    <option value="TN">Tennessee</option>
                    <option value="TX">Texas</option>
                    <option value="UT">Utah</option>
                    <option value="VT">Vermont</option>
                    <option value="VA">Virginia</option>
                    <option value="WA">Washington</option>
                    <option value="WV">West Virginia</option>
                    <option value="WI">Wisconsin</option>
                    <option value="WY">Wyoming</option>
                </select>
            </div>
            
            <div class="row">
                <label>Zip Code</label>
                <input type="text" name="zipCode" value="<?=$zipCode?>">
            </div>
            
            <div class="row">
                <input type="submit" name="create" value="Create Organization">
            </div>
        </form>

    <?php elseif($action == 'joinOrg'): ?>
        <h2>Join Organization Form</h2>
        <form name="join_org_form" method="post">
            <h3>Enter Your Information</h3>

            <div class="row">
                <label>First Name:</label>
                <input type="text" name="firstName" value="<?=$firstName?>">
            </div>

            <div class="row">
                <label>Last Name:</label>
                <input type="text" name="lastName" value="<?=$lastName?>">
            </div>

            <div class="row">
                <label>Phone Number:</label>
                <input type="text" name="phoneNum" value="<?=$phoneNum?>">
            </div>

            <div class="row">
                <label>Email:</label>
                <input type="text" name="email" value="<?=$email?>">
            </div>

            <div class="row">
                <label>Birthday:</label>
                <input type="date" name="birthdate" value="<?=$birthdate?>">
            </div>

            <div class="row">
                <label>Gender:</label>
                <input type="radio" value="Male" name="gender" <?php if($gender=="Male") echo('checked');?>> Male
                <input type="radio" value="Female" name="gender"<?php if($gender=="Female") echo('checked');?>> Female
                <br />
            </div>

            <div class="row">
                <label>Create Username:</label>
                <input type="text" name="newUser" value="<?=$newUser?>">
            </div>

            <div class="row">
                <label>Create Password:</label>
                <input type="text" name="newPass" value="<?=$newPass?>">
            </div>

            <div class="row">
                <label>Enter Organization Code</label>
                <input type="text" name="orgCode" value="<?=$enterOrgCode?>">
            </div>

            <div class="row">
                <input type="submit" name="join" value="Join Organization">
            </div>


        </form>

    <?php endif; ?>

<?php include __DIR__ . '/../include/footer.php'; ?>

</head>
<body>
