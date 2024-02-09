<?php

    include __DIR__ . '/../include/header.php';
    include __DIR__ . '/../model/DepartmentsDB.php';

    function verifyDepartmentInformation($name, $email){
        $error = '';
        $pattern1 = "/[^A-Za-z-]+/";
        $pattern3 = "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/";
        if(preg_match($pattern1,$name)){
            $error .= "<li>Department Name must not contain special characters or numbers!</li>";
        }
        elseif($name == ""){
            $error .= "<li>Please Enter a Department Name!</li>";
        }

        if(!preg_match($pattern3, $email)){
            $error .= "<li>Invalid Department Email!</li>";
        }
        elseif($email == ""){
            $error .= "<li>Please enter an Department Dmail.</li>";
        }
        return($error);
    }

    //initialize error
    $error= '';

    //initialize departments object
    $depObj = new departmentDB();

    //Form functionality for creating editing or deleting a department
    if(isset($_POST['create'])){
        
        $name = filter_input(INPUT_POST, 'name');
        $email = filter_input(INPUT_POST, 'email');
        $orgID = filter_input(INPUT_POST, 'orgID');

        //validate input
        $error = verifyDepartmentInformation($name,$email);

        //if valid input create the new department
        if($error ==''){
            $depObj->createDep($orgID,$name,$email);
        }

    }
    elseif(isset($_POST['edit'])){
        
        $name = filter_input(INPUT_POST, 'name');
        $email = filter_input(INPUT_POST, 'email');
        $depID = filter_input(INPUT_POST, 'depID');

        $error = verifyDepartmentInformation($name, $email);

        if($error ==''){
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

    //initialize action variable
    $action ='';

    //decides whether the form will be creating or editing. otherwise we are pulling data for view
    if(isset($_GET['action'])){
        $action = filter_input(INPUT_GET, 'action');
        if($action == "Edit"){
            $depID = filter_input(INPUT_GET, 'depID');
            $depart = $depObj->getDepartment($depID);
            $name = $depart[0]['depName'];
            $email = $depart[0]['depEmail'];
        }
    }else{
        $orgID = $_SESSION['orgID'];
        
        $departments = $depObj->getAllDepartments($orgID);
        //USING ORG ID SESSION ATM
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

    <title>LMS || Departments</title>
</head>
<body>
    
    <?php include __DIR__ . '/../include/aside.php'; ?>

    <div class="content">
        <p>main content goes here</p>

        <h1>Departments</h1>

        <?php if($action==''):?>

        <a href="departments.php?action=Create">Create New Department</a>
        

        <table class="table table-bordered text-center col-11">
            <thead>
                <tr>
                    <th>Department ID</th>
                    <th>Organization ID</th>
                    <th>Department Name</th>
                    <th>Department Email</th>
                </tr>
            </thead>

            <tbody>

            <?php foreach ($departments as $d):?>
                <tr>
                    <td><?= $d['orgID']; ?></td>
                    <td><?= $d['departmentID']; ?></td>
                    <td><?= $d['depName']; ?></td>
                    <td><?= $d['depEmail']; ?></td>
                    <td><a href="departments.php?action=Edit&depID=<?= $d['departmentID']?>">Edit</a></td>
                    <!-- LINK FOR UPDATE FUNCTIONALITY -> Look at how we are passing in our ID using PHP! -->
                </tr>
            <?php endforeach; ?>
                        
            </tbody>
        </table>

        
        <?php else:?>
        <h2><?=$action; ?> Department</h2>

        <form method="post" action="departments.php" name="Department_CRUD">

            <label>Department Name</label>
            <input type="text" name="name" value='<?=$name?>'>
            </br>
        
            <label>Department Email</label>
            <input type="text" name="email" value='<?=$email?>'>
            </br>

            <?php if($action == "Create"):?>

                <input type="hidden" name="orgID" value="<?=$_SESSION['orgID'];?>">
                <input type="submit" name="create" value="Create Department">

            <?php elseif($action == "Edit"): ?>
                <input type="hidden" name="depID" value="<?=$depID;?>">
                <input type="submit" name="edit" value="Edit Department">

                <input type="submit" name="delete" value="Delete Department">

            <?php endif; ?>

            
        </form>

        <a href="departments.php">
            <button>Go Back</button>
        </a>

        <?php endif; ?>

    </div>

<?php include __DIR__ . '/../include/footer.php'; ?>