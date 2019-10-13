<?php

declare(strict_types = 1);

namespace App\Handler;

use PDO;

class NeustarHandler {

    const database = 'neustar.db';
    const path = __DIR__ . '/../../../../data/';
    const schema = 'create table lookups (domain varchar(500), ip varchar(20))';
    const index = 'CREATE  INDEX index_domain  ON lookups(domain)';

    function __construct() {
        if (!file_exists(self::path . self::database)) {
            $this->initialize();
        }
    }

    private function initialize() {
        try {
            $db = new PDO('sqlite:' . self::path . self::database);
            $db->exec(self::schema);
            $db->exec(self::index);
        } catch (Exception $exc) {
            throw new Exception("Failed to Initialize Database. " . $exc->getMessage());
        }
    }

    public function select($sql) {
        try {
            $db = new PDO('sqlite:' . self::path . self::database);
            $stm = $db->prepare($sql);
            $stm->execute();
            $records = $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $exc) {
            throw new Exception("Failed to get Database Records " . $exc->getMessage() . " for query " . $sql);
        }
        return $records;
    }

    public function insert($sql) {
        try {
            $db = new PDO('sqlite:' . self::path . self::database);
            $stm = $db->prepare($sql);
            $stm->execute();            
        } catch (Exception $exc) {
            throw new Exception("Failed to set Database Records " . $exc->getMessage() . " for query " . $sql);
        }
    }    
    
    
}
