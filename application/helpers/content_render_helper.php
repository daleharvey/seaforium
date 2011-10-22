<?php

/**
 * All comments are run through here before they're displayed on screen
 *
 * @param string
 * @param mixed [null, array]
 */
function _ready_for_display($content, $author = null)
{
	// if the author is passed in, run
	if ($author)
		_format_me_script($content, $author);

	// add in pinkies
	_format_pinkies($content);

	// and convert all newlines to html line breaks
	return nl2br($content);
}

function _ready_for_source($content)
{
	_unplacehold_pinkies($content);

	return $content;
}

/**
 * All comments are run through here before they're saved
 *
 * @param string
 */
function _ready_for_save($content)
{
  _placehold_pinkies($content);
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
		'%11%' => '<span class="pinkie"><img src="/img/pinkies/11.gif" align="absmiddle" class="pinkie" alt=":)" /></span>',
		'%01%' => '<span class="pinkie"><img src="/img/pinkies/01.gif" align="absmiddle" class="pinkie" alt=":(" /></span>',
		'%05%' => '<span class="pinkie"><img src="/img/pinkies/05.gif" align="absmiddle" class="pinkie" alt=":D" /></span>',
		'%07%' => '<span class="pinkie"><img src="/img/pinkies/07.gif" align="absmiddle" class="pinkie" alt=";)" /></span>',
		'%08%' => '<span class="pinkie"><img src="/img/pinkies/08.gif" align="absmiddle" class="pinkie" alt=":P" /></span>',
		'%14%' => '<span class="pinkie"><img src="/img/pinkies/14.gif" align="absmiddle" class="pinkie" alt="" /></span>',
		'%10%' => '<span class="pinkie"><img src="/img/pinkies/10.gif" align="absmiddle" class="pinkie" alt=":[" /></span>',
		'%03%' => '<span class="pinkie"><img src="/img/pinkies/03.gif" align="absmiddle" class="pinkie" alt="\'(" /></span>',
		'%17%' => '<span class="pinkie"><img src="/img/pinkies/17.gif" align="absmiddle" class="pinkie" alt=":*" /></span>',
		'%16%' => '<span class="pinkie"><img src="/img/pinkies/16.gif" align="absmiddle" class="pinkie" alt="B-" /></span>',
		'%27%' => '<span class="pinkie"><img src="/img/pinkies/27.gif" align="absmiddle" class="pinkie" alt=":=" /></span>',
		'%22%' => '<span class="pinkie"><img src="/img/pinkies/22.gif" align="absmiddle" class="pinkie" alt="%(" /></span>',
		'%24%' => '<span class="pinkie"><img src="/img/pinkies/24.gif" align="absmiddle" class="pinkie" alt="O" /></span>',
		'%09%' => '<span class="pinkie"><img src="/img/pinkies/09.gif" align="absmiddle" class="pinkie" alt="8)" /></span>',
		'%06%' => '<span class="pinkie"><img src="/img/pinkies/06.gif" align="absmiddle" class="pinkie" alt=":{" /></span>',
		'%20%' => '<span class="pinkie"><img src="/img/pinkies/20.gif" align="absmiddle" class="pinkie" alt=":@" /></span>',
		'%18%' => '<span class="pinkie"><img src="/img/pinkies/18.gif" align="absmiddle" class="pinkie" alt="%(" /></span>',
		'%25%' => '<span class="pinkie"><img src="/img/pinkies/25.gif" align="absmiddle" class="pinkie" alt="><" /></span>',
		'%23%' => '<span class="pinkie"><img src="/img/pinkies/23.gif" align="absmiddle" class="pinkie" alt="RR" /></span>',
		'%26%' => '<span class="pinkie"><img src="/img/pinkies/26.gif" align="absmiddle" class="pinkie" alt="NH" /></span>',
		'%21%' => '<span class="pinkie"><img src="/img/pinkies/21.gif" align="absmiddle" class="pinkie" alt="fbm" /></span>',
	);

	foreach($pinkies as $key => $value)
	{
		$text = str_replace($key, $value, $text);
	}
}

function _placehold_pinkies(&$text)
{
	$pinkies = array(
		'[:)]'  => '%11%',
		'[:(]'  => '%01%',
		'[:D]'  => '%05%',
		'[;)]'  => '%07%',
		'[:P]'  => '%08%',
		'[>|]'  => '%14%',
		'[:[]'  => '%10%',
		'[\'(]' => '%03%',
		'[:*]'  => '%17%',
		'[B-]'  => '%16%',
		'[:=]'  => '%27%',
		'[:.]'  => '%22%',
		'[O]'   => '%24%',
		'[8)]'  => '%09%',
		'[:{]'  => '%06%',
		'[:@]'  => '%20%',
		'[%(]'  => '%18%',
		'[><]'  => '%25%',
		'[RR]'  => '%23%',
		'[NH]'  => '%26%',
		'[fbm]' => '%21%',
	);

	foreach($pinkies as $key => $value)
	{
		$text = str_replace($key, $value, $text);
	}
}

function _unplacehold_pinkies(&$text)
{
	$pinkies = array(
		'[:)]'  => '%11%',
		'[:(]'  => '%01%',
		'[:D]'  => '%05%',
		'[;)]'  => '%07%',
		'[:P]'  => '%08%',
		'[>|]'  => '%14%',
		'[:[]'  => '%10%',
		'[\'(]' => '%03%',
		'[:*]'  => '%17%',
		'[B-]'  => '%16%',
		'[:=]'  => '%27%',
		'[:.]'  => '%22%',
		'[O]'   => '%24%',
		'[8)]'  => '%09%',
		'[:{]'  => '%06%',
		'[:@]'  => '%20%',
		'[%(]'  => '%18%',
		'[><]'  => '%25%',
		'[RR]'  => '%23%',
		'[NH]'  => '%26%',
		'[fbm]' => '%21%',
	);

	foreach($pinkies as $key => $value)
	{
		$text = str_replace($value, $key, $text);
	}
}

function _format_me_script(&$text, $author)
{
	$me = '<span class="me">* '. anchor('/user/' . $author['url_safe_username'], $author['username']) .'</span>';
	$text = preg_replace('%(?<!/)(/{1}me{1})(?=\s)%i', $me, $text, 1);
}

function _format_pm_time($mysql_time)
{
	return date('M j \'y \@ g:ia', strtotime($mysql_time));
}
