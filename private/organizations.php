<?php

    include __DIR__ . '/../include/header.php';
    include __DIR__ . '/../model/OrganizationsDB.php';
    
    $orgDB = new OrganizationDB();
    $feedback="";
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
        
        $orgID = filter_input(INPUT_POST, 'orgID');
        $feedback = $orgDB->deleteOrganization($orgID);
        header('Location: ../private/organizations.php?action=Viewer');
        //delete all records of org from database and subsequent users/training/etc
    }

    //if search or coming to first time
    if(isset($_POST['search'])){

        
        $orgName = filter_input(INPUT_POST, 'orgName');

        $organizations = $orgDB->searchOrganizations($orgName);

    }
    else{
        $orgName = "";
        $organizations =  $orgDB->getAllOrganizations();
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
    
    <div class="mainContent">
        <?php include __DIR__ . '/../include/aside.php'; ?>

        <div class="pageContent container-fluid">
            <h2>Manage Organizations</h2>


            <?php if($action == 'Viewer'): ?>

            <form method="post" action="organizations.php?action=Viewer" name="Organization_Search">
                <div class="label">
                    <label>Organization Name:</label>
                </div>
                <div>
                    <input type="text" name="orgName" value="<?=$orgName;?>"/>
                </div>

                <div>
                    &nbsp;
                </div>
                <div>
                    <input type="submit" name="search" value="Search" />
                </div>
            </form>



            <h3>Viewer</h3>
            <table class="table table-striped table-hover table-dark">
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

            <h3>Editor</h3>

                <form method="post" action="organizations.php?action=Viewer" name="Organization_CRUD">

                        <div class="row">
                            <label>Organization Name</label>
                            <input type="text" name="orgName" value='<?=$orgName?>'>
                        </div>
                        
                        <div class="row">
                            <label>Organization Address</label>
                            <input type="text" name="address" value='<?=$address?>'>
                        </div>
                        
                        <div class="row">
                            <label>City </label>
                            <input type="text" name="city" value='<?=$city?>'>
                        </div>
                        
                        
                        <div class="row">
                            <label>State</label>
                            <select class="form-control text-secondary col-md-4" style="height: 40px;" type="text" name="state" value="<?=$state?>" required >
                                <option value="">State</option>
                                <option value="AL">Alabama</option>
                                <option value="AK">Alaska</option>
                                <option value="AZ">Arizona</option>
                                <option value="AR">Arkansas</option>
                                <option value="CA">California</option>
                                <option value="CO">Colorado</option>
                                <option value="CT">Connecticut</option>
                                <option value="DE">Delaware</option>
                                <option value="FL">Florida</option>
                                <option value="GA">Georgia</option>
                                <option value="HI">Hawaii</option>
                                <option value="ID">Idaho</option>
                                <option value="IL">Illinois</option>
                                <option value="IN">Indiana</option>
                                <option value="IA">Iowa</option>
                                <option value="KS">Kansas</option>
                                <option value="KY">Kentucky</option>
                                <option value="LA">Louisiana</option>
                                <option value="ME">Maine</option>
                                <option value="MD">Maryland</option>
                                <option value="MA">Massachusetts</option>
                                <option value="MI">Michigan</option>
                                <option value="MN">Minnesota</option>
                                <option value="MS">Mississippi</option>
                                <option value="MO">Missouri</option>
                                <option value="MT">Montana</option>
                                <option value="NE">Nebraska</option>
                                <option value="NV">Nevada</option>
                                <option value="NH">New Hampshire</option>
                                <option value="NJ">New Jersey</option>
                                <option value="NM">New Mexico</option>
                                <option value="NY">New York</option>
                                <option value="NC">North Carolina</option>
                                <option value="ND">North Dakota</option>
                                <option value="OH">Ohio</option>
                                <option value="OK">Oklahoma</option>
                                <option value="OR">Oregon</option>
                                <option value="PA">Pennsylvania</option>
                                <option value="RI">Rhode Island</option>
                                <option value="SC">South Carolina</option>
                                <option value="SD">South Dakota</option>
                                <option value="TN">Tennessee</option>
                                <option value="TX">Texas</option>
                                <option value="UT">Utah</option>
                                <option value="VT">Vermont</option>
                                <option value="VA">Virginia</option>
                                <option value="WA">Washington</option>
                                <option value="WV">West Virginia</option>
                                <option value="WI">Wisconsin</option>
                                <option value="WY">Wyoming</option>
                            </select>
                        </div>

                        <div class="row">
                            <label>Organization Zipcode</label>
                            <input type="text" name="zipCode" value='<?=$zipCode?>'>
                        </div>
                        

                        
                        

                        <div class="row">
                            <label>Organization Code</label>
                            <input type="text" name="orgCode" value='<?=$orgCode?>' disabled> 
                        </div>
                        
                        

                    
                    <input type="hidden" name="orgID" value="<?=$orgID;?>" readonly>
                    <input type="submit" name="edit" value="Edit Organization">

                    <?php if($_SESSION['isSiteAdmin']): ?>
                    <input type="submit" name="delete" value="Delete Organization">
                    
                    <form method="post">
                        <input type="hidden" name="orgID" value="<?=$orgID;?>" readonly>
                        <input type="submit" name="accessController" value="Access Organization Controller">
                    </form>
                    
                    <?php endif; ?>
                    

                        
                    </form>

                <a href="organizations.php?action=Viewer">
                    <button>Go Back</button>
                </a>

            <?php endif; ?>



        </div>  

    </div>

<?php include __DIR__ . '/../include/footer.php'; ?>