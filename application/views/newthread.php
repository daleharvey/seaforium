<?php

$subject = array(
	'name'	=> 'subject',
	'id'	=> 'subject',
	'value' => set_value('subject'),
	'maxlength'	=> 64,
	'size'	=> 30
);

$categories = array(
	'discussions' => array(
		'name' => 'category[]',
		'id' => 'cat-discussions',
		'value' => '1',
		'checked' => TRUE
	),
	'projects' => array(
		'name' => 'category[]',
		'id' => 'cat-projects',
		'value' => '2'
	),
	'advice' => array(
		'name' => 'category[]',
		'id' => 'cat-advice',
		'value' => '3'
	),
	'meaningless' => array(
		'name' => 'category[]',
		'id' => 'cat-meaningless',
		'value' => '4'
	)
);

$content = array(
	'name'	=> 'content',
	'id'	=> 'thread-content-input',
	'value' => set_value('content')
);

?>

				<div id="main-title"><h3>Whatchu got to say?</h3></div>

				<div id="new-thread">
				
					<p><strong>Think you have something good enough to say to post a thread about it? Well have it!</strong> Remember that posting a thread is no small thing. Millions of people will read it so make it good!</p>
					
					<div class="dotted-bar"></div>
					
					<?php echo form_open('/newthread'); ?>
						
						<div class="inp">
							<?php echo form_label('Step 1: Pick a category'); ?>
							
							<div id="category-selector">
								<?php echo form_radio($categories['discussions']); ?>
								<?php echo form_label('Discussions', $categories['discussions']['id']); ?>
								
								<?php echo form_radio($categories['projects']); ?>
								<?php echo form_label('Projects', $categories['projects']['id']); ?>
								
								<?php echo form_radio($categories['advice']); ?>
								<?php echo form_label('Advice', $categories['advice']['id']); ?>
								
								<?php echo form_radio($categories['meaningless']); ?>
								<?php echo form_label('Meaningless', $categories['meaningless']['id']); ?>
							</div>
						</div>
						
						<div class="inp">
							<?php echo form_label('Step 2: Write a thread title', $subject['id']); ?>
							<?php echo form_input($subject); ?>
						</div>
						
						<div class="inp">
							<?php echo form_label('Step 3: Type the content of your thread ', $content['id']); ?>
							<?php echo form_textarea($content); ?>
						</div>
						<?php echo form_submit('submit', 'Post Thread'); ?>
					<?php echo form_close(); ?>
					
				</div>