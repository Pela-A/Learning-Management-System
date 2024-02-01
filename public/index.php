<?php
include __DIR__ . '/../model/UsersDB.php';
include __DIR__ . '/../model/OrganizationsDB.php';

//binary search algorithm fastest if given a sorted array.
function binarySearch($arr, $target) {
    $left = 0;
    $right = count($arr) - 1;
    while ($left <= $right) {
        $mid = floor(($left + $right) / 2);
        // Check if the target value is found at the middle index
        if ($arr[$mid] === $target) {
            echo("found");
            return true;
        }
       // If the target is greater, ignore the left half
       if ($arr[$mid] < $target) {
          $left = $mid + 1;
       }
       // If the target is smaller, ignore the right half
       else {
          $right = $mid - 1;
       }
    }
    // Target value not found in the array
    echo("target not found");
    return false;
}

    $error = "";
    if (isset($_POST['login'])) {
        //no need to validate input
        $userArray = array('username' => filter_input(INPUT_POST, 'username'), 'password' => filter_input(INPUT_POST, 'password') );
        $user = new UserDB($userArray);

        


        $admin = $user->login();

        if(count($admin)>0){
            echo 'HELLO';
            session_start();
            $_SESSION['user']=$username;
            header('Location: ../private/test.php');
        }else{
            session_unset(); 
            $error = "Incorrect Username or Password!";
        }

    }
    else{
        $userArray = array('username' => '', 'password' => '');
        $user = new UserDB($userArray);
    }

    if (isset($_POST['create'])){

        //validate input information using either built in php stuff or regEx for now we assume everything is normal
        
        
        //account information
        $firstName = filter_input(INPUT_POST, 'firstName');
        $lastName = filter_input(INPUT_POST, 'lastName');
        $phoneNum = filter_input(INPUT_POST, 'phoneNum');
        $birthday = filter_input(INPUT_POST, 'birthday');
        $gender = filter_input(INPUT_POST, 'gender');
        $newUser = filter_input(INPUT_POST, 'newUser');
        $newPass = filter_input(INPUT_POST, 'newPass');

        //org information
        $orgName = filter_input(INPUT_POST, 'orgName');
        $address = filter_input(INPUT_POST, 'address');
        $city = filter_input(INPUT_POST, 'city');
        $state = filter_input(INPUT_POST, 'state');
        $zipCode = filter_input(INPUT_POST, 'zipCode');


        //after submitting the create form, if everything is valid we must create a new organization using the inputted info. then user and assign the orgID.
        //_________________________
        //creation of user object in database




        //creation of org

        
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

        echo($randomString);
        echo('this worked');
        //$organization->addOrganization();

        //code here to create organization.

        $organization->addOrganization();

        

        //redirect to landing page
        session_start();
        $_SESSION['user']=$username;
        header('Location: ../private/landingPage.php');
    }

    //if trying to join organization.
    if(isset($_POST['join'])){

    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
</head>
<body>
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

    <h2>Create Organization Form</h2>
    <form name="create_org_form" method="post">
            <h3>Enter Your Information</h3>

            <div class="row">
                <label>First Name:</label>
                <input type="text" name="firstName">
            </div>

            <div class="row">
                <label>Last Name:</label>
                <input type="text" name="lastName">
            </div>

            <div class="row">
                <label>Phone Number:</label>
                <input type="text" name="phoneNum">
            </div>
            
            <div class="row">
                <label>Birthday:</label>
                <input type="date" name="birthday">
            </div>
            
            <div class="row">
                <label>Gender:</label>
                <input type="radio" value="1" name="gender"> Male
                <input type="radio" value="0" name="gender"> Female
                <br />
            </div>
            
            <div class="row">
                <label>Create Username:</label>
                <input type="text" name="newUser">
            </div>
            
            <div class="row">
                <label>Create Password:</label>
                <input type="text" name="newPass">
            </div>
            
            <h3>Enter Organization Information</h3>

            <div class="row">
                <label>Organization Name</label>
                <input type="text" name="orgName">
            </div>
            
            <div class="row">
                <label>Address</label>
                <input type="text" name="address">
            </div>
            
            <div class="row">
                <label>City</label>
                <input type="text" name="city">
            </div>
            
            <div class="row">
                <label>State</label>
                <input type="text" name="state">
            </div>
            
            <div class="row">
                <label>Zip Code</label>
                <input type="text" name="zipCode">
            </div>
            
            <div class="row">
                <input type="submit" name="create" value="Create Organization">
            </div>
            
            

    </form>

    <h2>Join Organization Form</h2>
    <form name="join_org_form" method="post">
        <h3>Enter Your Information</h3>

        <div class="row">
            <label>First Name:</label>
            <input type="text" name="firstName">
        </div>

        <div class="row">
            <label>Last Name:</label>
            <input type="text" name="lastName">
        </div>

        <div class="row">
            <label>Phone Number:</label>
            <input type="text" name="phoneNum">
        </div>

        <div class="row">
            <label>Birthday:</label>
            <input type="date" name="birthday">
        </div>

        <div class="row">
            <label>Gender:</label>
            <input type="radio" value="1" name="gender"> Male
            <input type="radio" value="0" name="gender"> Female
            <br />
        </div>

        <div class="row">
            <label>Create Username:</label>
            <input type="text" name="newUser">
        </div>

        <div class="row">
            <label>Create Password:</label>
            <input type="text" name="newPass">
        </div>

        <div class="row">
            <label>Enter Organization Code</label>
            <input type="text" name="orgCode">
        </div>

        <div class="row">
            <input type="submit" name="join" value="Join Organization">
        </div>


    </form>
</body>
</html>