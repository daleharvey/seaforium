<?php

/**
 * All comments are run through here before they're saved
 *
 * @param string
 */
function _process_post($content)
{
  // We cant use the dom because any dom parser is going to throw away
  // the code inside the code tags, this will currently break for nested
  // code tags and code with [[> in it
  $content = str_replace('<code>', '<pre class="prettyprint linenums"><![CDATA[', $content);
  $content = str_replace('</code>', ']]></pre>', $content);

  _format_lists($content);
  _format_pinkies($content);
  $content = purify($content);
  return $content;
}

function _format_lists(&$content)
{
  $nlines = array();
  $lines = explode("\n", $content);
  $list_started = false;
  $list = "";

  foreach($lines as $line) {
    if (substr($line, 0, 3) === " * ") {
      if (!$list_started) {
        $list = "<ul>";
        $list_started = true;
      }
      $list .= "<li>" . substr($line, 3) . "</li>";
    } else {
      if ($list_started) {
        $nlines[] = $list . "</ul>";
        $list_started = false;
      }
      $nlines[] = $line;
    }
  }
  $content = implode("\n", $nlines);
}

/**
 * Convert all emoticons to images
 *
 * @param string
 */
function _format_pinkies(&$text)
{
  $pinkies = array(
    '[:)]' => '<span class="pinkie"><img src="/img/pinkies/11.gif" align="absmiddle" class="pinkie" alt=":)" /></span>',
    '[:(]' => '<span class="pinkie"><img src="/img/pinkies/01.gif" align="absmiddle" class="pinkie" alt=":(" /></span>',
    '[:D]' => '<span class="pinkie"><img src="/img/pinkies/05.gif" align="absmiddle" class="pinkie" alt=":D" /></span>',
    '[;)]' => '<span class="pinkie"><img src="/img/pinkies/07.gif" align="absmiddle" class="pinkie" alt=";)" /></span>',
    '[:P]' => '<span class="pinkie"><img src="/img/pinkies/08.gif" align="absmiddle" class="pinkie" alt=":P" /></span>',
    '[>|]' => '<span class="pinkie"><img src="/img/pinkies/14.gif" align="absmiddle" class="pinkie" alt="" /></span>',
    '[:[]' => '<span class="pinkie"><img src="/img/pinkies/10.gif" align="absmiddle" class="pinkie" alt=":[" /></span>',
    '[\'(]' => '<span class="pinkie"><img src="/img/pinkies/03.gif" align="absmiddle" class="pinkie" alt="\'(" /></span>',
    '[:*]' => '<span class="pinkie"><img src="/img/pinkies/17.gif" align="absmiddle" class="pinkie" alt=":*" /></span>',
    '[B-]' => '<span class="pinkie"><img src="/img/pinkies/16.gif" align="absmiddle" class="pinkie" alt="B-" /></span>',
    '[:=]' => '<span class="pinkie"><img src="/img/pinkies/27.gif" align="absmiddle" class="pinkie" alt=":=" /></span>',
    '[:.]' => '<span class="pinkie"><img src="/img/pinkies/22.gif" align="absmiddle" class="pinkie" alt="%(" /></span>',
    '[O]' => '<span class="pinkie"><img src="/img/pinkies/24.gif" align="absmiddle" class="pinkie" alt="O" /></span>',
    '[8)]' => '<span class="pinkie"><img src="/img/pinkies/09.gif" align="absmiddle" class="pinkie" alt="8)" /></span>',
    '[:{]' => '<span class="pinkie"><img src="/img/pinkies/06.gif" align="absmiddle" class="pinkie" alt=":{" /></span>',
    '[:@]' => '<span class="pinkie"><img src="/img/pinkies/20.gif" align="absmiddle" class="pinkie" alt=":@" /></span>',
    '[%(]' => '<span class="pinkie"><img src="/img/pinkies/18.gif" align="absmiddle" class="pinkie" alt="%(" /></span>',
    '[><]' => '<span class="pinkie"><img src="/img/pinkies/25.gif" align="absmiddle" class="pinkie" alt="><" /></span>',
    '[RR]' => '<span class="pinkie"><img src="/img/pinkies/23.gif" align="absmiddle" class="pinkie" alt="RR" /></span>',
    '[NH]' => '<span class="pinkie"><img src="/img/pinkies/26.gif" align="absmiddle" class="pinkie" alt="NH" /></span>',
    '[fbm]' => '<span class="pinkie"><img src="/img/pinkies/21.gif" align="absmiddle" class="pinkie" alt="fbm" /></span>',
  );

  foreach($pinkies as $key => $value) {
    $text = str_replace($key, $value, $text);
  }
}

function _format_me_script(&$text, $author)
{
  $me = '<span class="me">* '. anchor('/user/' . $author['url_safe_username'],
                                      $author['username']) .'</span>';
  $text = preg_replace('%(?<!/)(/{1}me{1})(?=\s)%i', $me, $text, 1);
}

function _format_pm_time($mysql_time)
{
  return date('M j \'y \@ g:ia', strtotime($mysql_time));
}
