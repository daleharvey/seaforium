<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function send_json($output, $code, $json) {
  $output->set_status_header($code);
  $output->set_header("Content-Type: application/json");
  $output->set_output(json_encode($json));
  return true;
}

?>