<?php
//option to create new department
//option to view/edit/delete a department.
$action ='';

include __DIR__ . '/../model/DepartmentsDB.php';

//Form functionality for creating editing or deleting a department
if(isset($_POST['create'])){
    echo("Create button clicked");
    //create new department

}
elseif(isset($_POST['edit'])){
    echo("Delete button clicked");
    //edit department information

}
elseif(isset($_POST['delete'])){
    echo("Delete button clicked");
    //delete department and any userBridge entries with that depID

}
else{
    $name = "";

    $email = "";
}

//decides whether the form will be creating or editing
if(isset($_GET['action'])){
    $action = filter_input(INPUT_GET, 'action');
}

//$obj = new DepartmentDB();
//var_dump($obj->siteAdminGetAllDepartments());

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
    <a href="editDepartment.php?action=Edit">Edit</a>


    

    
    
    <?php else:?>
    <h2><?=$action; ?> Department</h2>

    <form method="post" name="Department_CRUD">

        <input type="text" name="name" value='<?=$name?>'>
        </br>
    
        <input type="text" name="email" value='<?=$email?>'>
        </br>

        <?php if($action == "Create"):?>
    
        <input type="submit" name="create" value="Create Department">

        <?php elseif($action == "Edit"): ?>
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