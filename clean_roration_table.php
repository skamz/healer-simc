<?php

require_once(__DIR__ . "/autoloader.php");

$db = new Database();

$avgInfo = $db->query("select avg(avg_heal) as aheal, count(*) as cnt from priest_dc_result where iterations > 0")->fetchArray();

print_r($avgInfo);
if ($avgInfo["cnt"] < 2000) {
	exit("Low rows\n");
}

$sql = "select id from priest_dc_result where iterations > 0 and avg_heal > 0 and avg_heal < " . intval($avgInfo["aheal"]) . " limit 5000";
$idsRows = $db->query($sql)->fetchAll();
$ids = array_column($idsRows, "id");

$parts = array_chunk($ids, 10);
foreach ($parts as $part) {
	$sql = "delete from priest_dc_result where id in (" . implode(",", $part) . ")";
	$db->query($sql);
}
