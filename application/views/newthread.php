<?php

$subject = array(
	'name'	=> 'subject',
	'id'	=> 'subject',
	'value' => set_value('subject'),
	'maxlength'	=> 64,
	'size'	=> 30,
        'tabindex' => 5
);

$categories = array(
	'discussions' => array(
		'name' => 'category[]',
		'id' => 'cat-discussions',
		'value' => '1',
                'tabindex' => 1
	),
	'projects' => array(
		'name' => 'category[]',
		'id' => 'cat-projects',
		'value' => '2',
                'tabindex' => 2
	),
	'advice' => array(
		'name' => 'category[]',
		'id' => 'cat-advice',
		'value' => '3',
                'tabindex' => 3
	),
	'meaningless' => array(
		'name' => 'category[]',
		'id' => 'cat-meaningless',
		'value' => '4',
                'tabindex' => 4
	)
);

$content = array(
  'tabindex' => 6,
  'name'	=> 'content',
  'id'	=> 'thread-content-input',
  'value' => set_value('content')
);

?>

<div id="main-title"><h3>Whatchu got to say?</h3></div>

<div id="new-thread">

  <p><strong>Think you have something good enough to say to post a thread about it? Well have it!</strong> Remember that posting a thread is no small thing. Millions of people will read it so make it good!</p>

  <div class="dotted-bar"></div>

  <form method="post" action="/newthread">
    <div class="inp">
      <?php echo form_error('category[]'); ?>
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
      <?php echo form_error('subject'); ?>
      <?php echo form_label('Step 2: Write a thread title', $subject['id']); ?>
      <?php echo form_input($subject); ?>
    </div>
    <div class="inp">
      <?php echo form_error('content'); ?>
      <?php echo form_label('Step 3: Type the content of your thread ', $content['id']); ?>
      <div id="pinkies">
        <a href="javascript:insertAtCaret('thread-content-input', '[:)]');">
          <img src="/img/pinkies/11.gif" /></a>
        <a href="javascript:insertAtCaret('thread-content-input', '[:(]');">
          <img src="/img/pinkies/01.gif" /></a>
        <a href="javascript:insertAtCaret('thread-content-input', '[:D]');">
          <img src="/img/pinkies/05.gif" /></a>
        <a href="javascript:insertAtCaret('thread-content-input', '[;)]');">
          <img src="/img/pinkies/07.gif" /></a>
        <a href="javascript:insertAtCaret('thread-content-input', '[:P]');">
          <img src="/img/pinkies/08.gif" /></a>
        <a href="javascript:insertAtCaret('thread-content-input', '[>|]');">
          <img src="/img/pinkies/14.gif" /></a>
        <a href="javascript:insertAtCaret('thread-content-input', '[:[]');">
          <img src="/img/pinkies/10.gif" /></a>
        <a href="javascript:insertAtCaret('thread-content-input', '[\'(]');">
          <img src="/img/pinkies/03.gif" /></a>
        <a href="javascript:insertAtCaret('thread-content-input', '[:*]');">
          <img src="/img/pinkies/17.gif" /></a>
        <a href="javascript:insertAtCaret('thread-content-input', '[B-]');">
          <img src="/img/pinkies/16.gif" /></a>
        <a href="javascript:insertAtCaret('thread-content-input', '[:=]');">
          <img src="/img/pinkies/27.gif" /></a>
        <a href="javascript:insertAtCaret('thread-content-input', '[:.]');">
          <img src="/img/pinkies/22.gif" /></a>
        <a href="javascript:insertAtCaret('thread-content-input', '[O]');">
          <img src="/img/pinkies/24.gif" /></a>
        <a href="javascript:insertAtCaret('thread-content-input', '[8)]');">
          <img src="/img/pinkies/09.gif" /></a>
        <a href="javascript:insertAtCaret('thread-content-input', '[:{]');">
          <img src="/img/pinkies/06.gif" /></a>
        <a href="javascript:insertAtCaret('thread-content-input', '[:@]');">
          <img src="/img/pinkies/20.gif" /></a>
        <a href="javascript:insertAtCaret('thread-content-input', '[%(]');">
          <img src="/img/pinkies/18.gif" /></a>
        <a href="javascript:insertAtCaret('thread-content-input', '[><]');">
          <img src="/img/pinkies/25.gif" /></a>
        <a href="javascript:insertAtCaret('thread-content-input', '[RR]');">
          <img src="/img/pinkies/23.gif" /></a>
        <a href="javascript:insertAtCaret('thread-content-input', '[NH]');">
          <img src="/img/pinkies/26.gif" /></a>
        <a href="javascript:insertAtCaret('thread-content-input', '[fbm]');">
          <img src="/img/pinkies/21.gif" /></a>
       </div>
      <div style="overflow:auto;">
      <?php echo form_textarea($content); ?>
      <div id="post-shortcuts">
        <p>SHORTCUTS!</p>
        <ul>
          <li>&middot; <a href="javascript:insertAtCaret('thread-content-input', '<a href=%22%22></a>')">URL</a></li>
          <li>&middot; <a href="javascript:insertAtCaret('thread-content-input', '<img src=%22%22 />')">Image</a></li>
          <li>&middot; <a href="javascript:insertAtCaret('thread-content-input', '<spoiler></spoiler>')">Spoiler</a></li>
          <li>&middot; <a href="javascript:insertAtCaret('thread-content-input', '<code></code>')">Code</a></li>
          <li>&middot; <a href="javascript:insertAtCaret('thread-content-input', '<small>snigger</small>')">Snigger</a></li>
        </ul>
      </div>
      </div>
    </div>
  <button type="submit" name="" tabindex="7">Post Thread</button>
  <button type="button" id="preview-button"  tabindex="8">Preview</button>

  </form>

<div id="comment-preview" class="test-comment" style="display: none;">
  <div class="comment-container">
    <div class="user-block">
      <div class="username">You!</div>
      <div class="time">Seconds from now</div>

      <div class="user-information" style="background: url(/img/noavatar.gif);">
      <ul>
        <li>&nbsp;</li>
        <li>&nbsp;</li>
      </ul>
    </div>
  </div>
  <div class="content-block">
    <div class="content"></div>
  </div>
  <div style="clear: both;"></div>
</div>


</div>
<script src="/js/thread.js"></script>