<?php

    include __DIR__ . '/../include/header.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="..\assets\css\main.css">

    <title>Landing Page</title>
</head>
<body>
    
    <div class="mainContent">

        <?php include __DIR__ . '/../include/aside.php'; ?>

        <div class="pageContent container-fluid">

            <?php if($_SESSION['isSiteAdmin']): ?>
                <div class="row">
                    <a href="organizations.php?action=Viewer" class="landingContent mr-3 mb-3" style="height:350px; width:350px;">
                        <h3 style="margin: auto;">Manage Organizations</h3>
                        <img class="landingImage" src="..\assets\images\atlasPhotos\ModifyOrganization.png" alt="">
                    </a>
                    <a href="userAccount.php?action=Viewer" class="col-12 landingContent mr-3 mb-3" style="height:350px; width:350px;">
                        <h3 style="margin: auto;">Manage User Accounts</h3>
                        <img class="landingImage" src="..\assets\images\atlasPhotos\ValidateManageNewUsers.png" alt="">
                    </a>
                    <a href="loginAttempts.php?action=Viewer" class="col-12 landingContent mr-3 mb-3" style="height:350px; width:350px;">
                        <h3 style="margin: auto;">Manage User Accounts</h3>
                        <img class="landingImage" src="..\assets\images\atlasPhotos\LoginDashboard.png" alt="">
                    </a>
                </div>
            <?php elseif($_SESSION['isOrgAdmin']): ?>
                <div class="row">
                    <a href="organizations.php?action=Edit&orgID=<?= $_SESSION['orgID']; ?>" class="landingContent mr-3 mb-3" style="height:350px; width:350px;">
                        <h3 style="margin: auto;">Modify Organizations</h3>
                        <img class="landingImage" src="..\assets\images\atlasPhotos\ModifyOrganization.png" alt="">
                    </a>
                    <a href="departments.php?action=Viewer&orgID=<?= $_SESSION['orgID']; ?>" class="col-12 landingContent mr-3 mb-3" style="height:350px; width:350px;">
                        <h3 style="margin: auto;">Modify Departments</h3>
                        <img class="landingImage" src="..\assets\images\atlasPhotos\ModifyDepartments.png" alt="">
                    </a>
                    <a href="userAccount.php?action=Viewer&orgID=<?= $_SESSION['orgID']; ?>" class="col-12 landingContent mr-3 mb-3" style="height:350px; width:350px;">
                        <h3 style="margin: auto;">Modify User Accounts</h3>
                        <img class="landingImage" src="..\assets\images\atlasPhotos\LoginDashboard.png" alt="">
                    </a>
                    <a href="userAccount.php?action=Validator&orgID=<?= $_SESSION['orgID']; ?>" class="col-12 landingContent mr-3 mb-3" style="height:350px; width:350px;">
                        <h3 style="margin: auto;">Validate New Users</h3>
                        <img class="landingImage" src="..\assets\images\atlasPhotos\ModifyDepartments.png" alt="">
                    </a>
                    <a href="loginAttempts.php?action=Viewer&orgID=<?= $_SESSION['orgID']; ?>" class="col-12 landingContent mr-3 mb-3" style="height:350px; width:350px;">
                        <h3 style="margin: auto;">Login Dashboard</h3>
                        <img class="landingImage" src="..\assets\images\atlasPhotos\LoginDashboard.png" alt="">
                    </a>
                </div>
            <?php elseif($_SESSION['isTrainer']): ?>
                <div class="row">
                    <a href="trainingEntry.php?action=Validator" class="col-12 landingContent mr-3 mb-3" style="height:350px; width:350px;">
                        <h3 style="margin: auto;">Training Validator</h3>
                        <img class="landingImage" src="..\assets\images\atlasPhotos\ValidateCheckMark.png" alt="">
                    </a>
                    <a href="trainingModules.php?action=Create" class="col-12 landingContent mr-3 mb-3" style="height:350px; width:350px;">
                        <h3 style="margin: auto;">Create New Training Module</h3>
                        <img class="landingImage" src="..\assets\images\atlasPhotos\CreateTraining.png" alt="">
                    </a>
                    <a href="trainingModules.php?action=ViewAll" class="col-12 landingContent mr-3 mb-3" style="height:350px; width:350px;">
                        <h3 style="margin: auto;">Training Modules Viewer</h3>
                        <img class="landingImage" src="..\assets\images\atlasPhotos\ViewTraining.png" alt="">
                    </a>
                    <a href="trainingEntry.php?action=ViewAll" class="col-12 landingContent mr-3 mb-3" style="height:350px; width:350px;">
                        <h3 style="margin: auto;">Training Entry Viewer</h3>
                        <img class="landingImage" src="..\assets\images\atlasPhotos\ModifyDepartments.png" alt="">
                    </a>
                    <a href="trainingEntry.php?action=Create" class="col-12 landingContent mr-3 mb-3" style="height:350px; width:350px;">
                        <h3 style="margin: auto;">Log Training Event</h3>
                        <img class="landingImage" src="..\assets\images\atlasPhotos\EnterTraining.png" alt="">
                    </a>
                    <a href="trainingEntry.php?action=ViewAll&userID=<?= $_SESSION['userID']; ?>" class="col-12 landingContent mr-3 mb-3" style="height:350px; width:350px;">
                        <h3 style="margin: auto;">View Past Training</h3>
                        <img class="landingImage" src="..\assets\images\atlasPhotos\PastTraining.png" alt="">
                    </a>
                    <a href="loginAttempts.php?action=Viewer&orgID=<?= $_SESSION['orgID']; ?>" class="col-12 landingContent mr-3 mb-3" style="height:350px; width:350px;">
                        <h3 style="margin: auto;">Login Dashboard</h3>
                        <img class="landingImage" src="..\assets\images\atlasPhotos\LoginDashboard.png" alt="">
                    </a>
                </div>

            <?php else: ?>
                <div class="row">
                    <a href="trainingModules.php?action=ViewAll" class="col-12 landingContent mr-3 mb-3" style="height:350px; width:350px;">
                        <h3 style="margin: auto;">Training Modules Viewer</h3>
                        <img class="landingImage" src="..\assets\images\atlasPhotos\ViewTraining.png" alt="">
                    </a>
                    <a href="trainingEntry.php?action=Create" class="col-12 landingContent mr-3 mb-3" style="height:350px; width:350px;">
                        <h3 style="margin: auto;">Log Training Event</h3>
                        <img class="landingImage" src="..\assets\images\atlasPhotos\EnterTraining.png" alt="">
                    </a>
                    <a href="trainingEntry.php?action=ViewAll&userID=<?= $_SESSION['userID']; ?>" class="col-12 landingContent mr-3 mb-3" style="height:350px; width:350px;">
                        <h3 style="margin: auto;">View Past Training</h3>
                        <img class="landingImage" src="..\assets\images\atlasPhotos\PastTraining.png" alt="">
                    </a>
                    <a href="loginAttempts.php?action=Viewer&orgID=<?= $_SESSION['orgID']; ?>" class="col-12 landingContent mr-3 mb-3" style="height:350px; width:350px;">
                        <h3 style="margin: auto;">Login Dashboard</h3>
                        <img class="landingImage" src="..\assets\images\atlasPhotos\LoginDashboard.png" alt="">
                    </a>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <?php include __DIR__ . '/../include/footer.php'; ?>

