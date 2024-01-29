<?php
include __DIR__ . '/model/UsersDB.php';
    $error = "";
    if (isset($_POST['login'])) {
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');

        $admin = login($username, $password);

        if(count($admin)>0){
            echo 'HELLO';
            session_start();
            $_SESSION['user']=$username;
            header('Location: viewBooks.php');
        }else{
            session_unset(); 
            $error = "Incorrect Username or Password!";
        }

    }
    else{
        $username = '';
        $password = '';
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
    <form name="login_form" method="post">
        <h2>Admin Login</h2>
        
        <!--FORM-->

        <div class="row justify-content-center">

            <div class="col-sm text-center">
                <div class="label">
                    <label>Username:</label>
                </div>
                <div>
                    <input type="text" name="username" value="<?= $username; ?>" />
                </div>
            </div>
        </div>

        <div class="row justify-content-center">

            <div class="col-sm text-center">
                <div class="label">
                    <label>Password:</label>
                </div>
                <div>
                    <input type="password" name="password" value="<?= $password; ?>" />
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
</body>
</html>