--TEST--
MongoDB\Driver\Manager::selectServer() select a server from SDAM based on ReadPreference
--SKIPIF--
<?php require __DIR__ . "/../utils/basic-skipif.inc"; NEEDS("REPLICASET"); ?>
<?php CLEANUP(REPLICASET); CLEANUP(REPLICASET, "local", "example"); ?>
--FILE--
<?php
require_once __DIR__ . "/../utils/basic.inc";

$rp = new MongoDB\Driver\ReadPreference(MongoDB\Driver\ReadPreference::RP_PRIMARY);
$manager = new MongoDB\Driver\Manager(REPLICASET);
$server = $manager->selectServer($rp);
$rp2 = new MongoDB\Driver\ReadPreference(MongoDB\Driver\ReadPreference::RP_PRIMARY);
$server2 = $manager->selectServer($rp2);

// load fixtures for test
$bulk = new \MongoDB\Driver\BulkWrite();
$bulk->insert(array('_id' => 1, 'x' => 2, 'y' => 3));
$bulk->insert(array('_id' => 2, 'x' => 3, 'y' => 4));
$bulk->insert(array('_id' => 3, 'x' => 4, 'y' => 5));
$server->executeBulkWrite(NS, $bulk);

$query = new MongoDB\Driver\Query(array('x' => 3), array('projection' => array('y' => 1)));
$cursor = $server->executeQuery(NS, $query);

var_dump($cursor instanceof MongoDB\Driver\Cursor);
var_dump($server == $cursor->getServer());
var_dump(iterator_to_array($cursor));

$query = new MongoDB\Driver\Query(array('x' => 3), array('projection' => array('y' => 1)));
$cursor = $server2->executeQuery(NS, $query);

var_dump($cursor instanceof MongoDB\Driver\Cursor);
var_dump($server2 == $cursor->getServer());
var_dump(iterator_to_array($cursor));

$bulk = new \MongoDB\Driver\BulkWrite();
$bulk->insert(array('_id' => 1, 'x' => 2, 'y' => 3));
$bulk->insert(array('_id' => 2, 'x' => 3, 'y' => 4));
$bulk->insert(array('_id' => 3, 'x' => 4, 'y' => 5));
throws(function() use($server2, $bulk) {
    $server2->executeBulkWrite(NS, $bulk);
}, "MongoDB\Driver\BulkWriteException");
$result = $server2->executeBulkWrite("local.example", $bulk);
var_dump($result->getInsertedCount());
?>
===DONE===
<?php exit(0); ?>
--EXPECT--
bool(true)
bool(true)
array(1) {
  [0]=>
  array(2) {
    ["_id"]=>
    int(2)
    ["y"]=>
    int(4)
  }
}
bool(true)
bool(true)
array(1) {
  [0]=>
  array(2) {
    ["_id"]=>
    int(2)
    ["y"]=>
    int(4)
  }
}
OK: Got MongoDB\Driver\BulkWriteException
int(3)
===DONE===