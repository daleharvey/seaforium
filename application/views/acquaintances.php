<div id="main-title">
   <h3>How many Yay freaks does it take to screw in a light bulb?</h3>
</div>

<div class="bottomdashed">
	<form name="search-box-user" id="search-box-user" method="" action="">
    Filter Usernames
    <input type="text" value="" name="search-phrase-user" id="search-phrase-user" />
    <input type="submit" value="Search" />
    Type the first few characters of the username.
	</form>

  Browse Users
	<a href="/users/0/0">0</a>
	<a href="/users/0/1">1</a>
	<a href="/users/0/2">2</a>
	<a href="/users/0/3">3</a>
	<a href="/users/0/4">4</a>
	<a href="/users/0/5">5</a>
	<a href="/users/0/6">6</a>
	<a href="/users/0/7">7</a>
	<a href="/users/0/8">8</a>
	<a href="/users/0/9">9</a>
	<a href="/users/0/A">A</a>
	<a href="/users/0/B">B</a>
	<a href="/users/0/C">C</a>
	<a href="/users/0/D">D</a>
	<a href="/users/0/E">E</a>
	<a href="/users/0/F">F</a>
	<a href="/users/0/G">G</a>
	<a href="/users/0/H">H</a>
	<a href="/users/0/I">I</a>
	<a href="/users/0/J">J</a>
	<a href="/users/0/K">K</a>
	<a href="/users/0/L">L</a>
	<a href="/users/0/M">M</a>
	<a href="/users/0/N">N</a>
	<a href="/users/0/O">O</a>
	<a href="/users/0/P">P</a>
	<a href="/users/0/Q">Q</a>
	<a href="/users/0/R">R</a>
	<a href="/users/0/S">S</a>
	<a href="/users/0/T">T</a>
	<a href="/users/0/U">U</a>
	<a href="/users/0/V">V</a>
	<a href="/users/0/W">W</a>
	<a href="/users/0/X">X</a>
	<a href="/users/0/Y">Y</a>
	<a href="/users/0/Z">Z</a>
</div>

<div id="thread-navigation" class="pagination top">
	<?php echo $pagination; ?>
</div>

<div id="users">

<?php
foreach($users as $row) {
	$online_status = '<div class="offline">NOT ONLINE</div>';
	if ((int) $row['latest_activity'] > (time() - 300))
	{
		$online_status = '<div class="online">ONLINE</div>';
	}
  
  $listing_type = $type == 2 ? 'enemy' : 'buddy';
 ?>

  <div class="<?php echo $listing_type; ?>-listing">
    <div class="username"><a href="/user/<?php echo $row['username']; ?>">
       <?php echo $row['username']; ?>
    </a></div>
	<?php echo $online_status; ?>
    <div class="user_startdate">Member since
      <?php echo date('M jS y', strtotime($row['created'])); ?>
    </div>
    <div class="user_logdate">Last log date
      <?php echo date('M jS y', strtotime($row['last_login'])); ?>
    </div>
	<div class="user_logdate">
          <?php echo isset($row['threads_count']) ? $row['threads_count'] : '0'; ?> threads &nbsp;|&nbsp; <?php echo isset($row['comments_count']) ? $row['comments_count'] : '0'; ?> posts
	</div>
  </div>

<?php } ?>
</div>

<div class="blueline">&nbsp;</div>
<div class="pagination bottom">
	<?php echo $pagination; ?>
</div>
