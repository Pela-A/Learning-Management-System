<?php 

class JobCodeDB {
    private $jobCodeData;

    public function __construct() {
        if ($ini = parse_ini_file('dbconfig.ini')) {
            $jobCodePDO = new PDO(   "mysql:host=" . $ini['servername'] .
                                        ";port=" . $ini['port'] .
                                        ";dbname=" . $ini['dbname'],
                                        $ini['username'],
                                        $ini['password']);
            
            $jobCodePDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $jobCodePDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->jobCodeData = $jobCodePDO;
        } else {
            throw new Exception("<h2>Creation of database object failed!</h2>", 0, null);
        }
    }

    public function getAllJobCodes() {

    }
}

?>