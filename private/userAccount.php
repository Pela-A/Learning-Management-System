<?php

    include __DIR__ . '/../include/header.php';
    include __DIR__ . '/../model/UsersDB.php';
    include __DIR__ . '/../model/OrganizationsDB.php';
    include __DIR__ . '/../model/DepartmentsDB.php';

    $user = new UserDB();
    $depObj = new DepartmentDB();
    $orgObj = new OrganizationDB();
    $error = "";

    if(isset($_GET['action'])){
        $action = filter_input(INPUT_GET, 'action');
    }

    $orgs = $orgObj->getAllOrganizations();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>LMS || User Account</title>
</head>
<body>
    
    <div class="mainContent"">

        <?php include __DIR__ . '/../include/aside.php'; ?>

        <div class="pageContent">

            <?php if($action == 'Viewer'):
                $users = $user->getAllUsers();
                
                if($_SESSION['isSiteAdmin']): ?>
                    
                    <a href="userAccount.php?action=createUser">Create an Account</a>

                    <table class="table table-striped table-hover table-dark">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Birth Date</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Username</th>
                                <th>isSiteAdmin</th>
                                <th>isVerified</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($users as $u): ?>
                            <tr>
                                <td>
                                    <form action="view_users.php" method="POST">
                                        <input type="hidden" name="userId" value="<?= $u['userID']; ?>" />
                                        <input class="btn btn-danger btn-sm" type="submit" name="deleteUser" value="Delete" />
                                    </form>
                                </td>
                                <td><?= $u['firstName']; ?></td>
                                <td><?= $u['lastName']; ?></td>
                                <td><?= $u['email']; ?></td>
                                <td><?= $u['birthDate']; ?></td>
                                <td><?= $u['phoneNumber']; ?></td>
                                <td><?= $u['gender']==1?"Male":"Female" ?></td>
                                <td><?= $u['username'];?></td>
                                <td><?= $u['isSiteAdmin']==0?"No":"Yes" ?></td>
                                <td><?= $u['isVerified']==0?"No":"Yes" ?></td>
                                <td><a style="font-size: 14px; width: 60px; font-weight: 100px;" class="btn btn-danger btn-sm text-light" href="editUsers.php?action=Update&userId=<?= $u['userID']; ?>">Edit</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php elseif($_SESSION['isOrgAdmin']): ?>
                    
                    <a href="userAccount.php?action=createUser">Create an Account</a>

                    <table class="table table-striped table-hover table-dark">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Birth Date</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Username</th>
                                <th>isSiteAdmin</th>
                                <th>isVerified</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($users as $u): ?>
                            <tr>
                                <td>
                                    <form action="view_users.php" method="POST">
                                        <input type="hidden" name="userId" value="<?= $u['customerId']; ?>" />
                                        <input class="btn btn-danger btn-sm" type="submit" name="deleteUser" value="Delete" />
                                    </form>
                                </td>
                                <td><?= $u['firstName']; ?></td>
                                <td><?= $u['lastName']; ?></td>
                                <td><?= $u['email']; ?></td>
                                <td><?= $u['birthDate']; ?></td>
                                <td><?= $u['phone']; ?></td>
                                <td><?= $u['gender']==1?"Male":"Female" ?></td>
                                <td><?= $u['username'];?></td>
                                <td><?= $u['isSiteAdmin']==0?"No":"Yes" ?></td>
                                <td><?= $u['isVerified']==0?"No":"Yes" ?></td>
                                <td><a style="font-size: 14px; width: 60px; font-weight: 100px;" class="btn btn-danger btn-sm text-light" href="editUsers.php?action=Update&userId=<?= $u['customerId']; ?>">Edit</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php endif; ?>
            
            <?php elseif($action == 'createUser'):
                if($_SESSION['isSiteAdmin']): ?>

                    <form class="requires-validation" novalidate method="POST">

                        <select class="form-control text-dark col-md-12" style="height: 40px;" type="text" value="<?= $orgID; ?>" name="orgID" required>
                            <option value="">Select Organization</option>
                            <?php foreach($orgs as $o): ?>
                                <option value="<?= $o['orgID']; ?>"><?= $o['orgName'] . ", " . $o['state'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        
                        <select class="form-control text-dark col-md-12" style="height: 40px;" type="text" value="<?= $depID; ?>" name="depID" required>
                            <option value="">Select Department</option>
                            <?php $deps = $depObj->GetAllDepartments($orgs['orgID']);
                            foreach($deps as $d): ?>
                                <option value="<?= $d['depID']; ?>"><?= $d['depName'] ?></option>
                            <?php endforeach; ?>
                        </select>

                        <div class="col-md-12" >
                            <input class="form-control" type="text" name="firstName" placeholder="First Name" required>
                            <div class="valid-feedback">First name field is valid!</div>
                            <div class="invalid-feedback">First name field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="text" name="lastName" placeholder="Last Name" required>
                            <div class="valid-feedback">Last name field is valid!</div>
                            <div class="invalid-feedback">Last name field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="email" name="email" placeholder="Email Address" required>
                            <div class="valid-feedback">Email field is valid!</div>
                            <div class="invalid-feedback">Email field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="date" name="birthDate" required>
                            <div class="valid-feedback">Last name field is valid!</div>
                            <div class="invalid-feedback">Last name field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="text" name="phoneNumber" placeholder="Phone Number" required>
                            <div class="valid-feedback">Phone number field is valid!</div>
                            <div class="invalid-feedback">Phone number field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="password" name="password" placeholder="Enter password" required>
                            <div class="valid-feedback">Password field is valid!</div>
                            <div class="invalid-feedback">Password field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="password" name="confirmPassword" placeholder="Confirm password" required>
                            <div class="valid-feedback">Password field is valid!</div>
                            <div class="invalid-feedback">Password field cannot be blank!</div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="gender">Gender: </label>

                            <input type="radio" class="btn-check" name="gender" value=1 id="male" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="male">Male</label>

                            <input type="radio" class="btn-check" name="gender" value=0 id="female" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="female">Female</label>

                            <div class="valid-feedback mv-up">You selected a gender!</div>
                            <div class="invalid-feedback mv-up">Please select a gender!</div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="siteAdmin">Site Admin: </label>

                            <input type="radio" class="btn-check" name="siteAdmin" value=1 id="Yes" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="Yes">Yes</label>

                            <input type="radio" class="btn-check" name="siteAdmin" value=0 id="No" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="No">No</label>

                            <div class="valid-feedback mv-up">You selected a site admin status!</div>
                            <div class="invalid-feedback mv-up">Please select a site admin status!</div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="orgAdmin">Organization Admin: </label>

                            <input type="radio" class="btn-check" name="orgAdmin" value=1 id="Yes" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="Yes">Yes</label>

                            <input type="radio" class="btn-check" name="orgAdmin" value=0 id="No" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="No">No</label>

                            <div class="valid-feedback mv-up">You selected a organization admin status!</div>
                            <div class="invalid-feedback mv-up">Please select a organization admin status!</div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="trainer">Training Manager: </label>

                            <input type="radio" class="btn-check" name="trainer" value=1 id="Yes" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="Yes">Yes</label>

                            <input type="radio" class="btn-check" name="trainer" value=0 id="No" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="No">No</label>

                            <div class="valid-feedback mv-up">You selected a training manager status!</div>
                            <div class="invalid-feedback mv-up">Please select a training manager status!</div>
                        </div>

                        <div class="form-button mt-3">
                            <button id="submit" name="register_user" type="submit" class="btn btn-primary">Create New User</button>
                        </div>

                    </form>

                <?php elseif($_SESSION['isOrgAdmin']): ?>

                    <form class="requires-validation" novalidate method="POST">

                        <select class="form-control text-dark col-md-7" style="height: 40px;" type="text" value="<?= $customerId; ?>" name="customerId" required>
                            <?php foreach($orgs as $o): ?>
                                <option value="<?= $o['orgID']; ?>"><?= $o['orgName'] ?></option>
                            <?php endforeach; ?>
                        </select>

                        <div class="col-md-12" >
                            <input class="form-control" type="text" name="orgID" placeholder="First Name" required>
                            <div class="valid-feedback">First name field is valid!</div>
                            <div class="invalid-feedback">First name field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="text" name="firstName" placeholder="First Name" required>
                            <div class="valid-feedback">First name field is valid!</div>
                            <div class="invalid-feedback">First name field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="text" name="lastName" placeholder="Last Name" required>
                            <div class="valid-feedback">Last name field is valid!</div>
                            <div class="invalid-feedback">Last name field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="email" name="email" placeholder="Email Address" required>
                            <div class="valid-feedback">Email field is valid!</div>
                            <div class="invalid-feedback">Email field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="date" name="birthDate" required>
                            <div class="valid-feedback">Last name field is valid!</div>
                            <div class="invalid-feedback">Last name field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="text" name="phoneNumber" placeholder="Phone Number" required>
                            <div class="valid-feedback">Phone number field is valid!</div>
                            <div class="invalid-feedback">Phone number field cannot be blank!</div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="gender">Gender: </label>

                            <input type="radio" class="btn-check" name="gender" value=1 id="male" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="male">Male</label>

                            <input type="radio" class="btn-check" name="gender" value=0 id="female" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="female">Female</label>

                            <div class="valid-feedback mv-up">You selected a gender!</div>
                            <div class="invalid-feedback mv-up">Please select a gender!</div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="orgAdmin">Organization Admin: </label>

                            <input type="radio" class="btn-check" name="orgAdmin" value=1 id="Yes" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="Yes">Yes</label>

                            <input type="radio" class="btn-check" name="orgAdmin" value=0 id="No" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="No">No</label>

                            <div class="valid-feedback mv-up">You selected a organization admin status!</div>
                            <div class="invalid-feedback mv-up">Please select a organization admin status!</div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="trainer">Training Manager: </label>

                            <input type="radio" class="btn-check" name="trainer" value=1 id="Yes" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="Yes">Yes</label>

                            <input type="radio" class="btn-check" name="trainer" value=0 id="No" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="No">No</label>

                            <div class="valid-feedback mv-up">You selected a training manager status!</div>
                            <div class="invalid-feedback mv-up">Please select a training manager status!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="password" name="password" placeholder="Enter password" required>
                            <div class="valid-feedback">Password field is valid!</div>
                            <div class="invalid-feedback">Password field cannot be blank!</div>
                        </div>

                        <div class="col-md-12" >
                            <input class="form-control" type="password" name="confirmPassword" placeholder="Confirm password" required>
                            <div class="valid-feedback">Password field is valid!</div>
                            <div class="invalid-feedback">Password field cannot be blank!</div>
                        </div>

                        <div class="form-button mt-3">
                            <button id="submit" name="register_user" type="submit" class="btn btn-primary">Create New User</button>
                        </div>

                    </form>

                <?php endif; ?>
            <?php elseif($action == 'personalSettings'): 
                $account = $user->getUser($_SESSION['userID']);
                if($_SESSION['isSiteAdmin']): ?>

                    <div style="display: flex;">
                        <label for="">Username: </label>
                        <p><?= $account[0]["username"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Full Name: </label>
                        <p><?= $account[0]["firstName"] . " " . $account[0]["lastName"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Account Created: </label>
                        <p><?= $account[0]["letterDate"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Email: </label>
                        <p><?= $account[0]["email"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Birth Date: </label>
                        <p><?= $account[0]["birthDate"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Phone Number: </label>
                        <p><?= $account[0]["phoneNumber"]; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Gender: </label>
                        <p><?= $account[0]['gender']==1?"Male":"Female"; ?></p>
                    </div>

                    <div style="display: flex;">
                        <label for="">Website Administrator: </label>
                        <p><?= $account[0]['isSiteAdmin']==1?"Yes":"No"; ?></p>
                    </div>

                <?php elseif($_SESSION['isOrgAdmin']): ?>

                <?php elseif($_SESSION['isTrainer']): ?>

                <?php else: ?>

                <?php endif; ?>


            <?php elseif($action == 'ChangePassword'): ?>
            <?php endif; ?>

        </div>
    </div>

<?php include __DIR__ . '/../include/footer.php'; ?>