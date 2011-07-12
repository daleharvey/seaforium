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
		"Zimbabwe"
		
			
			);
/*
stdClass Object
(
    [id] => 1
    [username] => culthero
    [created] => 2011-07-12 12:30:29
    [last_login] => 2011-07-12 15:14:26
    [email] => zack@032koncept.net
    [new_post_notification] => 1
    [random_titles] => 1
    [timezone] => CST
    [country] => United States
    [website_1] => http://google.com
    [website_2] => http://msn.com
    [website_3] => http://reddit.com
    [aim] => myaim
    [msn] => mymsn
    [gchat] => mygchat
    [facebook] => http://facebook.com/myfacebook
    [lastfm] => http://last.fm/myaccount
    [about_blurb] => blurb.
    [flickr_username] => flickruser
    [delicious_username] => delcioususer
    [rss_feed_1] => http://rss.com/1.rss
    [rss_feed_2] => http://rss.com/2.rss
    [rss_feed_3] => http://rss.com/3.rss
    [custom_css] => yay_css.css
    [comments_shown] => 100
    [comment_count] => 2
    [thread_count] => 1
)
*/
echo "<pre>";
print_r($user_preferences);
echo "</pre>";

?>



				<div id="main-title">Your Preferences</div>
				
				<div id="preferences">
				
					
					<?php echo form_open('/preferences'); ?>
					
						<div class="input text">
							<?php echo form_label('Email Address', 'email'); ?>
							<?php echo form_input('email', $user_preferences->email); ?>
						</div>
						<div class="input text">
							<?php echo form_label('Show Random Titles', 'random_titles'); ?>
							<?php echo form_checkbox('random_titles', "1", $user_preferences->random_titles) ?>
						</div>
						<div class="input text">
							<?php echo form_label('New Post Notification', 'new_post_notification'); ?>
							<?php echo form_checkbox('new_post_notification', "1", $user_preferences->new_post_notification); ?>
						</div>
						
						<div class="input text">
							<?php echo form_label('Threads Shown', 'threads_shown'); ?>
							<?php echo form_dropdown('threads_shown', $elements_shown_options, $this->session->userdata('threads_shown').''); ?>
						</div>
						
						<div class="input text">
							<?php echo form_label('Comments Shown', 'comments_shown'); ?>
							<?php echo form_dropdown('comments_shown', $elements_shown_options, $this->session->userdata('comments_shown').''); ?>
						</div>
						
						
						<?php echo form_submit('submit', 'Submit'); ?>
					<?php echo form_close(); ?>
					
				</div>