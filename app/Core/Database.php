<?php 

namespace App\Core;

use PDO;

trait Database {
    private function connect(){
        $string = "mysql:host=".DBHOST.";dbname=".DBNAME;
        $con = new PDO($string,DBUSER,DBPASS);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $con;
    }

    public function query($query,$data = []){
        $con = $this->connect();
        $stm = $con->prepare($query);

        $check = $stm->execute($data);
        if($check){
            if (stripos($query, 'SELECT') === 0) {
                $result = $stm->fetchAll(PDO::FETCH_OBJ);
                if (is_array($result) && count($result)) {
                    return $result;
                }
            } else {
                return true;
            }
        }

        return false;
    }

    public function insert($query, $data = []){
        $con = $this->connect();
        $stm = $con->prepare($query);

        $check = $stm->execute($data);
        if($check){
            return $con->lastInsertId();
        }

        return false;
    }

    /**
     * Helper to handle automatic encryption of specific fields
     */
    public function querySecure($query, $data = [], $encryptedFields = []) {
        // Encrypt specify fields before execution
        foreach ($encryptedFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = encrypt($data[$field]);
            }
        }
        return $this->query($query, $data);
    }
}
