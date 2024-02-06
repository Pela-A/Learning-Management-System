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

    <title>LMS || Organizations</title>
</head>
<body>
    
    <div style="display: flex;">
        <?php include __DIR__ . '/../include/aside.php'; ?>

        <div id="controlPanel" class="content">
            <h3>Organization Control Panel</h3>
            <ul>
                <li><a href="userAccount.php">User Manager</a></li>
                <li><a href="departments.php">Department Manager</a></li>
                <li><a href="jobCodes.php">Job Code Manager</a></li>
                <li><a href="organizations.php">Organization Manager</a></li>
                <li><a href="loginAttempts.php">Login Attempts Manager</a></li>
                <li><a href="userAccount.php?action=validateUser">Validiate New Users</a></li>
            </ul>
        </div>
    </div>

<?php include __DIR__ . '/../include/footer.php'; ?>