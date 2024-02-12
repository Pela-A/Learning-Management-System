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

        //initialize results
        $results = [];

        //prepare our SQL statement

        $organizationTable = $this->orgData;
        $sqlString = $organizationTale->prepare("SELECT orgID, orgName, address, city, state, zipCode FROM Organizations ORDER BY orgName");

        //if our SQL statement returns results, populate our result array

        if ( $stmt->execute() && $stmt->rowCount() > 0){
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        }

        return ($results);
    }

    public function getAllOrgCodes (){
        $results = [];
        $organizationTable = $this->orgData;

        //never use spaces for column names in mySQL :/
        $sqlString = $organizationTable->prepare("SELECT orgCode FROM Organizations ORDER BY orgCode");

        if($sqlString->execute() && $sqlString->rowCount() > 0){
            //FETCH_COLUMN returns just an array of all values in column
            $results = $sqlString->fetchAll(PDO::FETCH_COLUMN);
        }

        return $results;

    }
    
    public function getOrgID ($orgCode){
        $results = [];
        $organizationTable = $this->orgData;

        //never use spaces for column names in mySQL :/
        $sqlString = $organizationTable->prepare("SELECT orgID FROM Organizations WHERE orgCode = :o");
        $sqlString->bindValue(':o', $orgCode);

        if($sqlString->execute() && $sqlString->rowCount() > 0){
            //FETCH_COLUMN returns just an array of all values in column
            $results = $sqlString->fetchAll(PDO::FETCH_COLUMN);
        }

        return $results[0];
    }

    public function createOrganization($orgName,$orgAddress,$orgCity,$orgState,$orgZip,$orgCode) {

        $results = 0;

        $organizationTable = $this->orgData;

        $sqlString = $organizationTable->prepare("INSERT INTO Organizations set orgName = :o, address = :a, city = :c, state = :s, zipcode = :z, orgCode = :oc ");


        //bind values
        $binds = array(
            ":o" => $orgName,
            ":a" => $orgAddress,
            ":c" => $orgCity,
            ":s" => $orgState,
            ":z" => $orgZip,
            ":oc" => $orgCode,
        );

        //if our SQL statement returns results, populate our results confirmation string
        if ($sqlString->execute($binds) && $sqlString->rowCount() > 0){
            $results = (int)$organizationTable->lastInsertId();
        }
        
        return ($results);

        




    }
}

?>