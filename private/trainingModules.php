<?php

    include __DIR__ . '/../include/header.php';
    include __DIR__ . '/../model/TrainingModuleDB.php';
    include __DIR__ . '/../model/TrainingEntryDB.php';
    include __DIR__ . '/../model/UsersDB.php';

    $moduleObj = new TrainingModuleDB();
    $entryObj = new TrainingEntryDB();
    $userObj = new UserDB();

    if(isset($_GET['action'])) {
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

    <title>Training Modules</title>
</head>
<body>
    
    <div class="mainContent"">

        <?php include __DIR__ . '/../include/aside.php'; ?>

        <div class="pageContent container-fluid">
            
            <?php if($action == "ViewAll"): 
                if($_SESSION['isSiteAdmin'] || $_SESSION['isOrgAdmin'] || $_SESSION['isTrainer']): 
                    $modules = $moduleObj->getAllTrainingModules($_SESSION['orgID']); ?>
                    
                    <h3>Training Module Viewer</h3>
                    <a class="btn btn-light form-control mb-2" href="trainingModules.php?action=CreateModule">Create Training Module</a>

                    <form method="POST">

                        <div style="display: flex;">
                            <input class="form-control" type="text" name="courseName" placeholder="Course Name">
                            <select class="form-control col-md-4" type="text" name="category">
                                <option value="">Select Category</option>
                                <?php 
                                    $uniqueCategories = array();
                                    foreach($modules as $m): 
                                        if(!in_array($m['category'], $uniqueCategories)) {
                                            $uniqueCategories[] = $m['category']; ?>
                                            <option value="<?= $m['category']; ?>"><?= $m['category']; ?></option>
                                        <?php 
                                        } 
                                    endforeach; ?>
                            </select>
                            
                            <input type="submit" name="submitSearch" class="form-control btn btn-light col-sm-1">
                        </div>

                    </form>

                    <table class="table table-striped table-hover table-dark mt-4">

                        <thead>
                            <tr>
                                <th></th>
                                <th>Course Name</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Credit Hours</th>
                                <th>Website URL</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($modules as $m): ?>
                                <tr>
                                    <td><a class="btn btn-light" href="">Delete</a></td>                                 
                                    <td><?= $m['courseName']; ?></td>
                                    <td><?= $m['description']; ?></td>
                                    <td><?= $m['category']; ?></td>
                                    <td><?= $m['creditHours']; ?></td>
                                    <td><?= $m['websiteURL']; ?></td>
                                    <td><a class="btn btn-light" href="">Edit</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                <?php endif; ?>

            <?php elseif($action == "Create"): 
                if($_SESSION['isTrainer']): ?>

                    

                <?php endif; ?>

            <?php elseif($action == "Edit"): 
                if($_SESSION['isTrainer']): ?>
                <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>

<?php include __DIR__ . '/../include/footer.php'; ?>