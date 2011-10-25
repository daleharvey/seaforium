<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function send_json($output, $code, $json)
{
  $output->set_status_header($code);
  $output->set_header("Content-Type: application/json");
  $output->set_output(json_encode($json));
  return true;
}

// We are pretty restrictive with usernames for now, can fix up later
function valid_username($username)
{
  if (preg_match('/^[a-z\d ]{1,32}$/i', $username)) {
    return true;
  } else {
    return false;
  }
}

function utc_time()
{
  $t = time();
  return $t + date("Z", $t);
}

function utf8decode($string) {
	return preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",$string);
}

?>