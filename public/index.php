<?php
include __DIR__ . '/../model/UsersDB.php';
    $error = "";
    if (isset($_POST['login'])) {
        $user = new UserDB();
        $user->username = filter_input(INPUT_POST, 'username');
        $user->password = filter_input(INPUT_POST, 'password');

        $admin = $user->login($user->username, $user->password);

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
        $user = new UserDB();
        $user->username = '';
        $user->password = '';
    }

    if (isset($_POST['create'])){
        //validate input information using either built in php stuff or regEx for now we assume everything is normal


        //after submitting the create form, if everything is valid we must create a new organization using the inputted info

    }

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
                    <input type="text" name="username" value="<?= $user->username; ?>" />
                </div>
            </div>
        </div>

        <div class="row justify-content-center">

            <div class="col-sm text-center">
                <div class="label">
                    <label>Password:</label>
                </div>
                <div>
                    <input type="password" name="password" value="<?= $user->password; ?>" />
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
                <input type="text" name="createOrgName">
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