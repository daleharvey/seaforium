<?php

function _ready_for_display($content)
{
	$content = nl2br($content);
	
	return $content;
}

function _ready_for_save($content)
{
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
	
	return strip_tags($final, '<img><a><em><i><b><strong><strike><del><address><code><pre><quote>');
}