<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function purify($dirty_html)
{

  if (is_array($dirty_html)) {

      foreach ($dirty_html as $key => $val) {
        $dirty_html[$key] = purify($val);
      }

      return $dirty_html;
  }

  if (trim($dirty_html) === '') {
    return $dirty_html;
  }

  require_once(APPPATH."helpers/library/htmlpurifier-4.3.0/library/HTMLPurifier.auto.php");
  require_once(APPPATH."helpers/library/htmlpurifier-4.3.0/library/HTMLPurifier.func.php");

  $config = HTMLPurifier_Config::createDefault();
  $config->set('HTML.Doctype', 'XHTML 1.0 Strict');

  return HTMLPurifier($dirty_html, $config);
}

?>