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

    //function to createUser
    public function createUser($orgID,$firstName,$lastName,$phoneNumber,$email,$birthdate,$gender,$letterDate,$username,$password,$isOrgAdmin,$isVerified){

        $results = 0;
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("INSERT INTO users set orgID = :o, firstName = :f, lastName = :ln, email = :e, birthDate = :b, phoneNumber = :pn, gender = :g, username = :u, password = sha1(:pass), isOrgAdmin = :org, isTrainer = :tr, profilePicture = :pp, isVerified = 1");

        //bind values
        $binds = array(
            ":o" => $orgID,
            ":f" => $firstName,
            ":ln" => $lastName,
            ":e" => $email,
            ":b" => $birthDate,
            ":pn" => $phoneNumber,
            ":g" => $gender,
            ":u" => $this->setUsername($firstName, $lastName, $birthDate),
            ":pass" => $password,
            ":org" => $isOrgAdmin,
            ":tr" => $isTrainer,
            ":pp" => '\assets\images\Default_pfp.svg.png',
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

        $sqlString = $userTable->prepare("SELECT * FROM (users
                                            INNER JOIN organizations ON users.orgID = organizations.orgID)
                                            ORDER BY lastName");

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    //this function will get all users within a given org id (loginAttempt Function)
    public function getAllUsersInOrg($orgID){
        $results = [];
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("SELECT * FROM users WHERE orgID = :o");
        $sqlString->bindValue(":o", $orgID);

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;



    }
    public function getUser($userID){
        $results = [];
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("SELECT * FROM (users 
                                            INNER JOIN organizations ON users.orgID = organizations.orgID)
                                            Where users.userID = :userID");

        $sqlString->bindValue(':userID', $userID);

        if($sqlString->execute() && $sqlString->rowCount() > 0){
            //fetch expects one record and gives it as a singular assoc array
            //fetch all possible to have multiple records pulled (multiple assoc array)
            $results = $sqlString->fetch(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function getLastUser() {
        $results = [];
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("SELECT userID FROM users WHERE userID = LAST_INSERT_ID()");

        if($sqlString->execute() && $sqlString->rowCount() > 0){
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function siteAdminCreateUser($orgID, $firstName, $lastName, $email, $birthDate, $phoneNumber, $gender, $password, $isSiteAdmin, $isOrgAdmin, $isTrainer){
        $results = 0;
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("INSERT INTO users set orgID = :o, firstName = :f, lastName = :ln, email = :e, birthDate = :b, phoneNumber = :pn, gender = :g, username = :u, password = sha1(:pass), isSiteAdmin = :siteAdmin, isOrgAdmin = :orgAdmin, isTrainer = :trainer, profilePicture = :pp, isVerified = 1");

        //bind values
        $binds = array(
            ":o" => $orgID,
            ":f" => $firstName,
            ":ln" => $lastName,
            ":e" => $email,
            ":b" => $birthDate,
            ":pn" => $phoneNumber,
            ":g" => $gender,
            ":u" => $this->setUsername($firstName, $lastName, $birthDate),
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

    public function orgAdminCreateUser($orgID, $firstName, $lastName, $email, $birthDate, $phoneNumber, $gender, $password, $isOrgAdmin, $isTrainer){
        $results = 0;
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("INSERT INTO users set orgID = :o, firstName = :f, lastName = :ln, email = :e, birthDate = :b, phoneNumber = :pn, gender = :g, username = :u, password = sha1(:pass), isOrgAdmin = :org, isTrainer = :tr, profilePicture = :pp, isVerified = 1");

        //bind values
        $binds = array(
            ":o" => $orgID,
            ":f" => $firstName,
            ":ln" => $lastName,
            ":e" => $email,
            ":b" => $birthDate,
            ":pn" => $phoneNumber,
            ":g" => $gender,
            ":u" => $this->setUsername($firstName, $lastName, $birthDate),
            ":pass" => $password,
            ":org" => $isOrgAdmin,
            ":tr" => $isTrainer,
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

    public function siteAdminUpdateUser($userID, $firstName, $lastName, $letterDate, $email, $birthDate, $phoneNumber, $gender, $username, $isOrgAdmin, $isSiteAdmin, $isTrainer){
        $results = [];
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("UPDATE users SET firstName = :first, lastName = :last, letterDate = :letter, email = :email, birthDate = :birth, phoneNumber = :phone, gender = :gender, username = :username, isOrgAdmin = :OrgAdmin, isSiteAdmin = :SiteAdmin, isTrainer = :trainer WHERE userID = :id");

        $boundParams = array(
            ":id"           =>$userID,
            ":first"        =>$firstName,
            ":last"         =>$lastName,
            ":letter"       =>$letterDate,
            ":email"        =>$email,
            ":birth"        =>$birthDate,
            ":phone"        =>$phoneNumber,
            ":gender"       =>$gender,
            ":username"     =>$username,
            ":OrgAdmin"     =>$isOrgAdmin,
            ":SiteAdmin"    =>$isSiteAdmin,
            ":trainer"      =>$isTrainer
        );

        if ($sqlString->execute($boundParams) && $sqlString->rowCount() > 0) {
            $results = "User Updated Successfully";
        }

        return $results;
    }

    public function orgAdminUpdateUser($userID, $firstName, $lastName, $letterDate, $email, $birthDate, $phoneNumber, $gender, $isOrgAdmin, $isTrainer){
        $results = [];
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("UPDATE users SET firstName = :first, lastName = :last, letterDate = :letter, email = :email, birthDate = :birth, phoneNumber = :phone, gender = :gender, isOrgAdmin = :OrgAdmin, isTrainer = :trainer WHERE userID = :id");

        $boundParams = array(
            ":id" =>$userID,
            ":first" =>$firstName,
            ":last" =>$lastName,
            ":letter" =>$letterDate,
            ":email" =>$email,
            ":birth" =>$birthDate,
            ":phone" =>$phoneNumber,
            ":gender" =>$gender,
            ":OrgAdmin" =>$isOrgAdmin,
            ":Trainer" =>$isTrainer
        );

        if ($sqlString->execute($boundParams) && $sqlString->rowCount() > 0) {
            $results = "User Updated Successfully";
        }

        return $results;
    }

    public function generalUpdateUser($userID, $firstName, $lastName, $email, $birthDate, $phoneNumber, $gender, $username){
        $results = [];
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("UPDATE users SET firstName = :first, lastName = :last, email = :email, birthDate = :birth, phoneNumber = :phone, gender = :gender, username = :username WHERE userID = :id");

        $boundParams = array(
            ":id"       =>$userID,
            ":first"    =>$firstName,
            ":last"     =>$lastName,
            ":email"    =>$email,
            ":birth"    =>$birthDate,
            ":phone"    =>$phoneNumber,
            ":gender"   =>$gender,
            ":username" =>$username
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

    public function searchUsers($firstName, $lastName, $organization, $gender, $isSiteAdmin, $isOrgAdmin, $isTrainer) {
        $results = [];
        $userTable = $this->userData;

        $sqlString = "SELECT * FROM users
                        INNER JOIN organizations ON users.orgID = organizations.orgID
                        WHERE 1=1";
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

        if ($organization != '') {
            $sqlString .= " AND organizations.orgName LIKE :organization";
            $binds['organization'] = '%'.$organization.'%';
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

    public function validatePassword($password) {
        // Password length should be between 8 and 20 characters
        if (strlen($password) < 8 || strlen($password) > 20) {
            return false;
        }

        // Password should contain at least one uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }

        // Password should contain at least one lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }

        // Password should contain at least one digit
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }

        // Password should contain at least one special character
        if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            return false;
        }

        return true;
    }








    ################# PLEASE DONT CHANGE STUFF HERE AND BELOW WITHOUT MENTIONING. IT MAKES MERGING A NIGHTMARE SPENT AN HOUR FIXING MERGE
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

    //login page functionality
    //used to check if a entered new username in user creation is already in the database (UNIQUE USERNAME VALIDATION)
    public function getAllUsername(){

        $results = [];
        $userTable = $this->userData;

        //never use spaces for column names in mySQL :/
        $sqlString = $userTable->prepare("SELECT username FROM users ORDER BY username");

        if($sqlString->execute() && $sqlString->rowCount() > 0){
            $results = $sqlString->fetchAll(PDO::FETCH_COLUMN);
        }

        return $results;

    }

    public function getUserID($username){
        $results = [];
        $userTable = $this->userData;

        //never use spaces for column names in mySQL :/
        $sqlString = $userTable->prepare("SELECT userID FROM users WHERE username = :u");
        $sqlString->bindValue(':u',$username);

        if($sqlString->execute() && $sqlString->rowCount() > 0){
            $results = $sqlString->fetchAll(PDO::FETCH_COLUMN);
        }

        return $results[0];


    }


    public function getUserLogin(){
        $sqlString = $userTable->prepare("SELECT * FROM Users Where userID = :u");
        $sqlString->bindValue(':u', $userID);

        if($sqlString->execute() && $sqlString->rowCount() > 0){
            //fetch expects one record and gives it as a singular assoc array
            //fetch all possible to have multiple records pulled (multiple assoc array)
            $results = $sqlString->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function createUserFromIndexPage($orgID,$firstName,$lastName,$phoneNumber,$email,$birthdate,$gender,$letterDate,$username,$password,$isOrgAdmin,$isVerified){

        $results = 0;
        $userTable = $this->userData;
        $sqlString = $userTable->prepare("INSERT INTO Users set orgID = :o, firstName = :f, lastName = :ln, phoneNumber = :pn, email = :e, birthDate = :b, gender = :g, letterDate = :l, username = :u, password = :p, isOrgAdmin = :oa, isVerified = :v ");

        //bind values
        $binds = array(
            ":o" => $orgID,
            ":f" => $firstName,
            ":ln" => $lastName,
            ":pn" => $phoneNumber,
            ":e" => $email,
            ":b" => $birthdate,
            ":g" => $gender,
            ":l" => $letterDate,
            ":u" => $username,
            ":p" => $password,
            ":oa" => $isOrgAdmin,
            ":v" => $isVerified,
        );


        //if our SQL statement returns results, populate our results confirmation string
        if ($sqlString->execute($binds) && $sqlString->rowCount() > 0){
            $results = (int)$userTable->lastInsertId();
        }
        
        return ($results);
    }

    public function getUserFromIndex($userID){
        $results = [];
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("SELECT * FROM Users Where userID = :u");
        $sqlString->bindValue(':u', $userID);

        if($sqlString->execute() && $sqlString->rowCount() > 0){
            //fetch expects one record and gives it as a singular assoc array
            //fetch all possible to have multiple records pulled (multiple assoc array)
            $results = $sqlString->fetch(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    ######### END OF ALEX CODE


}

?>