<?php

    include __DIR__ . '/../include/header.php';
    include __DIR__ . '/../model/UsersDB.php';
    include __DIR__ . '/../model/LoginAttemptsDB.php';
    include __DIR__ . '/../model/OrganizationsDB.php';

    $error= '';
    $action = '';
    $loginObj = new LoginDB();

    //get action variable
    if(isset($_GET['action'])){
        $action = filter_input(INPUT_GET, 'action');




    }

    //if edit comment
    if(isset($_POST['edit'])){
        
        $comments = filter_input(INPUT_POST, 'comments');
        $loginID = filter_input(INPUT_POST, 'loginID');

        $loginObj->editComments($loginID, $comments);
        
    }
    //if search or coming to first time
    if(isset($_POST['search'])){
        $attemptDate = filter_input(INPUT_POST, 'attemptDate');
        if($_SESSION['isSiteAdmin']){
            $userID = filter_input(INPUT_POST, 'userID');
            $orgID = filter_input(INPUT_POST, 'orgID');
            $orgDB = new organizationDB();
            $orgs = $orgDB->getAllOrganizations();
        }
        elseif($_SESSION['isOrgAdmin']){
            $userID = filter_input(INPUT_POST, 'userID');
            $orgID = $_SESSION['orgID'];
        }
        else{
            $userID = $_SESSION['userID'];
            $orgID = $_SESSION['orgID'];
        }
        $logins = $loginObj->searchLogins($attemptDate, $userID, $orgID);
    }
    //otherwise loading into page first time.
    else{
        if($_SESSION['isSiteAdmin']){
            $logins = $loginObj->getAllLogins();
            $orgDB = new organizationDB();
            $orgs = $orgDB->getAllOrganizations();
        }
        elseif($_SESSION['isOrgAdmin']){
            $logins = $loginObj->getAllOrgLogins($_SESSION['orgID']);
        }else{
            $logins = $loginObj->getAllPersonalLogins($_SESSION['userID']);
        }
        $attemptDate = "";
        $userID = "";
    }

        //siteADMIN
        //Alexander
        //AlexanderPela

        //orgADMIN
        //NewGuy13
        //Pelaman12


        //general USER
        //JoinUser215125
        //PelaMan12




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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    
    <?php include __DIR__ . '/../include/aside.php'; ?>

    <div class="content">
        <p>main content goes here</p>

        <?php if($action == "Viewer"):

            
            

            $userDB = new userDB();
            $users = $userDB->getAllUsersInOrg($_SESSION['orgID']);
            ?>

            <!--Search functionality -->
            <form method="POST" name="search_books" class="col-lg-10 offset-lg-1 ">
                <div class="row justify-content-center">
                    <div class="col-sm text-center">
                        <div class="label">
                            <label>Login Attempt Date:</label>
                        </div>
                        <div>
                            <input type="Date" name="attemptDate" value="<?=$attemptDate;?>"/>
                        </div>
                    </div>   

                    <?php if($_SESSION['isSiteAdmin']): ?>
                    <div class="col-sm text-center">
                        <div class="label">
                            <label>Organization ID:</label>
                        </div>
                        
                        <div>
                            <select class="form-control text-dark col-md-12" style="height: 40px;" type="text" name="orgID" id='organization_select'>
                                <option value="">Select Organization</option>
                                <?php foreach($orgs as $o): ?>
                                    <option value="<?=$o['orgID']?>"><?="(". $o['orgID'] . ") " . $o['orgName'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($_SESSION['isOrgAdmin'] || $_SESSION['isSiteAdmin']): ?>
                    <div class="col-sm text-center">
                        <div class="label">
                            <label>User ID:</label>
                        </div>
                        
                        <div>
                            <select class="form-control text-dark col-md-12" style="height: 40px;" type="text" name="userID" id='option_select'>
                                <option value="">Select User</option>
                                <?php foreach($users as $u): ?>
                                    <option value="<?=$u['userID']?>"><?="(". $u['userID'] . ") " . $u['firstName'] . " " . $u['lastName'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="row justify-content-center">
                    <div class="col-6 text-center">
                        <div>
                            &nbsp;
                        </div>
                        <div>
                            <input type="submit" name="search" value="Search" />
                        </div>

                    </div>

                </div>
                
            </form>
            <script>
                $(document).ready(function(){
                    $('#organization_select').change(function(){
                        var organization_id = $(this).val();
                        
                        $.ajax({
                            url: 'get_options.php',
                            type: 'post',
                            data: {category_id: category_id},
                            dataType: 'json',
                            success:function(response){
                                var len = response.length;
                                $("#option_select").empty();
                                for( var i = 0; i<len; i++){
                                    var id = response[i]['id'];
                                    var name = response[i]['name'];
                                    $("#option_select").append("<option value='"+id+"'>"+name+"</option>");
                                }
                            }
                        });
                    });
                });
            </script>
            <!--End search functionality -->

            
            
            
            
            
            
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
            

            <!--Form for editing -->
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