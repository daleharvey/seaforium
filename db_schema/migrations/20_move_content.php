<?php

require_once("../../application/config/database.php");
require_once("../../application/helpers/content_render_helper.php");

mysql_connect($db['default']['hostname'],
              $db['default']['username'],
              $db['default']['password']);

mysql_select_db($db['default']['database']) or die( "Unable to select database");

$result = mysql_query("SELECT comment_id, content FROM comments");

while ($row = mysql_fetch_assoc($result)) {

  $source = _ready_for_source($row['content']);
  $new = _ready_for_display($row['content']);
  $id = (int)$row['comment_id'];

  $query = "UPDATE comments SET content = '$new', original_content = '$source' " .
           "WHERE comment_id = $id;";

  mysql_query($query);

  echo "Updated \n";

}

mysql_free_result($result);

?>