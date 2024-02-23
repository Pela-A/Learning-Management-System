<?php 

class TrainingEntryDB {
    private $entryData;

    public function __construct() {
        if ($ini = parse_ini_file('dbconfig.ini')) {
            $entryPDO = new PDO("mysql:host=" . $ini['servername'] .
                                ";port=" . $ini['port'] .
                                ";dbname=" . $ini['dbname'],
                                $ini['username'],
                                $ini['password']);
            
            $entryPDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $entryPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->entryData = $entryPDO;
        } else {
            throw new Exception("<h2>Creation of database object failed!</h2>", 0, null);
        }
    }

    public function getAllTrainingEntries($orgID) {
        $results = [];
        $entryTable = $this->entryData;

        $sqlString = $entryTable->prepare("SELECT * FROM trainingentries INNER JOIN users ON TrainingEntries.userID = users.userID WHERE users.orgID = :o ORDER BY entryDate");
        $sqlString->bindValue(":o", $orgID);

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function getAllUserTrainingEntries($userID) {
        $results = [];
        $entryTable = $this->entryData;

        $sqlString = $entryTable->prepare("SELECT * FROM trainingentries INNER JOIN users ON TrainingEntries.userID = users.userID WHERE users.userID = :u ORDER BY entryDate");
        $sqlString->bindValue(":u", $userID);

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function getAllUnvalidatedTrainingEntries($orgID) {
        $results = [];
        $entryTable = $this->entryData;

        $sqlString = $entryTable->prepare("SELECT * FROM trainingentries INNER JOIN users ON TrainingEntries.userID = users.userID WHERE isValidated = 0 && users.orgID = :oi ORDER BY entryDate");
        $sqlString->bindValue(":oi", $orgID);

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function getTrainingEntry($entryID){
        $results = [];
        $entryTable = $this->entryData;

        $sqlString = $entryTable->prepare("SELECT * FROM TrainingEntries INNER JOIN users ON TrainingEntries.userID = users.userID WHERE entryID = :id");
        $sqlString->bindValue(":id", $entryID);

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function createTrainingEntry($userID, $courseName, $entryDate, $completeDate, $creditHours, $category, $description) {
        $results = [];
        $entryTable = $this->entryData;

        $sqlString = $entryTable->prepare("INSERT INTO TrainingEntries SET userID = :ui, courseName = :cn, entryDate = :ed, completeDate = :cd, isValidated = :iv, validateDate = :vd, validationComments = :vc, creditHours = :ch, category = :cg, description = :dp");

        $binds = array(
            ":ui" => $userID,
            ":cn" => $courseName,
            ":ed" => $entryDate,
            ":cd" => $completeDate,
            ":iv" => 0,
            ":vd" => null,
            ":vc" => null,
            ":ch" => $creditHours,
            ":cg" => $category,
            ":dp" => $description,
        );

        if($sqlString->execute($binds) && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;

    }

    public function deleteTrainingEntry($entryID){
        $results = [];
        $entryTable = $this->entryData;

        $sqlString = $entryTable->prepare("DELETE FROM TrainingEntries WHERE entryID = :id");
        $sqlString->bindValue(":id", $entryID);

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function searchUserTrainingEntry($courseName, $entryDate, $completeDate, $category){
        $results = [];
        $entryTable = $this->entryData;

        $sqlString = $entryTable->prepare("SELECT * FROM TrainingEntries INNER JOIN users ON TrainingEntries.userID = users.userID WHERE 1=1");

        $binds = [];

        if ($courseName != '') {
            $sqlString .= " AND courseName LIKE :courseName";
            $binds['courseName'] = '%'.$courseName.'%';
        }

        if ($entryDate != '') {
            $sqlString .= " AND entryDate LIKE :entryDate";
            $binds['entryDate'] = '%'.$entryDate.'%';
        }

        if ($completeDate != '') {
            $sqlString .= " AND completeDate LIKE :completeDate";
            $binds['completeDate'] = '%'.$completeDate.'%';
        }

        if ($category != '') {
            $sqlString .= " AND category LIKE :category";
            $binds['category'] = '%'.$category.'%';
        }

        $sqlString = $userTable->prepare($sqlString);
        if ($sqlString->execute($binds) && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return ($results);
    }

    public function searchAllTrainingEntry($firstName, $lastName, $courseName, $entryDate, $completeDate, $category){
        $results = [];
        $entryTable = $this->entryData;

        $sqlString = $entryTable->prepare("SELECT * FROM (TrainingEntries INNER JOIN users ON TrainingEntries.userID = users.userID) WHERE 0=0");

        $binds = [];

        if ($firstName != '') {
            $sqlString .= " AND users.firstName LIKE :first";
            $binds['first'] = '%'.$firstName.'%';
        }

        if ($lastName != '') {
            $sqlString .= " AND users.lastName LIKE :last";
            $binds['last'] = '%'.$lastName.'%';
        }

        if ($courseName != '') {
            $sqlString .= " AND courseName LIKE :courseName";
            $binds['courseName'] = '%'.$courseName.'%';
        }

        if ($entryDate != '') {
            $sqlString .= " AND entryDate LIKE :entryDate";
            $binds['entryDate'] = '%'.$entryDate.'%';
        }

        if ($completeDate != '') {
            $sqlString .= " AND completeDate LIKE :completeDate";
            $binds['completeDate'] = '%'.$completeDate.'%';
        }

        if ($category != '') {
            $sqlString .= " AND category LIKE :category";
            $binds['category'] = '%'.$category.'%';
        }

        $sqlString = $userTable->prepare($sqlString);
        if ($sqlString->execute($binds) && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return ($results);
    }

    public function updateTrainingEntry($entryID, $userID, $courseName, $entryDate, $completeDate, $isValidated, $validateDate, $validationComments, $creditHours, $category, $description){
        $results = [];
        $entryTable = $this->entryData;

        $sqlString = $entryTable->prepare("UPDATE TrainingEntries SET userID = :ui, courseName = :cn, entryDate = :ed, completeDate = :cd, isValidated = :iv, validateDate = :vd, validationComments = :vc, creditHours = :ch, category = :cg, description = :dp WHERE entryID = :ei");

        $binds = array(
            ":ui" => $userID,
            ":cn" => $courseName,
            ":ed" => $entryDate,
            ":cd" => $completeDate,
            ":iv" => $isValidated,
            ":vd" => $validateDate,
            ":vc" => $validationComments,
            ":ch" => $creditHours,
            ":cg" => $category,
            ":dp" => $description,
            ":ei" => $entryID
        );

        if($sqlString->execute($binds) && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function validateTrainingEntry($entryID, $isValidated, $validateDate, $validationComments){
        $results = [];
        $entryTable = $this->entryData;

        $sqlString = $entryTable->prepare("UPDATE TrainingEntries SET isValidated = :iv, validateDate = :vd, validationComments = :vc WHERE entryID = :id");
        
        $binds = array(
            ":iv" => $isValidated,
            ":vd" => $validateDate,
            ":vc" => $validationComments
        );

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }
}

?>