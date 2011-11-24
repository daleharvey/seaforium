<?php

	$elements_shown_options = array(
	                  '25'	=> '25',
	                  '50'	=> '50',
	                  '100'	=> '100'
	                );

	$content = array(
		'name'	=> 'content',
		'id'	=> 'thread-content-input',
		'value' => set_value('content')
	);


	$countries = array(
		"Afghanistan",
		"Albania",
		"Algeria",
		"Andorra",
		"Angola",
		"Antigua & Deps",
		"Argentina",
		"Armenia",
		"Australia",
		"Austria",
		"Azerbaijan",
		"Bahamas",
		"Bahrain",
		"Bangladesh",
		"Barbados",
		"Belarus",
		"Belgium",
		"Belize",
		"Benin",
		"Bhutan",
		"Bolivia",
		"Bosnia Herzegovina",
		"Botswana",
		"Brazil",
		"Brunei",
		"Bulgaria",
		"Burkina",
		"Burundi",
		"Cambodia",
		"Cameroon",
		"Canada",
		"Cape Verde",
		"Central African Rep",
		"Chad",
		"Chile",
		"China",
		"Colombia",
		"Comoros",
		"Congo",
		"Congo {Democratic Rep}",
		"Costa Rica",
		"Croatia",
		"Cuba",
		"Cyprus",
		"Czech Republic",
		"Denmark",
		"Djibouti",
		"Dominica",
		"Dominican Republic",
		"East Timor",
		"Ecuador",
		"Egypt",
		"El Salvador",
		"Equatorial Guinea",
		"Eritrea",
		"Estonia",
		"Ethiopia",
		"Fiji",
		"Finland",
		"France",
		"Gabon",
		"Gambia",
		"Georgia",
		"Germany",
		"Ghana",
		"Greece",
		"Grenada",
		"Guatemala",
		"Guinea",
		"Guinea-Bissau",
		"Guyana",
		"Haiti",
		"Honduras",
		"Hungary",
		"Iceland",
		"India",
		"Indonesia",
		"Iran",
		"Iraq",
		"Ireland {Republic}",
		"Israel",
		"Italy",
		"Ivory Coast",
		"Jamaica",
		"Japan",
		"Jordan",
		"Kazakhstan",
		"Kenya",
		"Kiribati",
		"Korea North",
		"Korea South",
		"Kosovo",
		"Kuwait",
		"Kyrgyzstan",
		"Laos",
		"Latvia",
		"Lebanon",
		"Lesotho",
		"Liberia",
		"Libya",
		"Liechtenstein",
		"Lithuania",
		"Luxembourg",
		"Macedonia",
		"Madagascar",
		"Malawi",
		"Malaysia",
		"Maldives",
		"Mali",
		"Malta",
		"Marshall Islands",
		"Mauritania",
		"Mauritius",
		"Mexico",
		"Micronesia",
		"Moldova",
		"Monaco",
		"Mongolia",
		"Montenegro",
		"Morocco",
		"Mozambique",
		"Myanmar, {Burma}",
		"Namibia",
		"Nauru",
		"Nepal",
		"Netherlands",
		"New Zealand",
		"Nicaragua",
		"Niger",
		"Nigeria",
		"Norway",
		"Oman",
		"Pakistan",
		"Palau",
		"Panama",
		"Papua New Guinea",
		"Paraguay",
		"Peru",
		"Philippines",
		"Poland",
		"Portugal",
		"Qatar",
		"Romania",
		"Russian Federation",
		"Rwanda",
		"St Kitts & Nevis",
		"St Lucia",
		"Saint Vincent & the Grenadines",
		"Samoa",
		"San Marino",
		"Sao Tome & Principe",
		"Saudi Arabia",
		"Senegal",
		"Serbia",
		"Seychelles",
		"Sierra Leone",
		"Singapore",
		"Slovakia",
		"Slovenia",
		"Solomon Islands",
		"Somalia",
		"South Africa",
		"Spain",
		"Sri Lanka",
		"Sudan",
		"Suriname",
		"Swaziland",
		"Sweden",
		"Switzerland",
		"Syria",
		"Taiwan",
		"Tajikistan",
		"Tanzania",
		"Thailand",
		"Togo",
		"Tonga",
		"Trinidad & Tobago",
		"Tunisia",
		"Turkey",
		"Turkmenistan",
		"Tuvalu",
		"Uganda",
		"Ukraine",
		"United Arab Emirates",
		"United Kingdom",
		"United States",
		"Uruguay",
		"Uzbekistan",
		"Vanuatu",
		"Vatican City",
		"Venezuela",
		"Vietnam",
		"Yemen",
		"Zambia",
		"Zimbabwe");

?>

<div id="main-title"><h3>Pimpin' ain't easy... but editing your profile is!</h3></div>

				<p class="prefpink"><strong>This is the page where you can edit your profile and change your YH settings.</strong> Your profile lets people learn a little about you. Tweak your YH settings to browse yay the way you want to.</p>

<div class="dotted-bar"></div>

<div id="preferences">

<?php if ($error) { ?>
  <div class="error"><?php echo $error; ?></div>
<?php } ?>

<?php echo form_open_multipart('/preferences');?>
<div style="float:right"><?php echo form_submit('submit', 'Save'); ?></div>
<h4 class="biglabel">Account Stuff</h4>

<div class="input text">
  <?php echo form_label('Email Address', 'email'); ?>
  <?php echo form_input('email', $user_preferences->email); ?>
</div>

<div class="input text">
  <label for="old_password">Original Password</label>
  <input type="password" name="old_password" id="old_password" value="" />
</div>

<div class="input text">
  <label for="password">New Password</label>
  <input type="password" name="password" id="password" value="" />
</div>

<div class="input text">
  <label for="password2">Confirm New Password</label>
  <input type="password" name="password2" id="password2" value="" />
</div>

<br />
<div class="blueline"></div>

<div style="float:right;margin-top:10px;"><?php echo form_submit('submit', 'Save'); ?></div>
<h4 class="biglabel">Personal Stuff</h4>

<div class="input text">
  <?php echo form_label('What\'s your name?', 'real_name'); ?>
  <?php echo form_input('real_name', $user_preferences->name); ?>
</div>
<div class="input text">
  <?php echo form_label('Where ya from?', 'location'); ?>
  <?php echo form_input('location', $user_preferences->location); ?>
</div>
<div class="input text">
<?php echo form_label('Tell us about yourself', 'about_blurb'); ?>
  <?php echo form_textarea('about_blurb', $user_preferences->about_blurb); ?>
</div>
<div class="input text">
<?php echo form_label('Website 1', 'website_1'); ?>
<?php echo form_input('website_1', $user_preferences->website_1); ?>
</div>
<div class="input text">
<?php echo form_label('Website 2', 'website_2'); ?>
<?php echo form_input('website_2', $user_preferences->website_2); ?>
</div>
<div class="input text">
<?php echo form_label('Website 3', 'website_3'); ?>
<?php echo form_input('website_3', $user_preferences->website_3); ?>
</div>
<div class="input text">
<?php echo form_label('RSS Feed 1', 'rss_feed_1'); ?>
<?php echo form_input('rss_feed_1', $user_preferences->rss_feed_1); ?>
</div>
<div class="input text">
<?php echo form_label('RSS Feed 2', 'rss_feed_2'); ?>
<?php echo form_input('rss_feed_2', $user_preferences->rss_feed_2); ?>
</div>
<div class="input text">
<?php echo form_label('RSS Feed 3', 'rss_feed_3'); ?>
<?php echo form_input('rss_feed_3', $user_preferences->rss_feed_3); ?>
</div>
<br />
<div class="blueline"></div>

<div style="float:right;margin-top:10px;"><?php echo form_submit('submit', 'Save'); ?></div>
<h4 class="biglabel">Other Websites</h4>


<div class="input text">
<?php echo form_label('Flickr Username', 'flickr_username'); ?>
<?php echo form_input('flickr_username', $user_preferences->flickr_username); ?>
</div>
<div class="input text">
<?php echo form_label('Del.icio.us Username', 'delicious_username'); ?>
							<?php echo form_input('delicious_username', $user_preferences->delicious_username); ?>
						</div>
<div class="input text">
<?php echo form_label('Facebook', 'facebook'); ?>
<?php echo form_input('facebook', $user_preferences->facebook); ?>
</div>
<div class="input text">
<?php echo form_label('AIM username', 'aim'); ?>
<?php echo form_input('aim', $user_preferences->aim); ?>
</div>
<div class="input text">
<?php echo form_label('Gchat (Jabber)', 'gchat'); ?>
<?php echo form_input('gchat', $user_preferences->gchat); ?>
</div>
<div class="input text">
<?php echo form_label('Last.fm', 'lastfm'); ?>
<?php echo form_input('lastfm', $user_preferences->lastfm); ?>
</div>
<div class="input text">
<?php echo form_label('MSN username', 'msn'); ?>
<?php echo form_input('msn', $user_preferences->msn); ?>
</div>
<div class="input text">
<?php echo form_label('Twitter', 'twitter'); ?>
<?php echo form_input('twitter', $user_preferences->twitter); ?>
</div>
<br />
<div class="blueline"></div>
<div style="float:right;margin-top:10px;"><?php echo form_submit('submit', 'Save'); ?></div>
<h4 class="biglabel">YH Settings</h4>
<div class="input text">
<?php echo form_label('Show Random Titles', 'random_titles'); ?>
<?php echo form_checkbox('random_titles', "1", $user_preferences->random_titles) ?>
</div>
<div class="input text">
<?php echo form_label('New Post Notification', 'new_post_notification'); ?>
<?php echo form_checkbox('new_post_notification', "1", $user_preferences->new_post_notification); ?>
</div>
<div class="input text">
<?php echo form_label('Hide Enemy Posts', 'hide_enemy_posts'); ?>
<?php echo form_checkbox('hide_enemy_posts', "1", $user_preferences->hide_enemy_posts); ?>
</div>
<div class="input text">
<?php echo form_label('Custom CSS', 'custom_css'); ?>
<?php echo form_input('custom_css', $user_preferences->custom_css); ?>
</div>
<div class="input text">
<?php echo form_label('Custom JavaScript', 'custom_js'); ?>
<?php echo form_input('custom_js', $user_preferences->custom_js); ?>
</div>

<div class="input text">
<?php echo form_label('Threads Shown', 'threads_shown'); ?>
<?php echo form_dropdown('threads_shown', $elements_shown_options, $this->session->userdata('threads_shown').''); ?>
</div>
<div class="input text">
<?php echo form_label('Comments Shown', 'comments_shown'); ?>
<?php echo form_dropdown('comments_shown', $elements_shown_options, $this->session->userdata('comments_shown').''); ?>
</div>

<div class="input text">
<label for="emot_upload">Avatar</label>
<input type="file" id="emot_upload" name="emot_upload" size="20" />
</div>
<?php echo form_close(); ?>

</div>
