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

function utf8_unescape($string) {
	return preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;", $string);
}

function utf8_entities($string) {
	// decode three byte unicode characters 
    $string = preg_replace("/([\340-\357])([\200-\277])([\200-\277])/e",        
    "'&#'.((ord('\\1')-224)*4096 + (ord('\\2')-128)*64 + (ord('\\3')-128)).';'",    
    $string); 

    // decode two byte unicode characters 
    $string = preg_replace("/([\300-\337])([\200-\277])/e", 
    "'&#'.((ord('\\1')-192)*64+(ord('\\2')-128)).';'", 
    $string); 

    return $string; 
}

?>