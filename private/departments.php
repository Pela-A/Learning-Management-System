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

        //siteADMIN
        //Alexander
        //AlexanderPela

        //orgADMIN
        //NewGuy13
        //Pelaman12


        //general USER
        //JoinUser215125
        //PelaMan12


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

    <div class="mainContent">
        <?php include __DIR__ . '/../include/aside.php'; ?>

        <div class="pageContent">

            <div>
                <h1><?= $organization[0]['orgName']; ?></h1>
                <h3><?= $organization[0]['address']. ", " . $organization[0]['city'] . ", " . $organization[0]['state']; ?></h3>
            </div>

        <?php if($action == 'Viewer'):
            $departments = $depObj->getAllDepartments($orgID);
            ?>
            <a href="departments.php?action=Add">Create New Department</a>

            <table class="table table-bordered text-center col-11">
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
                            <td><a href="departments.php?action=Edit&depID=<?=$d['depID']?>">Edit</a></td>
                            <!-- LINK FOR UPDATE FUNCTIONALITY -> Look at how we are passing in our ID using PHP! -->
                        </tr>
                    <?php endforeach; ?>
                                
                    </tbody>
                </table>

        <?php elseif($action == 'Edit'): 
            
            
            $depID = filter_input(INPUT_GET, 'depID');
            $depart = $depObj->getDepartment($depID);
            $name = $depart[0]['depName'];
            $email = $depart[0]['depEmail']; ?>

            <form method="post" action="departments.php?action=Viewer" name="Department_CRUD">

                    <label>Department Name</label>
                    <input type="text" name="name" value='<?=$name?>'>
                    </br>
                
                    <label>Department Email</label>
                    <input type="text" name="email" value='<?=$email?>'>
                    </br>

                
                <input type="hidden" name="depID" value="<?=$depID;?>">
                <input type="submit" name="edit" value="Edit Department">

                <input type="submit" name="delete" value="Delete Department">

                

                    
                </form>

            <a href="departments.php?action=Viewer">
                <button>Go Back</button>
            </a>

        <?php elseif($action == 'Add'): 
            $name = "";
            $email = "";

            
            
            ?>
            <form method="post" action="departments.php?action=Viewer" name="Department_CRUD">

                <label>Department Name</label>
                <input type="text" name="name" value='<?=$name?>'>
                </br>
            
                <label>Department Email</label>
                <input type="text" name="email" value='<?=$email?>'>
                </br>

            
                <input type="hidden" name="orgID" value="<?=$_SESSION['orgID'];?>">
                <input type="submit" name="create" value="Create Department">


                
            </form>

            <a href="departments.php?action=Viewer">
                <button>Go Back</button>
            </a>
        <?php endif; ?>

        </div>
    </div>

<?php include __DIR__ . '/../include/footer.php'; ?>