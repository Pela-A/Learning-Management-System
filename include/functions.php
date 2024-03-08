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

    //setting session variables on login/org creation.
    function setSessionLogin($userData) {
        session_start();
        $_SESSION['userID']=$userData['userID'];
        $_SESSION['orgID']=$userData['orgID'];
        $_SESSION['firstName']=$userData['firstName'];
        $_SESSION['lastName']=$userData['lastName'];
        $_SESSION['profilePicture']=$userData['profilePicture'];

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