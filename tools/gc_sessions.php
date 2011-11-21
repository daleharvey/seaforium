<?php

$cwd = dirname(__FILE__);

define('BASEPATH', $cwd . '/');

require_once(BASEPATH . "../application/config/database.php");

$mysqli = new mysqli();
$mysqli->connect($db['default']['hostname'], $db['default']['username'],
                 $db['default']['password'], $db['default']['database']);

$query = "DELETE FROM `sessions` WHERE `last_activity` < (UNIX_TIMESTAMP() - 300)";

if ($mysqli->query($query)) {
  echo "Success\n";
} else {
  echo "Failed\n";
}

?>