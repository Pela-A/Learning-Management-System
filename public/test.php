<?php

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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Document</title>
    <style>
        .profilePhoto{
            height:30px;
        }
    </style>
</head>
<body>
    <div class="row col-4 mb-2">
        <label class="form-label">Profile Picture:</label>
        <div class="form-check form-check-inline col-4">
            <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile1" value="..\assets\images\profilePhotos\BunnyProfile.png">
            <label class="form-check-label" for="flexRadioDefault1">
                <img style="height: 30px;" class="profilePhoto" src="..\assets\images\profilePhotos\BunnyProfile.png" alt="">
            </label>
        </div>
        <div class="form-check form-check-inline col-4">
            <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile2" value="..\assets\images\profilePhotos\DefaultProfile.png">
            <label class="form-check-label" for="flexRadioDefault1">
                <img class="profilePhoto" src="..\assets\images\profilePhotos\DefaultProfile.png" alt="">
            </label>
        </div>
        <div class="form-check form-check-inline col-4">
            <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile3" value="..\assets\images\profilePhotos\DogProfile.png">
            <label class="form-check-label" for="flexRadioDefault1">
                <img class="profilePhoto" src="..\assets\images\profilePhotos\DogProfile.png" alt="">
            </label>
        </div>
        <div class="form-check form-check-inline col-4">
            <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile4" value="..\assets\images\profilePhotos\ElephantProfile.png">
            <label class="form-check-label" for="flexRadioDefault1">
                <img class="profilePhoto" src="..\assets\images\profilePhotos\ElephantProfile.png" alt="">
            </label>
        </div>
        <div class="form-check form-check-inline col-4">
            <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile5" value="..\assets\images\profilePhotos\FrogProfile.png">
            <label class="form-check-label" for="flexRadioDefault1">
                <img class="profilePhoto" src="..\assets\images\profilePhotos\FrogProfile.png" alt="">
            </label>
        </div>
        <div class="form-check form-check-inline col-4">
            <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile6" value="..\assets\images\profilePhotos\HamsterProfile.png">
            <label class="form-check-label" for="flexRadioDefault1">
                <img class="profilePhoto" src="..\assets\images\profilePhotos\HamsterProfile.png" alt="">
            </label>
        </div>
        <div class="form-check form-check-inline col-4">
            <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile7" value="..\assets\images\profilePhotos\IceAgeProfile.png">
            <label class="form-check-label" for="flexRadioDefault1">
                <img class="profilePhoto" src="..\assets\images\profilePhotos\IceAgeProfile.png" alt="">
            </label>
        </div>
        <div class="form-check form-check-inline col-4">
            <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile8" value="..\assets\images\profilePhotos\LlamaProfile.png">
            <label class="form-check-label" for="flexRadioDefault1">
                <img class="profilePhoto" src="..\assets\images\profilePhotos\LlamaProfile.png" alt="">
            </label>
        </div>
        <div class="form-check form-check-inline col-4">
            <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile9" value="..\assets\images\profilePhotos\PenguinProfile.png">
            <label class="form-check-label" for="flexRadioDefault1">
                <img class="profilePhoto" src="..\assets\images\profilePhotos\PenguinProfile.png" alt="">
            </label>
        </div>
        <div class="form-check form-check-inline col-4">
            <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile10" value="..\assets\images\profilePhotos\PolarBear.png">
            <label class="form-check-label" for="flexRadioDefault1">
                <img class="profilePhoto" src="..\assets\images\profilePhotos\PolarBear.png" alt="">
            </label>
        </div>
        <div class="form-check form-check-inline col-4">
            <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile11" value="..\assets\images\profilePhotos\Porcupine.png" <?php if($profilePhoto == "..\assets\images\profilePhotos\Porcupine.png") echo('checked') ?>>
            <label class="form-check-label" for="flexRadioDefault1">
                <img class="profilePhoto" src="..\assets\images\profilePhotos\Porcupine.png" alt="">
            </label>
        </div>
        <div class="form-check form-check-inline col-4">
            <input class="form-check-input firstHalf" type="radio" name="profilePhoto" id="profile12" value="..\assets\images\profilePhotos\WalrusProfile.png">
            <label class="form-check-label" for="flexRadioDefault1">
                <img class="profilePhoto" src="..\assets\images\profilePhotos\WalrusProfile.png" alt="">
            </label>
        </div>

    </div>
</body>
</html>