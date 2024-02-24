<?php

    include __DIR__ . '/../include/header.php';
    include __DIR__ . '/../model/TrainingEntryDB.php';
    include __DIR__ . '/../model/UsersDB.php';
    include __DIR__ . '/../model/TrainingModuleDB.php';

    if(!isset($_SESSION['userID'])){
        header('Location: logout.php');
    }

    $userObj = new UserDB();
    $entryObj = new TrainingEntryDB();
    $moduleObj = new TrainingModuleDB();
    $users = "";
    $action = "";

    if(isset($_GET['action'])){
        $action = filter_input(INPUT_GET, 'action');
    }

    if(isset($_GET['entryID'])){
        $entryID = filter_input(INPUT_GET, 'entryID');
    }

    if(isset($_POST['searchUserButton'])){
        $courseName = filter_input(INPUT_POST, 'courseName');
        $completeDate = filter_input(INPUT_POST, 'completionDate');
        $entryDate = filter_input(INPUT_POST, 'entryDate');
        $category = filter_input(INPUT_POST, 'category');

        $entries = $entryObj->searchUserTrainingEntry($courseName, $entryDate, $completeDate, $category);

    }

    if(isset($_POST['searchAllButton'])){
        $firstName = filter_input(INPUT_POST, 'firstName');
        $lastName = filter_input(INPUT_POST, 'lastName');
        $completeDate = filter_input(INPUT_POST, 'completionDate');
        $entryDate = filter_input(INPUT_POST, 'entryDate');
        $category = filter_input(INPUT_POST, 'category');

        $entries = $entryObj->searchAllTrainingEntry($firstName, $lastName, $entryDate, $completeDate, $category);
    }

    if(isset($_POST['submitTrainingForUserFromModule'])) {
        $moduleID = filter_input(INPUT_POST, 'moduleID');
        $userID = filter_input(INPUT_POST, 'userID');
        $entryDate = filter_input(INPUT_POST, 'entryDate');
        $completeDate = filter_input(INPUT_POST, 'completionDate');

        $module = $moduleObj->getTrainingModule($moduleID);

        $courseName = $module[0]['courseName'];
        $creditHours = $module[0]['creditHours'];
        $category = $module[0]['category'];
        $description = $module[0]['description'];

        $entryObj->createTrainingEntry($userID, $courseName, $entryDate, $completeDate, $creditHours, $category, $description);
    }

    if(isset($_POST['submitManualTrainingForUser'])) {
        $userID = filter_input(INPUT_POST, 'userID');
        $courseName = filter_input(INPUT_POST, 'courseName');
        $creditHours = filter_input(INPUT_POST, 'creditHours');
        $category = filter_input(INPUT_POST, 'category');
        $description = filter_input(INPUT_POST, 'description');
        $entryDate = filter_input(INPUT_POST, 'entryDate');
        $completeDate = filter_input(INPUT_POST, 'completionDate');

        $entryObj->createTrainingEntry($userID, $courseName, $entryDate, $completeDate, $creditHours, $category, $description);
    }

    if(isset($_POST['submitTrainingModule'])) {
        $moduleID = filter_input(INPUT_POST, 'moduleID');
        $entryDate = filter_input(INPUT_POST, 'entryDate');
        $completeDate = filter_input(INPUT_POST, 'completionDate');

        $module = $moduleObj->getTrainingModule($moduleID);

        $courseName = $module[0]['courseName'];
        $creditHours = $module[0]['creditHours'];
        $category = $module[0]['category'];
        $description = $module[0]['description'];

        $entryObj->createTrainingEntry($_SESSION['userID'], $courseName, $entryDate, $completeDate, $creditHours, $category, $description);
    }

    if(isset($_POST['submitTrainingManually'])) {
        $courseName = filter_input(INPUT_POST, 'courseName');
        $creditHours = filter_input(INPUT_POST, 'creditHours');
        $category = filter_input(INPUT_POST, 'category');
        $description = filter_input(INPUT_POST, 'description');
        $entryDate = filter_input(INPUT_POST, 'entryDate');
        $completeDate = filter_input(INPUT_POST, 'completionDate');

        $entryObj->createTrainingEntry($_SESSION['userID'], $courseName, $entryDate, $completeDate, $creditHours, $category, $description);
    }

    if(isset($_POST['submitValidation'])) {
        $entryID = filter_input(INPUT_POST, 'entryID');
        $isValidated = filter_input(INPUT_POST, 'isValidated');
        $validationDate = filter_input(INPUT_POST, 'validationDate');
        $validationComments = filter_input(INPUT_POST, 'validationComments');

        $entryObj->validateTrainingEntry($entryID, $isValidated, $validateDate, $validationComments);
    }

    $users = $userObj->getAllUsersInOrg($_SESSION['orgID']); 
    $modules = $moduleObj->getAllTrainingModules($_SESSION['orgID']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Training Entries</title>
</head>
<body>
    
    <div class="mainContent">

        <?php include __DIR__ . '/../include/aside.php'; ?>

        <div class="pageContent container-fluid">

            <?php if($action == 'ViewAll'): ?>

                <h3>Training Entry Viewer</h3>

                <?php if($_SESSION['isSiteAdmin'] || $_SESSION['isOrgAdmin'] || $_SESSION['isTrainer']): 
                    $entries = $entryObj->getAllTrainingEntries($_SESSION['orgID']); ?>
                    <a class="form-control btn btn-light" href="trainingEntry.php?action=Create">Create new training entry</a>

                    <form class="requires-validation" method="POST" id="searchEntries" name="searchEntries">
                        <div style="display: flex;" class="my-2">
                            <div class="col-md-4">
                                <input class="form-control" type="text" name="firstName" placeholder="First Name">
                                <div class="valid-feedback">First name field is valid!</div>
                                <div class="invalid-feedback">First name field cannot be blank!</div>
                            </div>

                            <div class="col-md-4">
                                <input class="form-control" type="text" name="lastName" placeholder="Last Name">
                                <div class="valid-feedback">Last name field is valid!</div>
                                <div class="invalid-feedback">Last name field cannot be blank!</div>
                            </div>

                            <select class="form-control col-md-4" type="text" name="category">
                                <option value="">Select Category</option>
                                <?php 
                                    $uniqueCategories = array();
                                    foreach($entries as $e): 
                                        if(!in_array($e['category'], $uniqueCategories)) {
                                            $uniqueCategories[] = $e['category']; ?>
                                            <option value="<?= $e['category']; ?>"><?= $e['category']; ?></option>
                                        <?php 
                                        } endforeach; ?>
                            </select>
                        </div>

                        <div style="display: flex;">
                            <div class="">
                                <input class="form-control" type="date" name="entryDate" placeholder="Entry Date">
                                <div class="valid-feedback">Entry Date field is valid!</div>
                                <div class="invalid-feedback">EntryDate field cannot be blank!</div>
                            </div>

                            <div class="">
                                <input class="form-control" type="date" name="completeDate" placeholder="Completion Date">
                                <div class="valid-feedback">Completion Date field is valid!</div>
                                <div class="invalid-feedback">Completion Date field cannot be blank!</div>
                            </div>

                            <input type="submit" class="btn btn-light" id="searchAllBtn" name="searchAllButton" value="Search" />
                        </div>
                    </form>

                    <table class="table table-striped table-hover table-dark">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Course Name</th>
                                <th>Entry Date</th>
                                <th>Completion Date</th>
                                <th>Validated</th>
                                <th>Validation Date</th>
                                <th>Validation Comments</th>
                                <th>Credit Hours</th>
                                <th>Category</th>
                                <th>Description</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($entries as $e): ?>
                                <tr>                                   
                                    <td><?= $e['firstName']; ?></td>
                                    <td><?= $e['lastName']; ?></td>
                                    <td><?= $e['courseName']; ?></td>
                                    <td><?= $e['entryDate']; ?></td>
                                    <td><?= $e['completeDate']; ?></td>
                                    <td><?= $e['isValidated']==1?"Yes":"No" ?></td>
                                    <td><?= $e['validateDate'];?></td>
                                    <td><?= $e['validationComments']; ?></td>
                                    <td><?= $e['creditHours']; ?></td>
                                    <td><?= $e['category']; ?></td>
                                    <td><?= $e['description']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php else: 
                    $entries = $entryObj->getAllUserTrainingEntries($_SESSION['userID']); ?>
                    
                    <a href="trainingEntry.php?action=Create">Create new training entry</a>

                    <form class="requires-validation" method="POST" id="searchEntries" name="searchEntries" novalidate>
                        <div style="display: flex;">
                            <div class="col-md-6">
                                <input class="form-control" type="text" name="courseName" placeholder="Course Name" required>
                                <div class="valid-feedback">Course name field is valid!</div>
                                <div class="invalid-feedback">Course name field cannot be blank!</div>
                            </div>

                            <select class="form-control text-dark col-md-12" style="height: 40px;" type="text" name="category" required>
                                <option value="">Select Category</option>
                                <?php 
                                    $uniqueCategories = array();
                                    foreach($entries as $e): 
                                        if(!in_array($e['category'], $uniqueCategories)) {
                                            $uniqueCategories[] = $e['category']; ?>
                                            <option value="<?= $e['category']; ?>"><?= $e['category']; ?></option>
                                        <?php 
                                        } endforeach; ?>
                            </select>
                        </div>

                        <div style="display: flex;">
                            <div class="col-md-6">
                                <input class="form-control" type="date" name="entryDate" placeholder="Entry Date" required>
                                <div class="valid-feedback">Entry Date field is valid!</div>
                                <div class="invalid-feedback">EntryDate field cannot be blank!</div>
                            </div>

                            <div class="col-md-6">
                                <input class="form-control" type="date" name="completeDate" placeholder="Completion Date" required>
                                <div class="valid-feedback">Completion Date field is valid!</div>
                                <div class="invalid-feedback">Completion Date field cannot be blank!</div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="mb-3 mr-1" for="validated">Validated: </label>

                            <input type="radio" class="btn-check" name="validated" value=1 id="valYes" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-danger" for="valYes">Yes</label>

                            <input type="radio" class="btn-check" name="validated" value=0 id="valNo" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-danger" for="valNo">No</label>

                            <div class="valid-feedback mv-up">You selected a validation status!</div>
                            <div class="invalid-feedback mv-up">Please select a validation status!</div>
                        </div>

                        <input type="submit" class="btn btn-sm btn-danger" id="searchBtn" name="searchUserButton" value="Search" />

                    </form>

                    <table class="table table-striped table-hover table-dark">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Course Name</th>
                                <th>Entry Date</th>
                                <th>Completion Date</th>
                                <th>Validated</th>
                                <th>Validation Date</th>
                                <th>Validation Comments</th>
                                <th>Credit Hours</th>
                                <th>Category</th>
                                <th>Description</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($entries as $e): ?>
                                <tr>                                   
                                    <td><?= $e['userID']; ?></td>
                                    <td><?= $e['firstName']; ?></td>
                                    <td><?= $e['lastName']; ?></td>
                                    <td><?= $e['courseName']; ?></td>
                                    <td><?= $e['entryDate']; ?></td>
                                    <td><?= $e['completeDate']; ?></td>
                                    <td><?= $e['isValidated']==1?"Yes":"No" ?></td>
                                    <td><?= $e['validateDate'];?></td>
                                    <td><?= $e['validationComments']; ?></td>
                                    <td><?= $e['creditHours']; ?></td>
                                    <td><?= $e['category']; ?></td>
                                    <td><?= $e['description']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

            <?php elseif($action == 'Create'): ?>

                <?php if($_SESSION['isSiteAdmin'] || $_SESSION['isOrgAdmin'] || $_SESSION['isTrainer']): ?>

                    <div class="accordion" style="min-width: 1200px;" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Select Training Module
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">Course Name</th>
                                                <th scope="col">Description</th>
                                                <th scope="col">Category</th>
                                                <th scope="col">Credit Hours</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php foreach ($modules as $m): ?>
                                                <tr>                                   
                                                    <td><?= $m['courseName']; ?></td>
                                                    <td><?= $m['description']; ?></td>
                                                    <td><?= $m['category']; ?></td>
                                                    <td><?= $m['creditHours']; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>

                                    <form action="trainingEntry.php?action=ViewAll" method="POST">

                                        <div style="display: flex; flex-wrap: wrap;">
                                            <select class="form-select" name="userID" style="max-width: 200px;" aria-label="Default select example">
                                                <option value="">Select User</option>
                                                <?php 
                                                    $uniqueUsers = array();
                                                    foreach($users as $u): 
                                                        if(!in_array($u['userID'], $uniqueUsers)) {
                                                            $uniqueUsers[] = $u['userID']; ?>
                                                            <option value="<?= $u['userID']; ?>"><?= $u['firstName'] . " " . $u['lastName']; ?></option>
                                                <?php } endforeach; ?>
                                            </select>
                                        
                                            <select class="form-select" style="max-width: 800px;" name="moduleID" aria-label="Default select example">
                                                <option value="">Select Training Module</option>
                                                <?php foreach($modules as $m): ?>
                                                    <option value="<?= $m['moduleID']; ?>"><?= $m['courseName'] ?></option>
                                                <?php endforeach; ?>
                                            </select>    
                                            
                                            <div style="display: flex;">
                                                <label>Date Completed: </label>
                                                <input class="form-control" type="date" name="completionDate" value="">
                                            </div>

                                            <input type="hidden" name="entryDate" value="<?= date('Y-m-d'); ?>">

                                            <input type="submit" class="btn btn-sm btn-danger" id="submitTrainingForUserFromModule" name="submitTrainingForUserFromModule" value="Submit Training" />
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Manually Input Training
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <form action="trainingEntry.php?action=ViewAll" method="POST">
                                        <div style="display: flex;">
                                            
                                            <select class="form-select" name="userID" style="max-width: 200px;" aria-label="Default select example">
                                                <option value="">Select User</option>
                                                <?php 
                                                    $uniqueUsers = array();
                                                    foreach($users as $u): 
                                                        if(!in_array($u['userID'], $uniqueUsers)) {
                                                            $uniqueUsers[] = $u['userID']; ?>
                                                            <option value="<?= $u['userID']; ?>"><?= $u['firstName'] . " " . $u['lastName']; ?></option>
                                                <?php } endforeach; ?>
                                            </select>
                                        
                                            <div class="">
                                                <input class="form-control" type="text" name="courseName" placeholder="Course Name" required>
                                                <div class="valid-feedback">Course name field is valid!</div>
                                                <div class="invalid-feedback">Course name field cannot be blank!</div>
                                            </div>
                                        </div>

                                        <div style="display: flex;">
                                    
                                            <div class="">
                                                <input class="form-control" type="text" name="creditHours" placeholder="Credit Hours" required>
                                                <div class="valid-feedback">Credit hours field is valid!</div>
                                                <div class="invalid-feedback">Credit hours field cannot be blank!</div>
                                            </div>

                                            <div class="">
                                                <input class="form-control" type="text" name="category" placeholder="Category" required>
                                                <div class="valid-feedback">Category field is valid!</div>
                                                <div class="invalid-feedback">Category field cannot be blank!</div>
                                            </div>

                                            <div class="">
                                                <input class="form-control" type="text" name="description" placeholder="Description" required>
                                                <div class="valid-feedback">Description field is valid!</div>
                                                <div class="invalid-feedback">Description field cannot be blank!</div>
                                            </div>

                                        </div>

                                        <div style="display: flex;">
                                            <label>Date Completed: </label>
                                            <input class="form-control" type="date" name="completionDate" value="">
                                        </div>

                                        <input type="hidden" name="entryDate" value="<?= date('Y-m-d'); ?>">

                                        <input type="submit" class="btn btn-sm btn-danger" id="submitManualTrainingForUser" name="submitManualTrainingForUser" value="Submit Training" />

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    
                    <div class="accordion" style="min-width: 1200px;" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Select Training Module
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">Course Name</th>
                                                <th scope="col">Description</th>
                                                <th scope="col">Category</th>
                                                <th scope="col">Credit Hours</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php foreach ($modules as $m): ?>
                                                <tr>                                   
                                                    <td><?= $m['courseName']; ?></td>
                                                    <td><?= $m['description']; ?></td>
                                                    <td><?= $m['category']; ?></td>
                                                    <td><?= $m['creditHours']; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>

                                    <form action="trainingEntry.php?action=ViewAll">

                                        <select class="form-select" aria-label="Default select example">
                                            <option value="">Select Training Module</option>
                                            <?php foreach($modules as $m): ?>
                                                <option value="<?= $m['moduleID']; ?>"><?= $m['courseName'] ?></option>
                                            <?php endforeach; ?>
                                        </select>

                                        <input type="submit" class="btn btn-sm btn-danger" id="submitTraining" name="submitTraining" value="Submit Training" />

                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Manually Input Training
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <form action="trainingEntry.php?action=ViewAll">
                                        <div style="display: flex;">
                                        
                                            <div class="">
                                                <input class="form-control" type="text" name="courseName" placeholder="Course Name" required>
                                                <div class="valid-feedback">Course name field is valid!</div>
                                                <div class="invalid-feedback">Course name field cannot be blank!</div>
                                            </div>

                                            <div class="">
                                                <input class="form-control" type="text" name="creditHours" placeholder="Credit Hours" required>
                                                <div class="valid-feedback">Credit hours field is valid!</div>
                                                <div class="invalid-feedback">Credit hours field cannot be blank!</div>
                                            </div>

                                            <div class="">
                                                <input class="form-control" type="text" name="category" placeholder="Category" required>
                                                <div class="valid-feedback">Category field is valid!</div>
                                                <div class="invalid-feedback">Category field cannot be blank!</div>
                                            </div>

                                            <div class="">
                                                <input class="form-control" type="text" name="description" placeholder="Description" required>
                                                <div class="valid-feedback">Description field is valid!</div>
                                                <div class="invalid-feedback">Description field cannot be blank!</div>
                                            </div>
                                        </div>

                                        <div style="display: flex;">
                                            <label>Completion Date:</label>
                                            <input type="date" name="completionDate" value="">
                                        </div>
                                        
                                        <input type="hidden" name="entryDate" value="<?= date('Y-m-d'); ?>">

                                        <input type="submit" class="btn btn-sm btn-danger" id="submitTrainingManually" name="submitTraining" value="Submit Training" />

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>

            <?php elseif($action == 'Validator'): 
                if($_SESSION['isTrainer']): 
                    $entries = $entryObj->getAllUnvalidatedTrainingEntries($_SESSION['orgID']); ?>

                    <h3>Validate Training Entries</h3>

                    <form class="requires-validation" method="POST" id="searchEntries" name="searchEntries" novalidate>
                        <div style="display: flex;">
                            <div class="col-md-6">
                                <input class="form-control" type="text" name="firstName" placeholder="First Name" required>
                                <div class="valid-feedback">First name field is valid!</div>
                                <div class="invalid-feedback">First name field cannot be blank!</div>
                            </div>

                            <div class="col-md-6">
                                <input class="form-control" type="text" name="lastName" placeholder="Last Name" required>
                                <div class="valid-feedback">Last name field is valid!</div>
                                <div class="invalid-feedback">Last name field cannot be blank!</div>
                            </div>
                        </div>

                        <div style="display: flex;">
                            <div class="col-md-6">
                                <input class="form-control" type="date" name="entryDate" placeholder="Entry Date" required>
                                <div class="valid-feedback">Entry Date field is valid!</div>
                                <div class="invalid-feedback">EntryDate field cannot be blank!</div>
                            </div>

                            <div class="col-md-6">
                                <input class="form-control" type="date" name="completeDate" placeholder="Completion Date" required>
                                <div class="valid-feedback">Completion Date field is valid!</div>
                                <div class="invalid-feedback">Completion Date field cannot be blank!</div>
                            </div>
                        </div>

                        <input type="submit" class="btn btn-sm btn-danger" id="searchAllBtn" name="searchAllButton" value="Search" />

                    </form>

                    <table class="table table-striped table-hover table-dark">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Course Name</th>
                                <th>Entry Date</th>
                                <th>Completion Date</th>
                                <th>Validated</th>
                                <th>Validation Date</th>
                                <th>Validation Comments</th>
                                <th>Credit Hours</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($entries as $e): ?>
                                <tr>                                   
                                    <td><?= $e['firstName'] . " " . $e['lastName']; ?></td>
                                    <td><?= $e['courseName']; ?></td>
                                    <td><?= $e['entryDate']; ?></td>
                                    <td><?= $e['completeDate']; ?></td>
                                    <td><?= $e['isValidated']==1?"Yes":"No" ?></td>
                                    <td><?= $e['validateDate'];?></td>
                                    <td><?= $e['validationComments']; ?></td>
                                    <td><?= $e['creditHours']; ?></td>
                                    <td><?= $e['category']; ?></td>
                                    <td><?= $e['description']; ?></td>
                                    <td><a class="btn btn-light" href="trainingEntry.php?action=ValidateTraining&entryID=<?= $e['entryID']; ?>">Validate</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

            <?php elseif($action == "ValidateTraining"): 
                $entry = $entryObj->getTrainingEntry($entryID); ?>

                <h3>Validate Training Entry</h3>

                <form method="POST" action="trainingEntry.php?action=ViewAll">

                    <input type="hidden" name="entryID" value="<?= $entryID; var_dump($entryID); ?>">

                    <table class="table table-striped table-hover table-dark">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Course Name</th>
                                <th>Entry Date</th>
                                <th>Completion Date</th>
                                <th>Validated</th>
                                <th>Validation Date</th>
                                <th>Validation Comments</th>
                                <th>Credit Hours</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($entry as $e): ?>
                                <tr>                                   
                                    <td><?= $e['firstName'] . " " . $e['lastName']; ?></td>
                                    <td><?= $e['courseName']; ?></td>
                                    <td><?= $e['entryDate']; ?></td>
                                    <td><?= $e['completeDate']; ?></td>
                                    <td>
                                        <select name="isValidated">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </td>
                                    <td><input type="date" name="validationDate"></td>
                                    <td><textarea name="validationComments" cols="100" rows="4"></textarea></td>
                                    <td><?= $e['creditHours']; ?></td>
                                    <td><?= $e['category']; ?></td>
                                    <td><?= $e['description']; ?></td>
                                    <td><input class="btn btn-light" type="submit" name="submitValidation"></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </form>

            <?php endif; ?>
            
        </div>
    </div>

<?php include  __DIR__ . '/../include/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>