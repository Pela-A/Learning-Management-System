<?php 

    include __DIR__ . '/../include/functions.php';

    session_start();

    //Redirects to Home Page if session variable does not exist
    if(!isset($_SESSION['userID'])){
        
        header('Location: logout.php');
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
    <link rel="stylesheet" href="..\assets\css\main.css">
    <link rel="stylesheet" href="..\assets\css\header.css">

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

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
