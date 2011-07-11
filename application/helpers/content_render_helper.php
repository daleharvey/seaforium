<?php

function _ready_for_display($content, $author=null)
{
	//$content = ($author)?format_me_script($content, $author):$content;
	$content = format_pinkies(nl2br($content));
	
	return $content;
}

function _ready_for_save($content)
{
	/*
	$content = explode("\n", $content);
	$final = '';
	
	// here be code parsing
	
	$line_count = count($content);
	$incode = FALSE;
	$block = '';
	
	for($i = 0;
		$i < $line_count;
		$i++)
	{
		$with_tab = ("\t" == substr($content[$i], 0, 1));
		$with_spaces = ("    " == substr($content[$i], 0, 4));
		
		if ($with_tab || $with_spaces)
		{
			if (!$incode)
			{
				$incode = TRUE;
				$block = "\n<code>";
			}
			
			$block .= htmlentities($with_tab ? '    '.substr($content[$i], 1) : $content[$i])."\n";
		}
		elseif ($incode)
		{
			$incode = FALSE;

			$final .= $block."</code>\n";
		}
		else
		{
			$final .= $content[$i] ."\n";
		}
	}
	
	if ($incode)
		$final .= $block."</code>\n";
	*/
	
	return strip_tags($content, '<img><a><em><i><b><strong><strike><del><address><code><pre><quote>');
}

function format_pinkies($text)
{
	$text = str_replace('[:)]', '<span class="pinkie"><img src="/img/pinkies/11.gif" align="absmiddle" class="pinkie" alt=":)" /></span>', $text);
	$text = str_replace('[:(]', '<span class="pinkie"><img src="/img/pinkies/01.gif" align="absmiddle" class="pinkie" alt=":(" /></span>', $text);
	$text = str_replace('[:D]', '<span class="pinkie"><img src="/img/pinkies/05.gif" align="absmiddle" class="pinkie" alt=":D" /></span>', $text);
	$text = str_replace('[;)]', '<span class="pinkie"><img src="/img/pinkies/07.gif" align="absmiddle" class="pinkie" alt=";)" /></span>', $text);
	$text = str_replace('[:P]', '<span class="pinkie"><img src="/img/pinkies/08.gif" align="absmiddle" class="pinkie" alt=":P" /></span>', $text);
	$text = str_replace('[>|]', '<span class="pinkie"><img src="/img/pinkies/14.gif" align="absmiddle" class="pinkie" alt="" /></span>', $text);
	$text = str_replace('[:[]', '<span class="pinkie"><img src="/img/pinkies/10.gif" align="absmiddle" class="pinkie" alt=":[" /></span>', $text);
	$text = str_replace('[\'(]', '<span class="pinkie"><img src="/img/pinkies/03.gif" align="absmiddle" class="pinkie" alt="\'(" /></span>', $text);
	$text = str_replace('[:*]', '<span class="pinkie"><img src="/img/pinkies/17.gif" align="absmiddle" class="pinkie" alt=":*" /></span>', $text);
	$text = str_replace('[B-]', '<span class="pinkie"><img src="/img/pinkies/16.gif" align="absmiddle" class="pinkie" alt="B-" /></span>', $text);
	$text = str_replace('[:=]', '<span class="pinkie"><img src="/img/pinkies/27.gif" align="absmiddle" class="pinkie" alt=":=" /></span>', $text);
	$text = str_replace('[:.]', '<span class="pinkie"><img src="/img/pinkies/22.gif" align="absmiddle" class="pinkie" alt="%(" /></span>', $text);
	$text = str_replace('[O]', '<span class="pinkie"><img src="/img/pinkies/24.gif" align="absmiddle" class="pinkie" alt="O" /></span>', $text);
	$text = str_replace('[8)]', '<span class="pinkie"><img src="/img/pinkies/09.gif" align="absmiddle" class="pinkie" alt="8)" /></span>', $text);
	$text = str_replace('[:{]', '<span class="pinkie"><img src="/img/pinkies/06.gif" align="absmiddle" class="pinkie" alt=":{" /></span>', $text);
	$text = str_replace('[:@]', '<span class="pinkie"><img src="/img/pinkies/20.gif" align="absmiddle" class="pinkie" alt=":@" /></span>', $text);
	$text = str_replace('[%(]', '<span class="pinkie"><img src="/img/pinkies/18.gif" align="absmiddle" class="pinkie" alt="%(" /></span>', $text);
	$text = str_replace('[><]', '<span class="pinkie"><img src="/img/pinkies/25.gif" align="absmiddle" class="pinkie" alt="><" /></span>', $text);
	$text = str_replace('[RR]', '<span class="pinkie"><img src="/img/pinkies/23.gif" align="absmiddle" class="pinkie" alt="RR" /></span>', $text);
	$text = str_replace('[NH]', '<span class="pinkie"><img src="/img/pinkies/26.gif" align="absmiddle" class="pinkie" alt="NH" /></span>', $text);
	$text = str_replace('[fbm]', '<span class="pinkie"><img src="/img/pinkies/21.gif" align="absmiddle" class="pinkie" alt="fbm" /></span>', $text);
	
	return $text;
}

function format_me_script($text, $author)
{
	$username = $author['username'];
	$url_safe_username = $author['url_safe_username'];
	$me = '<span class="me">* '. anchor('/user/' . $url_safe_username, $username).'</span>';
	$text = preg_replace('%(?<!/)(/{1}me{1})(?=\s)%i', $me, $text, 1);
	return $text;
}

function _format_pm_time($mysql_time)
{
	return date('M j \'y \@ g:ia', strtotime($mysql_time));
}
