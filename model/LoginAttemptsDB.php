<?php 

class LoginDB {
    private $loginData;

    public function __construct() {
        if ($ini = parse_ini_file('dbconfig.ini')) {
            $loginPDO = new PDO(   "mysql:host=" . $ini['servername'] .
                                        ";port=" . $ini['port'] .
                                        ";dbname=" . $ini['dbname'],
                                        $ini['username'],
                                        $ini['password']);
            
            $loginPDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $loginPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->loginData = $loginPDO;
        } else {
            throw new Exception("<h2>Creation of database object failed!</h2>", 0, null);
        }
    }

    public function getAllLogins() {
        $results = [];
        $loginTable = $this->loginData;

        $sqlString = $loginTable->prepare("SELECT * FROM logginattempts");

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }
}

?>