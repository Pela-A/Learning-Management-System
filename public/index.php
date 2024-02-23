<?php
    //includes at top
    include __DIR__ . '/../model/UsersDB.php';
    include __DIR__ . '/../model/OrganizationsDB.php';
    include __DIR__ . '/../model/LoginAttemptsDB.php';
    include __DIR__ . '/../include/functions.php';

    //initialize error variable and action
    $error = "";
    $action = "";

    //used to change visible form
    //################### ADD NOT VERIFIED PAGE
    if(isset($_GET['action'])){
        $action = filter_input(INPUT_GET, 'action');
    }

    //logging in form post
    if (isset($_POST['login'])) {
    
        $username = filter_input(INPUT_POST, 'username'); 
        $password = filter_input(INPUT_POST, 'password');

        try {
            //create user object
            $userDB = new UserDB();
            $userData = $userDB->login($username, $password);

            //if results found create session vars and try to redirect
            if($userData != "No Results Found"){
    
                $loginDB = new LoginDB();
                $loginDate = date('Y-m-d H:i:s');
                $ip = getenv("REMOTE_ADDR");
                if($userData['isVerified'] == 0){
                    //login attempt funct with isSuccessful False (0)
                    $loginDB->addLoginAttempt($userData['userID'], $loginDate, 0, $ip);

                    //redirect to landing page
                    header('Location: index.php?action=notVerified');
                }else{
                    //call setSessionLogin
                    setSessionLogin($userData);

                    //login attempt funct with isSuccessful True (1)
                    $loginDB->addLoginAttempt($userData['userID'], $loginDate, 1, $ip);

                    //redirect to landing page
                    header('Location: ../private/landingPage.php');
                }   
            }else{
                $error = "Incorrect Username or Password!";
                if(linear_search($userDB->getAllUsername(), $username)){
                    $loginDate = date('Y-m-d H:i:s');
                    $ip = getenv("REMOTE_ADDR");
                    $loginDB = new LoginDB();
                    $loginDB->addLoginAttempt($userDB->getUserID($username), $loginDate, 0, $ip);
                }
            }
            
        } catch (Exception $error) {
            //unset session variables and give error
            echo "<h2>" . $error->getMessage() . "</h2>";
        }
    //Create organization
    }elseif(isset($_POST['create'])){

        //post entered user information
        $firstName = filter_input(INPUT_POST, 'firstName');
        $lastName = filter_input(INPUT_POST, 'lastName');
        $phoneNum = filter_input(INPUT_POST, 'phoneNum');
        $email = filter_input(INPUT_POST, 'email');
        $birthdate = filter_input(INPUT_POST, 'birthdate');
        $gender = filter_input(INPUT_POST, 'gender');
        $newPass = filter_input(INPUT_POST, 'newPass');
        $confirmPass = filter_input(INPUT_POST, 'confirmPass');

        //verifyUserInformation
        $error = verifyUserInformation($firstName,$lastName,$phoneNum,$email,$birthdate,$gender,$newPass,$confirmPass);
        
        //post entered org information
        $orgName = filter_input(INPUT_POST, 'orgName');
        $address = filter_input(INPUT_POST, 'address');
        $city = filter_input(INPUT_POST, 'city');
        $state = filter_input(INPUT_POST, 'state');
        $zipCode = filter_input(INPUT_POST, 'zipCode');

        $enterOrgCode = "";

        if($orgName == ""){
            $error .= "<li>Please enter an organization name!";
        }
        if($address == ""){
            $error .= "<li>Please enter an organization address!";
        }
        if($city == ""){
            $error .= "<li>Please enter an organization city!";
        }

        //zipcode verification
        $zipPattern = "/^[0-9]{5}$/";
        if(!preg_match($zipPattern, $zipCode)){
            $error .= "<li>Please enter a five digit zipcode for your organization!";
        }
        
        //If no errors, create organization and assign user to Org as OrgAdmin
        if($error == ""){
            //we want to create orgCodes until the code is not already in the database
            //this means we need to pull all orgCodes and compare the newly created to them all
            $tempObj = new OrganizationDB();
            $codes = $tempObj->getAllOrgCodes();
            do {
                //random orgCode creation of length 20
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < 20; $i++) {
                    $randomString .= $characters[random_int(0, $charactersLength - 1)];
                }
                //search for random string using linear search
                //check at end if another loop needs to happen. if we return zero that means we found that org code in db
            } while (linear_Search($codes, $randomString));

            //create organization object
            $organization = new OrganizationDB();

            //code here to create organization
            //newID represents last inserted record (created organization)
            $newID = $organization->createOrganization($orgName,$address,$city,$state,$zipCode,$randomString);

            //create USER Object and add to data base
            $makeUser = new UserDB();
            $newUserID=$makeUser->orgAdminCreateUser($newID, $firstName, $lastName, $email, $birthdate, $phoneNum, $gender, $newPass, 1, 0);
            $newUserData = $makeUser->getUser($newUserID);

            //call session set function. then redirect to landing page
            setSessionLogin($newUserData);

            //log their loginAttempt
            $loginDB = new LoginDB();
            $loginDate = date('Y-m-d H:i:s');
            $ip = getenv("REMOTE_ADDR");
            $loginDB->addLoginAttempt($newUserData['userID'], $loginDate, 1, $ip);

            
            //redirect to landing page
            header('Location: ../private/landingPage.php');

        }

    //if trying to join organization.
    }elseif(isset($_POST['join'])){

        //account information
        $firstName = filter_input(INPUT_POST, 'firstName');
        $lastName = filter_input(INPUT_POST, 'lastName');
        $phoneNum = filter_input(INPUT_POST, 'phoneNum');
        $email = filter_input(INPUT_POST, 'email');
        $birthdate = filter_input(INPUT_POST, 'birthdate');
        $gender = filter_input(INPUT_POST, 'gender');
        $newPass = filter_input(INPUT_POST, 'newPass');
        $confirmPass = filter_input(INPUT_POST, 'confirmPass');

        $error = verifyUserInformation($firstName,$lastName,$phoneNum,$email,$birthdate,$gender,$newPass,$confirmPass);
        
        $orgName = "";
        $address = "";
        $city = "";
        $state = "";
        $zipCode = "";

        $enterOrgCode = filter_input(INPUT_POST, 'orgCode');

        $orgObj = new OrganizationDB();
        $code = $orgObj->getAllOrgCodes();
        

        //if org code found and no errors for input we create user and send them to not verified. Page
        if(linear_Search($code, $enterOrgCode)){

            if($error == ""){
                //get orgID to join on
                $joinID = $orgObj -> getOrgID($enterOrgCode);
                $makeUser = new UserDB();
        
                //create new user
                $profilePicture = "";
                $newUserID = $makeUser->createGeneralUser($joinID,$firstName,$lastName,$email,$birthdate,$phoneNum,$gender,$newPass, $profilePicture);

                //send login attempt and redirect to not verified page
                $loginDB = new LoginDB();
                $loginDate = date('Y-m-d H:i:s');
                $ip = getenv("REMOTE_ADDR");
                $loginDB->addLoginAttempt($newUserID, $loginDate, 0, $ip);
                header('Location: index.php?action=notVerified');
            
            }
            
        }
        else{
            $error .= "<li>There is no organization with that Code!";
        }
        
        
    //first time loading to site initialize variables for sticky fields in forms
    }else{
        $username = "";
        $password = "";
        $firstName = "";
        $lastName = "";
        $phoneNum = "";
        $email = "";
        $birthdate = "";
        $gender = "";
        $newPass = "";
        $confirmPass = "";
        $orgName = "";
        $address = "";
        $city = "";
        $state = "";
        $zipCode = "";
        $enterOrgCode = "";

        
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
    <link rel="stylesheet" href="..\assets\css\indexPage.css">
    <title>Home Page</title>

    
</head>
<body>

    <nav class="navbar pageContent">
        <a class="navbar-brand" href="#">
            <img src="../assets/images/atlasPhotos/ATLAS_Logo.png" alt="Logo">
            <strong>ATLAS</strong>
        </a>
    </nav>

    <div class="container-fluid text-light mt-4">

        <div class="row pageContent">
            <div class="col-xl-8 col-md-12 py-4">

                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                        <img class="d-block w-100" src="../assets/images/atlasPhotos/testImg1.png" alt="First slide">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Picture 1</h5>
                            <p>Description Pic 1</p>
                        </div>
                        </div>
                        <div class="carousel-item">
                        <img class="d-block w-100" src="../assets/images/atlasPhotos/testImg2.png" alt="Second slide">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Picture 2</h5>
                            <p>Description Pic 2</p>
                        </div>
                        </div>
                        <div class="carousel-item">
                        <img class="d-block w-100" src="../assets/images/atlasPhotos/testImg3.png" alt="Third slide">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Picture 3</h5>
                            <p>Description Pic 3</p>
                        </div>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            
            <?php if($action == ''): ?>
                <div class=" col-xl-4 col-md-12 py-4 special">

                    <div class="row formContent pt-4 pb-5">


                


                
                        <h2>Login</h2>

                        <?php if($error != ""):?>
                            <div class="row">

                                <div class="col-sm">
                                    <div class="error">
                                        <?php echo($error); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <form name="login_form" method="post" class="px-4 pb-2 pt-2">
                            <div class="form-group">
                                <label class="form-label" >Username</label>
                                <input name="username" type="text" class="form-control" placeholder="Username" value="<?=$username?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Password</label>
                                <input name="password" type="password" class="form-control" placeholder="Password" value="<?=$password?>">
                            </div>
            
                            <input name="login" type="submit" value="Sign in" class="btn btn-block" style="margin-top: 5px;"></input>
                        </form>
                    </div>

                    <div class="row formContent py-4">
                        

                        
                            <div class="col-12 py-1">
                                <a href="index.php?action=createOrg">
                                    <button class="btn btn-block">
                                        Register Organization
                                    </button>
                                </a>
                            </div>
                        
                    
                        
                            <div class="col-12 py-1">
                                <a href="index.php?action=joinOrg"> 
                                    <button class="btn btn-block">Join Organization</button>
                                </a>
                            </div>
                            
                     
                        
                        
                        
                    </div>




                </div>

                

            <?php elseif($action == 'createOrg'): ?>

                <div class=" col-xl-4 col-md-12 py-4">

                    <div class="row formContent pt-4 pb-5">

            


                        <h2>Create Organization</h2>

                        <form name="create_org_form" method="post" class="row px-2 pb-2 pt-2 needs-validation" novalidate>
                            <h2>Account Information</h2>

                            <?php if($error != ""):?>
                                <div class="row">

                                    <div class="col-sm">
                                        <div class="error">
                                            <?php echo($error); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label">First Name:</label>
                                <input type="text" class="form-control" name="firstName" id="firstName" pattern="^[A-Za-z]+$" required/>
                                <div class="valid-feedback">
                                </div>
                                <div class="invalid-feedback">
                                    Please Provide a Valid First Name
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Last Name:</label>
                                <input type="text" class="form-control" name="lastName" id="lastName" pattern="^[A-Za-z]+$" required>
                                <div class="valid-feedback">
                                    Works
                                </div>
                                <div class="invalid-feedback">
                                    Please Provide a Valid Last Name
                                </div>
                            
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone Number:</label>
                                <input type="text" class="form-control" name="phoneNum" id="phoneNum" pattern="[0-9]{10}" placeholder="3778219909" required>
                            
                                <div class="invalid-feedback">
                                    Please Provide a Valid Phone Number (Ten Digits)
                                </div>
                            
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email:</label>
                                <input type="text" class="form-control" name="email" id="email" value="<?=$email?>">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Birthday:</label>
                                <input type="date" class="form-control" name="birthdate" id="birthdate" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Gender</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="genderMale">
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Male
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="genderFemale" required>
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        Female
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Create Password:</label>
                                <input type="text" class="form-control" name="newPass" id="newPass" pattern=".{8,}" required />
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm Password:</label>
                                <input type="text" class="form-control" name="confirmPass" id="confirmPass" pattern=".{8,}" required>
                            </div>


                            
                            <div class="part2">



                            
                                <h2>Organization Information</h3>

                                <div class="mb-3">
                                    <label class="form-label">Organization Name</label>
                                    <input type="text" class="form-control" name="orgName" id="orgName" value="" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" class="form-control" name="address" id="address" value="" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" name="city" id="city" value="" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">State</label>
                                    <select class="form-control text-secondary col-md-4" style="height: 40px;" type="text" name="state" required >
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
                                
                                <div class="mb-3">
                                    <label class="form-label">Zip Code</label>
                                    <input type="text" class="form-control" name="zipCode" id="zipCode" value="<?=$zipCode?>">
                                </div>
                                
                                <div class="mb-3">
                                    <input type="submit" class="btn btn-block"name="create" value="Create Organization">
                                </div>
                            </div>
                        </form>
                
                    </div>
                </div>

                <script>

                    // Example starter JavaScript for disabling form submissions if there are invalid fields
                    (function () {
                    'use strict'

                    // Fetch all the forms we want to apply custom Bootstrap validation styles to
                    var forms = document.querySelectorAll('.needs-validation')

                    // Loop over them and prevent submission
                    Array.prototype.slice.call(forms)
                        .forEach(function (form) {
                        form.addEventListener('submit', function (event) {
                            if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                            }

                            form.classList.add('was-validated')
                        }, false)
                        })
                    })()
                    /*var firstName = document.querySelector(`#firstName`)

                    console.log(firstName)
                    firstName.addEventListener(`change`, (e)=>{

                        console.log("on change worked")
                        var message=""

                        var pattern1 = /[^A-Za-z-]+/
                        if(firstName.value !== "" && !pattern1.test(firstName.value) )
                        {
                            //firstName.parentElement.classList.remove(`error`)
                            firstName.previousElementSibling.classList.add(`hidden`)
                            firstName.previousElementSibling.innerHTML = ""
                        }
                        else if(firstName.value == ""){
                            message = `Please enter a first name.`
                            //inputs[0].parentElement.classList.add(`error`)
                            firstName.previousElementSibling.classList.remove(`hidden`)
                            firstName.previousElementSibling.innerHTML = `* ${message}`
                        }
                        else{
                            message = `First Name contains special characters or numbers`
                            //inputs[0].parentElement.classList.add(`error`)
                            firstName.previousElementSibling.previousElementSibling.classList.add(`error`)
                            firstName.previousElementSibling.classList.remove(`hidden`)
                            firstName.previousElementSibling.innerHTML = `* ${message}`
                        }
                    })*/





                </script>
            <?php elseif($action == 'joinOrg'): ?>
                <h2>Join Organization Form</h2>
                <form name="join_org_form" method="post">
                    <h3>Enter Your Information</h3>

                    <?php if($error != ""):?>
                        <div class="row">

                            <div class="col-sm">
                                <div class="error">
                                    <?php echo($error); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <label>First Name:</label>
                        <input type="text" name="firstName" value="<?=$firstName?>">
                    </div>

                    <div class="row">
                        <label>Last Name:</label>
                        <input type="text" name="lastName" value="<?=$lastName?>">
                    </div>

                    <div class="row">
                        <label>Phone Number:</label>
                        <input type="text" name="phoneNum" value="<?=$phoneNum?>">
                    </div>

                    <div class="row">
                        <label>Email:</label>
                        <input type="text" name="email" value="<?=$email?>">
                    </div>

                    <div class="row">
                        <label>Birthday:</label>
                        <input type="date" name="birthdate" value="<?=$birthdate?>">
                    </div>

                    <div class="row">
                        <label>Gender:</label>
                        <input type="radio" value="1" name="gender" <?php if($gender=="1") echo('checked');?>> Male
                        <input type="radio" value="0" name="gender"<?php if($gender=="0") echo('checked');?>> Female
                        <br />
                    </div>

                    <div class="row">
                        <label>Create Password:</label>
                        <input type="text" name="newPass" value="<?=$newPass?>">
                    </div>

                    <div class="row">
                        <label>Confirm Password:</label>
                        <input type="text" name="confirmPass" value="<?=$confirmPass?>">
                    </div>

                    <div class="row">
                        <label>Enter Organization Code</label>
                        <input type="text" name="orgCode" value="<?=$enterOrgCode?>">
                    </div>

                    <div class="row">
                        <input type="submit" name="join" value="Join Organization">
                    </div>


                </form>

            <?php elseif($action == 'notVerified'): ?>

                <div class=" col-xl-4 col-md-12 py-4 special">

                    <div class="row formContent pt-4 pb-5">

                    <h2>Your account is currently not verified!</h2>

                    <p>Please contact your organization administrator!</p>

                    </div>

                </div>
                

            <?php endif; ?>


        </div>



        

        

    </div>
    <?php //include __DIR__ . '/../include/footer.php'; ?>


    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>

