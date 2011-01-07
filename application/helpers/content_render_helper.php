<?php

function _ready_for_display($content)
{
	$content = nl2br($content);
	
	return $content;
}

function _ready_for_save($content)
{
	$content = strip_tags($content, '<img><a><em><i><b><strong><strike><del><address>');
	
	return $content;
}