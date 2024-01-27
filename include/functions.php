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
?>