<?php
    //includes at top
    include __DIR__ . '/../model/UsersDB.php';
    include __DIR__ . '/../model/OrganizationsDB.php';
    include __DIR__ . '/../model/LoginAttemptsDB.php';
    include __DIR__ . '/../include/functions.php';

    //initialize error variable and action
    $error = "";
    $action = "";

    //used to change visible form
    //################### ADD NOT VERIFIED PAGE
    if(isset($_GET['action'])){
        $action = filter_input(INPUT_GET, 'action');
    }

    //logging in form post
    if (isset($_POST['login'])) {
    
        $username = filter_input(INPUT_POST, 'username'); 
        $password = filter_input(INPUT_POST, 'password');

        try {
            //create user object
            $userDB = new UserDB();
            $userData = $userDB->login($username, $password);

            //if results found create session vars and try to redirect
            if($userData != "No Results Found"){
    
                $loginDB = new LoginDB();
                $loginDate = date('Y-m-d H:i:s');
                $ip = getenv("REMOTE_ADDR");
                if($userData['isVerified'] == 0){
                    //login attempt funct with isSuccessful False (0)
                    $loginDB->addLoginAttempt($userData['userID'], $loginDate, 0, $ip);

                    //redirect to landing page
                    header('Location: index.php?action=notVerified');
                }else{
                    //call setSessionLogin
                    setSessionLogin($userData);

                    //login attempt funct with isSuccessful True (1)
                    $loginDB->addLoginAttempt($userData['userID'], $loginDate, 1, $ip);

                    //redirect to landing page
                    header('Location: ../private/landingPage.php');
                }   
            }else{
                $error = "Incorrect Username or Password!";
                if(linear_search($userDB->getAllUsername(), $username)){
                    $loginDate = date('Y-m-d H:i:s');
                    $ip = getenv("REMOTE_ADDR");
                    $loginDB = new LoginDB();
                    $loginDB->addLoginAttempt($userDB->getUserID($username), $loginDate, 0, $ip);
                }
            }
            
        } catch (Exception $error) {
            //unset session variables and give error
            echo "<h2>" . $error->getMessage() . "</h2>";
        }
    //Create organization
    }elseif(isset($_POST['create'])){

        //post entered user information
        $firstName = filter_input(INPUT_POST, 'firstName');
        $lastName = filter_input(INPUT_POST, 'lastName');
        $phoneNum = filter_input(INPUT_POST, 'phoneNum');
        $email = filter_input(INPUT_POST, 'email');
        $birthdate = filter_input(INPUT_POST, 'birthdate');
        $gender = filter_input(INPUT_POST, 'gender');
        $newPass = filter_input(INPUT_POST, 'newPass');
        $confirmPass = filter_input(INPUT_POST, 'confirmPass');

        //verifyUserInformation
        $error = verifyUserInformation($firstName,$lastName,$phoneNum,$email,$birthdate,$gender,$newPass,$confirmPass);
        
        //post entered org information
        $orgName = filter_input(INPUT_POST, 'orgName');
        $address = filter_input(INPUT_POST, 'address');
        $city = filter_input(INPUT_POST, 'city');
        $state = filter_input(INPUT_POST, 'state');
        $zipCode = filter_input(INPUT_POST, 'zipCode');

        $enterOrgCode = "";

        if($orgName == ""){
            $error .= "<li>Please enter an organization name!";
        }
        if($address == ""){
            $error .= "<li>Please enter an organization address!";
        }
        if($city == ""){
            $error .= "<li>Please enter an organization city!";
        }

        //zipcode verification
        $zipPattern = "/^[0-9]{5}$/";
        if(!preg_match($zipPattern, $zipCode)){
            $error .= "<li>Please enter a five digit zipcode for your organization!";
        }
        
        //If no errors, create organization and assign user to Org as OrgAdmin
        if($error == ""){
            //we want to create orgCodes until the code is not already in the database
            //this means we need to pull all orgCodes and compare the newly created to them all
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
                //search for random string using linear search
                //check at end if another loop needs to happen. if we return zero that means we found that org code in db
            } while (linear_Search($codes, $randomString));

            //create organization object
            $organization = new OrganizationDB();

            //code here to create organization
            //newID represents last inserted record (created organization)
            $newID = $organization->createOrganization($orgName,$address,$city,$state,$zipCode,$randomString);

            //create USER Object and add to data base
            $makeUser = new UserDB();
            $newUserID=$makeUser->orgAdminCreateUser($newID, $firstName, $lastName, $email, $birthdate, $phoneNum, $gender, $newPass, 1, 0);
            $newUserData = $makeUser->getUser($newUserID);

            //call session set function. then redirect to landing page
            setSessionLogin($newUserData);

            //log their loginAttempt
            $loginDB = new LoginDB();
            $loginDate = date('Y-m-d H:i:s');
            $ip = getenv("REMOTE_ADDR");
            $loginDB->addLoginAttempt($newUserData['userID'], $loginDate, 1, $ip);

            
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
        $newPass = filter_input(INPUT_POST, 'newPass');
        $confirmPass = filter_input(INPUT_POST, 'confirmPass');

        $error = verifyUserInformation($firstName,$lastName,$phoneNum,$email,$birthdate,$gender,$newPass,$confirmPass);
        
        $orgName = "";
        $address = "";
        $city = "";
        $state = "";
        $zipCode = "";

        $enterOrgCode = filter_input(INPUT_POST, 'orgCode');

        $orgObj = new OrganizationDB();
        $code = $orgObj->getAllOrgCodes();
        

        //if org code found and no errors for input we create user and send them to not verified. Page
        if(linear_Search($code, $enterOrgCode)){

            if($error == ""){
                //get orgID to join on
                $joinID = $orgObj -> getOrgID($enterOrgCode);
                $makeUser = new UserDB();
        
                //create new user
                $profilePicture = "";
                $newUserID = $makeUser->createGeneralUser($joinID,$firstName,$lastName,$email,$birthdate,$phoneNum,$gender,$newPass, $profilePicture);

                //send login attempt and redirect to not verified page
                $loginDB = new LoginDB();
                $loginDate = date('Y-m-d H:i:s');
                $ip = getenv("REMOTE_ADDR");
                $loginDB->addLoginAttempt($newUserID, $loginDate, 0, $ip);
                header('Location: index.php?action=notVerified');
            
            }
            
        }
        else{
            $error .= "<li>There is no organization with that Code!";
        }
        
        
    //first time loading to site initialize variables for sticky fields in forms
    }else{
        $username = "";
        $password = "";
        $firstName = "";
        $lastName = "";
        $phoneNum = "";
        $email = "";
        $birthdate = "";
        $gender = "";
        $newPass = "";
        $confirmPass = "";
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
    <div class="container">

        <div class="row">
            <h2>Welcome to 'ATLAS'</h2>


        </div>

        <div class="row">
            <p>This is our LMS which organizations can use to manage and track training!</p>
        </div>

        <div class="row">
            <img src="" alt="No Image :(">
        </div>



        <?php if($action == ''): ?>
            <h2>Login Form</h2>

            <?php if($error != ""):?>
                <div class="row">

                    <div class="col-sm">
                        <div class="error">
                            <?php echo($error); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <form name="login_form" method="post" class="px-4 py-3">
                <div class="form-group">
                    <label>Username</label>
                    <input name="username" type="text" class="form-control" placeholder="Username" value="<?=$username?>">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input name="password" type="password" class="form-control" placeholder="Password" value="<?=$password?>">
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" >
                    <label class="form-check-label">Remember me</label>
                </div>
                <input name="login" type="submit" value="Sign in" class="btn btn-primary" style="margin-top: 5px;"></input>
            </form>

            <a href="index.php?action=createOrg">Register Organization</a>
            <a href="index.php?action=joinOrg">Join Organization</a>

        <?php elseif($action == 'createOrg'): ?>
            <h2>Create Organization Form</h2>
            <form name="create_org_form" method="post">
                <h3>Enter Your Information</h3>

                <?php if($error != ""):?>
                    <div class="row">

                        <div class="col-sm">
                            <div class="error">
                                <?php echo($error); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

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
                    <input type="radio" value="1" name="gender" <?php if($gender==TRUE) echo('checked');?>> Male
                    <input type="radio" value="0" name="gender"<?php if($gender==FALSE) echo('checked');?>> Female
                    <br />
                </div>
                
                <div class="row">
                    <label>Create Password:</label>
                    <input type="text" name="newPass" value="<?=$newPass?>">
                </div>

                <div class="row">
                    <label>Confirm Password:</label>
                    <input type="text" name="confirmPass" value="<?=$confirmPass?>">
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
                    <select class="form-control text-secondary col-md-4" style="height: 40px;" type="text" name="state" selected="<?=$state?>" required >
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

                <?php if($error != ""):?>
                    <div class="row">

                        <div class="col-sm">
                            <div class="error">
                                <?php echo($error); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

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
                    <input type="radio" value="1" name="gender" <?php if($gender=="1") echo('checked');?>> Male
                    <input type="radio" value="0" name="gender"<?php if($gender=="0") echo('checked');?>> Female
                    <br />
                </div>

                <div class="row">
                    <label>Create Password:</label>
                    <input type="text" name="newPass" value="<?=$newPass?>">
                </div>

                <div class="row">
                    <label>Confirm Password:</label>
                    <input type="text" name="confirmPass" value="<?=$confirmPass?>">
                </div>

                <div class="row">
                    <label>Enter Organization Code</label>
                    <input type="text" name="orgCode" value="<?=$enterOrgCode?>">
                </div>

                <div class="row">
                    <input type="submit" name="join" value="Join Organization">
                </div>


            </form>

        <?php elseif($action == 'notVerified'): ?>
            <h2>Your account is current not verified!</h2>

            <p>Please contact your organization administrator!</p>

        <?php endif; ?>
        <?php include __DIR__ . '/../include/footer.php'; ?>

    </div>

</head>
<body>
