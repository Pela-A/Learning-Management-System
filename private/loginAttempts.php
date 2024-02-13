<?php

    include __DIR__ . '/../include/header.php';
    include __DIR__ . '/../model/LoginAttemptsDB.php';

    $error= '';
    $action = '';
    $loginObj = new LoginDB();

    if(isset($_GET['action'])){
        $action = filter_input(INPUT_GET, 'action');
    }

    if(isset($_POST['edit'])){
        
        $comments = filter_input(INPUT_POST, 'comments');
        $loginID = filter_input(INPUT_POST, 'loginID');

        $loginObj->editComments($loginID, $comments);
        

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

    <title>LMS || Login Attempts</title>
</head>
<body>
    
    <?php include __DIR__ . '/../include/aside.php'; ?>

    <div class="content">
        <p>main content goes here</p>

        <?php if($action == "Viewer"):

            //if site admin or org admin get all logins for an org. else only grab personal login attempts
            if($_SESSION['isSiteAdmin'] || $_SESSION['isOrgAdmin']){
                $logins = $loginObj->getAllLogins();
            }else{
                $logins = $loginObj->getAllPersonalLogins($_SESSION['userID']);
            }
            
            
            ?>
            <table class="table table-striped table-hover table-dark">
                <thead>
                    <tr>
                        <th>Login ID</th>
                        <th>User ID</th>
                        <th>Attempt Date</th>
                        <th>Successful</th>
                        <th>Comments</th>
                        <th>IP Address</th>
                        <?php if($_SESSION['isSiteAdmin'] || $_SESSION['isOrgAdmin']): ?>
                            <th>Edit</th>
                        <?php endif; ?>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($logins as $l):?>
                    <tr>
                        <td><?= $l['loginID']; ?></td>
                        <td><?= $l['userID']; ?></td>
                        <td><?= $l['attemptDate']; ?></td>
                        <td><?= $l['isSuccessful']==0?"No":"Yes"; ?></td>
                        <td><?= $l['comments']; ?></td>
                        <td><?= $l['ipAddress']; ?></td>
                        <?php if($_SESSION['isSiteAdmin'] || $_SESSION['isOrgAdmin']): ?>
                            <td><a href="loginAttempts.php?action=Edit&loginID=<?= $l['loginID']?>">Edit Comments</a></td>
                            <!-- LINK FOR UPDATE FUNCTIONALITY -> Look at how we are passing in our ID using PHP! -->
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                            
                </tbody>

            </table>

            




        <?php elseif($action == 'Edit'):


            $loginID = filter_input(INPUT_GET, 'loginID');
            $loginData = $loginObj->getLogin($loginID);
            $userID = $loginData['userID'];
            $attemptDate = $loginData['attemptDate'];
            $isSuccessful = $loginData['isSuccessful']==0?"No":"Yes";
            $comments = $loginData['comments'];
            $ip = $loginData['ipAddress'];
            ?>
            


            <form method="post" action="loginAttempts.php?action=Viewer" name="loginAttempts_CRUD">

                <label>User ID</label>
                <input type="text" name="name" value='<?=$userID?>' disabled>
                </br>
                
                <label>Attempt Date</label>
                <input type="date" name="attemptDate" value='<?=$attemptDate?>' disabled>
                </br>

                <label>Successful Login</label>
                <input type="text" name="isSuccessful" value='<?=$isSuccessful?>' disabled>
                </br>

                <label>Comments</label>
                <input type="text" name="comments" value='<?=$comments?>' autofocus>
                </br>

                <label>IP Address</label>
                <input type="text" name="ip" value='<?=$ip?>' disabled>
                </br>

                <input type="hidden" name="loginID" value="<?=$loginID;?>" readonly>

                <input type="submit" name="edit" value="Edit Comments">
                    
                </form>

            <a href="loginAttempts.php?action=Viewer">
                <button>Go Back</button>
            </a>
            

        <?php endif; ?>
    </div>

<?php include __DIR__ . '/../include/footer.php'; ?>