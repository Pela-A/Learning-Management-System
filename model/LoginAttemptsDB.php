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

    //used for editing comments
    public function getLogin($loginID){
        $results = [];

        $loginTable = $this->loginData;

        $sqlString = $loginTable->prepare("SELECT * FROM loginattempts Where loginID = :l");
        $sqlString->bindValue(':l', $loginID);

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetch(PDO::FETCH_ASSOC);
        }

        return $results;


    }

    //gets all logins at initial load of page
    public function getAllLogins() {
        $results = [];
        $loginTable = $this->loginData;

        $sqlString = $loginTable->prepare("SELECT * FROM loginattempts ORDER BY attemptDate");

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    //used for getting all personal logins on initial load of page
    public function getAllPersonalLogins($userID){
        $results = [];
        $loginTable = $this->loginData;

        $sqlString = $loginTable->prepare("SELECT * FROM loginattempts WHERE userID = :u ORDER BY attemptDate");
        $sqlString->bindValue(':u', $userID);
        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;


    }

    //used for getting all logins in an organization
    public function getAllOrgLogins($orgID){
        $results = [];
        $loginTable = $this->loginData;

        $sqlString = $loginTable->prepare("SELECT * FROM loginattempts INNER JOIN Users ON loginattempts.userID = users.userID WHERE orgID = :o ORDER BY attemptDate");
        $sqlString->bindValue(':o', $orgID);
        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function searchLogins($attemptDate, $successful, $userID, $orgID) {
        $results = [];
        $loginTable = $this->loginData;

        $sqlString = "SELECT * FROM loginattempts INNER JOIN users ON loginattempts.userID = users.userID WHERE 1=1 ";
        $binds = [];

        if ($attemptDate != '') {
            $sqlString .= " AND attemptDate LIKE :a";
            $binds[':a'] = '%'.$attemptDate.'%';
        }

        if($successful != '') {
            $sqlString .= "AND isSuccessful = :s";
            $binds[':s'] = $successful;
        }
        if ($userID != '') {
            $sqlString .= " AND loginattempts.userID = :u";
            $binds[':u'] = $userID;
        }
        if ($orgID != '') {
            $sqlString .= " AND orgID = :o";
            $binds[':o'] = $orgID;
        }

        $sqlString = $loginTable->prepare($sqlString);
        if ($sqlString->execute($binds) && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return ($results);
    }

    public function addLoginAttempt($userID, $attemptDate, $isSuccessful, $ip) {
        $results = "";
        $loginTable = $this->loginData;

        $sqlString = $loginTable->prepare("INSERT INTO loginattempts set userID = :u, attemptDate = :a, isSuccessful = :i, ipAddress = :ip");

        $binds = array(
            ":u" => $userID,
            ":a" => $attemptDate,
            ":i" => $isSuccessful,
            ":ip" => $ip,
        );

        if($sqlString->execute($binds) && $sqlString->rowCount() > 0) {
            $results = "Successful Log";
        }

        return $results;
    }

    public function editComments($loginID, $comments){
        $results = "";

        $loginTable = $this->loginData;
        $sqlString = $loginTable->prepare("UPDATE loginattempts set comments = :c WHERE loginID = :l");

        $binds = array(
            ":c" => $comments,
            ":l" => $loginID
        );
        
        //if our SQL statement returns results, populate our results confirmation string
        if ($sqlString->execute($binds) && $sqlString->rowCount() > 0){
            $results = 'Data Updated';
        }
        
        return ($results);
    }

}

?>