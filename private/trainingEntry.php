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
    $action = "";

    if(isset($_GET['action'])){
        $action = filter_input(INPUT_GET, 'action');
    }

    $entries = $entryObj->getAllTrainingEntries($_SESSION['orgID']);
    $users = $userObj->getAllUsers();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>LMS || Training Entries</title>
</head>
<body>
    
    <div class="mainContent">

        <?php include __DIR__ . '/../include/aside.php'; ?>

        <div class="pageContent">

            <?php if($action == 'ViewAll'): ?>

                <?php if($_SESSION['isSiteAdmin'] || $_SESSION['isOrgAdmin'] || $_SESSION['isTrainer']): ?>
                    <a href="trainingEntry.php?action=Create">Create new training entry</a>

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

                            <div class="col-md-6">
                                <input class="form-control" type="text" name="courseName" placeholder="Course Name" required>
                                <div class="valid-feedback">Course name field is valid!</div>
                                <div class="invalid-feedback">Course name field cannot be blank!</div>
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

                        <div style="display: flex; justify-content: space-evenly;">
                            <div class="mt-3">
                                <label class="mb-3 mr-1" for="validated">Validated: </label>

                                <input type="radio" class="btn-check" name="validated" value=1 id="valYes" autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-danger" for="valYes">Yes</label>

                                <input type="radio" class="btn-check" name="validated" value=0 id="valNo" autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-danger" for="valNo">No</label>

                                <div class="valid-feedback mv-up">You selected a validation status!</div>
                                <div class="invalid-feedback mv-up">Please select a validation status!</div>
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

                        <input type="submit" class="btn btn-sm btn-danger" id="searchBtn" name="searchButton" value="Search" />

                    </form>

                    <table class="table table-striped table-hover table-dark">
                        <thead>
                            <tr>
                                <th></th>
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
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($entries as $e): ?>
                                <tr>
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" name="userID" value="<?= $e['userID']; ?>" />
                                            <input class="btn btn-danger btn-sm" type="submit" name="deleteUser" value="Delete" />
                                        </form>
                                    </td>
                                    
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
                                    <td><a style="font-size: 14px; width: 60px; font-weight: 100px;" class="btn btn-danger btn-sm text-light" href="trainingEntry.php?action=Update&entryID=<?= $u['entryID']; ?>">Edit</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?php endif; ?>
                
            <?php elseif($action == 'ViewOne'): ?>
            <?php elseif($action == 'Create'): ?>
            <?php elseif($action == 'Update'): ?>
            <?php elseif($action == 'Validator'): ?>
            <?php endif; ?>
            
        </div>

    </div>

<?php include __DIR__ . '/../include/footer.php'; ?>