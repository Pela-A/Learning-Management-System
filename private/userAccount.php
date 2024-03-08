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
            $userObj->orgAdminCreateUser($_SESSION['orgID'], $firstName, $lastName, $email, $birthDate, $phoneNumber, $gender, $password, $isOrgAdmin, $isTrainer);
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
        if($_SESSION['isOrgAdmin']){
            $orgDetails = $orgObj->getOrganization($_SESSION['orgID']);
            $organization = $orgDetails['orgName'];
        }
        else{
            $organizationID = filter_input(INPUT_POST, 'organization');
            $orgDetails = $orgObj->getOrganization($organizationID);
            $organization = $orgDetails['orgName'];
        }
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
        $_SESSION['profilePicture'] = $profilePicture;

        header('Location: userAccount.php?action=Viewer');

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
        $_SESSION['profilePicture'] = $profilePicture;

        $userObj->orgAdminUpdateUser($userID, $firstName, $lastName, $letterDate, $email, $birthDate, $phoneNumber, $gender, $isOrgAdmin, $isTrainer, $profilePicture);
        
        header('Location: userAccount.php?action=Viewer');

        
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
        $_SESSION['profilePicture'] = $profilePicture;

        $users = $userObj->generalUpdateUser($userID, $firstName, $lastName, $email, $birthDate, $phoneNumber, $gender, $username, $profilePicture);
        header('Location: userAccount.php?action=personalSettings');

        //$error = validateUserInformation();

        
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
    <link rel="stylesheet" href="..\assets\css\main.css">

    <title>Account Settings</title>
</head>
<body>
    
    <div class="mainContent">

        <?php include __DIR__ . '/../include/aside.php'; ?>

        <div class="pageContent container-fluid">

            <?php if($action == 'Viewer'): ?>

                <h2>Manage User Accounts</h2>

                <?php if($_SESSION['isSiteAdmin'] && isset($_SESSION['orgID'])):
                    if(!isset($_POST['searchButton'])){
                        $users = $userObj->getAllUsersInOrg($_SESSION['orgID']);
                    }
                    ?>
                    
                    <div style="display: flex;" class="mb-3">
                        <a class="form-control btn btn-purple mr-2" href="userAccount.php?action=createUser">Create New User Account</a>
                        <a class="form-control btn btn-purple" href="orgControlPanel.php?action=Landing&ordID=<?= $orgID; ?>">Go Back</a>
                    </div>

                    <form class="requires-validation" method="POST" id="searchUsers" name="searchUsers" novalidate>
                        <div style="display: flex;">
                            <div class="">
                                <input style="width: 300px;" class="form-control" type="text" name="firstName" placeholder="First Name" required>
                                <div class="valid-feedback">First name field is valid!</div>
                                <div class="invalid-feedback">First name field cannot be blank!</div>
                            </div>

                            <div class="">
                                <input style="width: 300px;" class="form-control mx-2" type="text" name="lastName" placeholder="Last Name" required>
                                <div class="valid-feedback">Last name field is valid!</div>
                                <div class="invalid-feedback">Last name field cannot be blank!</div>
                            </div>

                            <select class="form-control" type="text" name="organization" required>
                                <option value="">Select Department</option>
                                <?php foreach($deps as $d): ?>
                                    <option value="<?= $d['depID']; ?>"><?= $d['depName']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div style="display: flex; justify-content: space-evenly;">
                            <div class="mt-3">
                                <label class="mb-3 mr-1" for="gender">Gender: </label>

                                <input type="radio" class="btn-check" name="gender" value=1 id="male" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="male">Male</label>

                                <input type="radio" class="btn-check" name="gender" value=0 id="female" autocomplete="off" required>
                                <label class="btn btn-outline-purple" for="female">Female</label>

                                <div class="valid-feedback mv-up">You selected a gender!</div>
                                <div class="invalid-feedback mv-up">Please select a gender!</div>
                            </div>

                            <div class=" mt-3">
                                <label class="mb-3 mr-1" for="isSiteAdmin">Site Admin: </label>

                                <input type="radio" class="btn-check" name="isSiteAdmin" value=1 id="siteAdminYes" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="siteAdminYes">Yes</label>

                                <input type="radio" class="btn-check" name="isSiteAdmin" value=0 id="siteAdminNo" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="siteAdminNo">No</label>

                                <div class="valid-feedback mv-up">You selected site admin status!</div>
                                <div class="invalid-feedback mv-up">Please select site admin status!</div>
                            </div>

                            <div class=" mt-3">
                                <label class="mb-3 mr-1" for="isOrgAdmin">Org Admin: </label>

                                <input type="radio" class="btn-check" name="isOrgAdmin" value=1 id="orgAdminYes" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="orgAdminYes">Yes</label>

                                <input type="radio" class="btn-check" name="isOrgAdmin" value=0 id="orgAdminNo" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="orgAdminNo">No</label>

                                <div class="valid-feedback mv-up">You selected org admin status!</div>
                                <div class="invalid-feedback mv-up">Please select org admin status!</div>
                            </div>

                            <div class=" mt-3">
                                <label class="mb-3 mr-1" for="isTrainer">Training Manager: </label>

                                <input type="radio" class="btn-check" name="isTrainer" value=1 id="trainerYes" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="trainerYes">Yes</label>

                                <input type="radio" class="btn-check" name="isTrainer" value=0 id="trainerNo" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="trainerNo">No</label>

                                <div class="valid-feedback mv-up">You selected trainer status!</div>
                                <div class="invalid-feedback mv-up">Please select trainer status!</div>
                            </div>

                            <input type="submit" class="btn btn-purple" style="height: 40px; margin-top: 13px;" name="searchButton" value="Search" />
                        </div>
                    </form>

                    <table class="table table-striped table-hover table-dark">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Organization</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Website Admin</th>
                                <th>Organization Admin</th>
                                <th>Training Manager</th>
                                <th>Verified</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" name="userID" value="<?= $u['userID']; ?>" />
                                            <input class="btn btn-purple" type="submit" name="deleteUser" value="Delete" />
                                        </form>
                                    </td>
                                    
                                    <td><?= $u['orgName']; ?></td>
                                    <td><?= $u['firstName'] . " " . $u['lastName']; ?></td>
                                    <td><?= $u['email']; ?></td>
                                    <td><?= $u['gender']==0?"Male":"Female" ?></td>
                                    <td><?= $u['isSiteAdmin']==0?"No":"Yes" ?></td>
                                    <td><?= $u['isOrgAdmin']==0?"No":"Yes" ?></td>
                                    <td><?= $u['isTrainer']==0?"No":"Yes" ?></td>
                                    <td><?= $u['isVerified']==0?"No":"Yes" ?></td>
                                    <td><a style="font-size: 14px; width: 60px; font-weight: 100px;" class="btn btn-purple" href="userAccount.php?action=updateUser&userID=<?= $u['userID']; ?>">Edit</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php elseif($_SESSION['isSiteAdmin'] && !isset($_SESSION['orgID'])): 
                    if(!isset($_POST['searchButton'])){
                        $users = $userObj->getAllUsers();
                    }
                    ?>

                    <a class="form-control btn btn-purple mb-3" href="userAccount.php?action=createUser" style="display: block;">Create New User Account</a>

                    <form class="requires-validation searchContent" method="POST" id="searchUsers" name="searchUsers" novalidate>
                        <div style="display: flex;">
                            <div class="">
                                <input style="width: 300px;" class="form-control" type="text" name="firstName" placeholder="First Name" required>
                                <div class="valid-feedback">First name field is valid!</div>
                                <div class="invalid-feedback">First name field cannot be blank!</div>
                            </div>

                            <div class="">
                                <input style="width: 300px;" class="form-control mx-2" type="text" name="lastName" placeholder="Last Name" required>
                                <div class="valid-feedback">Last name field is valid!</div>
                                <div class="invalid-feedback">Last name field cannot be blank!</div>
                            </div>

                            <select class="form-control "  type="text" name="organization" required>
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
                                <label class="btn   btn-outline-purple" for="male">Male</label>

                                <input type="radio" class="btn-check" name="gender" value=0 id="female" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="female">Female</label>

                                <div class="valid-feedback mv-up">You selected a gender!</div>
                                <div class="invalid-feedback mv-up">Please select a gender!</div>
                            </div>

                            <div class=" mt-3">
                                <label class="mb-3 mr-1" for="isSiteAdmin">Site Admin: </label>

                                <input type="radio" class="btn-check" name="isSiteAdmin" value=1 id="siteAdminYes" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="siteAdminYes">Yes</label>

                                <input type="radio" class="btn-check" name="isSiteAdmin" value=0 id="siteAdminNo" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="siteAdminNo">No</label>

                                <div class="valid-feedback mv-up">You selected site admin status!</div>
                                <div class="invalid-feedback mv-up">Please select site admin status!</div>
                            </div>

                            <div class=" mt-3">
                                <label class="mb-3 mr-1" for="isOrgAdmin">Org Admin: </label>

                                <input type="radio" class="btn-check" name="isOrgAdmin" value=1 id="orgAdminYes" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="orgAdminYes">Yes</label>

                                <input type="radio" class="btn-check" name="isOrgAdmin" value=0 id="orgAdminNo" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="orgAdminNo">No</label>

                                <div class="valid-feedback mv-up">You selected org admin status!</div>
                                <div class="invalid-feedback mv-up">Please select org admin status!</div>
                            </div>

                            <div class=" mt-3">
                                <label class="mb-3 mr-1" for="isTrainer">Training Manager: </label>

                                <input type="radio" class="btn-check" name="isTrainer" value=1 id="trainerYes" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="trainerYes">Yes</label>

                                <input type="radio" class="btn-check" name="isTrainer" value=0 id="trainerNo" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="trainerNo">No</label>

                                <div class="valid-feedback mv-up">You selected trainer status!</div>
                                <div class="invalid-feedback mv-up">Please select trainer status!</div>
                            </div>

                            <input type="submit" class="btn btn-purple" style="height: 40px; margin-top: 13px;" name="searchButton" value="Search" />
                        </div>
                    </form>

                    <table class="table table-striped table-hover table-dark">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Organization</th>
                                <th>Full Name</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Website Admin</th>
                                <th>Organization Admin</th>
                                <th>Training Manager</th>
                                <th>Verified</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" name="userID" value="<?= $u['userID']; ?>" />
                                            <input class="btn btn-purple" type="submit" name="deleteUser" value="Delete" />
                                        </form>
                                    </td>
                                    
                                    <td><?= $u['orgName']; ?></td>
                                    <td><?= $u['firstName'] . " " . $u['lastName']; ?></td>
                                    <td><?= $u['email']; ?></td>
                                    <td><?= $u['gender']==0?"Male":"Female" ?></td>
                                    <td><?= $u['isSiteAdmin']==0?"No":"Yes" ?></td>
                                    <td><?= $u['isOrgAdmin']==0?"No":"Yes" ?></td>
                                    <td><?= $u['isTrainer']==0?"No":"Yes" ?></td>
                                    <td><?= $u['isVerified']==0?"No":"Yes" ?></td>
                                    <td><a style="font-size: 14px; width: 60px; font-weight: 100px;" class="btn btn-purple" href="userAccount.php?action=updateUser&userID=<?= $u['userID']; ?>">Edit</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                <?php elseif($_SESSION['isOrgAdmin']):
                    if(!isset($_POST['searchButton'])){
                        $users = $userObj->getAllUsersInOrg($_SESSION['orgID']); 
                    }
                    $deps = $depObj->getAllDepartments($_SESSION['orgID']); ?>
                    
                    <div style="display: flex;" class="mb-3">
                        <a class="form-control btn btn-purple mr-2" href="userAccount.php?action=createUser">Create New User Account</a>
                    </div>

                    <form class="requires-validation" method="POST" id="searchUsers" name="searchUsers" novalidate>
                        <div style="display: flex;">
                            <div class="">
                                <input style="width: 300px;" class="form-control" type="text" name="firstName" placeholder="First Name" required>
                            </div>

                            <div class="">
                                <input style="width: 300px;" class="form-control mx-2" type="text" name="lastName" placeholder="Last Name" required>
                            </div>

                            <select class="form-control" type="text" name="organization" required>
                                <option value="">Select Department</option>
                                <?php foreach($deps as $d): ?>
                                    <option value="<?= $d['depID']; ?>"><?= $d['depName']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div style="display: flex; justify-content: space-evenly;">
                            <div class="mt-3">
                                <label class="mb-3 mr-1" for="gender">Gender: </label>

                                <input type="radio" class="btn-check" name="gender" value=0 id="male" autocomplete="off" required>
                                <label class="btn btn-outline-purple" for="male">Male</label>

                                <input type="radio" class="btn-check" name="gender" value=1 id="female" autocomplete="off" required>
                                <label class="btn btn-outline-purple" for="female">Female</label>

                                <div class="valid-feedback mv-up">You selected a gender!</div>
                                <div class="invalid-feedback mv-up">Please select a gender!</div>
                            </div>

                            <div class=" mt-3">
                                <label class="mb-3 mr-1" for="isOrgAdmin">Org Admin: </label>

                                <input type="radio" class="btn-check" name="isOrgAdmin" value=1 id="orgAdminYes" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="orgAdminYes">Yes</label>

                                <input type="radio" class="btn-check" name="isOrgAdmin" value=0 id="orgAdminNo" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="orgAdminNo">No</label>

                                <div class="valid-feedback mv-up">You selected org admin status!</div>
                                <div class="invalid-feedback mv-up">Please select org admin status!</div>
                            </div>

                            <div class=" mt-3">
                                <label class="mb-3 mr-1" for="isTrainer">Training Manager: </label>

                                <input type="radio" class="btn-check" name="isTrainer" value=1 id="trainerYes" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="trainerYes">Yes</label>

                                <input type="radio" class="btn-check" name="isTrainer" value=0 id="trainerNo" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="trainerNo">No</label>

                                <div class="valid-feedback mv-up">You selected trainer status!</div>
                                <div class="invalid-feedback mv-up">Please select trainer status!</div>
                            </div>

                            <input type="submit" class="btn btn-purple" style="height: 40px; margin-top: 13px;" name="searchButton" value="Search" />
                        </div>
                    </form>

                    <table class="table table-striped table-hover table-dark">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Organization</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Website Admin</th>
                                <th>Organization Admin</th>
                                <th>Training Manager</th>
                                <th>Verified</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" name="userID" value="<?= $u['userID']; ?>" />
                                            <input class="btn btn-purple" type="submit" name="deleteUser" value="Delete" />
                                        </form>
                                    </td>
                                    
                                    <td><?= $u['orgName']; ?></td>
                                    <td><?= $u['firstName'] . " " . $u['lastName']; ?></td>
                                    <td><?= $u['email']; ?></td>
                                    <td><?= $u['gender']==0?"Male":"Female" ?></td>
                                    <td><?= $u['isSiteAdmin']==0?"No":"Yes" ?></td>
                                    <td><?= $u['isOrgAdmin']==0?"No":"Yes" ?></td>
                                    <td><?= $u['isTrainer']==0?"No":"Yes" ?></td>
                                    <td><?= $u['isVerified']==0?"No":"Yes" ?></td>
                                    <td><a style="font-size: 14px; width: 60px; font-weight: 100px;" class="btn btn-purple" href="userAccount.php?action=updateUser&userID=<?= $u['userID']; ?>">Edit</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php endif; ?>
            
            <?php elseif($action == 'createUser'): ?>
                
                <h2>Create User Account</h2>
                
                <?php if($_SESSION['isSiteAdmin']): 
                    $orgs = $orgObj->getAllOrganizations(); ?>

                    <form action="userAccount.php?action=Viewer" class="requires-validation formContent mt-3" novalidate method="POST">

                        <div style="display: flex;">
                            <select class="form-control col-md-6" type="text" name="orgID" id='organization_select' required>
                                <option value="">Select Organization</option>
                                <?php foreach($orgs as $o): ?>
                                    <option value="<?= $o['orgID']; ?>"><?= $o['orgName'] . ", " . $o['state'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            
                            <select class="form-control col-md-6" type="text" name="depID" id='option_select' required>
                                <option value="">Select a Department</option>
                                
                                <?php foreach($deps as $d): ?>
                                    <option value="<?= $d['depID']; ?>"><?= $d['depName'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div style="display: flex;" class="mt-3 mb-3">
                            <div class="col-md-4" >
                                <input class="form-control" type="text" name="firstName" placeholder="First Name" required>
                                <div class="valid-feedback">First name field is valid!</div>
                                <div class="invalid-feedback">First name field cannot be blank!</div>
                            </div>

                            <div class="col-md-4" >
                                <input class="form-control" type="text" name="lastName" placeholder="Last Name" required>
                                <div class="valid-feedback">Last name field is valid!</div>
                                <div class="invalid-feedback">Last name field cannot be blank!</div>
                            </div>

                            <div class="col-md-4" >
                                <input class="form-control" type="email" name="email" placeholder="Email Address" required>
                                <div class="valid-feedback">Email field is valid!</div>
                                <div class="invalid-feedback">Email field cannot be blank!</div>
                            </div>
                        </div>
                        
                        <div style="display: flex;">
                    
                            <div class="col-md-3" >
                                <input class="form-control" type="date" name="birthDate" required>
                                <div class="valid-feedback">Last name field is valid!</div>
                                <div class="invalid-feedback">Last name field cannot be blank!</div>
                            </div>

                            <div class="col-md-3" >
                                <input class="form-control" type="text" name="phoneNumber" placeholder="Phone Number" required>
                                <div class="valid-feedback">Phone number field is valid!</div>
                                <div class="invalid-feedback">Phone number field cannot be blank!</div>
                            </div>

                            <div class="col-md-3" >
                                <input class="form-control" type="password" name="password" placeholder="Enter password" required>
                                <div class="valid-feedback">Password field is valid!</div>
                                <div class="invalid-feedback">Password field cannot be blank!</div>
                            </div>

                            <div class="col-md-3" >
                                <input class="form-control" type="password" name="confirmPassword" placeholder="Confirm password" required>
                                <div class="valid-feedback">Password field is valid!</div>
                                <div class="invalid-feedback">Password field cannot be blank!</div>
                            </div>
                        </div>

                        <div style="display: flex;">
                            <div class="col-md-3 mt-3">
                                <label class="mb-3 mr-1" for="gender">Gender: </label>

                                <input type="radio" class="btn-check" name="gender" value=0 id="male" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="male">Male</label>

                                <input type="radio" class="btn-check" name="gender" value=1 id="female" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="female">Female</label>

                                <div class="valid-feedback mv-up">You selected a gender!</div>
                                <div class="invalid-feedback mv-up">Please select a gender!</div>
                            </div>

                            <div class="col-md-3 mt-3">
                                <label class="mb-3 mr-1" for="siteAdmin">Site Admin: </label>

                                <input type="radio" class="btn-check" name="isSiteAdmin" value=1 id="siteYes" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="siteYes">Yes</label>

                                <input type="radio" class="btn-check" name="isSiteAdmin" value=0 id="siteNo" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="siteNo">No</label>

                                <div class="valid-feedback mv-up">You selected a site admin status!</div>
                                <div class="invalid-feedback mv-up">Please select a site admin status!</div>
                            </div>

                            <div class="col-md-3 mt-3">
                                <label class="mb-3 mr-1" for="orgAdmin">Organization Admin: </label>

                                <input type="radio" class="btn-check" name="isOrgAdmin" value=1 id="orgYes" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="orgYes">Yes</label>

                                <input type="radio" class="btn-check" name="isOrgAdmin" value=0 id="orgNo" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="orgNo">No</label>

                                <div class="valid-feedback mv-up">You selected a organization admin status!</div>
                                <div class="invalid-feedback mv-up">Please select a organization admin status!</div>
                            </div>

                            <div class="col-md-3 mt-3">
                                <label class="mb-3 mr-1" for="trainer">Training Manager: </label>

                                <input type="radio" class="btn-check" name="isTrainer" value=1 id="trYes" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="trYes">Yes</label>

                                <input type="radio" class="btn-check" name="isTrainer" value=0 id="trNo" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="trNo">No</label>

                                <div class="valid-feedback mv-up">You selected a training manager status!</div>
                                <div class="invalid-feedback mv-up">Please select a training manager status!</div>
                            </div>
                    
                        </div>

                        <div class="form-button mt-3">
                            <button name="submitSiteAdminCreateUser" type="submit" class="btn btn-purple">Create New User</button>
                        </div>

                    </form>

                    <script>
                        $(document).ready(function(){
                            $('#organization_select').change(function(){
                                var organization_id = $(this).val();
                                $.ajax({
                                    url: '../include/selectDepartments.php',
                                    type: 'post',
                                    data: {orgID: organization_id}, // Corrected organization ID parameter name
                                    dataType: 'json',
                                    success:function(response){
                                        console.log(response);
                                        var len = response.length;
                                        $("#option_select").empty();
                                        $("#option_select").append("<option value=''>Select Department</option>");
                                        response.forEach(function(item) {
                                            console.log($("#option_select").length)
                                            $("#option_select").append("<option value='" + item.depID + "'>" + item.depName +"</option>"); // Adjusted option format
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


                <?php elseif($_SESSION['isOrgAdmin']): 
                    $organization = $orgObj->getOrganization($_SESSION['orgID']); 
                    $deps = $depObj->getAllDepartments($_SESSION['orgID']); ?>

                    <div style="display: flex;">
                        <h4>Organization: </h4>
                        <h4 style="margin-left: 10px;"><?= $organization['orgName']; ?></h4>
                    </div>

                    <form action="userAccount.php?action=Viewer&orgID=<?= $_SESSION['orgID']; ?>" class="requires-validation formContent mt-3" novalidate method="POST">

                        <select class="form-control col-md-12" type="text" id="depID" name="depID" required>
                            <option value="">Select Department</option>
                            <?php foreach($deps as $d): ?>
                                <option value="<?= $d['depID']; ?>"><?= $d['depName']; ?></option>
                            <?php endforeach; ?>
                        </select>

                        <div class="row">
                            <div class="col-6 mt-4 mb-2" >
                                <input class="form-control" type="text" name="firstName" placeholder="First Name" required>
                                <div class="valid-feedback">First name field is valid!</div>
                                <div class="invalid-feedback">First name field cannot be blank!</div>
                            </div>

                            <div class="col-6 mt-4 mb-2" >
                                <input class="form-control" type="text" name="lastName" placeholder="Last Name" required>
                                <div class="valid-feedback">Last name field is valid!</div>
                                <div class="invalid-feedback">Last name field cannot be blank!</div>
                            </div>

                            <div class="col-6 my-2" >
                                <input class="form-control" type="email" name="email" placeholder="Email Address" required>
                                <div class="valid-feedback">Email field is valid!</div>
                                <div class="invalid-feedback">Email field cannot be blank!</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-3 my-2" >
                                <input class="form-control" type="date" name="birthDate" required>
                                <div class="valid-feedback">Last name field is valid!</div>
                                <div class="invalid-feedback">Last name field cannot be blank!</div>
                            </div>

                            <div class="col-6 my-2" >
                                <input class="form-control" type="text" name="phoneNumber" placeholder="Phone Number" required>
                                <div class="valid-feedback">Phone number field is valid!</div>
                                <div class="invalid-feedback">Phone number field cannot be blank!</div>
                            </div>

                            <div class="col-md-6 my-2" >
                                <input class="form-control" type="password" name="password" placeholder="Enter password" required>
                                <div class="valid-feedback">Password field is valid!</div>
                                <div class="invalid-feedback">Password field cannot be blank!</div>
                            </div>

                            <div class="col-md-6 my-2" >
                                <input class="form-control" type="password" name="confirmPassword" placeholder="Confirm password" required>
                                <div class="valid-feedback">Password field is valid!</div>
                                <div class="invalid-feedback">Password field cannot be blank!</div>
                            </div>
                        </div>

                        <div style="display: flex;">
                            <div class="col-md-4 mt-3">
                                <label class="mb-3 mr-1" for="gender">Gender: </label>

                                <input type="radio" class="btn-check" name="gender" value=0 id="male" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="male">Male</label>

                                <input type="radio" class="btn-check" name="gender" value=1 id="female" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="female">Female</label>

                                <div class="valid-feedback mv-up">You selected a gender!</div>
                                <div class="invalid-feedback mv-up">Please select a gender!</div>
                            </div>

                            <div class="col-md-4 mt-3">
                                <label class="mb-3 mr-1" for="orgAdmin">Organization Admin: </label>

                                <input type="radio" class="btn-check" name="orgAdmin" value=1 id="orgYes" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="orgYes">Yes</label>

                                <input type="radio" class="btn-check" name="orgAdmin" value=0 id="orgNo" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="orgNo">No</label>

                                <div class="valid-feedback mv-up">You selected a organization admin status!</div>
                                <div class="invalid-feedback mv-up">Please select a organization admin status!</div>
                            </div>

                            <div class="col-md-4 mt-3">
                                <label class="mb-3 mr-1" for="trainer">Training Manager: </label>

                                <input type="radio" class="btn-check" name="trainer" value=1 id="trYes" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="trYes">Yes</label>

                                <input type="radio" class="btn-check" name="trainer" value=0 id="trNo" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="trNo">No</label>

                                <div class="valid-feedback mv-up">You selected a training manager status!</div>
                                <div class="invalid-feedback mv-up">Please select a training manager status!</div>
                            </div>
                        </div>

                        <div class="form-button mt-3">
                            <button id="submit" name="submitOrgAdminCreateUser" type="submit" class="btn btn-purple">Create New User</button>
                        </div>

                    </form>

                <?php endif; ?>

            <?php elseif($action == 'personalSettings'):
                $account = $userObj->getUser($_SESSION['userID']);
                ?>

                <h2>Account Settings</h2>

                <div style="display: flex; margin-left: 10px; margin-bottom: 10px; margin-top: 10px;">
            
                    <a class="btn btn-purple" href="userAccount.php?action=updateUser&userID=<?= $account['userID']; ?>">Make Changes to Account</a>
                    <a style="margin-left: 10px;" class="btn btn-purple" href="userAccount.php?action=changePassword">Change Password</a>

                </div>
                
                <div class="row">

                    <div class="accountSettingsInfo col-3 py-4 px-3">

                        <div style="display: flex;">
                            <p>Username: <?=$account["username"]; ?></p>
                        </div>

                        <div style="display: flex;">
                            <p>Full Name: <?=$account["firstName"] . " " . $account["lastName"]; ?></p>
                        </div>

                        <div style="display: flex;">
                            <p>Account Created: <?=$account["letterDate"]; ?></p>
                        </div>

                        <div style="display: flex;">
                            <p>Email: <?=$account["email"]; ?></p>
                        </div>

                        <div style="display: flex;">
                            <p>Birthdate: <?= $account["birthDate"]; ?></p>
                        </div>

                        <div style="display: flex;">
                            <p>Phone Number: <?=$account["phoneNumber"]; ?></p>
                        </div>

                        <div style="display: flex;">
                            <p>Gender: <?=$account['gender']==0?"Male":"Female"; ?></p>
                        </div>

                        <?php if($_SESSION['isSiteAdmin']): ?>
                        <div style="display: flex;">
                            <p>Website Administrator: <?=$account['isSiteAdmin']==1?"Yes":"No"; ?></p>
                        </div>

                        <div style="display: flex;">
                            <p>Organization Administrator: <?=$account['isOrgAdmin']==1?"Yes":"No"; ?></p>
                        </div>

                        <div style="display: flex;">
                            <p>Training Manager: <?=$account['isTrainer']==1?"Yes":"No"; ?></p>
                        </div>

                        <?php elseif($_SESSION['isOrgAdmin']): ?>
                        <div style="display: flex;">
                            <p>Organization Administrator: <?=$account['isOrgAdmin']==1?"Yes":"No"; ?></p>
                        </div>

                        <div style="display: flex;">
                            <p>Training Manager: <?=$account['isTrainer']==1?"Yes":"No"; ?></p>
                        </div>

                        <?php elseif($_SESSION['isTrainer']): ?>
                        <div style="">
                            <p>Training Manager: <?=$account['isTrainer']==1?"Yes":"No"; ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                

                

            <?php elseif($action == 'updateUser'): ?>

                <h2>Update Account Information</h2>

                <?php if($_SESSION['isSiteAdmin']):
                    $account = $userObj->getUser($userID);
                    $organization = $orgObj->getOrganization($account['orgID']);

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

                    <p>Organization: <?=$organization['orgName']?> </p>
                    <form action="userAccount.php?action=Viewer" class="requires-validation row" novalidate method="POST">

                        <div class="row formContent col-7 mr-4">
                            <h3>User Information</h3>

                            <input class="form-control" type="hidden" value="<?= $userID; ?>" name="userID" required>
                        

                            
                            <div class="col-8" >
                                <label style="margin-top: 15px;">Username:</label>
                                <input class="form-control" type="text" value="<?= $username; ?>" name="username" placeholder="Username" readonly>
                                <div class="valid-feedback">Username field is valid!</div>
                                <div class="invalid-feedback">Username field cannot be blank!</div>
                            </div>

                            <div class="col-6 mt-2" >
                                <label>First Name: </label>
                                <input class="form-control" type="text" value="<?= $firstName; ?>" name="firstName" placeholder="First Name" required>
                                <div class="valid-feedback">First name field is valid!</div>
                                <div class="invalid-feedback">First name field cannot be blank!</div>
                            </div>

                            <div class="col-6 mt-2" >
                                <label>Last Name: </label>
                                <input class="form-control" type="text" value="<?= $lastName; ?>" name="lastName" placeholder="Last Name" required>
                                <div class="valid-feedback">Last name field is valid!</div>
                                <div class="invalid-feedback">Last name field cannot be blank!</div>
                            </div>
         
                            <div class="col-md-6" >
                                <label>Letter Date:</label>
                                <input class="form-control" type="date" value="<?= $letterDate; ?>" name="letterDate" placeholder="Letter Date" required>
                                <div class="valid-feedback">Letter date field is valid!</div>
                                <div class="invalid-feedback">Letter date field cannot be blank!</div>
                            </div>

                            <div class="col-md-6" >
                                <label>Email:</label>
                                <input class="form-control" type="email" value="<?= $email; ?>" name="email" placeholder="Email Address" readonly>
                                <div class="valid-feedback">Email field is valid!</div>
                                <div class="invalid-feedback">Email field cannot be blank!</div>
                            </div>

                            <div class="col-md-6" >
                                <label>Birth Date:</label>
                                <input class="form-control" type="date" value="<?= $birthDate; ?>" name="birthDate" required>
                                <div class="valid-feedback">Last name field is valid!</div>
                                <div class="invalid-feedback">Last name field cannot be blank!</div>
                            </div>

                            <div class="col-md-6" >
                                <label>Phone Number:</label>
                                <input class="form-control" type="text" value="<?= $phoneNumber; ?>" name="phoneNumber" placeholder="Phone Number" required>
                                <div class="valid-feedback">Phone number field is valid!</div>
                                <div class="invalid-feedback">Phone number field cannot be blank!</div>
                            </div>
                            

                            <div class="col-md-6 mt-2">
                                <label class="mb-3 mr-1" for="gender">Gender: </label>

                                <input type="radio" class="btn-check" name="gender" value=0 <?= $gender==0?"checked":""?> id="male" id="male" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="male">Male</label>

                                <input type="radio" class="btn-check" name="gender" value=1 <?= $gender==1?"checked":""?> id="female" id="female" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="female">Female</label>

                                <div class="valid-feedback mv-up">You selected a gender!</div>
                                <div class="invalid-feedback mv-up">Please select a gender!</div>
                            </div>

                            <div class="col-md-6">
                                <label class="mb-3 mr-1" for="isSiteAdmin">Website Admin: </label>

                                <input type="radio" class="btn-check" name="isSiteAdmin" value=1 <?= $isSiteAdmin==1?"checked":""?> id="siteYes" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="siteYes">Yes</label>

                                <input type="radio" class="btn-check" name="isSiteAdmin" value=0 <?= $isSiteAdmin==0?"checked":""?> id="siteNo" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="siteNo">No</label>

                                <div class="valid-feedback mv-up">You selected a website admin status!</div>
                                <div class="invalid-feedback mv-up">Please select a website admin status!</div>
                            </div>

                            <div class="col-md-6">
                                <label class="mb-3 mr-1" for="isOrgAdmin">Organization Admin: </label>

                                <input type="radio" class="btn-check" name="isOrgAdmin" value=1 <?= $isOrgAdmin==1?"checked":""?> id="orgYes" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="orgYes">Yes</label>

                                <input type="radio" class="btn-check" name="isOrgAdmin" value=0 <?= $isOrgAdmin==0?"checked":""?> id="orgNo" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="orgNo">No</label>

                                <div class="valid-feedback mv-up">You selected a organization admin status!</div>
                                <div class="invalid-feedback mv-up">Please select a organization admin status!</div>
                            </div>

                            <div class="col-md-6">
                                <label class="mb-3 mr-1" for="isTrainer">Training Manager: </label>

                                <input type="radio" class="btn-check" name="isTrainer" value=1 <?= $isTrainer==1?"checked":""?> id="trYes" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="trYes">Yes</label>

                                <input type="radio" class="btn-check" name="isTrainer" value=0 <?= $isTrainer==0?"checked":""?> id="trNo" autocomplete="off" required>
                                <label class="btn   btn-outline-purple" for="trNo">No</label>

                                <div class="valid-feedback mv-up">You selected a training manager status!</div>
                                <div class="invalid-feedback mv-up">Please select a training manager status!</div>
                            </div>

                        </div>

                        <div class="formContent col-4 ml-2">
                            <h3>Profile Pictures:</h3>
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
                            <button name="submitSiteAdminUpdateUser" type="submit" class="btn btn-purple">Update Information</button>
                        </div>

                    </form>

                <?php elseif($_SESSION['isOrgAdmin']):
                    $account = $userObj->getUser($userID);
                    $organization = $orgObj->getOrganization($_SESSION['orgID']);
                    $deps = $depObj->getAllDepartments($_SESSION['orgID']); 

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

                    <form action="userAccount.php?action=Viewer" class="requires-validation row" novalidate method="POST">

                        <div class=" formContent col-7">

                
                            <select class="form-select col-8" name="department" type="text" id="depID" name="depID" required>
                                <option value="">Select Department</option>
                                <?php foreach($deps as $d): ?>
                                    <option value="<?= $d['depID']; ?>"><?= $d['depName']; ?></option>
                                <?php endforeach; ?>
                            </select>

                            <input class="form-control" type="hidden" value="<?= $userID; ?>" name="userID" required>

                            <div class="col-8" >
                                <label>First Name: </label>
                                <input class="form-control" type="text" value="<?= $firstName; ?>" name="firstName" placeholder="First Name" required>
                                <div class="valid-feedback">First name field is valid!</div>
                                <div class="invalid-feedback">First name field cannot be blank!</div>
                            </div>

                            <div class="col-8" >
                                <label>Last Name: </label>
                                <input class="form-control" type="text" value="<?= $lastName; ?>" name="lastName" placeholder="Last Name" required>
                                <div class="valid-feedback">Last name field is valid!</div>
                                <div class="invalid-feedback">Last name field cannot be blank!</div>
                            </div>

                            <div class="col-8" >
                                <label>Letter Date: </label>
                                <input class="form-control" type="date" value="<?= $letterDate; ?>" name="letterDate" placeholder="Letter Date" required>
                                <div class="valid-feedback">Letter date field is valid!</div>
                                <div class="invalid-feedback">Letter date field cannot be blank!</div>
                            </div>

                            <div class="col-8" >
                                <label>Email: </label>
                                <input class="form-control" type="email" value="<?= $email; ?>" name="email" placeholder="Email Address" readonly>
                                <div class="valid-feedback">Email field is valid!</div>
                                <div class="invalid-feedback">Email field cannot be blank!</div>
                            </div>

                            <div class="col-8" >
                                <label>Birth Date: </label>
                                <input class="form-control" type="date" value="<?= $birthDate; ?>" name="birthDate" required>
                                <div class="valid-feedback">Last name field is valid!</div>
                                <div class="invalid-feedback">Last name field cannot be blank!</div>
                            </div>

                            <div class="col-8" >
                                <label>Phone Number: </label>
                                <input class="form-control" type="text" value="<?= $phoneNumber; ?>" name="phoneNumber" placeholder="Phone Number" required>
                                <div class="valid-feedback">Phone number field is valid!</div>
                                <div class="invalid-feedback">Phone number field cannot be blank!</div>
                            </div>

                            <div class="col-8 mt-3">
                                <label class="mb-3 mr-1" for="gender">Gender: </label>

                                <input type="radio" class="btn-check" name="gender" value=0 <?= $gender==0?"checked":""?> id="male" id="male" autocomplete="off" required>
                                <label class="btn btn-outline-purple" for="male">Male</label>

                                <input type="radio" class="btn-check" name="gender" value=1 <?= $gender==1?"checked":""?> id="female" id="female" autocomplete="off" required>
                                <label class="btn btn-outline-purple" for="female">Female</label>

                                <div class="valid-feedback mv-up">You selected a gender!</div>
                                <div class="invalid-feedback mv-up">Please select a gender!</div>
                            </div>

                            <div class="col-8 mt-3">
                                <label class="mb-3 mr-1" for="isOrgAdmin">Organization Admin: </label>

                                <input type="radio" class="btn-check" name="isOrgAdmin" value=1 <?= $isOrgAdmin==1?"checked":""?> id="YesOrgAdmin" autocomplete="off" required>
                                <label class="btn btn-outline-purple" for="YesOrgAdmin">Yes</label>

                                <input type="radio" class="btn-check" name="isOrgAdmin" value=0 <?= $isOrgAdmin==0?"checked":""?> id="NoOrgAdmin" autocomplete="off" required>
                                <label class="btn btn-outline-purple" for="NoOrgAdmin">No</label>

                                <div class="valid-feedback mv-up">You selected a organization admin status!</div>
                                <div class="invalid-feedback mv-up">Please select a organization admin status!</div>
                            </div>

                            <div class="col-8 mt-3">
                                <label class="mb-3 mr-1" for="isTrainer">Training Manager: </label>

                                <input type="radio" class="btn-check" name="isTrainer" value=1 <?= $isTrainer==1?"checked":""?> id="YesTrainer" autocomplete="off" required>
                                <label class="btn btn-outline-purple" for="YesTrainer">Yes</label>

                                <input type="radio" class="btn-check" name="isTrainer" value=0 <?= $isTrainer==0?"checked":""?> id="NoTrainer" autocomplete="off" required>
                                <label class="btn btn-outline-purple" for="NoTrainer">No</label>

                                <div class="valid-feedback mv-up">You selected a training manager status!</div>
                                <div class="invalid-feedback mv-up">Please select a training manager status!</div>
                            </div>

                        </div>



                        <div class="formContent col-4 ml-2">
                            <h3>Profile Pictures:</h3>
                            
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
                            <button name="submitOrgAdminUpdateUser" type="submit" class="btn btn-purple">Update Information</button>
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

                    
                    
                    

                    <form action="userAccount.php?action=personalSettings" class="needs-validation row" novalidate method="POST">

                        <p>Organization: <?= $organization['orgName']; ?></p>

                        <div class=" formContent col-7">

                        

                            <input class="form-control" type="hidden" value="<?= $userID; ?>" name="userID">
                            <h3>User Information</h3>
                            <div class="col-8" >
                                <label>Username: </label>
                                <input class="form-control" type="text" value="<?= $username; ?>" name="username" placeholder="Username" readonly>
                            </div>

                            <div class="col-8" >
                                <label>First Name:</label>
                                <input class="form-control" type="text" value="<?= $firstName; ?>" name="firstName" id="firstName" placeholder="First Name" onchange="validateFirstName(); checkInputs();">
                                <div id="firstFeedback" class="invalid-feedback">First name field cannot be blank!</div>
                            </div>

                            <div class="col-8" >
                                <label>Last Name:</label>
                                <input class="form-control" type="text" value="<?= $lastName; ?>" name="lastName" id="lastName" placeholder="Last Name" onchange="validateLastName(); checkInputs();">
                                <div id="lastFeedback" class="invalid-feedback">Last name field cannot be blank!</div>
                            </div>

                            <div class="col-8" >
                                <label>Email:</label>
                                <input class="form-control" type="text" value="<?= $email; ?>" name="email" id="email" placeholder="Email Address" readonly>
                                <div id="emailFeedback" class="invalid-feedback">Email field cannot be blank!</div>
                            </div>

                            <div class="col-8" >
                                <label>Birth Date:</label>
                                <input class="form-control" type="date" value="<?= $birthDate; ?>" name="birthDate" id="birthdate" onchange="validateBirthday(); checkInputs();">
                                <div id="birthdayFeedback" class="invalid-feedback">Last name field cannot be blank!</div>
                            </div>

                            <div class="col-8" >
                                <label>Phone Number: </label>
                                <input class="form-control" type="text" value="<?= $phoneNumber; ?>" name="phoneNumber" id="phoneNum" placeholder="Phone Number" onchange="validatePhoneNumber(); checkInputs();">
                                <div id="phoneFeedback" class="invalid-feedback">Phone number field cannot be blank!</div>
                            </div>

                            <div class="col-8 mt-3">
                                <label class="mb-3 mr-1" for="gender">Gender: </label>

                                <input type="radio" class="btn-check" name="gender" value=0 <?= $gender==0 ? "checked": ""?> id="male">
                                <label class="btn btn-outline-purple" for="male">Male</label>

                                <input type="radio" class="btn-check" name="gender" value=1 <?= $gender==1 ? "checked": ""?> id="female">
                                <label class="btn btn-outline-purple" for="female">Female</label>

                            </div>
                        </div>
                        <div class="formContent col-4 ml-2">
                            <h3>Profile Pictures</h3>
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
                            <button name="submitUpdateUser" id="update" type="submit" class="btn btn-purple">Update Information</button>
                        </div>

                    </form>

                    <script>

                        // Example starter JavaScript for disabling form submissions if there are invalid fields
                        (function () {
                        'use strict'

                        // Fetch all the forms we want to apply custom Bootstrap validation styles to
                        var forms = document.querySelectorAll('.needs-validation')

                        // Loop over them and prevent submission
                        Array.prototype.slice.call(forms)
                            .forEach(function (form) {
                            form.addEventListener('submit', async function (event) {
                                
                                await validateFirstName() 
                                await validateLastName() 
                                await validatePhoneNumber() 
                                await validateBirthday() 
                                
                                checkInputs()
                                
                                
                                
                            }, false)
                        })
                        })()

                        //validate birthday function
                        function validateBirthday(){
                            var birthday = $('#birthdate').val();
                            if(birthday == ""){
                                $('#birthdate').removeClass('is-valid').addClass('is-invalid');
                                $('#birthdayFeedback').html('Enter a Birthday.').removeClass('valid-feedback').addClass('invalid-feedback');
                            }else{
                                $('#birthdate').removeClass('is-invalid').addClass('is-valid');
                                $('#birthdayFeedback').html('Valid Birthday.').removeClass('invalid-feedback').addClass('valid-feedback');
                            }
                        }

                        function validateFirstName(){
                            var firstName = $('#firstName').val()
                            pattern = /^[A-Za-z]+$/
                            if(firstName != ""){
                                if(pattern.test(firstName)){
                                    $('#firstName').removeClass('is-invalid').addClass('is-valid');
                                    $('#firstFeedback').html('Valid First Name!').removeClass('invalid-feedback').addClass('valid-feedback');
                                }
                                else{
                                    $('#firstName').removeClass('is-valid').addClass('is-invalid');
                                    $('#firstFeedback').html('Invalid First Name!').removeClass('valid-feedback').addClass('invalid-feedback');
                                }
                            }else{
                                $('#firstName').removeClass('is-valid').addClass('is-invalid');
                                $('#firstFeedback').html('Enter a First Name!').removeClass('valid-feedback').addClass('invalid-feedback');
                            }
                            
                        }

                        function validateLastName(){
                            var lastName = $('#lastName').val()
                            pattern = /^[A-Za-z]+$/
                            if(lastName != ""){
                                if(pattern.test(lastName)){
                                    $('#lastName').removeClass('is-invalid').addClass('is-valid');
                                    $('#lastFeedback').html('Valid First Name!').removeClass('invalid-feedback').addClass('valid-feedback');
                                }
                                else{
                                    $('#lastName').removeClass('is-valid').addClass('is-invalid');
                                    $('#lastFeedback').html('Invalid First Name!').removeClass('valid-feedback').addClass('invalid-feedback');
                                }
                            }else{
                                $('#lastName').removeClass('is-valid').addClass('is-invalid');
                                $('#lastFeedback').html('Enter a First Name!').removeClass('valid-feedback').addClass('invalid-feedback');
                            }
                        }

                        function validatePhoneNumber(){
                            var phoneNum = $('#phoneNum').val()
                            pattern = /[0-9]{10}/
                            if(phoneNum != ""){
                                if(pattern.test(phoneNum)){
                                    $('#phoneNum').removeClass('is-invalid').addClass('is-valid');
                                    $('#phoneFeedback').html('Valid Phone Number!').removeClass('invalid-feedback').addClass('valid-feedback');
                                }
                                else{
                                    $('#phoneNum').removeClass('is-valid').addClass('is-invalid');
                                    $('#phoneFeedback').html('Invalid Phone Number! (10 Digits Only)').removeClass('valid-feedback').addClass('invalid-feedback');
                                }
                            }else{
                                $('#phoneNum').removeClass('is-valid').addClass('is-invalid');
                                $('#phoneFeedback').html('Enter a Phone Number!').removeClass('valid-feedback').addClass('invalid-feedback');
                            }

                        }

                        //check inputs and disable button if error.
                        function checkInputs(){
                            var form = document.querySelector('.needs-validation')
                            var inputs = form.getElementsByTagName("input");
                            for (var i = 0; i < inputs.length; i++) {
                                // Check if the current input element has the specified class
                                if (inputs[i].classList.contains("is-invalid")) {
                                    $('#update').prop('disabled', true); 
                                    event.preventDefault()
                                    event.stopPropagation()
                                    break;
                                }else {
                                    // Class is not applied to this input element
                                    $('#update').prop('disabled', false); 
                                }
                            }
                        }
                    </script>

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

                    <div style="display: flex;">
                        <div class="col-md-6" >
                            <input class="form-control" type="password" name="password" placeholder="New Password" required>
                            <div class="valid-feedback">Password field is valid!</div>
                            <div class="invalid-feedback">Password field cannot be blank!</div>
                            <div id="passwordCriteriaError"></div>
                        </div>

                        <div class="col-md-6" >
                            <input class="form-control" type="password" name="validatePassword" placeholder="Confirm Password" required>
                            <div class="valid-feedback">First name field is valid!</div>
                            <div class="invalid-feedback">First name field cannot be blank!</div>
                            <div id="passwordError"></div>
                        </div>
                    </div>

                    <div class="form-button mt-3 ml-3">
                        <button name="submitChangePassword" onclick="return validateForm()" type="submit" class="btn btn-purple">Change Password</button>
                    </div>

                </form>

                <script>
                    function validatePassword(password) {
                        // Password length should be between 8 and 20 characters
                        if (password.length < 8 || password.length > 20) {
                            return false;
                        }

                        // Password should contain at least one uppercase letter
                        if (!/[A-Z]/.test(password)) {
                            return false;
                        }

                        // Password should contain at least one lowercase letter
                        if (!/[a-z]/.test(password)) {
                            return false;
                        }

                        // Password should contain at least one digit
                        if (!/\d/.test(password)) {
                            return false;
                        }

                        // Password should contain at least one special character
                        if (!/[^a-zA-Z0-9]/.test(password)) {
                            return false;
                        }

                        return true;
                    }

                    // Function to check if passwords match
                    function checkPasswordMatch() {
                        var password = document.getElementsByName("password")[0].value;
                        var confirmPassword = document.getElementsByName("validatePassword")[0].value;
                        var errorDiv = document.getElementById("passwordError");

                        if (password !== confirmPassword) {
                            errorDiv.innerHTML = "Passwords do not match!";
                            errorDiv.style.color = "red";
                            return false;
                        } else {
                            errorDiv.innerHTML = "";
                            return true;
                        }
                    }

                    // Function to validate password criteria
                    function validatePasswordCriteria() {
                        var password = document.getElementsByName("password")[0].value;
                        var isValid = validatePassword(password);

                        if (!isValid) {
                            document.getElementById("passwordCriteriaError").innerHTML = "Password must be between 8 and 20 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
                            document.getElementById("passwordCriteriaError").style.color = "red";
                            return false;
                        } else {
                            document.getElementById("passwordCriteriaError").innerHTML = "";
                            return true;
                        }
                    }

                    // Function to validate all criteria before allowing submission
                    function validateForm() {
                        return checkPasswordMatch() && validatePasswordCriteria();
                    }
                </script>
            
            <?php elseif($action == 'Validator'): ?>
                <h2>Validate New Users</h2>
                
                <?php if(($_SESSION['isSiteAdmin'] && isset($_SESSION['orgID'])) || $_SESSION['isOrgAdmin']):                 
                    $users = $userObj->getAllUnvalidatedUsersInOrg($_SESSION['orgID']); ?>

                    <?php if($_SESSION['isSiteAdmin']): ?>
                    <a class="form-control btn btn-purple mb-3" style="display:block;" href="orgControlPanel.php?action=Landing&ordID=<?= $_SESSION['orgID']; ?>">Go Back</a>

                    <?php endif; ?>
                    <form method="POST" action="userAccount.php?action=Viewer&orgID=<?= $_SESSION['orgID']; ?>">

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
                                        <td><?= $u['gender']==0?"Male":"Female" ?></td>
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
                                        <td><input type="submit" class="btn btn-purple" name="submitValidation" value="Validate"></td>
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