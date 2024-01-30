<?php 

class OrganizationDB {
    private $organizationData;

    //creating protected variables for class that will be instantiated in the construct
    protected $orgID;
    protected $orgName;
    protected $orgAddress;
    protected $orgCity;
    protected $orgState;
    protected $orgZip;
    protected $orgCode;

    public function __construct($params = array()) {
        if ($ini = parse_ini_file('dbconfig.ini')) {
            $organizationPDO = new PDO(   "mysql:host=" . $ini['servername'] .
                                        ";port=" . $ini['port'] .
                                        ";dbname=" . $ini['dbname'],
                                        $ini['username'],
                                        $ini['password']);
            
            $organizationPDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $organizationPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->organizationData = $organizationPDO;

            //planning to make it so that protected variables above are given info from construct function input.
        } else {
            throw new Exception("<h2>Creation of database object failed!</h2>", 0, null);
        }

        //dynamically grabs appropriate information for object.
        foreach ($params as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function getAllOrganizations() {

        //initialize results
        $results = [];

        //prepare our SQL statement

        $organizationTable = $this->organizationData;
        $sqlString = $organizationTale->prepare("SELECT orgID, orgName, address, city, state, zipCode FROM Organizations ORDER BY orgName");

        //if our SQL statement returns results, populate our result array

        if ( $stmt->execute() && $stmt->rowCount() > 0){
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        }

        return ($results);
    }

    public function getAllOrgCodes (){
        $results = [];
        $organizationTable = $this->organizationData;

        //never use spaces for column names in mySQL :/
        $sqlString = $organizationTable->prepare("SELECT orgCode FROM Organizations ORDER BY orgCode");

        if($sqlString->execute() && $sqlString->rowCount() > 0){
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;

    }

    public function addOrganization() {

        




    }
}

?>