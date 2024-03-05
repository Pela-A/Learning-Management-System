<?php

    include __DIR__ . '/../include/header.php';
    include __DIR__ . '/../model/TrainingModuleDB.php';
    include __DIR__ . '/../model/TrainingEntryDB.php';
    include __DIR__ . '/../model/UsersDB.php';

    $moduleObj = new TrainingModuleDB();
    $entryObj = new TrainingEntryDB();
    $userObj = new UserDB();

    $modules = $moduleObj->getAllTrainingModules($_SESSION['orgID']); 

    if(isset($_GET['action'])) {
        $action = filter_input(INPUT_GET, 'action');
    }

    if(isset($_GET['moduleID'])) {
        $moduleID = filter_input(INPUT_GET, 'moduleID');
    }

    if(isset($_POST['submitCreateModule'])) {
        $courseName = filter_input(INPUT_POST, 'courseName');
        $description = filter_input(INPUT_POST, 'description');
        $category = filter_input(INPUT_POST, 'category');
        $creditHours = filter_input(INPUT_POST, 'creditHours');
        $websiteURL = filter_input(INPUT_POST, 'websiteURL');

        $moduleObj->createTrainingModule($_SESSION['orgID'], $courseName, $creditHours, $category, $description, $websiteURL);      
    }

    if(isset($_POST['submitUpdateModule'])){
        $moduleID = filter_input(INPUT_POST, 'moduleID');
        $courseName = filter_input(INPUT_POST, 'courseName');
        $description = filter_input(INPUT_POST, 'description');
        $category = filter_input(INPUT_POST, 'category');
        $creditHours = filter_input(INPUT_POST, 'creditHours');
        $websiteURL = filter_input(INPUT_POST, 'websiteURL');

        $moduleObj->updateTrainingModule($moduleID, $courseName, $creditHours, $category, $description, $websiteURL);      
    }
    
    if(isset($_POST['deleteModule'])){
        $moduleID = filter_input(INPUT_POST, 'moduleID');
        $moduleObj->deleteTrainingModule($moduleID);
    }

    if(isset($_POST['submitSearchModules'])) {
        $courseName = filter_input(INPUT_POST, 'courseName');
        $category = filter_input(INPUT_POST, 'category');

        $modules = $moduleObj->searchTrainingModule($_SESSION['orgID'], $courseName, $category);      
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

    <title>Training Modules</title>
</head>
<body>
    
    <div class="mainContent"">

        <?php include __DIR__ . '/../include/aside.php'; ?>

        <div class="pageContent container-fluid">
            
            <?php if($action == "ViewAll"): 
                if($_SESSION['isTrainer']): ?>

                    <h3>Training Module Viewer</h3>
                    <a class="btn btn-purple form-control mb-2" href="trainingModules.php?action=Create">Create Training Module</a>

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
                            
                            <input type="submit" name="submitSearchModules" class="form-control btn btn-purple col-sm-1">
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
                                    <td>
                                        <form action="trainingModules.php?action=ViewAll" method="POST">
                                            <input type="hidden" name="moduleID" value="<?= $m['moduleID']; ?>" />
                                            <input class="btn btn-purple" type="submit" name="deleteModule" value="Delete" />
                                        </form>
                                    </td>                                 
                                    <td><?= $m['courseName']; ?></td>
                                    <td><?= $m['description']; ?></td>
                                    <td><?= $m['category']; ?></td>
                                    <td><?= $m['creditHours']; ?></td>
                                    <td><?= $m['websiteURL']; ?></td>
                                    <td><a class="btn btn-purple" href="trainingModules.php?action=Edit&moduleID=<?= $m['moduleID']; ?>">Edit</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>

                <?php elseif($_SESSION['isSiteAdmin'] || $_SESSION['isOrgAdmin']): ?>
                    
                    <h3>Training Module Viewer</h3>

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
                            
                            <input type="submit" name="submitSearchModules" class="form-control btn btn-purple col-sm-1">
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
                                    <td>
                                        <form action="trainingModules.php?action=ViewAll" method="POST">
                                            <input type="hidden" name="moduleID" value="<?= $m['moduleID']; ?>" />
                                            <input class="btn btn-purple" type="submit" name="deleteModule" value="Delete" />
                                        </form>
                                    </td>                                 
                                    <td><?= $m['courseName']; ?></td>
                                    <td><?= $m['description']; ?></td>
                                    <td><?= $m['category']; ?></td>
                                    <td><?= $m['creditHours']; ?></td>
                                    <td><?= $m['websiteURL']; ?></td>
                                    <td><a class="btn btn-purple" href="trainingModules.php?action=Edit&moduleID=<?= $m['moduleID']; ?>">Edit</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                <?php endif; ?>

            <?php elseif($action == "Create"): 
                if($_SESSION['isTrainer']): ?>

                    <h3>Create Training Module</h3>

                    <form method="POST" action="trainingModules.php?action=ViewAll">

                        <label>Course Name: </label>
                        <input class="form-control" type="text" placeholder="Course Name" name="courseName">

                        <label>Description: </label>
                        <textarea class="form-control" placeholder="Description" name="description"></textarea>

                        <label>Category: </label>
                        <select class="form-control text-secondary" name="category">
                            <option value="Leadership Development">Leadership Development</option>
                            <option value="Communication Skills">Communication Skills</option>
                            <option value="Diversity and Inclusion Training">Diversity and Inclusion Training</option>
                            <option value="Customer Service Excellence">Customer Service Excellence</option>
                            <option value="Project Management">Project Management</option>
                            <option value="Time Management">Time Management</option>
                            <option value="Emotional Intelligence">Emotional Intelligence</option>
                            <option value="Conflict Resolution">Conflict Resolution</option>
                            <option value="Team Building">Team Building</option>
                            <option value="Sales Techniques">Sales Techniques</option>
                            <option value="Negotiation Skills">Negotiation Skills</option>
                            <option value="Stress Management">Stress Management</option>
                            <option value="Presentation Skills">Presentation Skills</option>
                            <option value="Innovation and Creativity">Innovation and Creativity</option>
                            <option value="Change Management">Change Management</option>
                            <option value="Financial Literacy">Financial Literacy</option>
                            <option value="Technical Skills">Technical Skills (e.g., programming languages, software applications)</option>
                            <option value="Health and Safety Training">Health and Safety Training</option>
                            <option value="Problem-Solving">Problem-Solving</option>
                            <option value="Cultural Sensitivity Training">Cultural Sensitivity Training</option>
                            <option value="Public Speaking">Public Speaking</option>
                            <option value="Coaching and Mentoring">Coaching and Mentoring</option>
                            <option value="Strategic Planning">Strategic Planning</option>
                            <option value="Human Resources Management">Human Resources Management</option>
                            <option value="Cybersecurity Awareness">Cybersecurity Awareness</option>
                            <option value="Risk Management">Risk Management</option>
                            <option value="Compliance Training">Compliance Training</option>
                            <option value="Supply Chain Management">Supply Chain Management</option>
                            <option value="Sustainability Practices">Sustainability Practices</option>
                            <option value="Remote Work Best Practices">Remote Work Best Practices</option>
                        </select>

                        <label>Credit Hours: </label>
                        <input class="form-control" type="text" placeholder="Number of Credit Hours" name="creditHours">

                        <label for="urlInput">URL:</label>
                        <input type="text" class="form-control" id="urlInput" name="websiteURL" placeholder="Enter URL">

                        <input class="btn btn-purple mt-3" type="submit" name="submitCreateModule">

                    </form>

                <?php endif; ?>

            <?php elseif($action == "Edit"): 
                if($_SESSION['isTrainer']): 
                    $module = $moduleObj->getTrainingModule($moduleID); ?>

                    <h3>Update Training Module</h3>

                    <form method="POST" action="trainingModules.php?action=ViewAll">

                        <label>Course Name: </label>
                        <input class="form-control" type="text" value="<?= $module[0]['courseName']; ?>" placeholder="Course Name" name="courseName">

                        <label>Description: </label>
                        <textarea class="form-control" placeholder="Description" name="description"><?php echo $module[0]['description']; ?></textarea>

                        <label>Category: </label>
                        <select class="form-control text-secondary" value="<?= $module[0]['category']; ?>" name="category">
                            <option value="Leadership Development">Leadership Development</option>
                            <option value="Communication Skills">Communication Skills</option>
                            <option value="Diversity and Inclusion Training">Diversity and Inclusion Training</option>
                            <option value="Customer Service Excellence">Customer Service Excellence</option>
                            <option value="Project Management">Project Management</option>
                            <option value="Time Management">Time Management</option>
                            <option value="Emotional Intelligence">Emotional Intelligence</option>
                            <option value="Conflict Resolution">Conflict Resolution</option>
                            <option value="Team Building">Team Building</option>
                            <option value="Sales Techniques">Sales Techniques</option>
                            <option value="Negotiation Skills">Negotiation Skills</option>
                            <option value="Stress Management">Stress Management</option>
                            <option value="Presentation Skills">Presentation Skills</option>
                            <option value="Innovation and Creativity">Innovation and Creativity</option>
                            <option value="Change Management">Change Management</option>
                            <option value="Financial Literacy">Financial Literacy</option>
                            <option value="Technical Skills">Technical Skills (e.g., programming languages, software applications)</option>
                            <option value="Health and Safety Training">Health and Safety Training</option>
                            <option value="Problem-Solving">Problem-Solving</option>
                            <option value="Cultural Sensitivity Training">Cultural Sensitivity Training</option>
                            <option value="Public Speaking">Public Speaking</option>
                            <option value="Coaching and Mentoring">Coaching and Mentoring</option>
                            <option value="Strategic Planning">Strategic Planning</option>
                            <option value="Human Resources Management">Human Resources Management</option>
                            <option value="Cybersecurity Awareness">Cybersecurity Awareness</option>
                            <option value="Risk Management">Risk Management</option>
                            <option value="Compliance Training">Compliance Training</option>
                            <option value="Supply Chain Management">Supply Chain Management</option>
                            <option value="Sustainability Practices">Sustainability Practices</option>
                            <option value="Remote Work Best Practices">Remote Work Best Practices</option>
                        </select>

                        <label>Credit Hours: </label>
                        <input class="form-control" type="text" value="<?= $module[0]['creditHours']; ?>" placeholder="Number of Credit Hours" name="creditHours">

                        <label for="urlInput">URL:</label>
                        <input type="text" class="form-control" id="urlInput" value="<?= $module[0]['websiteURL']; ?>" name="websiteURL" placeholder="Enter URL">

                        <input type="hidden" name="moduleID" value="<?= $module[0]['moduleID']; ?>">

                        <input class="btn btn-purple mt-3" type="submit" name="submitUpdateModule">

                    </form>

                <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>

<?php include __DIR__ . '/../include/footer.php'; ?>

<script>
    // Validate URL function
    function isValidURL(url) {
        // Regular expression for URL validation
        var urlPattern = new RegExp('^((https?:)?\\/\\/[\\w-]+(\\.[\\w-]+)+|www\\.[\\w-]+(\\.[\\w-]+)+)([\\w.,@?^=%&:/~+#-]*[\\w@?^=%&/~+#-])?$');
        return urlPattern.test(url);
    }

    // Form submission handling
    $('#urlForm').submit(function(event) {
        event.preventDefault(); // Prevent default form submission
        var urlInput = $('#urlInput').val(); // Get the value of the URL input field

        if (isValidURL(urlInput)) {
            // If URL is valid, submit the form (you can replace this with your own logic)
            alert('Valid URL: ' + urlInput);
        } else {
            // If URL is invalid, show error message and add invalid-feedback class
            $('#urlInput').addClass('is-invalid');
            $('#urlFeedback').show();
        }
    });
</script>