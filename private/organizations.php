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
    if(isset($_POST['submitSearch'])){
        $orgName = filter_input(INPUT_POST, 'orgName');
        $state = filter_input(INPUT_POST, 'state');

        $organizations = $orgDB->searchOrganizations($orgName, $state);
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
            <?php if($action == 'Viewer'): 
                if($_SESSION['isSiteAdmin'] && !isset($_SESSION['orgID'])): ?>

                    <h3>Organization Viewer</h3>

                    <form class="searchForms" method="post" action="organizations.php?action=Viewer" name="Organization_Search">
                        <div style="display:flex;">
                            <input class="form-control" type="text" name="orgName" value="<?=$orgName;?>" placeholder="Organization Name"/>
                            
                            <select class="form-control text-secondary" type="text" name="state" value="<?=$state?>">
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

                            <input class="btn btn-light" type="submit" name="submitSearch" />
                        </div>
                    </form>

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
                                <td><a class="btn btn-light" href="organizations.php?action=Edit&orgID=<?=$o['orgID']?>">Edit</a></td>
                                <!-- LINK FOR UPDATE FUNCTIONALITY -> Look at how we are passing in our ID using PHP! -->
                            </tr>
                        <?php endforeach; ?>
                                    
                        </tbody>
                    </table>

                <?php endif; ?>

            <?php elseif($action == 'Edit'):
                if($_SESSION['isSiteAdmin'] && isset($_SESSION['orgID'])):
                    $orgID = filter_input(INPUT_GET, 'orgID');
                    $organization = $orgDB->getOrganization($orgID);

                    $orgName = $organization['orgName'];
                    $address = $organization['address'];
                    $city = $organization['city'];
                    $state = $organization['state'];
                    $zipCode = $organization['zipCode'];
                    $orgCode = $organization['orgCode']; ?>

                    <h3>Organization Editor</h3>

                    <form method="post" action="organizations.php?action=Viewer" name="Organization_CRUD">

                        <div class="row">
                            <label>Organization Name</label>
                            <input class="form-control" type="text" name="orgName" value='<?=$orgName?>' required>
                        </div>
                        
                        <div class="row">
                            <label>Organization Address</label>
                            <input class="form-control" type="text" name="address" value='<?=$address?>' required>
                        </div>
                        
                        <div class="row">
                            <label>City </label>
                            <input class="form-control" type="text" name="city" value='<?=$city?>' required>
                        </div>
                        
                        <div class="row">
                            <label>State</label>
                            <select class="form-control text-secondary" style="height: 40px;" type="text" name="state" value="<?=$state?>" required >
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
                            <input class="form-control" type="text" name="zipCode" value='<?=$zipCode?>' required>
                        </div>

                        <div class="row">
                            <label>Organization Code</label>
                            <input class="form-control" type="text" name="orgCode" value='<?=$orgCode?>' disabled> 
                        </div>

                    
                        <input class="form-control" type="hidden" name="orgID" value="<?=$orgID;?>" readonly>

                        <div style="display:flex;">
                            <input class="form-control btn btn-light m-2" type="submit" name="edit" value="Submit Changes">
                            <a class="form-control btn btn-light m-2" href="organizations.php?action=Viewer">Go Back</a>
                            <a class="btn btn-light form-control m-2" href="orgControlPanel.php?action=Landing&orgID=<?= $orgID; ?>">Access Organization Controller</a>
                        </div> 
                    </form>

                <?php elseif($_SESSION['isOrgAdmin']): 
                    $orgID = filter_input(INPUT_GET, 'orgID');
                    $organization = $orgDB->getOrganization($orgID);

                    $orgName = $organization['orgName'];
                    $address = $organization['address'];
                    $city = $organization['city'];
                    $state = $organization['state'];
                    $zipCode = $organization['zipCode'];
                    $orgCode = $organization['orgCode']; ?>

                    <h3>Organization Editor</h3>

                    <form method="post" action="landingPage.php" name="Organization_CRUD">

                        <div class="row">
                            <label>Organization Name</label>
                            <input class="form-control" type="text" name="orgName" value='<?=$orgName?>' required>
                        </div>
                        
                        <div class="row">
                            <label>Organization Address</label>
                            <input class="form-control" type="text" name="address" value='<?=$address?>' required>
                        </div>
                        
                        <div class="row">
                            <label>City </label>
                            <input class="form-control" type="text" name="city" value='<?=$city?>' required>
                        </div>
                        
                        <div class="row">
                            <label>State</label>
                            <select class="form-control text-secondary" style="height: 40px;" type="text" name="state" value="<?=$state?>" required >
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
                            <input class="form-control" type="text" name="zipCode" value='<?=$zipCode?>' required>
                        </div>

                        <div class="row">
                            <label>Organization Code</label>
                            <input class="form-control" type="text" name="orgCode" value='<?=$orgCode?>' disabled> 
                        </div>

                    
                        <input class="form-control" type="hidden" name="orgID" value="<?=$orgID;?>" readonly>

                        <div style="display:flex;">
                            <input class="form-control btn btn-light m-2" type="submit" name="edit" value="Submit Changes">
                            <a class="form-control btn btn-light m-2" href="landingPage.php">Go Back</a>
                        </div> 
                    </form>

                <?php endif; ?>
            <?php endif; ?>
        </div>  
    </div>

<?php include __DIR__ . '/../include/footer.php'; ?>