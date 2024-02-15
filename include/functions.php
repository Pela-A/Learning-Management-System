<?php

    function isPostRequest() {
        return (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST');
    }

    function isGetRequest() {
        return (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'GET' && !empty($_GET));
    }

    function isUserLoggedIn() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        return (array_key_exists('isLoggedIn', $_SESSION) && ($_SESSION['isLoggedIn']));
    }

    function compressImage($filePath){
        $originalImage = imagecreatefromjpeg($filePath);
        $width = imagesx($originalImage);
        $height = imagesy($originalImage);
        
        $newWidth = $width * 0.1;
        $newHeight = $height * 0.1;

        $compressedImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($compressedImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        ob_start();
        imagejpeg($compressedImage, null, 75);
        $compressedImageData = ob_get_clean();

        imagedestroy($originalImage);
        imagedestroy($compressedImage);

        return $compressedImageData;
    }

    function linear_search($arr, $target) {
        for ($i = 0; $i < count($arr); $i++) {
     
           // If a match is found, return true.
           if ($arr[$i] === $target) {
              return true;
           }
        }
     
        // No match was found, hence return false.
        return false;
     }
    

    function verifyUserInformation($firstName,$lastName,$phoneNum,$email,$birthdate,$gender,$newUser,$newPass,$confirmPass){
        $error = "";
        //RegExpressions****


        //mark true if finds characters not included
        $pattern1 = "/[^A-Za-z-]+/";

        //mark true if string contains 10 numbers only
        $pattern2 = "/[0-9]{10}/";

        //mark true if valid email parameters
        $pattern3 = "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/";

        //first name check
        if(preg_match($pattern1, $firstName)){
            $error .= "<li>First name must not contain special characters or numbers!</li>";
        }
        elseif($firstName == ""){
            $error .= "<li>Please Enter a First Name!</li>";
        }

        //last name check
        if(preg_match($pattern1, $lastName)){
            $error .= "<li>Last name must not contain special characters or numbers!</li>";
        }
        elseif($lastName == ""){
            $error .= "<li>Please Enter a Last Name!</li>";
        }

        //phoneNum check
        if(!preg_match($pattern2, $phoneNum)){
            $error .= "<li>Invalid phone number!</li>";
        }

        //email check

        if(!preg_match($pattern3, $email)){
            $error .= "<li>Invalid Email!</li>";
        }
        elseif($email == ""){
            $error .= "<li>Please enter an email.</li>";
        }

        //birthdate check

        $currentDate = date('Y-m-d');
        if($birthdate > $currentDate || $birthdate ==""){
            $error.="<li>Please provide a valid birthdate!</li>";
        }

        //Gender check
        if($gender==""){
            $error .= "<li>Please provide a Gender!</li>";
        }

        //username check
        $testUser = new UserDB();
        $testUsernames = $testUser->getAllUsername();
        if(linear_search($testUsernames, $newUser)){
            $error .= "<li>Username is already in use!</li>";
        }
        elseif(strlen($newUser) < 8){
            $error .= "<li>Username must be at least 8 characters long!</li>";
        }

        //password check
        if(strlen($newPass < 8)){
            $error .= "<li>Password must be at least 8 characters long!</li>";
        }

        if($confirmPass != $newPass){
            $error .= "<li>Password and Confirm Password must be the same!</li>";
        }

        return($error);
    }

    //setting session variables on login/org creation.
    function setSessionLogin($userData) {
        session_start();
        $_SESSION['userID']=$userData['userID'];
        $_SESSION['orgID']=$userData['orgID'];
        $_SESSION['firstName']=$userData['firstName'];
        $_SESSION['lastName']=$userData['lastName'];

        if($userData['isSiteAdmin'] == 1){
            $_SESSION['isSiteAdmin'] = True;
        } else {
            $_SESSION['isSiteAdmin'] = False;
        }

        if($userData['isOrgAdmin'] == 1){
            $_SESSION['isOrgAdmin'] = True;
        } else {
            $_SESSION['isOrgAdmin'] = False;
        }

        if($userData['isTrainer'] == 1){
            $_SESSION['isTrainer'] = True;
        } else {
            $_SESSION['isTrainer'] = False;
        }

    }

    function verifyDepartmentInformation($name, $email){
        $error = '';
        $pattern1 = "/[^a-zA-Z ]+$/";
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
?>