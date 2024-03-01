<?php 

    require 'src/PHPMailer/Exception.php';
    require 'src/PHPMailer/PHPMailer.php';
    require 'src/PHPMailer/SMTP.php';

    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 6; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    echo $randomString;
    echo (strlen($randomString));

    // email atlascapstoneproj@gmail.com
    // password At123456!


    if (isset($_POST['resetPass'])){
        $email = filter_input(INPUT_POST, 'email');


    }else{
        $email="";
    }
    
    /*
    random token if doing link
    $token = bin2hex(random_bytes(32));
    echo($token);*/
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
</head>
<body>
    <!--This will contain code with email functionality to allow a user to reset their username/password
    "Sorry, we couldn't find an account associated with that email address. Please double-check your email or sign up for a new account."


    -->


    <nav class="navbar pageContent">
        <a class="navbar-brand" href="index.php">
            <img src="../assets/images/atlasPhotos/ATLAS_Logo.png" alt="Logo">
            <strong>ATLAS</strong>
        </a>
    </nav>





    <div class="container text-light mt-4">

        <div class="row pageContent">
            <div class="col-xl-8 col-md-12 py-4 formContent">
                <form name="emailEntry" method="post" class="row px-2 pb-2 pt-2 needs-validation" novalidate>
                    <div class="form-group">
                        
                        <label class="form-label" >Email</label>
                        <input name="email" type="text" class="form-control" placeholder="" value="<?=$randomString?>" maxlength="50" required pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$">

                        <input name="resetPass" type="submit" value="Reset Password" class="btn btn-block" style="margin-top: 5px;"></input>
                    </div>

                </form>

            </div>
        </div>
        

    </div>



    <script>

        /*$.ajax({
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
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                // Handle errors if needed
            }
            
        });*/
    </script>
</body>
</html>