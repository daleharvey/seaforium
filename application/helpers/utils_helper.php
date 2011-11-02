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

if ( ! function_exists('timespan'))
{
  function timespan($seconds = 1, $time = '', $ending = 'ago')
  {
    if (!is_numeric($seconds)) {
      return '';
    }

    $CI =& get_instance();
    $CI->lang->load('date');

    $timespan = $time - $seconds;

    if ($timespan < 60) {
      $return = $timespan.' ' .
        $CI->lang->line((($timespan > 1) ? 'date_seconds' : 'date_second'));
    } elseif ($timespan < 3600) {
      $return = floor($timespan / 60);
      $return = $return.' ' .
        $CI->lang->line((($return > 1) ? 'date_minutes' : 'date_minute'));
    } elseif ($timespan < 86400) {
      $return = floor($timespan / 3600);
      $return = $return.' ' .
        $CI->lang->line((($return > 1) ? 'date_hours' : 'date_hour'));
    } else {
      $return = floor($timespan / 86400);

      $return = $return.' ' .
        $CI->lang->line((($return > 1) ? 'date_days' : 'date_day'));
    }

    return $return.' '.$CI->lang->line($ending);
  }
}

function make_link($str)
{
  if ($str == '') {
    return $str;
  }

  if ((substr($str, 0, 7) !== 'http://' && substr($str, 0, 8) !== 'https://')) {
    return 'http://' . $str;
  }

  return $str;
}

?>