<?php

declare(strict_types = 1);

namespace AppTest\Handler;

use App\Handler\DnsHandler;
use App\Handler\NeustarHandler;
use PHPUnit\Framework\TestCase;

class DnsHandlerTest extends TestCase {

    public function testSingleValidDomain() {
        $domains = array("google.com");
        $neustar = new NeustarHandler();
        $dnsHandler = new DnsHandler($domains);
        $sql = "select * from lookups where domain in ('google.com')";
        $contents = $neustar->select($sql);
        $info = array_merge($contents, $dnsHandler->check($contents, $neustar));
        $this->assertTrue(!empty($info));
    }

    public function testMultipleValidDomain() {
        $domains = array("google.com", "yahoo.com", "php.net");
        $neustar = new NeustarHandler();
        $dnsHandler = new DnsHandler($domains);
        $sql = "select * from lookups where domain in ('google.com','yahoo.com','php.net')";
        $contents = $neustar->select($sql);
        $info = array_merge($contents, $dnsHandler->check($contents, $neustar));
        $this->assertTrue(!empty($info));
    }

    public function testSingleWrongDomain() {
        $domains = array("go-ogle.co.m");
        $neustar = new NeustarHandler();
        $dnsHandler = new DnsHandler($domains);
        $sql = "select * from lookups where domain in ('go-ogle.co.m')";
        $contents = $neustar->select($sql);
        $info = array_merge($contents, $dnsHandler->check($contents, $neustar));
        $this->assertTrue(!empty($info));
    }

    public function testMultipleWrongDomain() {
        $domains = array("go-ogle.co.m", "yaogle.co.m");
        $neustar = new NeustarHandler();
        $dnsHandler = new DnsHandler($domains);
        $sql = "select * from lookups where domain in ('go-ogle.co.m','yaogle.co.m')";
        $contents = $neustar->select($sql);
        $info = array_merge($contents, $dnsHandler->check($contents, $neustar));
        $this->assertTrue(!empty($info));
    }

    public function testMultipleMixDomain() {
        $domains = array("services.google.com", "yaogle.co.m");
        $neustar = new NeustarHandler();
        $dnsHandler = new DnsHandler($domains);
        $sql = "select * from lookups where domain in ('services.google.com','yaogle.co.m')";
        $contents = $neustar->select($sql);
        $info = array_merge($contents, $dnsHandler->check($contents, $neustar));
        $this->assertTrue(!empty($info));
    }

}
