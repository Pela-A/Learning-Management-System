    <div id="footer" style="padding-block: 15px;
                            margin: 0px;
                            background-color: rgba(217,217,217,.6); 
                            font-family: 'Advent Pro'; 
                            text-align: center; 
                            font-size: 18px;
                            width: 100%;">
        <div class="container">
            <?php 
                $file = basename($_SERVER['PHP_SELF']);
                $mod_date=date("F d Y h:i:s A", filemtime($file));
                echo "@APela & TKnott | Last published on $mod_date";
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>