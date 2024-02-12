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

        $sqlString = $userTable->prepare("SELECT * FROM Users Where userID = :userID");
        $sqlString->bindValue(':userID', $userID);

        if($sqlString->execute() && $sqlString->rowCount() > 0){
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function siteAdminCreateUser($orgID, $firstName, $lastName, $email, $birthDate, $phoneNumber, $gender, $password, $profilePicture, $isSiteAdmin, $isOrgAdmin, $isTrainer){
        $results = 0;
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("INSERT INTO users set orgID = :o, firstName = :f, lastName = :ln, email = :e, birthDate = :b, phoneNumber = :pn, gender = :g, username = :u, password = sha1(:pass), isSiteAdmin = :siteAdmin, isOrgAdmin = :orgAdmin, isTrainer = :trainer, profilePicture = :pp, isVerified = 1");

        //bind values
        $binds = array(
            ":o" => $orgID,
            ":f" => $firstName,
            ":ln" => $lastName,
            ":e" => $email,
            ":b" => $birthdate,
            ":pn" => $phoneNumber,
            ":g" => $gender,
            ":u" => "ATLAS_" . setUsername($firstName, $lastName, $birthDate),
            ":pass" => $password,
            ":siteAdmin" => $isSiteAdmin,
            ":orgAdmin" => $isOrgAdmin,
            ":trainer" => $isTrainer,
            ":pp" => '\assets\images\Default_pfp.svg.png'
        );


        //if our SQL statement returns results, populate our results confirmation string
        if ($sqlString->execute($binds) && $sqlString->rowCount() > 0){
            $results = (int)$userTable->lastInsertId();
        }
        
        return ($results);
    }

    public function orgAdminCreateUser($orgID, $firstName, $lastName, $email, $birthDate, $phoneNumber, $gender, $password, $profilePicture){
        $results = 0;
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("INSERT INTO users set orgID = :o, firstName = :f, lastName = :ln, email = :e, birthDate = :b, phoneNumber = :pn, gender = :g, username = :u, password = sha1(:pass), isOrgAdmin = 1, profilePicture = :pp, isVerified = 1");

        //bind values
        $binds = array(
            ":o" => $orgID,
            ":f" => $firstName,
            ":ln" => $lastName,
            ":e" => $email,
            ":b" => $birthdate,
            ":pn" => $phoneNumber,
            ":g" => $gender,
            ":u" => setUsername($firstName, $lastName, $birthDate),
            ":pass" => $password,
            ":pp" => '\assets\images\Default_pfp.svg.png',
        );


        //if our SQL statement returns results, populate our results confirmation string
        if ($sqlString->execute($binds) && $sqlString->rowCount() > 0){
            $results = (int)$userTable->lastInsertId();
        }
        
        return ($results);
    } 

    public function createGeneralUser($orgID, $firstName, $lastName, $email, $birthDate, $phoneNumber, $gender, $password, $profilePicture){

        $results = 0;
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("INSERT INTO users set orgID = :o, firstName = :f, lastName = :ln, email = :e, birthDate = :b, phoneNumber = :pn, gender = :g, username = :u, password = sha1(:pass), profilePicture = :pp, isVerified = 0");

        //bind values
        $binds = array(
            ":o" => $orgID,
            ":f" => $firstName,
            ":ln" => $lastName,
            ":e" => $email,
            ":b" => $birthdate,
            ":pn" => $phoneNumber,
            ":g" => $gender,
            ":u" => setUsername($firstName, $lastName, $birthDate),
            ":pass" => $password,
            ":pp" => '\assets\images\Default_pfp.svg.png',
        );


        //if our SQL statement returns results, populate our results confirmation string
        if ($sqlString->execute($binds) && $sqlString->rowCount() > 0){
            $results = (int)$userTable->lastInsertId();
        }
        
        return ($results);
    }

    public function siteAdminUpdateUser($userID, $firstName, $lastName, $letterDate, $email, $birthDate, $phoneNumber, $gender, $username, $isOrgAdmin, $isSiteAdmin, $isTrainer, $profilePicture){
        $results = [];
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("UPDATE users SET firstName = :first, lastName = :last, letterDate = :letter, email = :email, birthDate = :birth, phoneNumber = :phone, gender = :gender, username = :username, isOrgAdmin = :OrgAdmin, isSiteAdmin = :SiteAdmin, isTrainer = :trainer, profilePicture = :ProPic WHERE userID = :id");

        $boundParams = array(
            ":id" =>$userID,
            ":first" =>$firstName,
            ":last" =>$lastName,
            ":letter" => $letterDate,
            ":email" =>$email,
            ":birth" =>$birthDate,
            ":phone" =>$phoneNumber,
            ":gender" =>$gender,
            ":username" =>$username,
            ":OrgAdmin" => $isOrgAdmin,
            ":SiteAdmin" => $isSiteAdmin,
            ":Trainer" => $isTrainer,
            ":ProPic" =>$profilePicture,
        );

        if ($sqlString->execute($boundParams) && $sqlString->rowCount() > 0) {
            $results = "User Updated Successfully";
        }

        return $results;
    }

    public function orgAdminUpdateUser($userID, $firstName, $lastName, $letterDate, $email, $birthDate, $phoneNumber, $gender, $isOrgAdmin, $isTrainer, $profilePicture){
        $results = [];
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("UPDATE users SET firstName = :first, lastName = :last, letterDate = :letter, email = :email, birthDate = :birth, phoneNumber = :phone, gender = :gender, isOrgAdmin = :OrgAdmin, isTrainer = :trainer, profilePicture = :ProPic WHERE userID = :id");

        $boundParams = array(
            ":id" =>$userID,
            ":first" =>$firstName,
            ":last" =>$lastName,
            ":letter" => $letterDate,
            ":email" =>$email,
            ":birth" =>$birthDate,
            ":phone" =>$phoneNumber,
            ":gender" =>$gender,
            ":OrgAdmin" => $isOrgAdmin,
            ":Trainer" => $isTrainer,
            ":ProPic" =>$profilePicture,
        );

        if ($sqlString->execute($boundParams) && $sqlString->rowCount() > 0) {
            $results = "User Updated Successfully";
        }

        return $results;
    }

    public function generalUpdateUser($userID, $firstName, $lastName, $email, $birthDate, $phoneNumber, $gender, $profilePicture){
        $results = [];
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("UPDATE users SET firstName = :first, lastName = :last, email = :email, birthDate = :birth, phoneNumber = :phone, gender = :gender, profilePicture = :ProPic WHERE userID = :id");

        $boundParams = array(
            ":id" =>$userID,
            ":first" =>$firstName,
            ":last" =>$lastName,
            ":email" =>$email,
            ":birth" =>$birthDate,
            ":phone" =>$phoneNumber,
            ":gender" =>$gender,
            ":ProPic" =>$profilePicture,
        );

        if ($sqlString->execute($boundParams) && $sqlString->rowCount() > 0) {
            $results = "User Updated Successfully";
        }

        return $results;
    }

    public function deleteUser($userID){
        $results = [];
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("DELETE FROM users WHERE userID = :id");
        $sqlString->bindValue(":id", $userID);

        if ($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = "User Deleted";
        }
    }

    public function searchUsers($firstName, $lastName, $gender, $department, $jobCode, $isSiteAdmin, $isOrgAdmin, $isTrainer) {
        $results = [];
        $userTable = $this->userData;

        $sqlString = "SELECT * FROM users WHERE 0=0";
        $binds = [];

        if ($firstName != '') {
            $sqlString .= " AND firstName LIKE :first";
            $binds['first'] = '%'.$firstName.'%';
        }

        if ($lastName != '') {
            $sqlString .= " AND lastName LIKE :last";
            $binds['last'] = '%'.$lastName.'%';
        }

        if ($gender != '') {
            $sqlString .= " AND gender LIKE :gender";
            $binds['gender'] = '%'.$gender.'%';
        }

        if ($department != '') {
            $sqlString .= " AND department LIKE :department";
            $binds['department'] = '%'.$department.'%';
        }

        if ($jobCode != '') {
            $sqlString .= " AND jobCode LIKE :jobCode";
            $binds['jobCode'] = '%'.$jobCode.'%';
        }

        if ($isSiteAdmin != '') {
            $sqlString .= " AND isSiteAdmin LIKE :isSiteAdmin";
            $binds['isSiteAdmin'] = '%'.$isSiteAdmin.'%';
        }

        if ($isOrgAdmin != '') {
            $sqlString .= " AND isOrgAdmin LIKE :isOrgAdmin";
            $binds['isOrgAdmin'] = '%'.$isOrgAdmin.'%';
        }

        if ($isTrainer != '') {
            $sqlString .= " AND isTrainer LIKE :isTrainer";
            $binds['isTrainer'] = '%'.$isTrainer.'%';
        }

        $sqlStmt = $userTable->prepare($sqlString);
        if ($sqlStmt->execute($binds) && $sqlStmt->rowCount() > 0) {
            $results = $sqlStmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return ($results);
    }

    //added function for logging in
    public function login($username, $password){        
        $results = [];
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("SELECT * FROM users WHERE username=:user AND password=:pass");
        $sqlString->bindValue(':user', $username);
        $sqlString->bindValue(':pass', $password);

        if ( $sqlString->execute() && $sqlString->rowCount() > 0 ) {
            $results = $sqlString->fetch(PDO::FETCH_ASSOC);            
        } else {
            $results = "No Results Found";
        }

        return $results;
    }

    public function getProfilePicture($userID) {

    }

    public function getUsername($userID){

    }

    public function getPassword($userID){

    }

    public function setUsername($firstName, $lastName, $birthDate) {
        $firstInitial = substr($firstName, 0, 1);

        $birthYear = date('Y', strtotime($birthDate));
        
        $username = $firstInitial . $lastName . $birthYear;

        return $username;
    }

    public function changePassword($userID, $password) {
        $results = [];
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("UPDATE users SET password = sha1(:pass) WHERE userID = :id");

        $boundParams = array(
            ":id" =>$userID,
            ":pass" =>$password
        );

        if ($sqlString->execute($boundParams) && $sqlString->rowCount() > 0) {
            $results = "User Updated Successfully";
        }

        return $results;
    }
}

?>