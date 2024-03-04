<?php

    include __DIR__ . '/../include/header.php';
    include __DIR__ . '/../model/UsersDB.php';
    include __DIR__ . '/../model/OrganizationsDB.php';
    include __DIR__ . '/../model/DepartmentsDB.php';
    include __DIR__ . '/../model/UserDepBridgeDB.php';

    if(!isset($_SESSION['userID'])){
        header('Location: logout.php');
    }

    $userObj = new UserDB();
    $depObj = new DepartmentDB();
    $orgObj = new OrganizationDB();
    $userDepObj = new userDepDB();
    $error = "";
    $orgID = "";
    $userID = "";

    if(isset($_GET['action'])){
        $action = filter_input(INPUT_GET, 'action');
    }

    if(isset($_GET['orgID'])){
        $orgID = filter_input(INPUT_GET, 'orgID');
        $deps = $depObj->GetAllDepartments($_SESSION['orgID']);
    }

    if($_SESSION['isSiteAdmin']){
        if(!isset($_GET['orgID'])){
            unset($_SESSION['orgID']); // This will remove the 'orgID' session variable
        }
    }

    if(isset($_GET['userID'])){
        $userID = filter_input(INPUT_GET, 'userID');
    }

    $orgs = $orgObj->getAllOrganizations();

    if(isset($_POST['submitSiteAdminCreateUser'])) {
        $orgID = filter_input(INPUT_POST, 'orgID');
        $depID = filter_input(INPUT_POST, 'depID');
        $firstName = filter_input(INPUT_POST, 'firstName');
        $lastName = filter_input(INPUT_POST, 'lastName');
        $phoneNumber = filter_input(INPUT_POST, 'phoneNumber');
        $email = filter_input(INPUT_POST, 'email');
        $birthDate = filter_input(INPUT_POST, 'birthDate');
        $gender = filter_input(INPUT_POST, 'gender');
        $password = filter_input(INPUT_POST, 'password');
        $isSiteAdmin = filter_input(INPUT_POST, 'isSiteAdmin');
        $isOrgAdmin = filter_input(INPUT_POST, 'isOrgAdmin');
        $isTrainer = filter_input(INPUT_POST, 'isTrainer');

        $userObj->siteAdminCreateUser($orgID, $firstName, $lastName, $email, $birthDate, $phoneNumber, $gender, $password, $isSiteAdmin, $isOrgAdmin, $isTrainer);
        $userID = $userObj->getLastUser();
        $userDepObj->createRelationship($userID[0]['userID'], $depID);
    }

    if(isset($_POST['submitOrgAdminCreateUser'])) {
        $depID = filter_input(INPUT_POST, 'depID');
        $firstName = filter_input(INPUT_POST, 'firstName');
        $lastName = filter_input(INPUT_POST, 'lastName');
        $phoneNumber = filter_input(INPUT_POST, 'phoneNumber');
        $email = filter_input(INPUT_POST, 'email');
        $birthDate = filter_input(INPUT_POST, 'birthDate');
        $gender = filter_input(INPUT_POST, 'gender');
        $password = filter_input(INPUT_POST, 'password');
        $isOrgAdmin = filter_input(INPUT_POST, 'orgAdmin');
        $isTrainer = filter_input(INPUT_POST, 'trainer');

        //validate input
        $error = "";

        //if valid input create the new department
        if($error ==''){
            $userObj->orgAdminCreateUser($_SESSION['orgID'], $depID, $firstName, $lastName, $email, $birthDate, $phoneNumber, $gender, $password, $isOrgAdmin, $isTrainer);
        }
    }

    if(isset($_POST['deleteUser'])){
        $id = filter_input(INPUT_POST, 'userID');
        $userObj->deleteUser($id);
        $userDepObj->deleteAllUserRelationships($id);
    }

    if(isset($_POST['searchButton'])){
        $firstName = filter_input(INPUT_POST, 'firstName');
        $lastName = filter_input(INPUT_POST, 'lastName');
        $organization = filter_input(INPUT_POST, 'organization');
        $gender = filter_input(INPUT_POST, 'gender');
        $isSiteAdmin = filter_input(INPUT_POST, 'isSiteAdmin');
        $isOrgAdmin = filter_input(INPUT_POST, 'isOrgAdmin');
        $isTrainer = filter_input(INPUT_POST, 'isTrainer');

        $users = $userObj->searchUsers($firstName, $lastName, $organization, $gender, $isSiteAdmin, $isOrgAdmin, $isTrainer);

    } else {
        $firstName = '';
        $lastName = '';
        $organization = '';
        $gender = '';
        $isSiteAdmin = '';
        $isOrgAdmin = '';
        $isTrainer = '';
    }

    if(isset($_POST['submitSiteAdminUpdateUser'])){
        $userID = filter_input(INPUT_POST, 'userID');
        $department = filter_input(INPUT_POST, 'department');
        $firstName = filter_input(INPUT_POST, 'firstName');
        $lastName = filter_input(INPUT_POST, 'lastName');
        $letterDate = filter_input(INPUT_POST, 'letterDate');
        $phoneNumber = filter_input(INPUT_POST, 'phoneNumber');
        $email = filter_input(INPUT_POST, 'email');
        $birthDate = filter_input(INPUT_POST, 'birthDate');
        $gender = filter_input(INPUT_POST, 'gender');
        $username = filter_input(INPUT_POST, 'username');
        $isOrgAdmin = filter_input(INPUT_POST, 'isOrgAdmin');
        $isSiteAdmin = filter_input(INPUT_POST, 'isSiteAdmin');
        $isTrainer = filter_input(INPUT_POST, 'isTrainer');
        $profilePicture = filter_input(INPUT_POST, 'profilePhoto');

        $userObj->siteAdminUpdateUser($userID, $department, $firstName, $lastName, $letterDate, $email, $birthDate, $phoneNumber, $gender, $username, $isOrgAdmin, $isSiteAdmin, $isTrainer, $profilePicture);
    }

    if(isset($_POST['submitOrgAdminUpdateUser'])){
        $userID = filter_input(INPUT_POST, 'userID');
        $department = filter_input(INPUT_POST, 'department');
        $firstName = filter_input(INPUT_POST, 'firstName');
        $lastName = filter_input(INPUT_POST, 'lastName');
        $letterDate = filter_input(INPUT_POST, 'letterDate');
        $phoneNumber = filter_input(INPUT_POST, 'phoneNumber');
        $email = filter_input(INPUT_POST, 'email');
        $birthDate = filter_input(INPUT_POST, 'birthDate');
        $gender = filter_input(INPUT_POST, 'gender');
        $isOrgAdmin = filter_input(INPUT_POST, 'isOrgAdmin');
        $isTrainer = filter_input(INPUT_POST, 'isTrainer');
        $profilePicture = filter_input(INPUT_POST, 'profilePhoto');

        $userObj->orgAdminUpdateUser($userID, $firstName, $lastName, $letterDate, $email, $birthDate, $phoneNumber, $gender, $isOrgAdmin, $isTrainer, $profilePicture);
    }

    if(isset($_POST['submitUpdateUser'])){
        $userID = filter_input(INPUT_POST, 'userID');
        $firstName = filter_input(INPUT_POST, 'firstName');
        $lastName = filter_input(INPUT_POST, 'lastName');
        $phoneNumber = filter_input(INPUT_POST, 'phoneNumber');
        $email = filter_input(INPUT_POST, 'email');
        $birthDate = filter_input(INPUT_POST, 'birthDate');
        $gender = filter_input(INPUT_POST, 'gender');
        $username = filter_input(INPUT_POST, 'username');
        $profilePicture = filter_input(INPUT_POST, 'profilePhoto');

        //$error = validateUserInformation();

        if($error == ''){
            $users = $userObj->generalUpdateUser($userID, $firstName, $lastName, $email, $birthDate, $phoneNumber, $gender, $username, $profilePicture);
        }
    }

    if(isset($_POST['submitChangePassword'])){
        $password = filter_input(INPUT_POST, 'password');
        $validatePassword = filter_input(INPUT_POST, 'validatePassword');

        if($password === $validatePassword && $userObj->validatePassword($password)) {
            $userObj->changePassword($_SESSION['userID'], $password);
        } else {
            echo '<div class="alert alert-light" role="alert">Passwords do not match or do not meet the validation criteria. Please try again.</div>';
        }
        
    }

    if(isset($_POST['submitValidation'])){
        $isVerified = filter_input(INPUT_POST, 'isValidated');
        $userID = filter_input(INPUT_POST, 'userID');

        if($isVerified == 1) {
            $userObj->validateUser($userID);
        } elseif($isVerified == 0) {
            $userObj->deleteUser($userID);
        } else {

        }
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

    <title>Account Settings</title>
</head>
<body>
    
    <div class="mainContent">

        <?php include __DIR__ . '/../include/aside.php'; ?>

        <div class="pageContent container-fluid">

            <?php if($action == 'Viewer'): ?>

                <h2>Manage User Accounts</h2>

                <?php if($_SESSION['isSiteAdmin'] && isset($_SESSION['orgID'])): 
                    $users = $userObj->getAllUsersInOrg($_SESSION['orgID']); ?>
                    
                    <a class="form-control btn btn-light mb-3" href="userAccount.php?action=createUser">Create New User Account</a>

                    <form class="requires-validation" method="POST" id="searchUsers" name="searchUsers" novalidate>
                        <div style="display: flex;">
                            <div class="">
                                <input class="form-control" type="text" name="firstName" placeholder="First Name" required>
                                <div class="valid-feedback">Username field is valid!</div>
                                <div class="invalid-feedback">Username field cannot be blank!</div>
                            </div>

                            <div class="">
                                <input class="form-control" type="text" name="lastName" placeholder="Last Name" required>
                                <div class="valid-feedback">Email field is valid!</div>
                                <div class="invalid-feedback">Email field cannot be blank!</div>
                            </div>

                            <select class="form-control text-dark" style="height: 40px;" type="text" name="organization" required>
                                <option value="">Select Organization</option>
                                <?php foreach($orgs as $o): ?>
                                    <option value="<?= $o['orgID']; ?>"><?= $o['orgName'] . ", " . $o['state'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div style="display: flex; justify-content: space-evenly;">
                            <div class="mt-3">
                                <label class="mb-3 mr-1" for="gender">Gender: </label>

                                <input type="radio" class="btn-check" name="gender" value=1 id="male" autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-light" for="male">Male</label>

                                <input type="radio" class="btn-check" name="gender" value=0 id="female" autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-light" for="female">Female</label>

                                <div class="valid-feedback mv-up">You selected a gender!</div>
                                <div class="invalid-feedback mv-up">Please select a gender!</div>
                            </div>

                            <div class=" mt-3">
                                <label class="mb-3 mr-1" for="isSiteAdmin">Site Admin: </label>

                                <input type="radio" class="btn-check" name="isSiteAdmin" value=1 id="siteAdminYes" autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-light" for="siteAdminYes">Yes</label>

                                <input type="radio" class="btn-check" name="isSiteAdmin" value=0 id="siteAdminNo" autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-light" for="siteAdminNo">No</label>

                                <div class="valid-feedback mv-up">You selected site admin status!</div>
                                <div class="invalid-feedback mv-up">Please select site admin status!</div>
                            </div>

                            <div class=" mt-3">
                                <label class="mb-3 mr-1" for="isOrgAdmin">Org Admin: </label>

                                <input type="radio" class="btn-check" name="isOrgAdmin" value=1 id="orgAdminYes" autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-light" for="orgAdminYes">Yes</label>

                                <input type="radio" class="btn-check" name="isOrgAdmin" value=0 id="orgAdminNo" autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-light" for="orgAdminNo">No</label>

                                <div class="valid-feedback mv-up">You selected org admin status!</div>
                                <div class="invalid-feedback mv-up">Please select org admin status!</div>
                            </div>

                            <div class=" mt-3">
                                <label class="mb-3 mr-1" for="isTrainer">Training Manager: </label>

                                <input type="radio" class="btn-check" name="isTrainer" value=1 id="trainerYes" autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-light" for="trainerYes">Yes</label>

                                <input type="radio" class="btn-check" name="isTrainer" value=0 id="trainerNo" autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-light" for="trainerNo">No</label>

                                <div class="valid-feedback mv-up">You selected trainer status!</div>
                                <div class="invalid-feedback mv-up">Please select trainer status!</div>
                            </div>

                            <input type="submit" class="btn btn-light" style="height: 40px;" name="searchButton" value="Search" />
                        </div>
                    </form>

                    <table class="table table-striped table-hover table-dark">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Organization</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Birth Date</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Username</th>
                                <th>Website Admin</th>
                                <th>Organization Admin</th>
                                <th>Training Manager</th>
                                <th>Verified</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" name="userID" value="<?= $u['userID']; ?>" />
                                            <input class="btn btn-light" type="submit" name="deleteUser" value="Delete" />
                                        </form>
                                    </td>
                                    
                                    <td><?= $u['orgName']; ?></td>
                                    <td><?= $u['firstName']; ?></td>
                                    <td><?= $u['lastName']; ?></td>
                                    <td><?= $u['email']; ?></td>
                                    <td><?= $u['birthDate']; ?></td>
                                    <td><?= $u['phoneNumber']; ?></td>
                                    <td><?= $u['gender']==1?"Male":"Female" ?></td>
                                    <td><?= $u['username'];?></td>
                                    <td><?= $u['isSiteAdmin']==0?"No":"Yes" ?></td>
                                    <td><?= $u['isOrgAdmin']==0?"No":"Yes" ?></td>
                                    <td><?= $u['isTrainer']==0?"No":"Yes" ?></td>
                                    <td><?= $u['isVerified']==0?"No":"Yes" ?></td>
                                    <td><a style="font-size: 14px; width: 60px; font-weight: 100px;" class="btn btn-light" href="userAccount.php?action=updateUser&userID=<?= $u['userID']; ?>">Edit</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php elseif($_SESSION['isSiteAdmin'] && !isset($_SESSION['orgID'])): 
                    $users = $userObj->getAllUsers(); ?>

                    <a class="form-control btn btn-light mb-3" href="userAccount.php?action=createUser">Create New User Account</a>

                    <form class="requires-validation" method="POST" id="searchUsers" name="searchUsers" novalidate>
                        <div style="display: flex;">
                            <div class="">
                                <input class="form-control" type="text" name="firstName" placeholder="First Name" required>
                                <div class="valid-feedback">Username field is valid!</div>
                                <div class="invalid-feedback">Username field cannot be blank!</div>
                            </div>

                            <div class="">
                                <input class="form-control" type="text" name="lastName" placeholder="Last Name" required>
                                <div class="valid-feedback">Email field is valid!</div>
                                <div class="invalid-feedback">Email field cannot be blank!</div>
                            </div>

                            <select class="form-control text-dark" style="height: 40px;" type="text" name="organization" required>
                                <option value="">Select Organization</option>
                                <?php foreach($orgs as $o): ?>
                                    <option value="<?= $o['orgID']; ?>"><?= $o['orgName'] . ", " . $o['state'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div style="display: flex; justify-content: space-evenly;">
                            <div class="mt-3">
                                <label class="mb-3 mr-1" for="gender">Gender: </label>

                                <input type="radio" class="btn-check" name="gender" value=1 id="male" autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-light" for="male">Male</label>

                                <input type="radio" class="btn-check" name="gender" value=0 id="female" autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-light" for="female">Female</label>

                                <div class="valid-feedback mv-up">You selected a gender!</div>
                                <div class="invalid-feedback mv-up">Please select a gender!</div>
                            </div>

                            <div class=" mt-3">
                                <label class="mb-3 mr-1" for="isSiteAdmin">Site Admin: </label>

                                <input type="radio" class="btn-check" name="isSiteAdmin" value=1 id="siteAdminYes" autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-light" for="siteAdminYes">Yes</label>

                                <input type="radio" class="btn-check" name="isSiteAdmin" value=0 id="siteAdminNo" autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-light" for="siteAdminNo">No</label>

                                <div class="valid-feedback mv-up">You selected site admin status!</div>
                                <div class="invalid-feedback mv-up">Please select site admin status!</div>
                            </div>

                            <div class=" mt-3">
                                <label class="mb-3 mr-1" for="isOrgAdmin">Org Admin: </label>

                                <input type="radio" class="btn-check" name="isOrgAdmin" value=1 id="orgAdminYes" autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-light" for="orgAdminYes">Yes</label>

                                <input type="radio" class="btn-check" name="isOrgAdmin" value=0 id="orgAdminNo" autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-light" for="orgAdminNo">No</label>

                                <div class="valid-feedback mv-up">You selected org admin status!</div>
                                <div class="invalid-feedback mv-up">Please select org admin status!</div>
                            </div>

                            <div class=" mt-3">
                                <label class="mb-3 mr-1" for="isTrainer">Training Manager: </label>

                                <input type="radio" class="btn-check" name="isTrainer" value=1 id="trainerYes" autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-light" for="trainerYes">Yes</label>

                                <input type="radio" class="btn-check" name="isTrainer" value=0 id="trainerNo" autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-light" for="trainerNo">No</label>

                                <div class="valid-feedback mv-up">You selected trainer status!</div>
                                <div class="invalid-feedback mv-up">Please select trainer status!</div>
                            </div>

                            <input type="submit" class="btn btn-light" style="height: 40px;" name="searchButton" value="Search" />
                        </div>
                    </form>

                    <table class="table table-striped table-hover table-dark">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Organization</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Birth Date</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Username</th>
                                <th>Website Admin</th>
                                <th>Organization Admin</th>
                                <th>Training Manager</th>
                                <th>Verified</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" name="userID" value="<?= $u['userID']; ?>" />
                                            <input class="btn btn-light" type="submit" name="deleteUser" value="Delete" />
                                        </form>
                                    </td>
                                    
                                    <td><?= $u['orgName']; ?></td>
                                    <td><?= $u['firstName']; ?></td>
                                    <td><?= $u['lastName']; ?></td>
                                    <td><?= $u['email']; ?></td>
                                    <td><?= $u['birthDate']; ?></td>
                                    <td><?= $u['phoneNumber']; ?></td>
                                    <td><?= $u['gender']==1?"Male":"Female" ?></td>
                                    <td><?= $u['username'];?></td>
                                    <td><?= $u['isSiteAdmin']==0?"No":"Yes" ?></td>
                                    <td><?= $u['isOrgAdmin']==0?"No":"Yes" ?></td>
                                    <td><?= $u['isTrainer']==0?"No":"Yes" ?></td>
                                    <td><?= $u['isVerified']==0?"No":"Yes" ?></td>
                                    <td><a style="font-size: 14px; width: 60px; font-weight: 100px;" class="btn btn-light" href="userAccount.php?action=updateUser&userID=<?= $u['userID']; ?>">Edit</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                <?php elseif($_SESSION['isOrgAdmin']): ?>
                    
                    <a class="form-control btn btn-light" href="userAccount.php?action=createUser">Create an Account</a>

                    <?php $users = $userObj->getAllUsersInOrg($_SESSION['orgID']); ?>

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
                                <th>Organization Admin</th>
                                <th>Training Manager</th>
                                <th>Verified</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($users as $u): ?>
                            <tr>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="userID" value="<?= $u['userID']; ?>" />
                                        <input class="btn btn-light btn-sm" type="submit" name="deleteUser" value="Delete" />
                                    </form>
                                </td>
                                <td><?= $u['firstName']; ?></td>
                                <td><?= $u['lastName']; ?></td>
                                <td><?= $u['email']; ?></td>
                                <td><?= $u['birthDate']; ?></td>
                                <td><?= $u['phoneNumber']; ?></td>
                                <td><?= $u['gender']==1?"Male":"Female" ?></td>
                                <td><?= $u['username'];?></td>
                                <td><?= $u['isOrgAdmin']==0?"No":"Yes" ?></td>
                                <td><?= $u['isTrainer']==0?"No":"Yes" ?></td>
                                <td><?= $u['isVerified']==0?"No":"Yes" ?></td>
                                <td><a style="font-size: 14px; width: 60px; font-weight: 100px;" class="btn btn-light btn-sm text-light" href="userAccount.php?action=updateUser&userID=<?= $u['userID']; ?>">Edit</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php endif; ?>
            
            <?php elseif($action == 'createUser'): ?>
                
                <h2>Create User Account</h2>
                
                <?php if($_SESSION['isSiteAdmin']): ?>

                    <form action="userAccount.php?action=Viewer" class="requires-validation" novalidate method="POST">

                        <select class="form-control text-dark col-md-12" style="height: 40px;" type="text" id="orgID" name="orgID" required>
                            <option value="">Select Organization</option>
                            <?php foreach($orgs as $o): ?>
                                <option value="<?= $o['orgID']; ?>"><?= $o['orgName'] . ", " . $o['state'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        
                        <select class="form-control text-dark col-md-12" style="height: 40px;" type="text" id="depID" name="depID" required>
                            <option value="">Select Department</option>
                            
                            <?php foreach($deps as $d): ?>
                                <option value="<?= $d['depID']; ?>"><?= $d['depName'] ?></option>
                            <?php endforeach; ?>
                        </select>

                        <div class="col-md-12" >
                            <input class="form-control" type="text" name="firstName" placeholder="First Name" required>
                            <div class="valid-feedback">First name field is valid!</div>
                            <div class="invalid-feedback">First name field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="text" name="lastName" placeholder="Last Name" required>
                            <div class="valid-feedback">Last name field is valid!</div>
                            <div class="invalid-feedback">Last name field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="email" name="email" placeholder="Email Address" required>
                            <div class="valid-feedback">Email field is valid!</div>
                            <div class="invalid-feedback">Email field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="date" name="birthDate" required>
                            <div class="valid-feedback">Last name field is valid!</div>
                            <div class="invalid-feedback">Last name field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="text" name="phoneNumber" placeholder="Phone Number" required>
                            <div class="valid-feedback">Phone number field is valid!</div>
                            <div class="invalid-feedback">Phone number field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="password" name="password" placeholder="Enter password" required>
                            <div class="valid-feedback">Password field is valid!</div>
                            <div class="invalid-feedback">Password field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="password" name="confirmPassword" placeholder="Confirm password" required>
                            <div class="valid-feedback">Password field is valid!</div>
                            <div class="invalid-feedback">Password field cannot be blank!</div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="gender">Gender: </label>

                            <input type="radio" class="btn-check" name="gender" value=1 id="male" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="male">Male</label>

                            <input type="radio" class="btn-check" name="gender" value=0 id="female" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="female">Female</label>

                            <div class="valid-feedback mv-up">You selected a gender!</div>
                            <div class="invalid-feedback mv-up">Please select a gender!</div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="siteAdmin">Site Admin: </label>

                            <input type="radio" class="btn-check" name="isSiteAdmin" value=1 id="siteYes" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="siteYes">Yes</label>

                            <input type="radio" class="btn-check" name="isSiteAdmin" value=0 id="siteNo" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="siteNo">No</label>

                            <div class="valid-feedback mv-up">You selected a site admin status!</div>
                            <div class="invalid-feedback mv-up">Please select a site admin status!</div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="orgAdmin">Organization Admin: </label>

                            <input type="radio" class="btn-check" name="isOrgAdmin" value=1 id="orgYes" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="orgYes">Yes</label>

                            <input type="radio" class="btn-check" name="isOrgAdmin" value=0 id="orgNo" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="orgNo">No</label>

                            <div class="valid-feedback mv-up">You selected a organization admin status!</div>
                            <div class="invalid-feedback mv-up">Please select a organization admin status!</div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="trainer">Training Manager: </label>

                            <input type="radio" class="btn-check" name="isTrainer" value=1 id="trYes" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="trYes">Yes</label>

                            <input type="radio" class="btn-check" name="isTrainer" value=0 id="trNo" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="trNo">No</label>

                            <div class="valid-feedback mv-up">You selected a training manager status!</div>
                            <div class="invalid-feedback mv-up">Please select a training manager status!</div>
                        </div>

                        <div class="form-button mt-3">
                            <button name="submitSiteAdminCreateUser" type="submit" class="btn btn-light">Create New User</button>
                        </div>

                    </form>

                <?php elseif($_SESSION['isOrgAdmin']): 
                    $organization = $orgObj->getOrganization($_SESSION['orgID']); ?>

                    <div style="display: flex;">
                        <h4>Organization: </h4>
                        <h4><?= $organization[0]['orgName']; ?></h4>
                    </div>

                    <form action="userAccount.php?action=Viewer&orgID=<?= $_SESSION['orgID']; ?>" class="requires-validation" novalidate method="POST">

                        <select class="form-control text-dark col-md-12" style="height: 40px;" type="text" id="depID" name="depID" required>
                            <option value="">Select Department</option>
                            <?php foreach($deps as $d): ?>
                                <option value="<?= $d['depID']; ?>"><?= $d['depName']; ?></option>
                            <?php endforeach; ?>
                        </select>

                        <div class="col-md-12" >
                            <input class="form-control" type="text" name="firstName" placeholder="First Name" required>
                            <div class="valid-feedback">First name field is valid!</div>
                            <div class="invalid-feedback">First name field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="text" name="lastName" placeholder="Last Name" required>
                            <div class="valid-feedback">Last name field is valid!</div>
                            <div class="invalid-feedback">Last name field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="email" name="email" placeholder="Email Address" required>
                            <div class="valid-feedback">Email field is valid!</div>
                            <div class="invalid-feedback">Email field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="date" name="birthDate" required>
                            <div class="valid-feedback">Last name field is valid!</div>
                            <div class="invalid-feedback">Last name field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="text" name="phoneNumber" placeholder="Phone Number" required>
                            <div class="valid-feedback">Phone number field is valid!</div>
                            <div class="invalid-feedback">Phone number field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="password" name="password" placeholder="Enter password" required>
                            <div class="valid-feedback">Password field is valid!</div>
                            <div class="invalid-feedback">Password field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="password" name="confirmPassword" placeholder="Confirm password" required>
                            <div class="valid-feedback">Password field is valid!</div>
                            <div class="invalid-feedback">Password field cannot be blank!</div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="gender">Gender: </label>

                            <input type="radio" class="btn-check" name="gender" value=1 id="male" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="male">Male</label>

                            <input type="radio" class="btn-check" name="gender" value=0 id="female" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="female">Female</label>

                            <div class="valid-feedback mv-up">You selected a gender!</div>
                            <div class="invalid-feedback mv-up">Please select a gender!</div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="orgAdmin">Organization Admin: </label>

                            <input type="radio" class="btn-check" name="orgAdmin" value=1 id="orgYes" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="orgYes">Yes</label>

                            <input type="radio" class="btn-check" name="orgAdmin" value=0 id="orgNo" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="orgNo">No</label>

                            <div class="valid-feedback mv-up">You selected a organization admin status!</div>
                            <div class="invalid-feedback mv-up">Please select a organization admin status!</div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="trainer">Training Manager: </label>

                            <input type="radio" class="btn-check" name="trainer" value=1 id="trYes" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="trYes">Yes</label>

                            <input type="radio" class="btn-check" name="trainer" value=0 id="trNo" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="trNo">No</label>

                            <div class="valid-feedback mv-up">You selected a training manager status!</div>
                            <div class="invalid-feedback mv-up">Please select a training manager status!</div>
                        </div>

                        <div class="form-button mt-3">
                            <button id="submit" name="submitOrgAdminCreateUser" type="submit" class="btn btn-light">Create New User</button>
                        </div>

                    </form>

                <?php endif; ?>

            <?php elseif($action == 'personalSettings'): ?>

                <h2>Account Settings</h2>

                <?php $account = $userObj->getUser($_SESSION['userID']); ?>

                <a class="form-control btn btn-light" href="userAccount.php?action=updateUser&userID=<?= $account['userID']; ?>">Make Changes to Account</a>
                
                <?php if($_SESSION['isSiteAdmin']): ?>

                    <div style="display: flex;">
                        <label for="">Username: </label>
                        <p><?= $account["username"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Full Name: </label>
                        <p><?= $account["firstName"] . " " . $account["lastName"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Account Created: </label>
                        <p><?= $account["letterDate"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Email: </label>
                        <p><?= $account["email"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Birth Date: </label>
                        <p><?= $account["birthDate"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Phone Number: </label>
                        <p><?= $account["phoneNumber"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Gender: </label>
                        <p><?= $account['gender']==1?"Male":"Female"; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Website Administrator: </label>
                        <p><?= $account['isSiteAdmin']==1?"Yes":"No"; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Organization Administrator: </label>
                        <p><?= $account['isOrgAdmin']==1?"Yes":"No"; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Training Manager: </label>
                        <p><?= $account['isTrainer']==1?"Yes":"No"; ?></p>
                    </div>

                    <a class="form-control btn btn-light" href="userAccount.php?action=changePassword">Change Password</a>

                <?php elseif($_SESSION['isOrgAdmin']): ?>

                    <div style="display: flex;">
                        <label for="">Username: </label>
                        <p><?= " " . $account["username"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Full Name: </label>
                        <p><?= " " . $account["firstName"] . " " . $account["lastName"]; ?></p>
                    </div>
                    
                    <div style="display: flex;">
                        <label for="">Organization: </label>
                        <p><?= " " . $account["orgName"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Account Created: </label>
                        <p><?= " " . $account["letterDate"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Email: </label>
                        <p><?= " " . $account["email"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Birth Date: </label>
                        <p><?= " " . $account["birthDate"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Phone Number: </label>
                        <p><?= " " . $account["phoneNumber"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Gender: </label>
                        <p><?= " " . $account['gender']==1?"Male":"Female"; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Organization Administrator: </label>
                        <p><?= " " . $account['isOrgAdmin']==1?"Yes":"No"; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Training Manager: </label>
                        <p><?= " " . $account['isTrainer']==1?"Yes":"No"; ?></p>
                    </div>

                    <a href="userAccount.php?action=changePassword">Change Password</a>

                <?php elseif($_SESSION['isTrainer']): ?>

                    <div style="display: flex;">
                        <label for="">Username: </label>
                        <p><?= " " . $account["username"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Full Name: </label>
                        <p><?= " " . $account["firstName"] . " " . $account["lastName"]; ?></p>
                    </div>
                    
                    <div style="display: flex;">
                        <label for="">Organization: </label>
                        <p><?= " " . $account["orgName"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Account Created: </label>
                        <p><?= " " . $account["letterDate"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Email: </label>
                        <p><?= " " . $account["email"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Birth Date: </label>
                        <p><?= " " . $account["birthDate"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Phone Number: </label>
                        <p><?= " " . $account["phoneNumber"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Gender: </label>
                        <p><?= " " . $account['gender']==1?"Male":"Female"; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Training Manager: </label>
                        <p><?= " " . $account['isTrainer']==1?"Yes":"No"; ?></p>
                    </div>

                    <a href="userAccount.php?action=changePassword">Change Password</a>

                <?php else: ?>

                    <div style="display: flex;">
                        <label for="">Username: </label>
                        <p><?= " " . $account["username"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Full Name: </label>
                        <p><?= " " . $account["firstName"] . " " . $account["lastName"]; ?></p>
                    </div>
                    
                    <div style="display: flex;">
                        <label for="">Organization: </label>
                        <p><?= " " . $account["orgName"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Account Created: </label>
                        <p><?= " " . $account["letterDate"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Email: </label>
                        <p><?= " " . $account["email"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Birth Date: </label>
                        <p><?= " " . $account["birthDate"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Phone Number: </label>
                        <p><?= " " . $account["phoneNumber"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Gender: </label>
                        <p><?= " " . $account['gender']==1?"Male":"Female"; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Organization Administrator: </label>
                        <p><?= " " . $account['isOrgAdmin']==1?"Yes":"No"; ?></p>
                    </div>

                    <a href="userAccount.php?action=changePassword">Change Password</a>

                <?php endif; ?>

            <?php elseif($action == 'updateUser'): ?>

                <h2>Update Account Information</h2>

                <?php if($_SESSION['isSiteAdmin']):
                    $account = $userObj->getUser($userID);
                    $organization = $orgObj->getOrganization($_SESSION['orgID']);

                    if($account != null){
                        $firstName = $account['firstName'];
                        $lastName = $account['lastName'];
                        $letterDate = $account['letterDate'];
                        $email = $account['email'];
                        $phoneNumber = $account['phoneNumber'];
                        $birthDate = $account['birthDate'];
                        $gender = $account['gender'];
                        $username = $account['username'];
                        $isSiteAdmin = $account['isSiteAdmin'];
                        $isOrgAdmin = $account['isOrgAdmin'];
                        $isTrainer = $account['isTrainer'];
                        $isVerified = $account['isVerified'];
                        $profilePicture = $account['profilePicture'];
                    } else {
                        $firstName = "";
                        $lastName = "";
                        $letterDate = "";
                        $email = "";
                        $phoneNumber = "";
                        $birthDate = "";
                        $gender = "";
                        $username = "";
                        $isSiteAdmin = "";
                        $isOrgAdmin = "";
                        $isTrainer = "";
                        $isVerified = "";
                        $profilePicture = "";
                    } ?>

                    <div style="display: flex;">
                        <p>Organization: <?=$organization['orgName']?> </p>
                    </div>

                    <form action="userAccount.php?action=Viewer" class="row requires-validation" novalidate method="POST">

                        <select class="form-control text-dark col-md-12" name="department" style="height: 40px;" type="text" id="depID" name="depID" required>
                            <option value="">Select Department</option>
                            <?php foreach($deps as $d): ?>
                                <option value="<?= $d['depID']; ?>"><?= $d['depName']; ?></option>
                            <?php endforeach; ?>
                        </select>

                        <input class="form-control" type="hidden" value="<?= $userID; ?>" name="userID" required>

                        <div class="col-md-6" >
                            <label>Username:</label>
                            <input class="form-control" type="text" value="<?= $username; ?>" name="username" placeholder="Username" required>
                            <div class="valid-feedback">Username field is valid!</div>
                            <div class="invalid-feedback">Username field cannot be blank!</div>
                        </div>

                        <div class="col-md-6" >
                            <label>First Name: </label>
                            <input class="form-control" type="text" value="<?= $firstName; ?>" name="firstName" placeholder="First Name" required>
                            <div class="valid-feedback">First name field is valid!</div>
                            <div class="invalid-feedback">First name field cannot be blank!</div>
                        </div>

                        <div class="col-md-4" >
                            <label>Last Name: </label>
                            <input class="form-control" type="text" value="<?= $lastName; ?>" name="lastName" placeholder="Last Name" required>
                            <div class="valid-feedback">Last name field is valid!</div>
                            <div class="invalid-feedback">Last name field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <label>Letter Date:</label>
                            <input class="form-control" type="date" value="<?= $letterDate; ?>" name="letterDate" placeholder="Letter Date" required>
                            <div class="valid-feedback">Letter date field is valid!</div>
                            <div class="invalid-feedback">Letter date field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <label>Email:</label>
                            <input class="form-control" type="email" value="<?= $email; ?>" name="email" placeholder="Email Address" required>
                            <div class="valid-feedback">Email field is valid!</div>
                            <div class="invalid-feedback">Email field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <label>Birth Date:</label>
                            <input class="form-control" type="date" value="<?= $birthDate; ?>" name="birthDate" required>
                            <div class="valid-feedback">Last name field is valid!</div>
                            <div class="invalid-feedback">Last name field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <label>Phone Number:</label>
                            <input class="form-control" type="text" value="<?= $phoneNumber; ?>" name="phoneNumber" placeholder="Phone Number" required>
                            <div class="valid-feedback">Phone number field is valid!</div>
                            <div class="invalid-feedback">Phone number field cannot be blank!</div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="gender">Gender: </label>

                            <input type="radio" class="btn-check" name="gender" value=1 <?= $gender==1?"checked":""?> id="male" id="male" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="male">Male</label>

                            <input type="radio" class="btn-check" name="gender" value=0 <?= $gender==0?"checked":""?> id="female" id="female" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="female">Female</label>

                            <div class="valid-feedback mv-up">You selected a gender!</div>
                            <div class="invalid-feedback mv-up">Please select a gender!</div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="isSiteAdmin">Website Admin: </label>

                            <input type="radio" class="btn-check" name="isSiteAdmin" value=1 <?= $isSiteAdmin==1?"checked":""?> id="siteYes" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="siteYes">Yes</label>

                            <input type="radio" class="btn-check" name="isSiteAdmin" value=0 <?= $isSiteAdmin==0?"checked":""?> id="siteNo" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="siteNo">No</label>

                            <div class="valid-feedback mv-up">You selected a website admin status!</div>
                            <div class="invalid-feedback mv-up">Please select a website admin status!</div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="isOrgAdmin">Organization Admin: </label>

                            <input type="radio" class="btn-check" name="isOrgAdmin" value=1 <?= $isOrgAdmin==1?"checked":""?> id="orgYes" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="orgYes">Yes</label>

                            <input type="radio" class="btn-check" name="isOrgAdmin" value=0 <?= $isOrgAdmin==0?"checked":""?> id="orgNo" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="orgNo">No</label>

                            <div class="valid-feedback mv-up">You selected a organization admin status!</div>
                            <div class="invalid-feedback mv-up">Please select a organization admin status!</div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="isTrainer">Training Manager: </label>

                            <input type="radio" class="btn-check" name="isTrainer" value=1 <?= $isTrainer==1?"checked":""?> id="trYes" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="trYes">Yes</label>

                            <input type="radio" class="btn-check" name="isTrainer" value=0 <?= $isTrainer==0?"checked":""?> id="trNo" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="trNo">No</label>

                            <div class="valid-feedback mv-up">You selected a training manager status!</div>
                            <div class="invalid-feedback mv-up">Please select a training manager status!</div>
                        </div>

                        <div class="row col-4 ml-2 mb-2">
                            <label class="form-label">Profile Pictures:</label>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile1" value="..\assets\images\profilePhotos\BunnyProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\BunnyProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\BunnyProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile2" value="..\assets\images\profilePhotos\DefaultProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\DefaultProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\DefaultProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile3" value="..\assets\images\profilePhotos\DogProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\DogProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\DogProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile4" value="..\assets\images\profilePhotos\ElephantProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\ElephantProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\ElephantProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile5" value="..\assets\images\profilePhotos\FrogProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\FrogProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\FrogProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile6" value="..\assets\images\profilePhotos\HamsterProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\HamsterProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\HamsterProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile7" value="..\assets\images\profilePhotos\IceAgeProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\IceAgeProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\IceAgeProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile8" value="..\assets\images\profilePhotos\LlamaProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\LlamaProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\LlamaProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile9" value="..\assets\images\profilePhotos\PenguinProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\PenguinProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\PenguinProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile10" value="..\assets\images\profilePhotos\PolarBear.png" <?php if($profilePicture == "..\assets\images\profilePhotos\PolarBear.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\PolarBear.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile11" value="..\assets\images\profilePhotos\Porcupine.png" <?php if($profilePicture == "..\assets\images\profilePhotos\Porcupine.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\Porcupine.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile12" value="..\assets\images\profilePhotos\WalrusProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\WalrusProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\WalrusProfile.png" alt="">
                                </label>
                            </div>

                        </div>





                        <div class="form-button mt-3">
                            <button name="submitSiteAdminUpdateUser" type="submit" class="btn btn-light">Update Information</button>
                        </div>

                    </form>

                <?php elseif($_SESSION['isOrgAdmin']):
                    $account = $userObj->getUser($_SESSION['userID']);
                    $organization = $orgObj->getOrganization($_SESSION['orgID']); 

                    if($account != null){
                        $firstName = $account['firstName'];
                        $lastName = $account['lastName'];
                        $letterDate = $account['letterDate'];
                        $email = $account['email'];
                        $phoneNumber = $account['phoneNumber'];
                        $birthDate = $account['birthDate'];
                        $gender = $account['gender'];
                        $isOrgAdmin = $account['isOrgAdmin'];
                        $isTrainer = $account['isTrainer'];
                        $isVerified = $account['isVerified'];
                        $profilePicture = $account['profilePicture'];
                    } else {
                        $firstName = "";
                        $lastName = "";
                        $letterDate = "";
                        $email = "";
                        $phoneNumber = "";
                        $birthDate = "";
                        $gender = "";
                        $isOrgAdmin = "";
                        $isTrainer = "";
                        $isVerified = "";
                        $profilePicture = "";
                    }?>

                    <div style="display: flex;">
                        <p>Organization: </p>
                        <p><?= $organization['orgName']; ?></p>
                    </div>

                    <form action="userAccount.php?action=Viewer" class="requires-validation" novalidate method="POST">

                        <select class="form-control text-dark col-md-12" name="department" style="height: 40px;" type="text" id="depID" name="depID" required>
                            <option value="">Select Department</option>
                            <?php foreach($deps as $d): ?>
                                <option value="<?= $d['depID']; ?>"><?= $d['depName']; ?></option>
                            <?php endforeach; ?>
                        </select>

                        <input class="form-control" type="hidden" value="<?= $userID; ?>" name="userID" required>

                        <div class="col-md-12" >
                            <label>First Name: </label>
                            <input class="form-control" type="text" value="<?= $firstName; ?>" name="firstName" placeholder="First Name" required>
                            <div class="valid-feedback">First name field is valid!</div>
                            <div class="invalid-feedback">First name field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <label>Last Name: </label>
                            <input class="form-control" type="text" value="<?= $lastName; ?>" name="lastName" placeholder="Last Name" required>
                            <div class="valid-feedback">Last name field is valid!</div>
                            <div class="invalid-feedback">Last name field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <label>Letter Date: </label>
                            <input class="form-control" type="date" value="<?= $letterDate; ?>" name="letterDate" placeholder="Letter Date" required>
                            <div class="valid-feedback">Letter date field is valid!</div>
                            <div class="invalid-feedback">Letter date field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <label>Email: </label>
                            <input class="form-control" type="email" value="<?= $email; ?>" name="email" placeholder="Email Address" required>
                            <div class="valid-feedback">Email field is valid!</div>
                            <div class="invalid-feedback">Email field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <label>Birth Date: </label>
                            <input class="form-control" type="date" value="<?= $birthDate; ?>" name="birthDate" required>
                            <div class="valid-feedback">Last name field is valid!</div>
                            <div class="invalid-feedback">Last name field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <label>Phone Number: </label>
                            <input class="form-control" type="text" value="<?= $phoneNumber; ?>" name="phoneNumber" placeholder="Phone Number" required>
                            <div class="valid-feedback">Phone number field is valid!</div>
                            <div class="invalid-feedback">Phone number field cannot be blank!</div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="gender">Gender: </label>

                            <input type="radio" class="btn-check" name="gender" value=1 <?= $gender==1?"checked":""?> id="male" id="male" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="male">Male</label>

                            <input type="radio" class="btn-check" name="gender" value=0 <?= $gender==1?"checked":""?> id="female" id="female" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="female">Female</label>

                            <div class="valid-feedback mv-up">You selected a gender!</div>
                            <div class="invalid-feedback mv-up">Please select a gender!</div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="isOrgAdmin">Organization Admin: </label>

                            <input type="radio" class="btn-check" name="isOrgAdmin" value=1 <?= $isOrgAdmin==1?"checked":""?> id="Yes" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="Yes">Yes</label>

                            <input type="radio" class="btn-check" name="isOrgAdmin" value=0 <?= $isOrgAdmin==0?"checked":""?> id="No" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="No">No</label>

                            <div class="valid-feedback mv-up">You selected a organization admin status!</div>
                            <div class="invalid-feedback mv-up">Please select a organization admin status!</div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="isTrainer">Training Manager: </label>

                            <input type="radio" class="btn-check" name="isTrainer" value=1 <?= $isTrainer==1?"checked":""?> id="Yes" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="Yes">Yes</label>

                            <input type="radio" class="btn-check" name="isTrainer" value=0 <?= $isTrainer==0?"checked":""?> id="No" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="No">No</label>

                            <div class="valid-feedback mv-up">You selected a training manager status!</div>
                            <div class="invalid-feedback mv-up">Please select a training manager status!</div>
                        </div>



                        <div class="row col-4 ml-2 mb-2">
                            <label class="form-label">Profile Pictures:</label>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile1" value="..\assets\images\profilePhotos\BunnyProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\BunnyProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\BunnyProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile2" value="..\assets\images\profilePhotos\DefaultProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\DefaultProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\DefaultProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile3" value="..\assets\images\profilePhotos\DogProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\DogProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\DogProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile4" value="..\assets\images\profilePhotos\ElephantProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\ElephantProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\ElephantProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile5" value="..\assets\images\profilePhotos\FrogProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\FrogProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\FrogProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile6" value="..\assets\images\profilePhotos\HamsterProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\HamsterProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\HamsterProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile7" value="..\assets\images\profilePhotos\IceAgeProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\IceAgeProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\IceAgeProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile8" value="..\assets\images\profilePhotos\LlamaProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\LlamaProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\LlamaProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile9" value="..\assets\images\profilePhotos\PenguinProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\PenguinProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\PenguinProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile10" value="..\assets\images\profilePhotos\PolarBear.png" <?php if($profilePicture == "..\assets\images\profilePhotos\PolarBear.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\PolarBear.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile11" value="..\assets\images\profilePhotos\Porcupine.png" <?php if($profilePicture == "..\assets\images\profilePhotos\Porcupine.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\Porcupine.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile12" value="..\assets\images\profilePhotos\WalrusProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\WalrusProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\WalrusProfile.png" alt="">
                                </label>
                            </div>

                        </div>


                        <div class="form-button mt-3">
                            <button name="submitOrgAdminUpdateUser" type="submit" class="btn btn-light">Update Information</button>
                        </div>

                    </form>

                <?php else:
                    $account = $userObj->getUser($_SESSION['userID']);
                    $organization = $orgObj->getOrganization($_SESSION['orgID']); 

                    if($account != null){
                        $firstName = $account['firstName'];
                        $lastName = $account['lastName'];
                        $email = $account['email'];
                        $phoneNumber = $account['phoneNumber'];
                        $birthDate = $account['birthDate'];
                        $gender = $account['gender'];
                        $username = $account['username'];
                        $profilePicture = $account['profilePicture'];
                    } else {
                        $firstName = "";
                        $lastName = "";
                        $email = "";
                        $phoneNumber = "";
                        $birthDate = "";
                        $gender = "";
                        $username = "";
                        $profilePicture = "";
                    }?>

                    <div style="display: flex;">
                        <p>Organization: </p>
                        <p><?= $organization['orgName']; ?></p>
                    </div>

                    <form action="userAccount.php?action=personalSettings" class="requires-validation" novalidate method="POST">


                        <input class="form-control" type="hidden" value="<?= $userID; ?>" name="userID" required>
                        <div class="col-md-12" >
                            <label>Username: </label>
                            <input class="form-control" type="text" value="<?= $username; ?>" name="username" placeholder="Username" required>
                            <div class="valid-feedback">Username field is valid!</div>
                            <div class="invalid-feedback">Username field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <label>First Name:</label>
                            <input class="form-control" type="text" value="<?= $firstName; ?>" name="firstName" placeholder="First Name" required>
                            <div class="valid-feedback">First name field is valid!</div>
                            <div class="invalid-feedback">First name field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <label>Last Name:</label>
                            <input class="form-control" type="text" value="<?= $lastName; ?>" name="lastName" placeholder="Last Name" required>
                            <div class="valid-feedback">Last name field is valid!</div>
                            <div class="invalid-feedback">Last name field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <label>Email:</label>
                            <input class="form-control" type="email" value="<?= $email; ?>" name="email" placeholder="Email Address" required>
                            <div class="valid-feedback">Email field is valid!</div>
                            <div class="invalid-feedback">Email field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <label>Birth Date:</label>
                            <input class="form-control" type="date" value="<?= $birthDate; ?>" name="birthDate" required>
                            <div class="valid-feedback">Last name field is valid!</div>
                            <div class="invalid-feedback">Last name field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <label>Phone Number: </label>
                            <input class="form-control" type="text" value="<?= $phoneNumber; ?>" name="phoneNumber" placeholder="Phone Number" required>
                            <div class="valid-feedback">Phone number field is valid!</div>
                            <div class="invalid-feedback">Phone number field cannot be blank!</div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="gender">Gender: </label>

                            <input type="radio" class="btn-check" name="gender" value=1 <?= $gender==1?"checked":""?> id="male" id="male" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="male">Male</label>

                            <input type="radio" class="btn-check" name="gender" value=0 <?= $gender==1?"checked":""?> id="female" id="female" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="female">Female</label>

                            <div class="valid-feedback mv-up">You selected a gender!</div>
                            <div class="invalid-feedback mv-up">Please select a gender!</div>
                        </div>

                        <div class="row col-4 ml-2 mb-2">
                            <label class="form-label">Profile Pictures:</label>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile1" value="..\assets\images\profilePhotos\BunnyProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\BunnyProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\BunnyProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile2" value="..\assets\images\profilePhotos\DefaultProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\DefaultProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\DefaultProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile3" value="..\assets\images\profilePhotos\DogProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\DogProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\DogProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile4" value="..\assets\images\profilePhotos\ElephantProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\ElephantProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\ElephantProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile5" value="..\assets\images\profilePhotos\FrogProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\FrogProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\FrogProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile6" value="..\assets\images\profilePhotos\HamsterProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\HamsterProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\HamsterProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile7" value="..\assets\images\profilePhotos\IceAgeProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\IceAgeProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\IceAgeProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile8" value="..\assets\images\profilePhotos\LlamaProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\LlamaProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\LlamaProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile9" value="..\assets\images\profilePhotos\PenguinProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\PenguinProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\PenguinProfile.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile10" value="..\assets\images\profilePhotos\PolarBear.png" <?php if($profilePicture == "..\assets\images\profilePhotos\PolarBear.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\PolarBear.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile11" value="..\assets\images\profilePhotos\Porcupine.png" <?php if($profilePicture == "..\assets\images\profilePhotos\Porcupine.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\Porcupine.png" alt="">
                                </label>
                            </div>
                            <div class="form-check form-check-inline col-3">
                                <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile12" value="..\assets\images\profilePhotos\WalrusProfile.png" <?php if($profilePicture == "..\assets\images\profilePhotos\WalrusProfile.png") echo('checked') ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <img class="profilePhoto" src="..\assets\images\profilePhotos\WalrusProfile.png" alt="">
                                </label>
                            </div>

                        </div>

                        <div class="form-button mt-3">
                            <button name="submitUpdateUser" type="submit" class="btn btn-light">Update Information</button>
                        </div>

                    </form>

                <?php endif; ?>

            <?php elseif($action == 'changePassword'): ?>

                <form action="userAccount.php?action=personalSettings" class="requires-validation" novalidate method="POST">

                    <h2>Change Password</h2>

                    <ul>
                        <li>Password length should be between 8 and 20 characters</li>
                        <li>Password should contain at least one uppercase letter</li>
                        <li>Password should contain at least one lowercase letter</li>
                        <li>Password should contain at least one digit</li>
                        <li>Password should contain at least one special character</li>
                    </ul>

                    <div class="col-md-12" >
                        <input class="form-control" type="text" name="password" placeholder="New Password" required>
                        <div class="valid-feedback">Password field is valid!</div>
                        <div class="invalid-feedback">Password field cannot be blank!</div>
                    </div>

                    <div class="col-md-12" >
                        <input class="form-control" type="text" name="validatePassword" placeholder="Confirm Password" required>
                        <div class="valid-feedback">First name field is valid!</div>
                        <div class="invalid-feedback">First name field cannot be blank!</div>
                    </div>

                    <?php ?>

                    <div class="form-button mt-3">
                        <button name="submiteChangePassword" type="submit" class="btn btn-light">Change Password</button>
                    </div>

                </form>
            
            <?php elseif($action == 'Validator'): ?>
                <h3>Validate New Users</h3>
                
                <?php if(($_SESSION['isSiteAdmin'] && isset($_SESSION['orgID'])) || $_SESSION['isOrgAdmin']):                 
                    $users = $userObj->getAllUnvalidatedUsersInOrg($_SESSION['orgID']); ?>

                    <form method="POST" action="userAccount.php?action=Viewer">

                        <table class="table table-striped table-hover table-dark">
                            <thead>
                                <tr>
                                    <th>Organization</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Birth Date</th>
                                    <th>Phone</th>
                                    <th>Gender</th>
                                    <th>Username</th>
                                    <th>Organization Admin</th>
                                    <th>Training Manager</th>
                                    <th>Verified</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($users as $u): ?>
                                    <tr>                                  
                                        <td><?= $u['orgName']; ?></td>
                                        <td><?= $u['firstName']; ?></td>
                                        <td><?= $u['lastName']; ?></td>
                                        <td><?= $u['email']; ?></td>
                                        <td><?= $u['birthDate']; ?></td>
                                        <td><?= $u['phoneNumber']; ?></td>
                                        <td><?= $u['gender']==1?"Male":"Female" ?></td>
                                        <td><?= $u['username'];?></td>
                                        <td><?= $u['isOrgAdmin']==0?"No":"Yes" ?></td>
                                        <td><?= $u['isTrainer']==0?"No":"Yes" ?></td>
                                        <td>
                                            <select class="form-control" name="isValidated">
                                                <option value=""></option>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </td>
                                        <td><input type="hidden" name="userID" value="<?= $u['userID']; ?>"></td>
                                        <td><input type="submit" class="btn btn-light" name="submitValidation" value="Validate"></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </form>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const validationForm = document.getElementById("validationForm");
                            const validateButtons = validationForm.querySelectorAll(".validateBtn");

                            validateButtons.forEach(button => {
                                button.addEventListener("click", function(event) {
                                    const row = event.target.closest("tr");
                                    const isValidated = row.querySelector("select[name='isValidated']").value;
                                    const userID = row.querySelector("input[name='userID']").value;

                                    const formData = new FormData();
                                    formData.append("isValidated", isValidated);
                                    formData.append("userID", userID);

                                    fetch("userAccount.php?action=Viewer", {
                                        method: "POST",
                                        body: formData
                                    }).then(response => {
                                        // Handle response if needed
                                    }).catch(error => {
                                        console.error('Error:', error);
                                    });
                                });
                            });
                        });
                    </script>

                <?php else: 
                    header('Location: userAccount.php?action=Viewer'); ?>    
                    
                <?php endif; ?>
            
            <?php endif; ?>
        
        </div>
    </div>

<?php include __DIR__ . '/../include/footer.php'; ?>