<?php 

class userDepDB {
    private $userDeptData;

    public function __construct() {
        if ($ini = parse_ini_file('dbconfig.ini')) {
            $userDeptPDO = new PDO(   "mysql:host=" . $ini['servername'] .
                                        ";port=" . $ini['port'] .
                                        ";dbname=" . $ini['dbname'],
                                        $ini['username'],
                                        $ini['password']);
            
            $userDeptPDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $userDeptPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->userDeptData = $userDeptPDO;
        } else {
            throw new Exception("<h2>Creation of database object failed!</h2>", 0, null);
        }  
    }

    public function getAllUserRelationships($userID) {
        $results = [];
        $userDeptTable = $this->userDeptData;

        $sqlString = $userDeptTable->prepare("SELECT * FROM depusersbridge WHERE userID = :u");
        $sqlString->bindValue(":u", $userID);

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function getAllDeptRelationships($depID) {
        $results = [];
        $userDeptTable = $this->userDeptData;

        $sqlString = $userDeptTable->prepare("SELECT * FROM depusersbridge WHERE depID = :d");
        $sqlString->bindValue(":d", $depID);

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function createRelationship($userID, $depID) {
        $results = [];
        $userDeptTable = $this->userDeptData;

        $sqlString = $userDeptTable->prepare("INSERT INTO depusersbridge SET userID = :u, depID = :d");

        $binds = array(
            ":u" => $userID,
            ":d" => $depID
        );

        if($sqlString->execute($binds) && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function deleteRelationship($userID, $depID) {
        $results = [];
        $userDeptTable = $this->userDeptData;

        $sqlString = $userDeptTable->prepare("DELETE FROM depusersbridge WHERE userID = :u AND depID = :d");
        
        $binds = array(
            ":u" => $userID,
            ":d" => $depID
        );

        if ($sqlString->execute($binds) && $sqlString->rowCount() > 0) {
            $results = "Relationship Deleted";
        }
    }

    public function deleteAllUserRelationships($userID) {
        $results = [];
        $userDeptTable = $this->userDeptData;

        $sqlString = $userDeptTable->prepare("DELETE FROM depusersbridge WHERE userID = :u");
        
        $binds = array(
            ":u" => $userID,
        );

        if ($sqlString->execute($binds) && $sqlString->rowCount() > 0) {
            $results = "User Deleted";
        }
    }

}

?>