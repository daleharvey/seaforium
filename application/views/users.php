<div id="main-title">
   <h3>How many Yay freaks does it take to screw in a light bulb?</h3>
</div>

<!--
<div class="bottomdashed" style="height:50px;">
	<div style="margin-bottom:5px;">
	<form method="get" action="/users">
	Filter Usernames
	<input type="text" name="kw" value="" class="fstandard">
	<input type="submit" value="Search" class="fstandard" id="usersearchbutton">
	User search allows the grep style wildcard tokens * % and |
	</form>
	</div>
        Browse Users (<?php echo $user_count; ?>)
	<a href="users?kw=0*">0</a>

	<a href="users?kw=1*">1</a>
	<a href="users?kw=2*">2</a>
	<a href="users?kw=3*">3</a>
	<a href="users?kw=4*">4</a>
	<a href="users?kw=5*">5</a>
	<a href="users?kw=6*">6</a>

	<a href="users?kw=7*">7</a>
	<a href="users?kw=8*">8</a>
	<a href="users?kw=9*">9</a>
	<a href="users?kw=A*">A</a>
	<a href="users?kw=B*">B</a>
	<a href="users?kw=C*">C</a>

	<a href="users?kw=D*">D</a>
	<a href="users?kw=E*">E</a>
	<a href="users?kw=F*">F</a>
	<a href="users?kw=G*">G</a>
	<a href="users?kw=H*">H</a>
	<a href="users?kw=I*">I</a>

	<a href="users?kw=J*">J</a>
	<a href="users?kw=K*">K</a>
	<a href="users?kw=L*">L</a>
	<a href="users?kw=M*">M</a>
	<a href="users?kw=N*">N</a>
	<a href="users?kw=O*">O</a>

	<a href="users?kw=P*">P</a>
	<a href="users?kw=Q*">Q</a>
	<a href="users?kw=R*">R</a>
	<a href="users?kw=S*">S</a>
	<a href="users?kw=T*">T</a>
	<a href="users?kw=U*">U</a>

	<a href="users?kw=V*">V</a>
	<a href="users?kw=W*">W</a>
	<a href="users?kw=X*">X</a>
	<a href="users?kw=Y*">Y</a>
	<a href="users?kw=Z*">Z</a>

</div>

<hr />

<div id="threadnav" class="pagination top">
   <div class="main-pagination">
    <a class="pagea selected" href="http://www.yayhooray.com/users?page=1">1</a>
    <a class="pagea " href="http://www.yayhooray.com/users?page=2">2</a>
    <a class="pagea " href="http://www.yayhooray.com/users?page=3">3</a>
    <a class="pagea " href="http://www.yayhooray.com/users?page=4">4</a>
    <a class="pagea " href="http://www.yayhooray.com/users?page=5">5</a>
    <a class="pageelipsa" href="http://www.yayhooray.com/users?page=457">...</a>
    <a class="pagea " href="http://www.yayhooray.com/users?page=457">457</a>
    <span class="paging-text">1 - 40 of 18,270 Users</span>
  </div>
</div>
-->
<div id="users">

<?php foreach($users as $row) { ?>

  <div class="user-listing">
    <div class="username"><a href="/user/<?php echo $row['username']; ?>">
       <?php echo $row['username']; ?>
    </a></div>
    <div class="user_startdate">Member since
      <?php echo date('M jS y', strtotime($row['created'])); ?>
    </div>
    <div class="user_logdate">Last log date
      <?php echo date('M jS y', strtotime($row['last_login'])); ?>
    </div>
  </div>

<?php } ?>
</div>

<!--<div class="clear blueline"></div>

<div id="threadfooter">
	<div class="paging tenpx">
		 <a class="pagea selected" href="http://www.yayhooray.com/users?page=1">1</a>  <a class="pagea " href="http://www.yayhooray.com/users?page=2">2</a>  <a class="pagea " href="http://www.yayhooray.com/users?page=3">3</a>  <a class="pagea " href="http://www.yayhooray.com/users?page=4">4</a>  <a class="pagea " href="http://www.yayhooray.com/users?page=5">5</a> <a class="pageelipsa" href="http://www.yayhooray.com/users?page=457">...</a>  <a class="pagea " href="http://www.yayhooray.com/users?page=457">457</a>		1 - 40 of 18,270 Users	</div>

</div>-->