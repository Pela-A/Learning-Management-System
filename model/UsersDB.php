<?php 

class UserDB {
    private $userData;
    
    //constructor for UserDB class
    public function __construct() {
        if ($ini = parse_ini_file('dbconfig.ini')) {
            $userPDO = new PDO( "mysql:host=" . $ini['servername'] .
                                ";port=" . $ini['port'] .
                                ";dbname=" . $ini['dbname'],
                                $ini['username'],
                                $ini['password']);
            
            $userPDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $userPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->userData = $userPDO;
        } else {
            throw new Exception("<h2>Creation of database object failed!</h2>", 0, null);
        }
    }

    public function createUser(){

        $results = 0;
        $userTable = $this->userData;
        $sqlString = $userTable->prepare("INSERT INTO Users set orgID = :o, firstName = :f, lastName = :ln, phoneNumber = :pn, email = :e, birthDate = :b, gender = :g, letterDate = :l, username = :u, password = :p, isOrgAdmin = :oa, isVerified = :v ");

        //bind values
        $binds = array(
            ":o" => $this->orgID,
            ":f" => $this->firstName,
            ":ln" => $this->lastName,
            ":pn" => $this->phoneNumber,
            ":e" => $this->email,
            ":b" => $this->birthdate,
            ":g" => $this->gender,
            ":l" => $this->letterDate,
            ":u" => $this->username,
            ":p" => $this->password,
            ":oa" => $this->isOrgAdmin,
            ":v" => $this->isVerified,
        );


        //if our SQL statement returns results, populate our results confirmation string
        if ($sqlString->execute($binds) && $sqlString->rowCount() > 0){
            $results = (int)$userTable->lastInsertId();
        }
        
        return ($results);
    }

    public function getAllUsers() {
        $results = [];
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("SELECT * FROM users ORDER BY lastName");

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function getUser($userID){
        $results = [];
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("SELECT * FROM Users Where userID = $userID");

        if($sqlString->execute() && $sqlString->rowCount() > 0){
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    //added function for logging in
    public function login($username, $password){
        
        $results = [];
        $userTable = $this->userData;

        $stmt = $userTable->prepare("SELECT * FROM users WHERE username=:user AND password=:pass");
        $stmt->bindValue(':user', $username);
        $stmt->bindValue(':pass', $password);

        if ( $stmt->execute() && $stmt->rowCount() > 0 ) {
            $results = $stmt->fetch(PDO::FETCH_ASSOC);            
        } else {
            $results = "No Results Found";
        }

        return $results;
    }

}

?>