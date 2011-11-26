<?php


$username = $this->session->userdata('username');
$user_id = $this->session->userdata('user_id');

$logged_in = $this->sauth->is_logged_in();

$css = "/css/forum.css";

if ($this->session->userdata('custom_css')) {
  $css = $this->session->userdata('custom_css');
}

$latest_comment_timestamps = $this->latest_dal->get_latest();
if ($latest_comment_timestamps->num_rows() > 0) {
  foreach($latest_comment_timestamps->result() as $row) {
    if (substr($row->last_comment_created, 0, 4) == '0000') {
      $latest_comment[strtolower($row->name)] = "";
    } else {
      $latest_comment[strtolower($row->name)] = '(' .
        timespan(strtotime($row->last_comment_created), time()) . ')';
    }
  }
}

$body_class = '';
foreach (explode('/', $_SERVER['REQUEST_URI']) as $segment) {
  if ($segment !== '') {
    $body_class .= "yh_$segment ";
  }
}
if ($body_class === '') {
  $body_class = 'yh_home';
}

$view_html = $this->session->userdata('view_html') == '1';

?>
<!DOCTYPE html>
<html>

  <head>
    <title><?php if (isset($page_title)) { echo $page_title . ' | '; } echo $this->config->item('site_name'); ?></title>

    <link rel="shortcut icon" href="/favicon.ico" />
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link href="/css/prettify.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="<?php echo $css; ?>?v=<?php echo $this->config->item('version'); ?>" />
    <script type="text/javascript" src="/js/jquery-1.6.4.min.js"></script>

    <base href="<?php echo site_url(); ?>" />
  </head>

<body class="<?php echo trim($body_class); ?>" >

  <a name="top"></a>

  <div id="wrapper">
    <div id="middle">
      <div id="left-column">
        <a href="/" id="header">New Yay</a>
<?php if (!$logged_in) {
$button_texts = array("Get In!", "Do it!", "Booya!", "Push Me",
                      "Zippity!", "Engage!");
$login_txt = $button_texts[array_rand($button_texts)];
?>

        <div class="lc-node login" id="login-box">
          <h5>Not a member?</h5>
          <p><img src="/img/pinkies/07.gif" width="14" height="14" align="absmiddle"/>
            <a href="/auth/register" class="white">Click to register, n00b!</a></p>
	  <p class="error"></p>
          <form action="/auth/login" method="post" id="login-form">
	    <div>
	      <label>U:</label>
              <input type="text" name="username" tabindex="1" id="username" />
              <button tabindex="3" type="submit"><?php echo $login_txt ?></button>
	    </div>
	    <div>
	      <label>P:</label>
              <input type="password" name="password" tabindex="2" id="password" />
              <a href="#" id="forgot-password">Forgot it?</a>
            </div>
          </form>
	</div>

<?php } else { ?>

        <div class="lc-node welcome">
          <h4>
            Hi, <a href="/user/<?php echo $username; ?>"><?php echo $username; ?></a>
          </h4>
          <a href="/user/<?php echo $username; ?>">
	    <img src="/img/emoticons/<?php echo $this->session->userdata('emoticon') ? $user_id : 0; ?>.gif" class="main_avatar" />
          </a>
          <ul>
	    <li><a href="/preferences" id="preferences">Preferences</a></li>
            <li><form action='/auth/logout' method='POST'>
              <input type='submit' id="logout_btn" value="logout" /></form></li>
          </ul>
        </div>
<?php }

if ($logged_in) {
  $unread_messages = $this->message_dal->unread_messages($user_id);

  if ($unread_messages === 0) {
    $unread_msg = "No New Messages";
  } else {
    $unread_msg = $unread_messages .'<span> Unread Message' .
      ($unread_messages === 1 ? '' : 's') . '</span>';
  }

?>
        <div class="lc-node" id="messaging">
          <ul>
            <li class="messages<?php if ($unread_messages > 0) { echo " new"; }?>">
              <a href="/messages/inbox"><?php echo $unread_msg; ?></a>
            </li>
          </ul>
        </div>
<?php } ?>
        <div class="lc-node" id="threads">
          <h3><a href="/">Threads</a></h3>
          <ul id="thread-categories">
            <li><a href="/f/discussions">Discussions</a>
              <?php echo $latest_comment['discussions']; ?></li>
            <li><a href="/f/projects">Projects</a>
              <?php echo $latest_comment['projects']; ?></li>
            <li><a href="/f/advice">Advice</a>
              <?php echo $latest_comment['advice']; ?></li>
            <li><a href="/f/meaningless">Meaningless</a>
              <?php echo $latest_comment['meaningless']; ?></li>
          </ul>
          <ul id="special-threads">
            <li><a href="/f/meaningful">All Forums</a></li>
            <li><a href="/f/meaningful">All But Meaningless</a></li>
<?php if ($logged_in) { ?>
            <li><a href="/f/participated">Participated</a></li>
            <li><a href="/f/favorites">Favourite</a></li>
            <li><a href="/f/hidden">Hidden</a></li>
            <li><a href="/started/<?php echo $username; ?>">Started</a></li>
<?php } ?>
          </ul>

           <ul id="search-title">
             <li>
               <strong>Search Thread Titles</strong><br/>
               <form name="search-box" id="search-box" method="" action="">
                 <input type="text" value="" name="search-phrase" id="search-phrase" />
                 <input type="submit" value="Go" />
               </form>
             </li>
           </ul>
<?php if ($logged_in) { ?>
          <hr />
          <a id="toggle-html" data-active="<?php echo $view_html ? 'true' : 'false'; ?>">
             Turn <?php echo $view_html ? 'off' : 'on'; ?> html</a>
<?php } ?>
         </div>

<?php if ($this->sauth->is_logged_in()) { ?>

         <div class="lc-node" id="buddy-list">
           <h3><a href="/buddies">Buddies</a>
            <a href="/users" class="users-link">(All users)</a></h3>

<?php $buddy_info = $this->user_dal->get_active_users($user_id); ?>

           <p>ONLINE BUDDIES (<?php echo $buddy_info['buddies']->num_rows . "/" . $buddy_info['buddy_count']; ?>)</p>
         <div>
<?php
  $buddies = $buddy_info['buddies']->result();
  $j = count($buddies);
  $i = 1;
foreach($buddies as $user) { ?>
             <span><?php echo anchor('/user/'.url_title($user->username, 'dash', TRUE), $user->username), ($i === $j ? '' : ','); ?></span>
<?php ++$i; } ?>
         </div>
       </div>

<?php } ?>
         <a href="http://patrickobrienfoundation.org/" id="pobf"><img src="/img/pobf.gif" /></a>

     </div>

     <div id="right-column">
