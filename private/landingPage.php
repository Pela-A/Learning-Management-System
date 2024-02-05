<?php

include __DIR__ . '/../include/header.php';
include __DIR__ . '/../include/functions.php';

//start session
session_start();

//if userID not in session variable return to home page
if(!isset($_SESSION['userID'])){
    header('Location: logout.php');
}

include __DIR__ . '/../model/UsersDB.php';

//we need to grab all the user information at this point using the session userID
$tempOBJ = new UserDB();
$user = $tempOBJ->getUser($_SESSION['userID']);

//if user not verified banish them to the home page
if(!$user[0]['isVerified']){
    header('Location: ../public/index.php');
}


//this pages content will load dynamically based on admin rights (tinyint isSiteAdmin, isOrgAdmin, isTrainer)
//we should not load training for site admin.
//everyone else gets training.
//

$landingType = "General User";
if($user[0]['isSiteAdmin']){
    //load site admin control panel
    $landingType = "Website Admin";
}else{
    //load personal training into side bar

}

if($user[0]['isOrgAdmin']){
    //load org admin control panel into side bar
    $landingType = "Organization Admin";
}elseif($user[0]['isTrainer']){
    //load trainer control panel into side bar
    $landingType = "Training Manager";
}

$name = $user[0]['firstName'] . " " . $user[0]['lastName'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <div class="nav">
        <h2><?=$landingType;?> Landing Page</h2>

        <?php if($user[0]['isSiteAdmin']): ?>
            <h3>Site Admin Control Panel</h3>
            <a href="loginAttempt.php">Login Dashboard</a>
            <a href="validateJoin.php">Validiate New Users</a>
            <a href="editUserAccount.php">Modify Existing Users</a>
            <a href="editDepartment.php">Modify Departments</a>
            <a href="editJobCode.php">Modify Job Codes</a>
            <a href="editOrganization.php">Modify Organizations</a>
        <?php else: ?>
            <h3>Personal Training</h3>
            <a href="trainingModuleViewer.php">View Training Modules</a>
            <a href="enterTraining.php">Enter Training</a>
            <a href="pastTraining.php">View Past Training</a>
        <?php endif;?>

        <?php if($user[0]['isOrgAdmin']): ?>
            <h3>Organization Admin Control Panel</h3>
            <a href="loginAttempt.php">Login Dashboard</a>
            <a href="validateJoin.php">Validiate New Users</a>
            <a href="editUserAccount.php">Modify Existing Users</a>
            <a href="editDepartment.php">Modify Departments</a>
            <a href="editJobCode.php">Modify Job Codes</a>
        <?php elseif($user[0]['isTrainer']): ?>
            <h3>Training Manager Control Panel</h3>
            <a href="trainingValidationViewer.php">Validate Training</a>
            <a href="editTrainingModule.php">Create New Training</a>
        <?php endif; ?>

    </div>

    <div class="content">

            <p>main content goes here</p>
    </div>

<?php include __DIR__ . '/../include/footer.php'; ?>