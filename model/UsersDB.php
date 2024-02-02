<?php 

class UserDB {
    private $userData;
    
    protected string $username;
    protected string $password;

    protected int $userID;
    protected int $orgID;

    protected string $firstName;
    protected string $lastName;
    protected string $letterDate;
    protected string $email;
    protected string $birthdate;
    protected string $phoneNumber;
    protected string $gender;
    protected string $profilePicture;

    protected int $isOrgAdmin;
    protected int $isSiteAdmin;
    protected int $isTrainer;
    protected int $isVerified;
    



    //constructor for UserDB class
    public function __construct($params = array()) {
        if ($ini = parse_ini_file('dbconfig.ini')) {
            $userPDO = new PDO(   "mysql:host=" . $ini['servername'] .
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

        //loops through params array and assigns appropriate values for this instance of object
        //constructor gets dynamic input
        foreach ($params as $key => $value) {
            $this->{$key} = $value;
        }



    }

    //getters for Username and Password. Allows us to have sticky text fields
    public function getUsername (){
        return $this->username;
    }
    public function getPassword(){
        return $this->password;
    }

    
    //function for getting all users **needs to be updated**

    public function getAllUsers() {
        $results = [];
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("SELECT customerId, firstName, lastName, email, phone, gender, isAdmin FROM photousers ORDER BY lastName");

        if($sqlString->execute() && $sqlString->rowCount() > 0){
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }
    
    public function createUser(){

        $results = 0;

        $userTable = $this->userData;

        $sqlString = $userTable->prepare("INSERT INTO Users set orgID = :o, firstName = :f, lastName = :ln, phoneNumber = :pn, email = :e, birthdate = :b, gender = :g, letterDate = :l, username = :u, password = :p, isOrgAdmin = :oa, isVerified = :v ");


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

    //function finds one user based on userID
    public function getUser($userID){
        
        $results = [];

        $userTable = $this->userData;

        $sqlString = $userTable->prepare("SELECT * FROM Users Where userID = :UI");
        $sqlString->bindValue(':UI', $userID);

        if($sqlString->execute() && $sqlString->rowCount() > 0){
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    //used for validation of username being unique
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

    //added function for logging in
    public function login(){
        
        $results = [];
        //i grabbed the userData variable to allow use of prepare statements.
        //statement here connects to database and checks that the password is valid
        $stmt = $this->userData->prepare("SELECT userID, Username, Password FROM Users WHERE Username=:user AND Password=:pass");
        $stmt->bindValue(':user', $this->username);
        $stmt->bindValue(':pass', $this->password);
       
        if ( $stmt->execute() && $stmt->rowCount() > 0 ) {
             $results = $stmt->fetch(PDO::FETCH_ASSOC);            
        }
         
        return $results;
    }
}

?>