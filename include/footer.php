<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Document</title>
</head>
<body>
    
    <footer style=" padding-block: 15px;
                    margin: 0px; 
                    font-family: 'Advent Pro'; 
                    text-align: center; 
                    font-size: 18px;
                    width: 100%;
                    background-color: rgba(217,217,217);
                    position: fixed;
                    bottom: 0;"> 
                    
        <div class="container">
            <?php 
                $file = basename($_SERVER['PHP_SELF']);
                $mod_date=date("F d Y h:i:s A", filemtime($file));
                echo "@APela & TKnott | Last published on $mod_date";
            ?>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>