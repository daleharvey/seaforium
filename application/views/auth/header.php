<?php
$button_texts = array("Get In!",
                      "Do it!",
                      "Booya!",
                      "Push Me",
                      "Zippity!",
                      "Engage!");
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Yayhooray 2.0</title>
    <link rel="shortcut icon" href="/favicon.ico" />
    <meta name="viewport"
       content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/forum.css" />
    <base href="<?php echo site_url(); ?>" />
  </head>
  <body>
    <a name="top"></a>
    <div id="wrapper">
      <div id="middle">
        <div id="left-column">
          <a href="/" id="header">New Yay</a>
          <div class="lc-node login" id="login-box">

            <p class="error">&nbsp;</p>

            <!--<h5>Not a member? Wanna join up? Tell us why!</h5>
            <p><img src="/img/pinkies/07.gif" width="14" height="14" />
              <a href="/invite" class="white">Click for more info, n00b!</a></p>-->


            <form id="login-form">
              <div>
                <label>U:</label>
                <input type="text" name="username" tabindex="1" id="username" />
                <button tabindex="3" type="submit">
                  <?php echo $button_texts[array_rand($button_texts)]; ?>
                </button>
              </div>
              <div>
                <label>P:</label>
                <input type="password" name="password" tabindex="2" id="password" />
                <a href="#" id="forgot-password">Forgot?</a>
              </div>
            </form>
          </div>
      </div>
