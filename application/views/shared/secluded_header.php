<?php

$css = $this->agent->is_mobile() ? "mobile.css" : "forum.css";

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>New Forum</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" type="text/css" href="/css/<?php echo $css; ?>" />
	
	<base href="<?php echo site_url(); ?>" />
</head>
<body>
	
	<div id="wrapper">
		