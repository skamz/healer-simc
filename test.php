<?php

require_once(__DIR__ . "/autoloader.php");

file_put_contents("php://stdout", "stdout", 8);
file_put_contents("php://stderr", "stderr", 8);
file_put_contents("/dev/stdout", "/dev/stdout", 8);
file_put_contents("/dev/stderr", "/dev/stderr", 8);
error_log("test error_log");