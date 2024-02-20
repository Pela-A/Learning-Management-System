<?php

    include __DIR__ . '/../include/header.php';
    include __DIR__ . '/../model/OrganizationsDB.php';
    
    $orgDB = new OrganizationDB();

    if(isset($_GET['action'])){
        $action = filter_input(INPUT_GET, 'action');
    }


    if(isset($_POST['edit'])){        
        echo("edit button pressed");

        $orgID = filter_input(INPUT_POST, 'orgID');
        $orgName = filter_input(INPUT_POST, 'orgName');
        $address = filter_input(INPUT_POST, 'address');
        $city = filter_input(INPUT_POST, 'city');
        $state = filter_input(INPUT_POST, 'state');
        $zipCode = filter_input(INPUT_POST, 'zipCode');

        $orgDB->updateOrganization($orgID, $orgName, $address, $city, $state, $zipCode);
    }
    elseif(isset($_POST['delete'])){
        echo("delete button pressed");
        //delete all records of org from database and subsequent users/training/etc
    }

    //if search or coming to first time
    if(isset($_POST['search'])){
        // search organization functionality
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

    <title>Organization Manager</title>
</head>
<body>
    
    <div class="mainContent"">

    <div class="content">
        <p>main content goes here</p>


        <?php if($action == 'Viewer'): 
            
            $organizations =  $orgDB->getAllOrganizations();
            
        ?>
        <table class="table table-bordered text-center col-11">
            <thead>
                <tr>
                    <th>Organization ID</th>
                    <th>Organization Name</th>
                    <th>Organization Address</th>
                    <th>Organization City</th>
                    <th>Organization State</th>
                    <th>Organization Zipcode</th>
                    <th>Organization Code</th>
                    <th>Edit Organizations</th>
                </tr>
            </thead>

            <tbody>

            <?php foreach ($organizations as $o):?>
                <tr>
                    <td><?= $o['orgID']; ?></td>
                    <td><?= $o['orgName']; ?></td>
                    <td><?= $o['address']; ?></td>
                    <td><?= $o['city']; ?></td>
                    <td><?= $o['state']; ?></td>
                    <td><?= $o['zipCode']; ?></td>
                    <td><?= $o['orgCode']; ?></td>
                    <td><a href="organizations.php?action=Edit&orgID=<?=$o['orgID']?>">Edit</a></td>
                    <!-- LINK FOR UPDATE FUNCTIONALITY -> Look at how we are passing in our ID using PHP! -->
                </tr>
            <?php endforeach; ?>
                        
            </tbody>
        </table>

        <?php elseif($action == 'Edit'):

            $orgID = filter_input(INPUT_GET, 'orgID');
            $organization = $orgDB->getOrganization($orgID);

            $orgName = $organization['orgName'];
            $address = $organization['address'];
            $city = $organization['city'];
            $state = $organization['state'];
            $zipCode = $organization['zipCode'];
            $orgCode = $organization['orgCode'];

        ?>

            <form method="post" action="organizations.php?action=Viewer" name="Organization_CRUD">

                    <label>Organization Name</label>
                    <input type="text" name="orgName" value='<?=$orgName?>'>
                    </br>
                
                    <label><Address></Address></label>
                    <input type="text" name="address" value='<?=$address?>'>
                    </br>

                    <label>City </label>
                    <input type="text" name="city" value='<?=$city?>'>
                    </br>

                    <label>State</label>
                    <input type="text" name="state" value='<?=$state?>'>
                    </br>

                    <label>Organization Zipcode</label>
                    <input type="text" name="zipCode" value='<?=$zipCode?>'>
                    </br>

                    <label>Organization Code</label>
                    <input type="text" name="orgCode" value='<?=$orgCode?>' disabled>
                    </br>

                
                <input type="hidden" name="orgID" value="<?=$orgID;?>" readonly>
                <input type="submit" name="edit" value="Edit Organization">

                <input type="submit" name="delete" value="Delete Organization">

                

                    
                </form>

            <a href="organizations.php?action=Viewer">
                <button>Go Back</button>
            </a>

        <?php endif; ?>



    </div>

<?php include __DIR__ . '/../include/footer.php'; ?>