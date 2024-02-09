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
    
    <div class="mainContent"">

        <?php include __DIR__ . '/../include/aside.php'; ?>

        <?php if($action == 'Viewer'):
            $users = $user->getAllUsers();
            if($_SESSION['isSiteAdmin']): ?>
                
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

            <?php elseif($_SESSION['isOrgAdmin']): ?>
                
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
        
        <?php if($action == 'personalSettings'): 
            $account = $user->getUser($_SESSION['userID']);
            if($_SESSION['isSiteAdmin']): ?>

                <div style="display: flex;">
                    <label for="">Username: </label>
                    <p><?= $account[0]["username"]; ?></p>
                </div>

                <div style="display: flex;">
                    <label for="">Full Name: </label>
                    <p><?= $account[0]["firstName"] . " " . $account[0]["lastName"]; ?></p>
                </div>

                <div style="display: flex;">
                    <label for="">Account Created: </label>
                    <p><?= $account[0]["letterDate"]; ?></p>
                </div>

                <div style="display: flex;">
                    <label for="">Email: </label>
                    <p><?= $account[0]["email"]; ?></p>
                </div>

                <div style="display: flex;">
                    <label for="">Birth Date: </label>
                    <p><?= $account[0]["birthDate"]; ?></p>
                </div>

                <div style="display: flex;">
                    <label for="">Phone Number: </label>
                    <p><?= $account[0]["phoneNumber"]; ?></p>
                </div>

                <div style="display: flex;">
                    <label for="">Gender: </label>
                    <p><?= $account[0]['gender']==1?"Male":"Female"; ?></p>
                </div>

                <div style="display: flex;">
                    <label for="">Website Administrator: </label>
                    <p><?= $account[0]['isSiteAdmin']==1?"Yes":"No"; ?></p>
                </div>

            <?php elseif($_SESSION['isOrgAdmin']): ?>

            <?php elseif($_SESSION['isTrainer']): ?>

            <?php else: ?>

            <?php endif; ?>
        <?php endif; ?>

        <?php if($action == 'ChangePassword'): ?>
        <?php endif; ?>

    </div>

<?php include __DIR__ . '/../include/footer.php'; ?>