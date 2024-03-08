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

    if(isset($_GET['userID'])){
        $userID = filter_input(INPUT_GET, 'userID');
    }

    if(isset($_POST['searchAllButton'])){
        $firstName = filter_input(INPUT_POST, 'firstName');
        $lastName = filter_input(INPUT_POST, 'lastName');
        $courseName = filter_input(INPUT_POST, 'courseName');
        $category = filter_input(INPUT_POST, 'category');

        $entries = $entryObj->searchAllTrainingEntry($_SESSION['orgID'], $firstName, $lastName, $courseName, $category);
    }
    else{
        $entries = $entryObj->getAllTrainingEntries($_SESSION['orgID']);
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
        $entryDate = date('Y-m-d');
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

        echo($_SESSION['userID']);
        $entryObj->createTrainingEntry($_SESSION['userID'], $courseName, $entryDate, $completeDate, $creditHours, $category, $description);
    }

    if(isset($_POST['submitValidation'])) {
        $entry = filter_input(INPUT_POST, 'entryID');
        $isValidated = filter_input(INPUT_POST, 'isValidated');
        $validateDate = filter_input(INPUT_POST, 'validationDate');
        $validationComments = filter_input(INPUT_POST, 'validationComments');

        $entryObj->validateTrainingEntry($entry, $isValidated, $validateDate, $validationComments);
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
    <link rel="stylesheet" href="..\assets\css\main.css">

    <title>Training Entries</title>
</head>
<body>
    
    <div class="mainContent">

        <?php include __DIR__ . '/../include/aside.php'; ?>

        <div class="pageContent container-fluid">

            <?php if($action == 'ViewAll'): ?>

                <h2>Training Entry Viewer</h2>

                <?php if(isset($userID)):
                    if(isset($_POST['searchUserButton'])){
                        $courseName = filter_input(INPUT_POST, 'courseName');
                        $category = filter_input(INPUT_POST, 'category');

                        $entries = $entryObj->searchUserTrainingEntry($_SESSION['userID'], $courseName, $category);
                    }else {
                        $entries = $entryObj->getAllUserTrainingEntries($_SESSION['userID']);
                    } 
                    ?>
                    
                    
                    <a class="btn btn-purple" style="margin-bottom: 10px; display:block;" href="trainingEntry.php?action=Create">Create new training entry</a>

                    <form class="requires-validation mb-3" method="POST" id="searchEntries" name="searchEntries" novalidate>
                        <div style="display: flex; justify-content: space-between;" class="searchContent">
                            <div class="col-4">
                                <input class="form-control" type="text" name="courseName" placeholder="Course Name" required>
                            </div>

                            <select class="form-control col-4 mr-2" type="text" name="category" required>
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

                            <input type="submit" style="max-height: 40px;" class="btn btn-purple" name="searchUserButton" value="Search" />
                        </div>
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

                <?php elseif($_SESSION['isSiteAdmin'] || $_SESSION['isOrgAdmin'] || $_SESSION['isTrainer']): ?>
                    <a class="btn btn-purple" style="margin-bottom: 10px; display:block;" href="trainingEntry.php?action=Create">Create new training entry</a>

                    <form class="requires-validation mb-3" method="POST" id="searchEntries" name="searchEntries">
                        <div style="display: flex; justify-content: space-between;" class="searchContent">
                            <div class="col-md-3">
                                <input class="form-control" type="text" name="firstName" placeholder="First Name">
                            </div>

                            <div class="col-md-3">
                                <input class="form-control" type="text" name="lastName" placeholder="Last Name">
                            </div>

                            <select class="form-control col-md-3 mx-3" type="text" name="category">
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

                            <input type="submit" class="btn btn-purple" id="searchAllBtn" name="searchAllButton" value="Search" />
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


                    
                <?php endif; ?>

            <?php elseif($action == 'Create'): ?>

                <?php if($_SESSION['isSiteAdmin'] || $_SESSION['isOrgAdmin'] || $_SESSION['isTrainer']): ?>

                    <div class="accordion" style="min-width: 1200px;" id="accordionExample">
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Select Training Module
                                </button>
                            </h3>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <table class="table table-striped table-dark table-hover">
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

                                    <form action="trainingEntry.php?action=ViewAll" class="py-3" method="POST">

                                        <select class="form-select mb-3" name="userID" style="max-width: 200px;" aria-label="Default select example">
                                            <option value="">Select User</option>
                                            <?php 
                                                $uniqueUsers = array();
                                                foreach($users as $u): 
                                                    if(!in_array($u['userID'], $uniqueUsers)) {
                                                        $uniqueUsers[] = $u['userID']; ?>
                                                        <option value="<?= $u['userID']; ?>"><?= $u['firstName'] . " " . $u['lastName']; ?></option>
                                            <?php } endforeach; ?>
                                        </select>
                                    
                                        <select class="form-select mb-3" style="max-width: 400px;" name="moduleID" aria-label="Default select example">
                                            <option value="">Select Training Module</option>
                                            <?php foreach($modules as $m): ?>
                                                <option value="<?= $m['moduleID']; ?>"><?= $m['courseName'] ?></option>
                                            <?php endforeach; ?>
                                        </select>    
                                        
                                        <div style="display: flex;" class="col-6 mb-3 px-0">
                                            <label class="col-3" >Completion Date:</label>
                                            <input class="form-control" type="date" name="completionDate" id="completiondate" onchange="">
                                        </div>

                                        <input type="hidden" name="entryDate" value="<?= date('Y-m-d'); ?>">

                                        <input type="submit" class="btn  btn-purple" id="submitTrainingForUserFromModule" name="submitTrainingForUserFromModule" value="Submit Training" />

                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Manually Input Training
                                </button>
                            </h3>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <form action="trainingEntry.php?action=ViewAll" method="POST">
                                        <div class="row">
                                            
                                            <select class="col-6 py-2 form-select" name="userID" aria-label="Default select example">
                                                <option value="">Select User</option>
                                                <?php 
                                                    $uniqueUsers = array();
                                                    foreach($users as $u): 
                                                        if(!in_array($u['userID'], $uniqueUsers)) {
                                                            $uniqueUsers[] = $u['userID']; ?>
                                                            <option value="<?= $u['userID']; ?>"><?= $u['firstName'] . " " . $u['lastName']; ?></option>
                                                <?php } endforeach; ?>
                                            </select>
                                        
                                            
                                        
                                            <div class="col-6 py-2 pl-0 mt-2">
                                                <input class="form-control" type="text" name="courseName" placeholder="Course Name" required>
                                                <div class="valid-feedback">Course name field is valid!</div>
                                                <div class="invalid-feedback">Course name field cannot be blank!</div>
                                            </div>

                                            <div class="col-6 py-2 pl-0 mt-2">
                                                <input class="form-control" type="text" name="creditHours" placeholder="Credit Hours" required>
                                                <div class="valid-feedback">Credit hours field is valid!</div>
                                                <div class="invalid-feedback">Credit hours field cannot be blank!</div>
                                            </div>

                                            <div class="col-6 py-2 pl-0">
                                                <input class="form-control" type="text" name="category" placeholder="Category" required>
                                                <div class="valid-feedback">Category field is valid!</div>
                                                <div class="invalid-feedback">Category field cannot be blank!</div>
                                            </div>

                                            <div class="col-6 py-2 pl-0">
                                                <input class="form-control" type="text" name="description" placeholder="Description" required>
                                                <div class="valid-feedback">Description field is valid!</div>
                                                <div class="invalid-feedback">Description field cannot be blank!</div>
                                            </div>
                                        </div>

                                        <div style="display: flex;" class="col-6 py-3 pl-0">
                                            <label class="col-3" >Completion Date:</label>
                                            <input class="form-control" type="date" name="completionDate" id="completiondate" onchange="">
                                        </div>

                                        <input type="hidden" name="entryDate" value="<?= date('Y-m-d'); ?>">

                                        <input type="submit" class="btn  btn-purple" id="submitManualTrainingForUser" name="submitManualTrainingForUser" value="Submit Training" />

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    
                    <div class="accordion" style="min-width: 1200px;" id="accordionExample">
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Select Training Module
                                </button>
                            </h3>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <table class="table table-striped table-dark table-hover">
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

                                    <form action="trainingEntry.php?action=ViewAll&userID=<?=$_SESSION['userID']?>" class="py-3" method="POST">

                                        <select class="form-select mb-3" name="moduleID" aria-label="Default select example">
                                            <option value="">Select Training Module</option>
                                            <?php foreach($modules as $m): ?>
                                                <option value="<?= $m['moduleID']; ?>"><?= $m['moduleID'] ?></option>
                                            <?php endforeach; ?>
                                        </select>

                                        <div style="display: flex;" class="col-6 py-3 px-0">
                                            <label class="col-3" >Completion Date:</label>
                                            <input class="form-control" type="date" name="completionDate" id="completiondate" onchange="">
                                        </div>

                                        <input type="submit" class="btn btn-purple" id="submitTraining" name="submitTrainingModule" value="Submit Training" />

                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Manually Input Training
                                </button>
                            </h3>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <form action="trainingEntry.php?action=ViewAll&userID=<?=$_SESSION['userID']?>" method="POST">
                                        <div style="" class="row">
                                        
                                            <div class="col-6 py-2">
                                                <input class="form-control" type="text" name="courseName" placeholder="Course Name" required>
                                                <div class="valid-feedback">Course name field is valid!</div>
                                                <div class="invalid-feedback">Course name field cannot be blank!</div>
                                            </div>

                                            <div class="col-6 py-2">
                                                <input class="form-control" type="text" name="creditHours" placeholder="Credit Hours" required>
                                                <div class="valid-feedback">Credit hours field is valid!</div>
                                                <div class="invalid-feedback">Credit hours field cannot be blank!</div>
                                            </div>

                                            <div class="col-6">
                                                <input class="form-control" type="text" name="category" placeholder="Category" required>
                                                <div class="valid-feedback">Category field is valid!</div>
                                                <div class="invalid-feedback">Category field cannot be blank!</div>
                                            </div>

                                            <div class="col-6">
                                                <input class="form-control" type="text" name="description" placeholder="Description" required>
                                                <div class="valid-feedback">Description field is valid!</div>
                                                <div class="invalid-feedback">Description field cannot be blank!</div>
                                            </div>
                                        </div>

                                        <div style="display: flex;" class="col-6 py-3">
                                            <label class="col-3" >Completion Date:</label>
                                            <input class="form-control" type="date" name="completionDate" id="completiondate" onchange="">
                                        </div>
                                        
                                        <input type="hidden" name="entryDate" value="<?= date('Y-m-d'); ?>">

                                        <input type="submit" class="btn btn-purple" id="submitTrainingManually" name="submitTrainingManually" value="Submit Training" />

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>

            <?php elseif($action == 'Validator'): 
                if($_SESSION['isTrainer']): 
                    if(!isset($_POST['searchAllButton'])){
                        $entries = $entryObj->getAllUnvalidatedTrainingEntries($_SESSION['orgID']);
                    }
                         ?>
                    
                    

                    <h2>Validate Training Entries</h2>

                    <form class="requires-validation" method="POST" id="searchEntries" name="searchEntries" novalidate>
                        <div class="searchContent">

                        
                            <div style="display: flex;">
                                <div class="col-6">
                                    <input class="form-control" type="text" name="firstName" placeholder="First Name" required>
                                </div>

                                <div class="col-6">
                                    <input class="form-control" type="text" name="lastName" placeholder="Last Name" required>
                                </div>
                            </div>

                            <div style="">
                                <div style="" class="col-12">
                                    <label for="">Entry Date:</label>
                                    <input class="form-control" type="date" name="entryDate" placeholder="Entry Date" required>
                                </div>

                                <div class="col-12">
                                    <label for="">Completion Date:</label>
                                    <input class="form-control" type="date" name="completeDate" placeholder="Completion Date" required>
                                </div>
                            </div>

                            <input type="submit" class="btn btn-purple col-6 ml-3 mt-3" id="searchAllBtn" name="searchAllButton" value="Search" />
                        </div>
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
                                    <td><a class="btn btn-purple" href="trainingEntry.php?action=ValidateTraining&entryID=<?= $e['entryID']; ?>">Validate</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

            <?php elseif($action == "ValidateTraining"): 
                $entry = $entryObj->getTrainingEntry($entryID); ?>

                <h2>Validate Training Entry</h2>

                <form method="POST" action="trainingEntry.php?action=Validator" class="formContent mt-3">

                    <input type="hidden" name="entryID" value="<?= $entryID; ?>">

                    <label for="">Full Name</label>
                    <input class="form-control" type="text" disabled value="<?=$entry[0]['firstName'] . " " .$entry[0]['lastName']?>">

                    <label for="">Course Name: </label>
                    <input class="form-control" type="text" disabled value="<?=$entry[0]['courseName']?>">

                    <label for="">Entry Date: </label>
                    <input class="form-control" type="date" disabled value="<?=$entry[0]['entryDate']?>">

                    <label for="">Completion Date: </label>
                    <input class="form-control" type="date" disabled value="<?=$entry[0]['completeDate']?>">

                    <label for="">Validated:</label>
                    <select class="form-select" name="isValidated">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>

                    <label for="">Validation Date: </label>
                    <input class="form-control" type="date" name="validationDate" id="" >

                    <label for="">Validation Comments: </label>
                    <textarea class="form-control" name="validationComments" cols="100" rows="4"></textarea>

                    <label for="">Credit Hours: </label>
                    <input type="text" class="form-control" name="" id="" disabled value="<?=$entry[0]['creditHours']?>">

                    <label for="">Category: </label>
                    <input type="text" class="form-control" name="" id="" disabled value="<?=$entry[0]['category']?>">

                    <label for="">Description: </label>
                    <input type="text" class="form-control" name="" id="" disabled value="<?=$entry[0]['description']?>">

                    <input class="btn btn-purple my-2" type="submit" name="submitValidation">
                        
                </form>

            <?php endif; ?>
            
        </div>
    </div>

<?php include  __DIR__ . '/../include/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>