<?php 

    include __DIR__ . '/../include/functions.php';

    session_start();

    header("Content-Security-Policy: default-src 'self'; script-src 'self' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; object-src 'none'; frame-ancestors 'none';");


    // Redirect if session does not exist
    if (!isset($_SESSION['userID'])) {
        header('Location: logout.php');
        exit;
    }

    // Expire session after 30 minutes of inactivity
    if (isset($_SESSION['SESSION_CREATED']) && (time() - $_SESSION['SESSION_CREATED'] > 1800)) {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit;
    }
    $_SESSION['SESSION_CREATED'] = time(); // Reset timer on activity

    // Prevent session hijacking
    if (!isset($_SESSION['user_agent'])) {
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    } elseif ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit;
    }

    //Establishes landingType Session variable dependant on admin status
    if($_SESSION['isSiteAdmin'] == True){
        $_SESSION['landingType'] = "Website Admin";
    } elseif($_SESSION['isOrgAdmin'] == True) {
        $_SESSION['landingType'] = "Organization Admin";
    } elseif ($_SESSION['isTrainer'] == True) {
        $_SESSION['landingType'] = "Training Manager";
    } else {
        $_SESSION['landingType'] = 'General';
    }

    //Concatenates name variables into fullname
    $_SESSION['fullName'] = $_SESSION['firstName'] . " " . $_SESSION['lastName'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="..\assets\css\header.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>



    <title>Document</title>
</head>
<body>

    <nav class="navbar navbar-expand-lg px-1" id="header" style="width: 100%; justify-content: space-between;">
        
        <div style="display:flex;">
            <img src="..\assets\images\atlasPhotos\ATLAS_Logo.png" alt="ATLAS Logo" style="max-height: 40px;">
            <a class="navbar-brand text-light" href="landingPage.php">ATLAS</a>
        </div>

        <div style="display: flex;">
            <ul style="margin: 0px;">
                <li class="nav-item dropdown" style="display:flex;">
                    
                    <img style="height: 30px;" class="m-auto pl-3" src="<?=$_SESSION['profilePicture']; ?>" alt="">
                    
                    <a class="nav-link mx-2 active dropdown-toggle text-light" href="#" id="assignmentsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Welcome <?=$_SESSION['firstName']; ?></a>
                    <div class="dropdown-menu bg-dark" style="width: 200px; font-size: 20px; margin-left: 12px; margin-top: 7px;" aria-labelledby="assignmentsDropdown">
                        <a class="dropdown-item" href="userAccount.php?action=personalSettings"><img src="..\assets\images\atlasPhotos\Settings.png" alt="Settings">Account Settings</a>
                        <a class="dropdown-item" href="userAccount.php?action=changePassword"><img src="..\assets\images\atlasPhotos\ChangePassword.png" alt="Logout">Change Password</a>
                        <a class="dropdown-item" href="logout.php"><img src="..\assets\images\atlasPhotos\LogoutIcon.png" alt="Logout">Sign out</a>
                    </div>
                </li>
            </ul>
        </div>        
    </nav>           

</body>
</html>

