<?php 

    //

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/aside.css">

    <title>Document</title>
</head>
<body>
    
    <aside>
        <h2><?=$_SESSION['landingType'];?> Landing Page</h2>

        <?php if($_SESSION['isSiteAdmin'] || $_SESSION['isOrgAdmin']): ?>
            <h3>Admin Control Panel</h3>

            <ul>
                <li><a href="userAccount.php">User Manager</a></li>
                <li><a href="departments.php">Department Manager</a></li>
                <li><a href="jobCode.php">Job Code Manager</a></li>
                <li><a href="organizations.php">Organization Manager</a></li>
                <li><a href="loginAttempts.php">Login Attempts Manager</a></li>
                <li><a href="userAccount.php?action=validateUser">Validiate New Users</a></li>
            </ul>

        <?php elseif($_SESSION['isTrainer']): ?>
            <h3>Training Manager Control Panel</h3>
            <ul>
                <li><a href="trainingValidation.php">User Training Validator</a></li>
                <li><a href="trainingModule.php?action=Create">Create New Training Module</a></li>
                <li><a href="trainingModule.php">Training Modules Viewer</a></li>
                <li><a href="trainingEntry.php">Training Entry Viewer</a></li>
            </ul>
            
        <?php endif;?>

        <h3>Personal Training</h3>
        <ul>
            <li><a href="trainingEntry.php?action=Create">Log Training Event</a></li>
            <li><a href="trainingModule.php">Training Modules Viewer</a></li>
            <li><a href="userTraining.php">View Past Training</a></li>
        </ul>

        <h3>Account Settings</h3>
        <h3>Logout</h3>

    </aside>

</body>
</html>