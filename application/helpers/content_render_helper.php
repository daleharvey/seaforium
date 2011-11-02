<?php

/**
 * All comments are run through here before they're saved
 *
 * @param string
 */
function _process_post($content, $username)
{
  // We cant use the dom because any dom parser is going to throw away
  // the code inside the code tags, this will currently break for nested
  // code tags and code with [[> in it
  $content = str_replace('<code>', '<pre class="prettyprint linenums"><![CDATA[', $content);
  $content = str_replace('</code>', ']]></pre>', $content);

  $author = array(
    'username' => $username,
    'url_safe_username' => url_title($username, 'dash')
  );

  //_format_me_script($content, $author);
  _format_me_scriptNEW($content, $author);

  _format_pinkies($content);
  $content = purify($content);
  return $content;
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

function _format_me_scriptNEW(&$text, $author)
{
	$complete_text = '';
	$me = '<span class="me">* '. anchor('/user/' . $author['url_safe_username'], $author['username']) .'</span>';
	$text_split = preg_split("%(<(/?)blockquote([^>]*)>)%i", $text, -1, PREG_SPLIT_DELIM_CAPTURE);
//print_r($text_split);
	$n = 0;
	for($i=0; $i<count($text_split); $i=$i+4){
		$the_item_text = '';
		$formatted_text = '';
		$the_item_text = $text_split[$i];
		$formatted_text = $the_item_text;
		if ($i==0) {
			$formatted_text = str_replace("/me ", $me.' ', $the_item_text);
			$nestarray[$n] = $me;
			$n++;
		}else{
			$formatted_text = $the_item_text;
			if ($replacement_string != '') $formatted_text = str_replace("/me ", $replacement_string.' ', $the_item_text);
		}
		$j = $i+1;
		if (count($text_split) > $j) {
			if (stristr($text_split[$j], '<blockquote title') !== FALSE) {
				//echo "next row has a starter tag with name - ";
				$result = preg_match('%blockquote title="(.*)"%i',trim($text_split[$j]),$outtie);
				if (false === $result) {
				    throw new Exception(sprintf('Regular Expression failed: %s.', $pattern));
					break 2;
				}
				$replacement_string = '';
				if (isset($outtie[1])) {
					if (strlen(trim($outtie[1])) > 0) {
						$replacement_string = '<span class="me">* '. anchor('/user/' . url_title($outtie[1]), $outtie[1]) .'</span>';
					}
				}
				$nestarray[$n] = $replacement_string;
				$n++;

			}elseif (stristr($text_split[$j], '<blockquote') !== FALSE) {
				//echo "next row has a starter tag - ";
				$replacement_string = '';
				$nestarray[$n] = $replacement_string;
				$n++;
				//LEAVE IT AS /me

			}elseif (stristr($text_split[$j], '</blockquote') !== FALSE) {
				//echo "next row has a closing tag - ";
				$n = $n-2;
				$replacement_string = $nestarray[$n];
				$n++;
			}
			$formatted_text .= $text_split[$j];
		}
		$complete_text .= $formatted_text;
	}
	$text = $complete_text;
}

function _format_pm_time($mysql_time)
{
  return date('M j \'y \@ g:ia', strtotime($mysql_time));
}
