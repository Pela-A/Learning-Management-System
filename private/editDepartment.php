<?php
//option to create new department
//option to view/edit/delete a department.

session_start();

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

//initialize action variable
$action ='';

//decides whether the form will be creating or editing. otherwise we are pulling data for view
if(isset($_GET['action'])){
    $action = filter_input(INPUT_GET, 'action');
    if($action = "Edit"){
        $depID = filter_input(INPUT_GET, 'depID');
    }
}elseif($_SESSION['isSiteAdmin'] == TRUE){
    $departments = $depObj->siteAdminGetAllDepartments();
}else{
    $departments = $depObj->orgAdminGetAllDepartments();
}





//Form functionality for creating editing or deleting a department
if(isset($_POST['create'])){
    echo("Create button clicked");
    $name = filter_input(INPUT_POST, 'name');
    $email = filter_input(INPUT_POST, 'email');
    $orgID = filter_input(INPUT_POST, 'orgID');

    //validate input
    $error = verifyDepartmentInformation($name,$email);

    //if valid input create the new department
    if($error ==''){
        $depObj->createDep($orgID,$depName,$depEmail);
    }

}
elseif(isset($_POST['edit'])){
    echo("Delete button clicked");
    $name = filter_input(INPUT_POST, 'name');
    $email = filter_input(INPUT_POST, 'email');
    $depID = filter_input(INPUT_POST, 'depID');

    $error = verifyDepartmentInformation($name, $email);

    if($error ==''){

        
        $depObj->editDep($depID, $depName, $depEmail);
    }
    //edit department information

}
elseif(isset($_POST['delete'])){
    echo("Delete button clicked");
    $depID = filter_input(INPUT_POST, 'depID');
    
    //delete department and any userBridge entries with that depID

    $depObj->deleteDep($depID);
    $depObj->deleteDepBridge($depID);
}
else{
    $name = "";

    $email = "";

}


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <h1>Departments</h1>

    <?php if($action==''):?>

    

    <a href="editDepartment.php?action=Create">Create New Department</a>


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
                <td><?= $b['depEmail']; ?></td>
                <td><a href="editDepartment.php?action=Edit&depID=<?= $d['depID']?>">Edit</a></td>
                <!-- LINK FOR UPDATE FUNCTIONALITY -> Look at how we are passing in our ID using PHP! -->
            </tr>
        <?php endforeach; ?>
                    
        </tbody>
    </table>









    

    
    
    <?php else:?>
    <h2><?=$action; ?> Department</h2>

    <form method="post" action="editDepartment.php" name="Department_CRUD">

        <label>Department Name</label>
        <input type="text" name="name" value='<?=$name?>'>
        </br>
    
        <label>Department Email</label>
        <input type="text" name="email" value='<?=$email?>'>
        </br>

        <?php if($action == "Create"):?>

            <input type="hidden" name="orgID" value="">
            <input type="submit" name="create" value="Create Department">

        <?php elseif($action == "Edit"): ?>
            <input type="hidden" name="depID" value="<?=$depID?>">
            <input type="submit" name="edit" value="Edit Department">

            <input type="submit" name="delete" value="Delete Department">

        <?php endif; ?>

        
    </form>

    <a href="editDepartment.php">
        <button>Go Back</button>
    </a>

    <?php endif; ?>


    






</body>
</html>