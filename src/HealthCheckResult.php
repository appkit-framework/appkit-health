<?php

namespace AppKit\Health;

use AppKit\Health\HealthIndicatorInterface;

class HealthCheckResult {
    private $status;
    private $details;

    function __construct($data) {
        $this -> status = true;
        $this -> details = $this -> parseRecur($data);
    }

    public function isHealthy() {
        return $this -> status;
    }

    public function getDetails() {
        return $this -> details;
    }

    private function parseRecur($data) {
        if(is_array($data)) {
            foreach($data as $k => $v)
                $data[$k] = $this -> parseRecur($v);

            return $data;
        } else if($data instanceof self || $data instanceof HealthIndicatorInterface) {
            if($data instanceof HealthIndicatorInterface)
                $data = $data -> checkHealth();

            if(!$data -> isHealthy())
                $this -> status = false;

            return $data -> getDetails();
        } else {
            $data = (bool) $data;

            if(!$data)
                $this -> status = false;

            return $data;
        }
    }
}
