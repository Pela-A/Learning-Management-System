<?php 

class UserDB {
    private $userData;

    public function __construct() {
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
    }

    public function getAllUsers() {
        $results = [];
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("SELECT customerId, firstName, lastName, email, phone, gender, isAdmin FROM photousers ORDER BY lastName");

        if($sqlString->execute() && $sqlString->rowCount() > 0){
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }
    public function login($user, $pass){
        global $db;
        
        $result = [];
        $stmt = $db->prepare("SELECT * FROM Users WHERE Username=:user AND Password=:pass");
        $stmt->bindValue(':user', $user);
        $stmt->bindValue(':pass', $pass);
       
        if ( $stmt->execute() && $stmt->rowCount() > 0 ) {
             $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        
         }
         
        return ($result);
    }
}

?>