<?php 

class OrganizationDB {
    private $orgData;

    public function __construct() {
        if ($ini = parse_ini_file('dbconfig.ini')) {
            $orgPDO = new PDO( "mysql:host=" . $ini['servername'] .
                                        ";port=" . $ini['port'] .
                                        ";dbname=" . $ini['dbname'],
                                        $ini['username'],
                                        $ini['password']);
            
            $orgPDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $orgPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->orgData = $orgPDO;

            //planning to make it so that protected variables above are given info from construct function input.
        } else {
            throw new Exception("<h2>Creation of database object failed!</h2>", 0, null);
        }
    }

    public function getOrganization($orgID) {
        $results = [];
        $orgTable = $this->orgData;

        $sqlString = $orgTable->prepare("SELECT * FROM organizations Where orgID = :orgID");
        $sqlString->bindValue(':orgID', $orgID);

        if($sqlString->execute() && $sqlString->rowCount() > 0){
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function getAllOrganizations() {
        $results = [];
        $orgTable = $this->orgData;

        $sqlString = $orgTable->prepare("SELECT * FROM organizations ORDER BY orgName");

        if ( $sqlString->execute() && $sqlString->rowCount() > 0){
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return ($results);
    }

    public function getAllOrgCodes (){
        $results = [];
        $orgTable = $this->orgData;

        //never use spaces for column names in mySQL :/
        $sqlString = $orgTable->prepare("SELECT orgCode FROM organizations ORDER BY orgCode");

        if($sqlString->execute() && $sqlString->rowCount() > 0){
            //FETCH_COLUMN returns just an array of all values in column
            $results = $sqlString->fetchAll(PDO::FETCH_COLUMN);
        }

        return $results;

    }
    
    public function getOrgID (){
        $results = [];
        $orgTable = $this->orgData;

        //never use spaces for column names in mySQL :/
        $sqlString = $orgTable->prepare("SELECT orgID FROM organizations WHERE orgCode = :o");
        $sqlString->bindValue(':o', $this->orgCode);

        if($sqlString->execute() && $sqlString->rowCount() > 0){
            //FETCH_COLUMN returns just an array of all values in column
            $results = $sqlString->fetchAll(PDO::FETCH_COLUMN);
        }

        return $results[0];
    }

    public function createOrganization() {

        $results = 0;

        $orgTable = $this->orgData;

        $sqlString = $orgTable->prepare("INSERT INTO Organizations set orgName = :o, address = :a, city = :c, state = :s, zipcode = :z, orgCode = :oc ");


        //bind values
        $binds = array(
            ":o" => $this->orgName,
            ":a" => $this->orgAddress,
            ":c" => $this->orgCity,
            ":s" => $this->orgState,
            ":z" => $this->orgZip,
            ":oc" => $this->orgCode,
        );

        //if our SQL statement returns results, populate our results confirmation string
        if ($sqlString->execute($binds) && $sqlString->rowCount() > 0){
            $results = (int)$orgTable->lastInsertId();
        }
        
        return ($results);

        




    }

    public function updateOrganization() {

    }
    
    public function deleteOrganization() {

    }
    
}

?>