<?php

namespace App\Handler;

class DnsHandler {

    private $domain;

    const regExp = "^((?:(?:(?:\w[\.\-\+]?)*)\w)+)((?:(?:(?:\w[\.\-\+]?){0,62})\w)+)\.(\w{2,6})$";

    function __construct($param) {
        $this->domain = $param;
    }

    public function check($contents, $neustar) {
        $info = array();
        foreach ($this->domain as $row) {
            if (array_search($row, array_column($contents, 'domain')) === FALSE) {
                array_push($info, $this->validate($row, $neustar));
            }
        }
        return $info;
    }

    private function validate($domain, $neustar) {
        $info = array();
        try {
            $result = dns_get_record($domain, DNS_A);
            if (!empty($result)) {
                $this->populate($domain, $result[0]['ip'], $neustar);
                $info = array("domain" => $domain, "ip" => $result[0]['ip']);
            } else {
                $info = array("domain" => $domain, "ip" => "Domain doesn't exist");
            }
        } catch (Exception $exc) {
            throw new Exception("Failed to Adquire the domain information, possible internet connection problem " . $exc->getMessage());
        }
        return $info;
    }

    private function populate($domain, $ip, $neustar) {
        try {
            $sql = "insert into lookups (domain,ip) values ('" . $domain . "','" . $ip . "')";
            $neustar->insert($sql);
        } catch (Exception $exc) {
            throw new Exception("Unable to update DNS Lookup Database " . $exc->getMessage());
        }
    }

    public function sanitize() {
        $state = true;
        /*
          foreach ($this->domain as $domain) {
          if (preg_match(self::regExp, $domain)) {
          echo "Se encontró una coincidencia.";
          } else {
          echo "No se encontró ninguna coincidencia.";
          }
          }
         * 
         */
        return $state;
    }

}
