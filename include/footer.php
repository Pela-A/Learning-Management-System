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

</body>
</html>