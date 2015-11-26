--TEST--
MongoDB\Driver\Manager: Invalid namespace
--EXTENSIONS--
pcs
--SKIPIF--
<?php require __DIR__ . "/../utils/basic-skipif.inc"?>
--FILE--
<?php
require_once __DIR__ . "/../utils/basic.inc";

$manager = new MongoDB\Driver\Manager(STANDALONE);
$bulk = new MongoDB\Driver\BulkWrite;
$bulk->insert(array("my" => "value"));

echo throws(function() use($manager, $bulk) {
    $manager->executeBulkWrite("database", $bulk);
}, "MongoDB\Driver\Exception\InvalidArgumentException"), "\n";

echo throws(function() use($manager) {
    $manager->executeQuery("database", new MongoDB\Driver\Query(array("document "=> 1)));
}, "MongoDB\Driver\Exception\InvalidArgumentException"), "\n";
?>
===DONE===
<?php exit(0); ?>
--EXPECT--
OK: Got MongoDB\Driver\Exception\InvalidArgumentException
Invalid namespace provided: database
OK: Got MongoDB\Driver\Exception\InvalidArgumentException
Invalid namespace provided: database
===DONE===