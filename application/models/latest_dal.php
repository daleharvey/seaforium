<?php

class Latest_dal extends Model
{
  function Latest()
  {
    parent::__construct();
  }

  /**
   * Get some threads from the database
   *
   * @return	int
   */
  function get_latest()
  {
    $sql = "SELECT name,last_comment_created FROM categories";
    return $this->db->query($sql);
  }

}

?>