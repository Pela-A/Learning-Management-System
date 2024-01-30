<?php 

class UserDB {
    private $userData;
    
    protected string $username;
    protected string $password;


    //i think I understand what is going on with the construct of the classes but I wanna clarify a few things
    //are we going to give the construct input and assign the properties inside the construct?
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
        //this makes our constructor have dynamic input
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

    //added function for logging in
    public function login(){
        
        
        $result = [];
        //i grabbed the userData variable to allow use of prepare statements.
        //statement here connects to database and checks that the password is valid
        $stmt = $this->userData->prepare("SELECT Username, Password FROM Users WHERE Username=:user AND Password=:pass");
        $stmt->bindValue(':user', $this->username);
        $stmt->bindValue(':pass', $this->password);
       
        if ( $stmt->execute() && $stmt->rowCount() > 0 ) {
             $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        
        }
         
        return ($result);
    }
}

?>