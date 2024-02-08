<?php

    include __DIR__ . '/../include/header.php';
    include __DIR__ . '/../model/UsersDB.php';

    $user = new UserDB();
    $error = "";

    if(isset($_GET['action'])){
        $action = filter_input(INPUT_GET, 'action');
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

    <title>LMS || User Account</title>
</head>
<body>
    
    <div style="display: flex;">

        <?php include __DIR__ . '/../include/aside.php'; ?>

        <div class="mainContent">

            <?php if($action == 'Viewer'): ?>
                <?php if($_SESSION['isSiteAdmin']): 

                    $users = $user->siteAdminGetAllUsers(); ?>
                    
                    <table class="table table-striped table-hover table-dark">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Birth Date</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Username</th>
                                <th>isSiteAdmin</th>
                                <th>isVerified</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($users as $u): ?>
                            <tr>
                                <td>
                                    <form action="view_users.php" method="POST">
                                        <input type="hidden" name="userId" value="<?= $u['userID']; ?>" />
                                        <input class="btn btn-danger btn-sm" type="submit" name="deleteUser" value="Delete" />
                                    </form>
                                </td>
                                <td><?= $u['firstName']; ?></td>
                                <td><?= $u['lastName']; ?></td>
                                <td><?= $u['email']; ?></td>
                                <td><?= $u['birthDate']; ?></td>
                                <td><?= $u['phoneNumber']; ?></td>
                                <td><?= $u['gender']==1?"Male":"Female" ?></td>
                                <td><?= $u['username'];?></td>
                                <td><?= $u['isSiteAdmin']==0?"No":"Yes" ?></td>
                                <td><?= $u['isVerified']==0?"No":"Yes" ?></td>
                                <td><a style="font-size: 14px; width: 60px; font-weight: 100px;" class="btn btn-danger btn-sm text-light" href="editUsers.php?action=Update&userId=<?= $u['userID']; ?>">Edit</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php elseif($_SESSION['isOrgAdmin']): 
                    
                    $users = $user->orgAdminGetAllUsers(); ?>
                    
                    <table class="table table-striped table-hover table-dark">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Birth Date</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Username</th>
                                <th>isSiteAdmin</th>
                                <th>isVerified</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($users as $u): ?>
                            <tr>
                                <td>
                                    <form action="view_users.php" method="POST">
                                        <input type="hidden" name="userId" value="<?= $u['customerId']; ?>" />
                                        <input class="btn btn-danger btn-sm" type="submit" name="deleteUser" value="Delete" />
                                    </form>
                                </td>
                                <td><?= $u['firstName']; ?></td>
                                <td><?= $u['lastName']; ?></td>
                                <td><?= $u['email']; ?></td>
                                <td><?= $u['birthDate']; ?></td>
                                <td><?= $u['phone']; ?></td>
                                <td><?= $u['gender']==1?"Male":"Female" ?></td>
                                <td><?= $u['username'];?></td>
                                <td><?= $u['isSiteAdmin']==0?"No":"Yes" ?></td>
                                <td><?= $u['isVerified']==0?"No":"Yes" ?></td>
                                <td><a style="font-size: 14px; width: 60px; font-weight: 100px;" class="btn btn-danger btn-sm text-light" href="editUsers.php?action=Update&userId=<?= $u['customerId']; ?>">Edit</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php endif; ?>
            <?php endif; ?>
            
            <?php if($action == 'User'): ?>
                <?php if($_SESSION['isSiteAdmin']): ?>

                <?php elseif($_SESSION['isOrgAdmin']): ?>

                <?php elseif($_SESSION['isTrainer']): ?>
                
                <?php else: ?>

                <?php endif; ?>
            <?php endif; ?>

        </div>

    </div>

<?php include __DIR__ . '/../include/footer.php'; ?>