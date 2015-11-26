--TEST--
PHPC-430: Query constructor arguments are modified
--EXTENSIONS--
pcs
--SKIPIF--
<?php require __DIR__ . "/../utils/basic-skipif.inc"?>
--FILE--
<?php
require_once __DIR__ . "/../utils/basic.inc";

$options = ['sort' => ['x' => 1]];

$optionsCopy = $options;
$optionsCopy['cursorFlags'] = 0;

$query = new MongoDB\Driver\Query([], $options);

var_dump($options);
var_dump($optionsCopy);

?>
===DONE===
<?php exit(0); ?>
--EXPECT--
array(1) {
  ["sort"]=>
  array(1) {
    ["x"]=>
    int(1)
  }
}
array(2) {
  ["sort"]=>
  array(1) {
    ["x"]=>
    int(1)
  }
  ["cursorFlags"]=>
  int(0)
}
===DONE===
