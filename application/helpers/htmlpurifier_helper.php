<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."helpers/library/htmlpurifier-4.3.0/library/HTMLPurifier.auto.php");
require_once(APPPATH."helpers/library/htmlpurifier-4.3.0/library/HTMLPurifier.func.php");

class HTMLPurifier_Filter_YouTube extends HTMLPurifier_Filter
{
  public $name = 'YouTube';

  public function preFilter($html, $config, $context) {
    $pre_regex = '#<object[^>]+>.+?'.
      'http://www.youtube.com/((?:v|cp)/[A-Za-z0-9\-_=]+).+?</object>#s';
    $pre_replace = '<span class="youtube-embed">\1</span>';
    return preg_replace($pre_regex, $pre_replace, $html);
  }

  public function postFilter($html, $config, $context) {
    $post_regex = '#<span class="youtube-embed">((?:v|cp)/[A-Za-z0-9\-_=]+)</span>#';
    return preg_replace_callback($post_regex, array($this, 'postFilterCallback'),
                                 $html);
  }
  protected function armorUrl($url) {
    return str_replace('--', '-&#45;', $url);
  }
  protected function postFilterCallback($matches) {
    $url = $this->armorUrl($matches[1]);
    return '<object width="425" height="350" type="application/x-shockwave-flash" '.
      'data="http://www.youtube.com/'.$url.'">'.
      '<param name="movie" value="http://www.youtube.com/'.$url.'"></param>'.
      '<!--[if IE]>'.
      '<embed src="http://www.youtube.com/'.$url.'"'.
      'type="application/x-shockwave-flash"'.
      'wmode="transparent" width="425" height="350" />'.
      '<![endif]-->'.
      '</object>';
  }
}


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

  $config = HTMLPurifier_Config::createDefault();
  $config->set('HTML.Doctype', 'XHTML 1.0 Strict');
  $config->set('AutoFormat.Linkify', true);
  $config->set('HTML.SafeObject', true);
  $config->set('Output.FlashCompat', true);
  $config->set('Output.Newline', '<br />');
  $def = $config->getHTMLDefinition(true);
  $def->addElement(
   'spoiler',   // name
   'Block',  // content set
   'Flow', // allowed children
   'Common', // attribute collection
   array()
  );

  return HTMLPurifier($dirty_html, $config);
}

?>