<?php


    include __DIR__ . '/../include/header.php';
    if(!$_SESSION['isSiteAdmin'] && !$_SESSION['isOrgAdmin']){
        header('Location: ../private/landingPage.php');
    }
    include __DIR__ . '/../model/DepartmentsDB.php';
    include __DIR__ . '/../model/OrganizationsDB.php';

    $error= '';
    $action = '';
    $depObj = new DepartmentDB();
    $orgObj = new OrganizationDB();

    if(isset($_GET['action'])){
        $action = filter_input(INPUT_GET, 'action');
    }

    $orgID = $_SESSION['orgID'];
    $organization = $orgObj->getOrganization($orgID);

    //Form functionality for creating editing or deleting a department
    if(isset($_POST['create'])){
        
        $name = filter_input(INPUT_POST, 'name');
        $email = filter_input(INPUT_POST, 'email');
        $orgID = filter_input(INPUT_POST, 'orgID');

        //validate input
        $error = verifyDepartmentInformation($name,$email);

        //if valid input create the new department
        if($error == ''){
            $depObj->createDep($orgID,$name,$email);
        }

    }
    elseif(isset($_POST['edit'])){
        
        $name = filter_input(INPUT_POST, 'name');
        $email = filter_input(INPUT_POST, 'email');
        $depID = filter_input(INPUT_POST, 'depID');

        $error = verifyDepartmentInformation($name, $email);


    
        if($error == ''){
            $depObj->editDep($depID, $name, $email);
        }
        //edit department information

    }
    elseif(isset($_POST['delete'])){
        
        $depID = filter_input(INPUT_POST, 'depID');
        
        //delete department and any userBridge entries with that depID

        $depObj->deleteDep($depID);
        $depObj->deleteDepBridge($depID);
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

    <title>Department Manager</title>
</head>
<body>

    <div class="mainContent" style="display:flex;">
        <?php include __DIR__ . '/../include/aside.php'; ?>

        <div class="pageContent container-fluid">

            <div>
                <h2 style="min-width: 400px;"><?= $organization['orgName']; ?></h2>
                <h3><?= $organization['address']. ", " . $organization['city'] . ", " . $organization['state']; ?></h3>
            </div>

            <?php if($action == 'Viewer'):
                if($_SESSION['isSiteAdmin'] && isset($_SESSION['orgID'])):
                    $departments = $depObj->getAllDepartments($orgID); ?>

                    <div style="display: flex;" class="mb-3">
                        <a class="form-control btn btn-purple" href="departments.php?action=Add">Create New Department</a>
                        
                        <a class="form-control btn btn-purple ml-2" href="orgControlPanel.php?action=Landing&ordID=<?= $orgID; ?>">Go Back</a>
                    </div>

                    <table class="table table-striped table-hover table-dark">
                        <thead>
                            <tr>
                                <th>Department ID</th>
                                <th>Department Name</th>
                                <th>Department Email</th>
                                <th>Edit Department</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($departments as $d):?>
                                <tr>
                                    <td><?= $d['depID']; ?></td>
                                    <td><?= $d['depName']; ?></td>
                                    <td><?= $d['depEmail']; ?></td>
                                    <td><a class="btn btn-purple" href="departments.php?action=Edit&depID=<?=$d['depID']?>">Edit</a></td>
                                    <!-- LINK FOR UPDATE FUNCTIONALITY -> Look at how we are passing in our ID using PHP! -->
                                </tr>
                            <?php endforeach; ?>
                                    
                        </tbody>
                    </table>
                
                <?php elseif($_SESSION['isOrgAdmin']): 
                    $departments = $depObj->getAllDepartments($orgID); ?>

                    <div style="display: flex;" class="mb-3">
                        <a class="form-control btn btn-purple" href="departments.php?action=Add">Create New Department</a>
                    </div>

                    <table class="table table-striped table-hover table-dark">
                        <thead>
                            <tr>
                                <th>Department ID</th>
                                <th>Department Name</th>
                                <th>Department Email</th>
                                <th>Edit Department</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($departments as $d):?>
                                <tr>
                                    <td><?= $d['depID']; ?></td>
                                    <td><?= $d['depName']; ?></td>
                                    <td><?= $d['depEmail']; ?></td>
                                    <td><a class="btn btn-purple" href="departments.php?action=Edit&depID=<?=$d['depID']?>">Edit</a></td>
                                    <!-- LINK FOR UPDATE FUNCTIONALITY -> Look at how we are passing in our ID using PHP! -->
                                </tr>
                            <?php endforeach; ?>
                                    
                        </tbody>
                    </table>
                
                <?php endif; ?>

            <?php elseif($action == 'Edit'): 
                
                $depID = filter_input(INPUT_GET, 'depID');
                $depart = $depObj->getDepartment($depID);
                $name = $depart[0]['depName'];
                $email = $depart[0]['depEmail']; ?>

                <form method="post" action="departments.php?action=Viewer" name="Department_CRUD" class="formContent mt-3">

                    <h3>Edit Department</h3>
                    <div style="display:flex;" class="mb-3">
                        <div class="col-md-6">
                            <label>Department Name</label>
                            <input class="form-control" type="text" name="name" value='<?=$name?>'>
                        </div>
                    
                        <div class="col-md-6">
                            <label>Department Email</label>
                            <input class="form-control" type="text" name="email" value='<?=$email?>'>
                        </div>
                    </div>

                    <input class="form-control" type="hidden" name="depID" value="<?=$depID;?>">
                    <div style="display: flex;">
                
                        <input class="btn btn-purple mr-3" type="submit" name="edit" value="Edit Department">
                        <input class="btn btn-purple mr-3" type="submit" name="delete" value="Delete Department">
                        <a class="form-control btn btn-purple" href="departments.php?action=Viewer">Go Back</a>
                
                    </div>
                </form>

            <?php elseif($action == 'Add'): 
                $name = "";
                $email = ""; ?>
                
                <form method="post" action="departments.php?action=Viewer" name="Department_CRUD" class="formContent mt-3">

                    <h3>Add Department</h3>
                    <div class="mb-3" style="display: flex;">
                        <input class="form-control col-8" type="text" name="name" value='<?=$name?>' placeholder="Department Name">
                        <input class="form-control col-8 ml-3" type="text" name="email" value='<?=$email?>' placeholder="Department Email">
                        <input class="form-control col-2 ml-3 btn btn-purple" type="submit" name="create" value="Create Department">
                    </div>

                    <input class="form-control" type="hidden" name="orgID" value="<?=$_SESSION['orgID'];?>">

                    <div style="display: flex;">
                
                        <a class="form-control btn btn-purple" href="departments.php?action=Viewer">Go Back</a>

                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

<?php include __DIR__ . '/../include/footer.php'; ?>