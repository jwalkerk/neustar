<?php

declare(strict_types = 1);

namespace AppTest\Handler;

use App\Handler\NeustarHandler;
use PHPUnit\Framework\TestCase;

class NeustarHandlerTest extends TestCase {

    public function testConnection() {
        $neustarHandler = new NeustarHandler();
        $this->assertTrue(method_exists($neustarHandler, 'insert'));
    }

    public function testBasicQuery() {
        $neustarHandler = new NeustarHandler();
        $sql = "select sqlite_version()";
        $contents = $neustarHandler->select($sql);
        $this->assertTrue(!empty($contents));
    }

    public function testExistLookupTable() {
        $neustarHandler = new NeustarHandler();
        $sql = "select name from sqlite_master where type='table' and name='lookups';";
        $contents = $neustarHandler->select($sql);
        $this->assertTrue(!empty($contents));
    }    

    public function testLookupContent() {
        $neustarHandler = new NeustarHandler();
        $sql = "select count(*) from lookups";
        $contents = $neustarHandler->select($sql);
        $this->assertTrue(!empty($contents));
    }    
    
}
