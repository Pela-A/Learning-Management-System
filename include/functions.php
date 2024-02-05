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

    //binary search algorithm fastest if given a sorted array.
    function binarySearch($arr, $target) {
        $left = 0;
        $right = count($arr) - 1;
        while ($left <= $right) {
            $mid = floor(($left + $right) / 2);
            // Check if the target value is found at the middle index
            if ($arr[$mid] === $target) {
                return true;
            }
            // If the target is greater, ignore the left half
            if ($arr[$mid] < $target) {
                $left = $mid + 1;
            }
            // If the target is smaller, ignore the right half
            else {
                $right = $mid - 1;
            }
        }
        // Target value not found in the array
        return false;
    }

    //[ 'AL', 'AK', 'AS', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FM', 'FL', 'GA', 'GU', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MH', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'MP', 'OH', 'OK', 'OR', 'PW', 'PA', 'PR', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VI', 'VA', 'WA', 'WV', 'WI', 'WY' ];



    function verifyUserInformation($firstName,$lastName,$phoneNum,$email,$birthdate,$gender,$newUser,$newPass){
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
        if(binarySearch($testUsernames, $newUser)){
            $error .= "<li>Username is already in use!</li>";
        }
        elseif(strlen($newUser) < 8){
            $error .= "<li>Username must be at least 8 characters long!</li>";
        }

        //password check
        if(strlen($newPass < 8)){
            $error .= "<li>Password must be at least 8 characters long!</li>";
        }

        return($error);
    }
?>