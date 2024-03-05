<?php

    include __DIR__ . '/../include/header.php';

    if(!isset($_SESSION['userID'])){
        header('Location: logout.php');
    }

    if(isset($_GET['action'])){
        $action = filter_input(INPUT_GET, 'action');
    }

    if(isset($_GET['orgID'])){
        $_SESSION['orgID'] = filter_input(INPUT_GET, 'orgID');
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
    <link rel="stylesheet" href="..\assets\css\main.css">
    <link rel="stylesheet" href="..\assets\css\orgControlPanel.css">

    <title>Admin Controller</title>
</head>
<body>
    
    <div class="mainContent" style="display: flex;">
        <?php include __DIR__ . '/../include/aside.php'; ?>

        <div class="pageContent container-fluid">
            <h3>Organization Control Panel</h3>
            <ul id="controlPanel">
                <li><a class="controlPanelWidget" href="userAccount.php?action=Viewer&orgID=<?= $_SESSION['orgID']; ?>">User Manager</a></li>
                <li><a class="controlPanelWidget" href="departments.php?action=Viewer&orgID=<?= $_SESSION['orgID']; ?>">Department Manager</a></li>
                <li><a class="controlPanelWidget" href="organizations.php?action=Edit&orgID=<?= $_SESSION['orgID']; ?>">Organization Manager</a></li>
                <li><a class="controlPanelWidget" href="loginAttempts.php?action=Viewer&orgID=<?= $_SESSION['orgID']; ?>">Login Attempts Manager</a></li>
                <li><a class="controlPanelWidget" href="userAccount.php?action=Validator&orgID=<?= $_SESSION['orgID']; ?>">Validiate New Users</a></li>
            </ul>
        </div>
    </div>

<?php include __DIR__ . '/../include/footer.php'; ?>