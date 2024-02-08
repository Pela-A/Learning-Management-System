<?php 

class JobCodeDB {
    private $jobCodeData;

    public function __construct() {
        if ($ini = parse_ini_file('dbconfig.ini')) {
            $jobCodePDO = new PDO(   "mysql:host=" . $ini['servername'] .
                                        ";port=" . $ini['port'] .
                                        ";dbname=" . $ini['dbname'],
                                        $ini['username'],
                                        $ini['password']);
            
            $jobCodePDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $jobCodePDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->jobCodeData = $jobCodePDO;
        } else {
            throw new Exception("<h2>Creation of database object failed!</h2>", 0, null);
        }
    }

    public function getAllJobCodes() {

    }




    //MUST ADD ORGID to jobCode Table
    public function createJob($orgID, $positionName){

        $results = 0;
        $jobTable = $this->jobCodeData;
        $sqlString = $jobTable->prepare("INSERT INTO JobCodes set orgID = :o, positionName = :p");

        //bind values
        $binds = array(
            ":o" => $orgID,
            ":p" => $positionName,
        );


        //if our SQL statement returns results, populate our results confirmation string
        if ($sqlString->execute($binds) && $sqlString->rowCount() > 0){
            $results = "New Job Code Added"
        }
        
        return ($results);
    }

    //edit jobcode method
    public function editJob($jobCodeID, $positionName){

        $results = "";

        $jobTable = $this->jobCodeData;
        $sqlString = $jobTable->prepare("UPDATE JobCodes set positionName = :p WHERE jobCodeID = :j");


        $binds = array(
            ":j" => $jobCodeID,
            ":p" => $positionName
        );
        
        
        //if our SQL statement returns results, populate our results confirmation string
        if ($sqlString->execute($binds) && $sqlString->rowCount() > 0){
            $results = 'Data Updated';
        }
        
        return ($results);
    }


    //delete jobcode method
    public function deleteJob($jobCodeID){

        $results = "Data was not deleted";

        $jobTable = $this->jobCodeData;
        $sqlString = $jobTable->prepare("DELETE FROM JobCodes WHERE jobCodeID=:id");
        
        
        $sqlString->bindValue(':id', $jobCodeID);
            
        if ($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = 'Data Deleted';
        }
        
        return ($results);
    }

    //delete jobCodeUsersBridge method
    public function deleteJobBridge($jobCodeID){

        $results = "Data was not deleted";

        $jobTable = $this->jobCodeData;
        $sqlString = $jobTable->prepare("DELETE FROM JobCodeUsersBridge WHERE jobCodeID=:id");
        
        
        $sqlString->bindValue(':id', $jobCodeID);
            
        if ($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = 'Data Deleted';
        }
        
        return ($results);
    }
}



?>