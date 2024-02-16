<?php 

class TrainingModuleDB {
    private $moduleData;

    public function __construct() {
        if ($ini = parse_ini_file('dbconfig.ini')) {
            $modulePDO = new PDO(   "mysql:host=" . $ini['servername'] .
                                    ";port=" . $ini['port'] .
                                    ";dbname=" . $ini['dbname'],
                                    $ini['username'],
                                    $ini['password']);
            
            $modulePDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $modulePDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->moduleData = $modulePDO;
        } else {
            throw new Exception("<h2>Creation of database object failed!</h2>", 0, null);
        }
    }

    public function getAllTrainingModules($orgID) {
        $results = [];
        $moduleTable = $this->moduleData;

        $sqlString = $moduleTable->prepare("SELECT * FROM trainingmodules WHERE orgID = :o ORDER BY courseName");
        $sqlString->bindValue(":o", $orgID);

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function getTrainingModule($moduleID){
        $results = [];
        $moduleTable = $this->moduleData;

        $sqlString = $moduleTable->prepare("SELECT * FROM trainingmodules WHERE moduleID = :m ORDER BY courseName");
        $sqlString->bindValue(":m", $moduleID);

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function createTrainingModule($orgID, $courseName, $creditHours, $category, $description, $websiteURL, $imageURL){
        $results = [];
        $moduleTable = $this->moduleData;

        $sqlString = $moduleTable->prepare("INSERT INTO trainingmodules SET orgID = :oi, courseName = :cn, creditHours = :ch, category = :cg, description = :de, websiteURL = :wu, imageURL = :iu");

        $binds = array(
            ":oi" => $orgID,
            ":cn" => $courseName,
            ":ch" => $creditHours,
            ":cg" => $category,
            ":dp" => $description,
            ":wu" => $websiteURL,
            ":iu" => $imageURL
        );

        if($sqlString->execute($binds) && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function deleteTrainingModule($moduleID){
        $results = [];
        $moduleTable = $this->moduleData;

        $sqlString = $moduleTable->prepare("DELETE FROM trainingmodules WHERE moduleID = :id");
        $sqlString->bindValue(":id", $moduleID);

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function searchTrainingModule($orgID, $courseName, $category){
        $results = [];
        $moduleTable = $this->moduleData;

        $sqlString = $moduleTable->prepare("SELECT * FROM trainingmodules WHERE 1=1");

        $binds = [];

        if ($courseName != '') {
            $sqlString .= " AND courseName LIKE :cn";
            $binds['cn'] = '%'.$courseName.'%';
        }

        if ($category != '') {
            $sqlString .= " AND category LIKE :cg";
            $binds['cg'] = '%'.$category.'%';
        }

        $sqlString = $userTable->prepare($sqlString);
        if ($sqlString->execute($binds) && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return ($results);
    }

    public function updateTrainingModule($orgID, $courseName, $creditHours, $category, $description, $websiteURL, $imageURL){
        $results = [];
        $moduleTable = $this->moduleData;

        $sqlString = $moduleTable->prepare("UPDATE trainingmodules SET orgID = :oi, courseName = :cn, creditHours = :ch, category = :cg, description = :de, websiteURL = :wu, imageURL = :iu");

        $binds = array(
            ":oi" => $orgID,
            ":cn" => $courseName,
            ":ch" => $creditHours,
            ":cg" => $category,
            ":dp" => $description,
            ":wu" => $websiteURL,
            ":iu" => $imageURL
        );

        if($sqlString->execute($binds) && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

}

?>