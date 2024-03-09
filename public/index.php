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
    
        
        //post entered org information
        $orgName = filter_input(INPUT_POST, 'orgName');
        $address = filter_input(INPUT_POST, 'address');
        $city = filter_input(INPUT_POST, 'city');
        $state = filter_input(INPUT_POST, 'state');
        $zipCode = filter_input(INPUT_POST, 'zipcode');

        $enterOrgCode = "";

        
        
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
            $error .= "<li>There is no organization with that Code!</li>";
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
        $zipcode = "";
        $enterOrgCode = "";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="..\assets\css\indexPage.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    <title>Atlas | Home Page</title>

    <style>

        .navbar {
            position: fixed;
            background-color: rgb(0, 0, 0, 0.75);
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000; /* Ensure the navbar has a higher z-index */
        }

        .indexContent {
            position: absolute;
            width: 100vw;
            height: 100vh;
            top: 0;
            left: 0;
            z-index: 999; /* Lower z-index for the carousel */
        }

        .carousel-item img {
            width: 100vw;
            height: 100vh;
            object-fit: cover; /* Ensure the image covers the entire area */
            object-position: center; /* Center the image within the carousel item */
        }

    </style>
    
</head>
<body>

    <nav class="navbar">
        <a class="navbar-brand" style="margin: 0px;" href="index.php">
            <img style="height: 50px;" src="../assets/images/atlasPhotos/ATLAS_Logo.png" alt="Logo">
            <strong>ATLAS</strong>
        </a>
    </nav>

    <div class="container-fluid">

        <div class="indexContent" style="display: flex;">
            <div id="carouselExampleCaptions" class="carousel slide carousel-fade col-7" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="..\assets\images\atlasPhotos\testImg1.png" class="" alt="...">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Log in/Sign up</h5>
                            <p>Choose whether you want to create your own organization, or join another!</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="..\assets\images\atlasPhotos\testImg2.png" class="" alt="...">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Manage your Organization</h5>
                            <p>Accept users into your organization, or modify settings and user access rights for any of its users!</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="..\assets\images\atlasPhotos\testImg3.png" class="" alt="...">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Manage Trainings</h5>
                            <p>Enter and track your trainings for your organization! Keep track of Credit Hours.</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            
            <?php if($action == ''): ?>
                <div class="col-5 text-light" style="display: flex; flex-direction: column; justify-content: center;">

                    <div class="formContent">

                        <h2>Login</h2>

                        <?php if($error != ""):?>
                            <div class="row">
                                <div class="error">
                                    <?php echo($error); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <form name="login_form" method="post" class="">
                            <div class="form-group">
                                <label class="form-label">Username</label>
                                <input name="username" type="text" class="form-control" placeholder="Username" value="<?=$username?>" maxlength="50">
                            </div>
                            <div class="form-group">
                                <label class="form-label mt-3">Password</label>
                                <input name="password" type="password" class="form-control" placeholder="Password" value="<?=$password?>" maxlength="50">
                            </div>
            
                            <input name="login" type="submit" value="Sign in" class="mt-3 btn btn-block" style="margin-top: 5px;"></input>
                        </form>
                    </div>

                    <div class="formContent">
                        <div class="col-12 mb-3">
                            <a href="index.php?action=createOrg">
                                <button style="width: 300px;" class="btn btn-block">
                                    Register Organization
                                </button>
                            </a>
                        </div>

                        <div class="col-12">
                            <a href="index.php?action=joinOrg"> 
                                <button style="width: 300px;" class="btn btn-block">Join Organization</button>
                            </a>
                        </div>       
                    </div>
                </div>
            <?php elseif($action == 'createOrg'): ?>

                <div class="">

                    <div class="row formContent pt-4 pb-5">

                        <form name="create_org_form" method="post" class="row px-2 pb-2 pt-2 needs-validation" novalidate>

                            <div class="row part1">

                                <h2>Account Information</h2>

                                <div class="col-6 mb-2">
                                    <label class="form-label">First Name:</label>
                                    <input type="text" class="form-control firstHalf" name="firstName" id="firstName" onchange="validateFirstName(); checkInputs();" maxlength="50"/>
                                    <div id="firstFeedback" class="invalid-feedback">
                                        Provide a Valid First Name
                                    </div>
                                </div>

                                <div class="col-6 mb-2">
                                    <label class="form-label">Last Name:</label>
                                    <input type="text" class="form-control firstHalf" name="lastName" id="lastName" onchange="validateLastName(); checkInputs();" maxlength="50">
                                    <div id="lastFeedback" class="invalid-feedback">
                                        Please Provide a Valid Last Name
                                    </div>
                                
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Phone Number:</label>
                                    <input type="text" class="form-control firstHalf" name="phoneNum" id="phoneNum" placeholder="3778219909" onchange="validatePhoneNumber(); checkInputs();" maxlength="10">
                                
                                    <div id="phoneFeedback" class="invalid-feedback">
                                        Please Provide a Valid Phone Number (Ten Digits)
                                    </div>
                                
                                </div>

                                <div class="col-6 mb-2">
                                    <label class="form-label">Email:</label>
                                    <input type="text" class="form-control" name="email" id="email" onchange="validateEmail(); checkInputs();" maxlength="50">
                                    <div id="emailFeedback" class="invalid-feedback">
                                        Provide a valid Email
                                    </div>
                                </div>
                                
                                <div class="col-6 mb-2">
                                    <label class="form-label">Birthday:</label>
                                    <input type="date" class="form-control" name="birthdate" id="birthdate" onchange="validateBirthday(); checkInputs();">
                                    <div id="birthdayFeedback" class="invalid-feedback">
                                        Provide a valid Birthdate
                                    </div>
                                </div>
                                
                                <div class="col-12 mb-2">
                                    <label class="form-label">Gender:</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input firstHalf" type="radio" name="gender" id="maleGender" value="0" onchange="validateGender(); checkInputs();">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Male
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input firstHalf" type="radio" name="gender" id="femaleGender" value="1" onchange="validateGender(); checkInputs();">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            Female
                                        </label>
                                    </div>

                                </div>
                                
                                <div class="col-6 mb-2">
                                    <label class="form-label">Create Password:</label>
                                    <input type="text" class="form-control firstHalf" name="newPass" id="newPass" maxlength="50" onchange="comparePasswords(); checkInputs();"/>
                                    <div id="passwordFeedback" class="invalid-feedback">
                                        Password must be atleast 6 characters.
                                    </div>
                                </div>

                                <div class="col-6 mb-2">
                                    <label class="form-label">Confirm Password:</label>
                                    <input type="text" class="form-control firstHalf" name="confirmPass" id="confirmPass" maxlength="50" onchange="comparePasswords(); checkInputs();">
                                    <div id="confirmFeedback" class="invalid-feedback">
                                        Password must be atleast 6 characters.
                                    </div>
                                </div>

                                <button class="btn btn-block" type="button" id="continue">Continue</button>

                            </div>
                            
                            <div class="row part2 hidden">

                                <h2>Organization Information</h3>

                                <div class="col-12 mb-2">
                                    <label class="form-label">Organization Name</label>
                                    <input type="text" class="form-control" name="orgName" id="orgName" value="" maxlength="50" onchange="validateOrgName(); checkInputs();">
                                    <div id="orgFeedback" class="invalid-feedback">
                                        Enter an Organization Name!
                                    </div>
                                </div>
                                
                                <div class="col-6 mb-2">
                                    <label class="form-label">Address</label>
                                    <input type="text" class="form-control" name="address" id="address" value="" maxlength="50" onchange="validateAddress(); checkInputs();">
                                    <div id="addressFeedback" class="invalid-feedback">
                                        Enter an Address!
                                    </div>
                                </div>
                                
                                <div class="col-6 mb-2">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" name="city" id="city" value="" maxlength="50" onchange="validateCity(); checkInputs();">
                                    <div id="cityFeedback" class="invalid-feedback">
                                        Enter a city!
                                    </div>
                                </div>
                                
                                <div class="col-6 mb-2">
                                    <label class="form-label">State</label>
                                    <select class="form-control text-secondary" style="height: 40px;" type="text" name="state" id="state" onchange="validateState(); checkInputs();">
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
                                    <div id="stateFeedback" class="invalid-feedback">
                                        Select a State!
                                    </div>
                                </div>
                                
                                <div class="col-6 mb-2">
                                    <label class="form-label">Zip Code</label>
                                    <input type="text" class="form-control" name="zipcode" id="zipcode" maxlength="5" placeholder="55555" onchange="validateZipcode(); checkInputs();">
                                    <div id="zipcodeFeedback" class="invalid-feedback">
                                        Zipcode must be 5 digits!
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <button class="btn btn-block" type="button" id="back">Back</button>
                                </div>
                                
                                <div class="mb-2">
                                    <input type="submit" id="create" class="btn btn-block"name="create" value="Create Organization">
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
                        form.addEventListener('submit', async function (event) {
                            
                            await validateFirstName() 
                            await validateLastName() 
                            await validatePhoneNumber() 
                            await validateEmail() 
                            await comparePasswords()
                            await validateBirthday() 
                            await validateGender()
                            await validateOrgName()
                            await validateAddress()
                            await validateCity()
                            await validateState()
                            await validateZipcode()
                            
                            checkInputs()
                            
                            
                            
                        }, false)
                    })
                    })()

                    var cont = document.querySelector(`#continue`)
                    var back = document.querySelector(`#back`)

                    var part1 = document.querySelector(`.part1`)
                    var part2 = document.querySelector(`.part2`)

                    cont.addEventListener('click', function(){
                        part1.classList.add('hidden')
                        part2.classList.remove('hidden')
                    })
                    back.addEventListener('click', function(){
                        
                        
                        part1.classList.remove('hidden')
                        part2.classList.add('hidden')
                    })

                    function validateOrgName(){
                        var orgName = $('#orgName').val()
                        if(orgName != ""){
                            $('#orgName').removeClass('is-invalid').addClass('is-valid');
                            $('#orgFeedback').html('Valid Organization Name!').removeClass('invalid-feedback').addClass('valid-feedback');
                        }else{
                            $('#orgName').removeClass('is-valid').addClass('is-invalid');
                            $('#orgFeedback').html('Enter an organization name!').removeClass('valid-feedback').addClass('invalid-feedback');
                        }
                    }

                    function validateAddress(){
                        var address = $('#address').val()
                        if(address != ""){
                            $('#address').removeClass('is-invalid').addClass('is-valid');
                            $('#addressFeedback').html('Valid Address!').removeClass('invalid-feedback').addClass('valid-feedback');
                        }else{
                            $('#address').removeClass('is-valid').addClass('is-invalid');
                            $('#addressFeedback').html('Enter an address!').removeClass('valid-feedback').addClass('invalid-feedback');
                        }
                    }

                    function validateCity(){
                        var city = $('#city').val()
                        if(city != ""){
                            $('#city').removeClass('is-invalid').addClass('is-valid');
                            $('#cityFeedback').html('Valid city!').removeClass('invalid-feedback').addClass('valid-feedback');
                        }else{
                            $('#city').removeClass('is-valid').addClass('is-invalid');
                            $('#cityFeedback').html('Enter a city!').removeClass('valid-feedback').addClass('invalid-feedback');
                        }
                    }

                    function validateZipcode(){
                    
                        var zipcode = $('#zipcode').val()
                        pattern = /[0-9]{5}/
                        if(zipcode != ""){
                            if(pattern.test(zipcode)){
                                $('#zipcode').removeClass('is-invalid').addClass('is-valid');
                                $('#zipcodeFeedback').html('Valid Zipcode!').removeClass('invalid-feedback').addClass('valid-feedback');
                            }
                            else{
                                $('#zipcode').removeClass('is-valid').addClass('is-invalid');
                                $('#zipcodeFeedback').html('Invalid Zipcode! (5 Digits)').removeClass('valid-feedback').addClass('invalid-feedback');
                            }
                        }else{
                            $('#zipcode').removeClass('is-valid').addClass('is-invalid');
                            $('#zipcodeFeedback').html('Enter a Zipcode!').removeClass('valid-feedback').addClass('invalid-feedback');
                        }
                    }

                    function validateState(){
                        var state = $('#state').val()
                        if(state == ""){
                            $('#state').removeClass('is-valid').addClass('is-invalid');
                            $('#stateFeedback').html('Select a State.').removeClass('valid-feedback').addClass('invalid-feedback');
                        }
                        else{
                            $('#state').removeClass('is-invalid').addClass('is-valid');
                            $('#stateFeedback').html('Valid State.').removeClass('invalid-feedback').addClass('valid-feedback');
                        }
                    }
                    //validate birthday function
                    function validateBirthday(){
                        var birthday = $('#birthdate').val();
                        if(birthday == ""){
                            $('#birthdate').removeClass('is-valid').addClass('is-invalid');
                            $('#birthdayFeedback').html('Enter a Birthday.').removeClass('valid-feedback').addClass('invalid-feedback');
                        }else{
                            $('#birthdate').removeClass('is-invalid').addClass('is-valid');
                            $('#birthdayFeedback').html('Valid Birthday.').removeClass('invalid-feedback').addClass('valid-feedback');
                        }
                    }

                    //validate gender function
                    function validateGender(){
                        var male = $('#maleGender')
                        var female = $('#femaleGender')

                        if(male.prop('checked') || female.prop('checked')){
                            $('#maleGender').removeClass('is-invalid').addClass('is-valid');
                            $('#femaleGender').removeClass('is-invalid').addClass('is-valid');
                            $('#genderFeedback').html('Valid Gender.').removeClass('invalid-feedback').addClass('valid-feedback');                            
                        }else{
                            $('#maleGender').removeClass('is-valid').addClass('is-invalid');
                            $('#femaleGender').removeClass('is-valid').addClass('is-invalid');
                            $('#genderFeedback').html('Select a Gender.').removeClass('valid-feedback').addClass('invalid-feedback');                           
                        }
                    }

                    //validate email function
                    function validateEmail(){
                        var email = $('#email').val();
                        pattern2 = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
                        if(pattern2.test(email)){

                            $.ajax({
                                url: '../include/checkEmail.php',
                                type: 'post',
                                data: {email: email},
                                dataType: 'json',
                                success:function(response){
                                    
                                    if(response == true){
                                        $('#email').removeClass('is-invalid').addClass('is-valid');
                                        $('#emailFeedback').html('Email is available.').removeClass('invalid-feedback').addClass('valid-feedback');
                                    }else {
                                        $('#email').removeClass('is-valid').addClass('is-invalid');
                                        $('#emailFeedback').html('Email is already in use.').removeClass('valid-feedback').addClass('invalid-feedback');                                       
                                    }
                                    checkInputs();
                                },
                                error: function(xhr, status, error) {
                                    console.error(xhr.responseText);
                                    // Handle errors if needed
                                }
                                
                            });
                        }
                        else{
                            $('#email').removeClass('is-valid').addClass('is-invalid');
                            $('#emailFeedback').html('Provide a valid Email!').removeClass('valid-feedback').addClass('invalid-feedback');                            
                        }
                    }

                    function comparePasswords(){
                        var password = $('#newPass').val()
                        var confirmPassword = $('#confirmPass').val()

                        if(password.length >= 6 || confirmPassword.length >= 6){
                            if(password !== confirmPassword){
                                $('#confirmPass').removeClass('is-valid').addClass('is-invalid');
                                $('#newPass').removeClass('is-valid').addClass('is-invalid');
                                $('#confirmFeedback').html('Password and Confirm Password must be the Same!').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#passwordFeedback').html('Password and Confirm Password must be the Same!').removeClass('valid-feedback').addClass('invalid-feedback');
                            }else{
                                $('#confirmPass').removeClass('is-invalid').addClass('is-valid');
                                $('#newPass').removeClass('is-invalid').addClass('is-valid');
                                $('#confirmFeedback').html('Valid Passwords!').removeClass('invalid-feedback').addClass('valid-feedback');
                                $('#passwordFeedback').html('Valid Passwords!').removeClass('invalid-feedback').addClass('valid-feedback');
                            }

                        }
                        else{
                            $('#confirmPass').removeClass('is-valid').addClass('is-invalid');
                            $('#newPass').removeClass('is-valid').addClass('is-invalid');
                            $('#confirmFeedback').html('Confirm Password must be at least 6 characters!').removeClass('valid-feedback').addClass('invalid-feedback');
                            $('#passwordFeedback').html('Password must be at least 6 characters!').removeClass('valid-feedback').addClass('invalid-feedback');
                        }
                    }
                    function validateFirstName(){
                        var firstName = $('#firstName').val()
                        pattern = /^[A-Za-z]+$/
                        if(firstName != ""){
                            if(pattern.test(firstName)){
                                $('#firstName').removeClass('is-invalid').addClass('is-valid');
                                $('#firstFeedback').html('Valid First Name!').removeClass('invalid-feedback').addClass('valid-feedback');
                            }
                            else{
                                $('#firstName').removeClass('is-valid').addClass('is-invalid');
                                $('#firstFeedback').html('Invalid First Name!').removeClass('valid-feedback').addClass('invalid-feedback');
                            }
                        }else{
                            $('#firstName').removeClass('is-valid').addClass('is-invalid');
                            $('#firstFeedback').html('Enter a First Name!').removeClass('valid-feedback').addClass('invalid-feedback');
                        }
                        
                    }

                    function validateLastName(){
                        var lastName = $('#lastName').val()
                        pattern = /^[A-Za-z]+$/
                        if(lastName != ""){
                            if(pattern.test(lastName)){
                                $('#lastName').removeClass('is-invalid').addClass('is-valid');
                                $('#lastFeedback').html('Valid First Name!').removeClass('invalid-feedback').addClass('valid-feedback');
                            }
                            else{
                                $('#lastName').removeClass('is-valid').addClass('is-invalid');
                                $('#lastFeedback').html('Invalid First Name!').removeClass('valid-feedback').addClass('invalid-feedback');
                            }
                        }else{
                            $('#lastName').removeClass('is-valid').addClass('is-invalid');
                            $('#lastFeedback').html('Enter a First Name!').removeClass('valid-feedback').addClass('invalid-feedback');
                        }
                    }

                    function validatePhoneNumber(){
                        var phoneNum = $('#phoneNum').val()
                        pattern = /[0-9]{10}/
                        if(phoneNum != ""){
                            if(pattern.test(phoneNum)){
                                $('#phoneNum').removeClass('is-invalid').addClass('is-valid');
                                $('#phoneFeedback').html('Valid Phone Number!').removeClass('invalid-feedback').addClass('valid-feedback');
                            }
                            else{
                                $('#phoneNum').removeClass('is-valid').addClass('is-invalid');
                                $('#phoneFeedback').html('Invalid Phone Number! (10 Digits Only)').removeClass('valid-feedback').addClass('invalid-feedback');
                            }
                        }else{
                            $('#phoneNum').removeClass('is-valid').addClass('is-invalid');
                            $('#phoneFeedback').html('Enter a Phone Number!').removeClass('valid-feedback').addClass('invalid-feedback');
                        }

                    }

                    //check inputs and disable button if error.
                    function checkInputs(){
                        var form = document.querySelector('.needs-validation')
                        var inputs = form.getElementsByTagName("input");
                        for (var i = 0; i < inputs.length; i++) {
                            // Check if the current input element has the specified class
                            if (inputs[i].classList.contains("is-invalid")) {
                                $('#create').prop('disabled', true); 
                                event.preventDefault()
                                event.stopPropagation()
                                break;
                            }else {
                                // Class is not applied to this input element
                                $('#create').prop('disabled', false); 
                            }
                        }
                    }

                </script>
            <?php elseif($action == 'joinOrg'): ?>

                <div class="">
                
                    <div class="row formContent pt-4 pb-5">

                        <form name="join_org_form" method="post" class="row px-2 pb-2 pt-2 needs-validation" novalidate>
                            <div class="row">

                                <h2>Account Information</h2>
                                <?php if($error != ""):?>
                                    <div class="row">

                                        <div class="col-sm">
                                            <div class="error">
                                                <?php echo($error)?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="col-6 mb-2">
                                    <label class="form-label">First Name:</label>
                                    <input type="text" class="form-control" name="firstName" id="firstName" onchange="validateFirstName(); checkInputs();" maxlength="50" value="<?=$firstName?>"/>
                                    <div id="firstFeedback" class="invalid-feedback">
                                        Provide a Valid First Name
                                    </div>
                                </div>

                                <div class="col-6 mb-2">
                                    <label class="form-label">Last Name:</label>
                                    <input type="text" class="form-control" name="lastName" id="lastName" onchange="validateLastName(); checkInputs();" maxlength="50" value="<?=$lastName?>"/>
                                    <div id="lastFeedback" class="invalid-feedback">
                                        Please Provide a Valid Last Name
                                    </div>

                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Phone Number:</label>
                                    <input type="text" class="form-control" name="phoneNum" id="phoneNum" placeholder="3778219909" onchange="validatePhoneNumber(); checkInputs();" maxlength="10" value="<?=$phoneNum?>">

                                    <div id="phoneFeedback" class="invalid-feedback">
                                        Please Provide a Valid Phone Number (Ten Digits)
                                    </div>

                                </div>

                                <div class="col-6 mb-2">
                                    <label class="form-label">Email:</label>
                                    <input type="text" class="form-control" name="email" id="email" onchange="validateEmail(); checkInputs();" maxlength="50" value="<?=$email?>">

                                    <div id="emailFeedback" class="invalid-feedback">
                                        Provide a valid Email!
                                    </div>

                                </div>

                                <div class="col-6 mb-2">
                                    <label class="form-label">Birthday:</label>
                                    <input type="date" class="form-control" name="birthdate" id="birthdate" value="<?=$birthdate?>"required max="<?=date('Y-m-d')?>" onchange="validateBirthday(); checkInputs();">
                                    <div id="birthdayFeedback" class="invalid-feedback">
                                        Provide a Date!
                                    </div>
                                </div>

                                <div class="col-12 mb-2">
                                    <label class="form-label">Gender:</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="maleGender" value="0" onchange="validateGender(); checkInputs();" <?php if($gender == "0") echo('checked') ?>>
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Male
                                        </label>
                                        
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="femaleGender" value="1" required onchange="validateGender(); checkInputs();" <?php if($gender == "1") echo('checked') ?>>
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            Female
                                        </label>
                                    </div>
                                    
                                    

                                </div>

                                <div class="col-6 mb-2">
                                    <label class="form-label">Create Password:</label>
                                    <input type="text" class="form-control firstHalf" name="newPass" id="newPass" onchange="comparePasswords(); checkInputs();" maxlength="50" value="<?=$newPass?>"/>
                                    <div id="passwordFeedback" class="invalid-feedback">
                                        Password must be atleast 6 characters!
                                    </div>
                                </div>

                                <div class="col-6 mb-2">
                                    <label class="form-label">Confirm Password:</label>
                                    <input type="text" class="form-control firstHalf" name="confirmPass" id="confirmPass" onchange="comparePasswords(); checkInputs();" maxlength="50" value="<?=$confirmPass?>"/>
                                    <div id="confirmFeedback" class="invalid-feedback">
                                        Password must be atleast 6 characters.
                                    </div>
                                </div>
            
                                <div class="mb-2">
                                    <label class="form-label">Enter Organization Code</label>
                                    <input type="text" id="orgCode" name="orgCode" class="form-control" onchange="validateOrgCode(); checkInputs();" maxlength="20" value="<?=$enterOrgCode?>">
                                    <div id="orgCodeFeedback" class="invalid-feedback">
                                        Org Code must be 20 characters.
                                    </div>
                                </div>
                                    

                            
                                <div class="mb-2">
                                    <input type="submit" id="join" name="join" value="Join Organization" class="btn btn-block">
                                </div>
                            </div>  
                            
                        </div>
                    </div>

                </form>
                <script>

                    // Example starter JavaScript for disabling form submissions if there are invalid fields
                    (function () {
                    'use strict'

                    // Fetch all the forms we want to apply custom Bootstrap validation styles to
                    var forms = document.querySelectorAll('.needs-validation')

                    // Loop over them and prevent submission
                    Array.prototype.slice.call(forms)
                        .forEach(function (form) {
                        form.addEventListener('submit', async function (event) {
                            
                            await validateFirstName() 
                            await validateLastName() 
                            await validatePhoneNumber() 
                            await validateEmail() 
                            await comparePasswords()
                            await validateBirthday() 
                            await validateOrgCode() 
                            await validateGender()
                            
                            checkInputs()
                            
                            
                            
                        }, false)
                    })
                    })()

                    function validateBirthday(){
                        var birthday = $('#birthdate').val();
                        if(birthday == ""){
                            $('#birthdate').removeClass('is-valid').addClass('is-invalid');
                            $('#birthdayFeedback').html('Enter a Birthday.').removeClass('valid-feedback').addClass('invalid-feedback');
                        }else{
                            $('#birthdate').removeClass('is-invalid').addClass('is-valid');
                            $('#birthdayFeedback').html('Valid Birthday.').removeClass('invalid-feedback').addClass('valid-feedback');
                        }
                    }

                    function validateGender(){
                        var male = $('#maleGender')
                        var female = $('#femaleGender')

                        if(male.prop('checked') || female.prop('checked')){
                            $('#maleGender').removeClass('is-invalid').addClass('is-valid');
                            $('#femaleGender').removeClass('is-invalid').addClass('is-valid');
                            $('#genderFeedback').html('Valid Gender.').removeClass('invalid-feedback').addClass('valid-feedback');                            
                        }else{
                            $('#maleGender').removeClass('is-valid').addClass('is-invalid');
                            $('#femaleGender').removeClass('is-valid').addClass('is-invalid');
                            $('#genderFeedback').html('Select a Gender.').removeClass('valid-feedback').addClass('invalid-feedback');                           
                        }
                    }

                    function validateEmail(){
                        var email = $('#email').val();
                        pattern2 = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
                        if(pattern2.test(email)){

                            $.ajax({
                                url: '../include/checkEmail.php',
                                type: 'post',
                                data: {email: email},
                                dataType: 'json',
                                success:function(response){
                                    
                                    if(response == true){
                                        $('#email').removeClass('is-invalid').addClass('is-valid');
                                        $('#emailFeedback').html('Email is available.').removeClass('invalid-feedback').addClass('valid-feedback');
                                    }else {
                                        $('#email').removeClass('is-valid').addClass('is-invalid');
                                        $('#emailFeedback').html('Email is already in use.').removeClass('valid-feedback').addClass('invalid-feedback');                                       
                                    }
                                    checkInputs();
                                },
                                error: function(xhr, status, error) {
                                    console.error(xhr.responseText);
                                    // Handle errors if needed
                                }
                                
                            });
                        }
                        else{
                            $('#email').removeClass('is-valid').addClass('is-invalid');
                            $('#emailFeedback').html('Provide a valid Email!').removeClass('valid-feedback').addClass('invalid-feedback');                            
                        }
                    }

                    function comparePasswords(){
                        var password = $('#newPass').val()
                        var confirmPassword = $('#confirmPass').val()

                        if(password.length >= 6 || confirmPassword.length >= 6){
                            if(password !== confirmPassword){
                                $('#confirmPass').removeClass('is-valid').addClass('is-invalid');
                                $('#newPass').removeClass('is-valid').addClass('is-invalid');
                                $('#confirmFeedback').html('Password and Confirm Password must be the Same!').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#passwordFeedback').html('Password and Confirm Password must be the Same!').removeClass('valid-feedback').addClass('invalid-feedback');
                            }else{
                                $('#confirmPass').removeClass('is-invalid').addClass('is-valid');
                                $('#newPass').removeClass('is-invalid').addClass('is-valid');
                                $('#confirmFeedback').html('Valid Passwords!').removeClass('invalid-feedback').addClass('valid-feedback');
                                $('#passwordFeedback').html('Valid Passwords!').removeClass('invalid-feedback').addClass('valid-feedback');
                            }

                        }
                        else{
                            $('#confirmPass').removeClass('is-valid').addClass('is-invalid');
                            $('#newPass').removeClass('is-valid').addClass('is-invalid');
                            $('#confirmFeedback').html('Confirm Password must be at least 6 characters!').removeClass('valid-feedback').addClass('invalid-feedback');
                            $('#passwordFeedback').html('Password must be at least 6 characters!').removeClass('valid-feedback').addClass('invalid-feedback');
                        }
                    }
                    function validateFirstName(){
                        var firstName = $('#firstName').val()
                        pattern = /^[A-Za-z]+$/
                        if(firstName != ""){
                            if(pattern.test(firstName)){
                                $('#firstName').removeClass('is-invalid').addClass('is-valid');
                                $('#firstFeedback').html('Valid First Name!').removeClass('invalid-feedback').addClass('valid-feedback');
                            }
                            else{
                                $('#firstName').removeClass('is-valid').addClass('is-invalid');
                                $('#firstFeedback').html('Invalid First Name!').removeClass('valid-feedback').addClass('invalid-feedback');
                            }
                        }else{
                            $('#firstName').removeClass('is-valid').addClass('is-invalid');
                            $('#firstFeedback').html('Enter a First Name!').removeClass('valid-feedback').addClass('invalid-feedback');
                        }
                        
                    }

                    function validateLastName(){
                        var lastName = $('#lastName').val()
                        pattern = /^[A-Za-z]+$/
                        if(lastName != ""){
                            if(pattern.test(lastName)){
                                $('#lastName').removeClass('is-invalid').addClass('is-valid');
                                $('#lastFeedback').html('Valid First Name!').removeClass('invalid-feedback').addClass('valid-feedback');
                            }
                            else{
                                $('#lastName').removeClass('is-valid').addClass('is-invalid');
                                $('#lastFeedback').html('Invalid First Name!').removeClass('valid-feedback').addClass('invalid-feedback');
                            }
                        }else{
                            $('#lastName').removeClass('is-valid').addClass('is-invalid');
                            $('#lastFeedback').html('Enter a First Name!').removeClass('valid-feedback').addClass('invalid-feedback');
                        }
                    }

                    function validatePhoneNumber(){
                        var phoneNum = $('#phoneNum').val()
                        pattern = /[0-9]{10}/
                        if(phoneNum != ""){
                            if(pattern.test(phoneNum)){
                                $('#phoneNum').removeClass('is-invalid').addClass('is-valid');
                                $('#phoneFeedback').html('Valid Phone Number!').removeClass('invalid-feedback').addClass('valid-feedback');
                            }
                            else{
                                $('#phoneNum').removeClass('is-valid').addClass('is-invalid');
                                $('#phoneFeedback').html(' Invalid Phone Number! (10 Digits Only)').removeClass('valid-feedback').addClass('invalid-feedback');
                            }
                        }else{
                            $('#phoneNum').removeClass('is-valid').addClass('is-invalid');
                            $('#phoneFeedback').html(' Enter a Phone Number!').removeClass('valid-feedback').addClass('invalid-feedback');
                        }

                    }

                    function validateOrgCode(){
                        var orgCode = $('#orgCode').val()
                        if(orgCode.length != 20){
                            $('#orgCode').removeClass('is-valid').addClass('is-invalid');
                            $('#orgCodeFeedback').html('Organization Code must be 20 characters long!').removeClass('valid-feedback').addClass('invalid-feedback');
                        }else{
                            $('#orgCode').removeClass('is-invalid').addClass('is-valid');
                            $('#orgCodeFeedback').html('Organization Code is valid!').removeClass('invalid-feedback').addClass('valid-feedback');
                        }
                    }
                    function checkInputs(){
                        var form = document.querySelector('.needs-validation')
                        var inputs = form.getElementsByTagName("input");
                        for (var i = 0; i < inputs.length; i++) {
                            // Check if the current input element has the specified class
                            if (inputs[i].classList.contains("is-invalid")) {
                                $('#join').prop('disabled', true); 
                                event.preventDefault()
                                event.stopPropagation()
                                break;
                            }else {
                                // Class is not applied to this input element
                                $('#join').prop('disabled', false); 
                            }
                        }
                    }

                </script>

            <?php elseif($action == 'notVerified'): ?>

                <div class="">

                    <div class="row formContent pt-4 pb-5">

                    <h2>Your account is currently not verified!</h2>

                    <p>Please contact your organization administrator!</p>

                    </div>

                </div>
                
            <?php endif; ?>
        </div>
    </div>
    <?php //include __DIR__ . '/../include/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</body>