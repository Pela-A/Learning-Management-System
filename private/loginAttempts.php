<?php

    include __DIR__ . '/../include/header.php';
    include __DIR__ . '/../model/UsersDB.php';
    include __DIR__ . '/../model/LoginAttemptsDB.php';
    include __DIR__ . '/../model/OrganizationsDB.php';

    $error= '';
    $action = '';
    $loginObj = new LoginDB();
    $userObj = new UserDB();
    $orgObj = new OrganizationDB();

    

    //get action variable
    if(isset($_GET['action'])){
        $action = filter_input(INPUT_GET, 'action');
    }

    if($_SESSION['isSiteAdmin']){
        if(!isset($_GET['orgID'])){
            unset($_SESSION['orgID']); // This will remove the 'orgID' session variable
        }
    }

    //if edit comment
    if(isset($_POST['edit'])){        
        $comments = filter_input(INPUT_POST, 'comments');
        $loginID = filter_input(INPUT_POST, 'loginID');
        $loginObj->editComments($loginID, $comments);
    }

    if($_SESSION['isSiteAdmin'] && !isset($_SESSION['orgID'])) {
        $users = $userObj->getAllUsers();
        $logins = $loginObj->getAllLogins();
        $orgs = $orgObj->getAllOrganizations();
    } elseif(($_SESSION['isSiteAdmin'] && isset($_SESSION['orgID'])) || $_SESSION['isOrgAdmin']){
        $users = $userObj->getAllUsersInOrg($_SESSION['orgID']); 
        $logins = $loginObj->getAllOrgLogins($_SESSION['orgID']); 
        $org = $orgObj->getOrganization($_SESSION['orgID']); 
    } else {
        $logins = $loginObj->getAllPersonalLogins($_SESSION['userID']);
    }

    //if search or coming to first time
    if(isset($_POST['searchUserLogins'])){
        $successful = filter_input(INPUT_POST, 'successful');

        $logins = $loginObj->searchUserLogins($successful, $_SESSION['userID']);
    }

    if(isset($_POST['searchAllLogins'])) {
        $orgID = filter_input(INPUT_POST, 'orgID');
        $userID = filter_input(INPUT_POST, 'userID');
        $successful = filter_input(INPUT_POST, 'successful');

        $logins = $loginObj->searchAllLogins($orgID, $userID, $successful);
    }

    if(isset($_POST['searchAllOrgLogins'])){
        $userID = filter_input(INPUT_POST, 'userID');
        $successful = filter_input(INPUT_POST, 'successful');

        $logins = $loginObj->searchAllLogins($_SESSION['orgID'], $userID, $successful);
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

    <title>Login Manager</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    
    <div class="mainContent">
        <?php include __DIR__ . '/../include/aside.php'; ?>

        <div class="pageContent container-fluid">
            <?php if($action == "Viewer"): ?>
                <h2>User Login Attempts</h2>
                <?php if($_SESSION['isSiteAdmin'] && !isset($_SESSION['orgID'])): ?>

                    <form method="POST" name="search">

                        <div class="" style="display: flex; justify-content: space-between;">
                            <select class="form-control text-secondary col-md-3" style="height: 40px; width: 500px;" type="text" name="orgID" id='organization_select'>
                                <option value="">Select Organization</option>
                                <?php foreach($orgs as $o): ?>
                                    <option value="<?=$o['orgID']?>"><?="(". $o['orgID'] . ") " . $o['orgName'] ?></option>
                                <?php endforeach; ?>
                            </select>

                            <select class="form-control text-secondary col-md-3" style="height: 40px; width: 500px;" type="text" name="userID" id='option_select'>
                                <option value="">Select Organization ID to populate</option>
                                <option value="">Select a User ID</option>
                                <?php foreach($users as $u): ?>
                                    <option value="<?=$u['userID']?>"><?="(". $u['userID'] . ") " . $u['firstName'] . " " . $u['lastName'] ?></option>
                                <?php endforeach; ?>  
                            </select>

                            <div class="col-md-3">
                                <label>Successful Login: </label>

                                <input type="radio" class="btn-check" name="successful" value="1" id="successfulYes" autocomplete="off">
                                <label class="btn btn-outline-purple" for="successfulYes">Yes</label>

                                <input type="radio" class="btn-check" name="successful" value="0" id="successfulNo" autocomplete="off">
                                <label class="btn btn-outline-purple" for="successfulNo">No</label>
                            </div>

                            <input class="btn btn-purple col-md-3" style="width: 150px;" type="submit" name="searchAllLogins" value="Search" />
                        </div>

                    </form>

                    <table class="table table-striped table-hover table-dark">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Attempt Date</th>
                                <th>Successful</th>
                                <th>Comments</th>
                                <th>IP Address</th>
                                <th>Edit</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($logins as $l):?>
                                <tr>
                                    <td><?= $l['firstName'] . " " . $l['lastName']; ?></td>
                                    <td><?= $l['attemptDate']; ?></td>
                                    <td><?= $l['isSuccessful']==0?"No":"Yes"; ?></td>
                                    <td><?= $l['comments']; ?></td>
                                    <td><?= $l['ipAddress']; ?></td>
                                    <td><a class="btn btn-purple" href="loginAttempts.php?action=Edit&loginID=<?= $l['loginID']?>">Edit Comments</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <script>
                        $(document).ready(function(){
                            $('#organization_select').change(function(){
                                var organization_id = $(this).val();
                                $.ajax({
                                    url: '../include/selectUsers.php',
                                    type: 'post',
                                    data: {organization_id: organization_id},
                                    dataType: 'json',
                                    success:function(response){
                                        var len = response.length;
                                        $("#option_select").empty();
                                        $("#option_select").append("<option value=''>Select a User ID</option>");
                                        response.forEach(function(item) {
                                            $("#option_select").append("<option value='" + item.userID + "'>("  + item.userID + ") " + item.firstName + " " + item.lastName +"</option>");
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        console.error(xhr.responseText);
                                        // Handle errors if needed
                                    }
                                    
                                });
                            });
                        });
                    </script>

                <?php elseif(($_SESSION['isSiteAdmin'] && isset($_SESSION['orgID'])) || $_SESSION['isOrgAdmin']): ?>

                    <?php if($_SESSION['isSiteAdmin']): ?>
                    <a class="form-control btn btn-purple mb-3" style="display:block;" href="orgControlPanel.php?action=Landing&ordID=<?= $_SESSION['orgID']; ?>">Go Back</a>

                    <?php endif; ?>
                    <form method="POST" name="search">

                        <div style="display: flex;" class="mb-3">
                            <select class="col-md-6 form-control text-secondary mr-5" style="height: 40px;" type="text" name="userID" id='option_select'>
                                <option value="">Select User Account</option>
                                <?php foreach($users as $u): ?>
                                    <option value="<?=$u['userID']?>"><?="(". $u['userID'] . ") " . $u['firstName'] . " " . $u['lastName'] ?></option>
                                <?php endforeach; ?>  
                            </select>

                            <div style="display: flex; justify-content: space-between;" class="col-md-4">
                                <div>
                                    <label class="mr-2">Successful Login: </label>

                                    <input type="radio" class="btn-check" name="successful" value="1" id="successfulYes" autocomplete="off">
                                    <label class="btn btn-outline-purple" for="successfulYes">Yes</label>

                                    <input type="radio" class="btn-check" name="successful" value="0" id="successfulNo" autocomplete="off">
                                    <label class="btn btn-outline-purple" for="successfulNo">No</label>
                                </div>
                            </div>

                            <input class="form-control btn btn-purple" type="submit" name="searchAllOrgLogins" value="Search" />
                        </div>
                    </form>

                    <table class="table table-striped table-hover table-dark">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Attempt Date</th>
                                <th>Successful</th>
                                <th>Comments</th>
                                <th>IP Address</th>
                                <th>Edit</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($logins as $l):?>
                                <tr>
                                    <td><?= $l['firstName'] . " " . $l['lastName']; ?></td>
                                    <td><?= $l['email']; ?></td>
                                    <td><?= $l['attemptDate']; ?></td>
                                    <td><?= $l['isSuccessful']==0?"No":"Yes"; ?></td>
                                    <td><?= $l['comments']; ?></td>
                                    <td><?= $l['ipAddress']; ?></td>
                                    <td><a class="btn btn-purple" href="loginAttempts.php?action=Edit&loginID=<?= $l['loginID']?>">Edit Comments</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php else: ?>

                    <form method="POST" class="">
                        <div style="display: flex; justify-content: space-between;" class="mt-2 searchContent">
                            <div>
                                <label>Successful Login: </label>

                                <input type="radio" class="btn-check" name="successful" value="1" id="successfulYes" autocomplete="off">
                                <label class="btn btn-outline-purple" for="successfulYes">Yes</label>

                                <input type="radio" class="btn-check" name="successful" value="0" id="successfulNo" autocomplete="off">
                                <label class="btn btn-outline-purple" for="successfulNo">No</label>
                            </div>
                            <div>
                                <input class="btn btn-purple" type="submit" name="searchUserLogins" value="Search" />
                            </div>
                        </div>
                    </form>

                    <table class="table table-striped table-hover table-dark">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Attempt Date</th>
                                <th>Successful</th>
                                <th>Comments</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($logins as $l):?>
                                <tr>
                                    <td><?= $l['firstName'] . " " . $l['lastName']; ?></td>
                                    <td><?= $l['attemptDate']; ?></td>
                                    <td><?= $l['isSuccessful']==0?"No":"Yes"; ?></td>
                                    <td><?= $l['comments']; ?></td>
                                    <td><?= $l['ipAddress']; ?></td>
                                    <?php if($_SESSION['isSiteAdmin'] || $_SESSION['isOrgAdmin']): ?>
                                        <td><a class="btn btn-purple" href="loginAttempts.php?action=Edit&loginID=<?= $l['loginID']?>">Edit Comments</a></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php endif; ?>
            
            <?php elseif($action == 'Edit'):
                $loginID = filter_input(INPUT_GET, 'loginID');
                $loginData = $loginObj->getLogin($loginID);

                $userID = $loginData['userID'];
                $attemptDate = $loginData['attemptDate'];
                $isSuccessful = $loginData['isSuccessful']==0?"No":"Yes";
                $comments = $loginData['comments'];
                $ip = $loginData['ipAddress'];

                $userData = $userObj->getUser($userID); ?>

                <h2>Edit Login Comments</h2>
                
                <!--Form for editing -->
                <form method="post" action="loginAttempts.php?action=Viewer" name="loginAttempts_CRUD" class="formContent mt-3">

                    <label>Full Name</label>
                    <input class="form-control" type="text" name="name" value='<?=$userData['firstName'] . " " . $userData['lastName']; ?>' disabled>

                    <label>Attempt Date</label>
                    <input class="form-control" type="date" name="attemptDate" value='<?=$attemptDate?>' disabled>

                    <label>Successful Login</label>
                    <input class="form-control" type="text" name="isSuccessful" value='<?=$isSuccessful?>'  disabled>

                    <label>Comments</label>
                    <textarea class="form-control" name="comments" value="<?=$comments?>" cols="100" rows="4" placeholder="Enter Comments" autofocus><?php echo($comments);?></textarea>
                    

                    <label>IP Address</label>
                    <input class="form-control" type="text" name="ip" value='<?=$ip?>' disabled>

                    <input class="form-control" type="hidden" name="loginID" value="<?=$loginID;?>" readonly>

                    <div style="display: flex;" class="mt-3">
                        <input class="btn btn-purple mr-3" type="submit" name="edit" value="Update Login Comments">
                        
                        <a class="btn btn-purple" style="display:block;" href="loginAttempts.php?action=Viewer">Go Back</a>
                    </div>

                </form>

            <?php endif; ?>
        </div>
    </div>    

<?php include __DIR__ . '/../include/footer.php'; ?>