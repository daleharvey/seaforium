<?php

$logged_in = $this->sauth->is_logged_in();
$owner = $logged_in && $this->meta['user_id'] == $info['user_id'];

?>

<div id="thread">
  <div id="main-title"<?php echo $owner && $info['editable'] ? ' class="changeling"' : '' ?>>
    <h3><?php echo $info['title'] ?></h3>
    <?php if ($logged_in) { ?>
      <a class="favourite<?php echo in_array($thread_id, $favorites) ? ' added' : ''; ?>" rel="<?php echo $thread_id; ?>"></a>
    <?php } ?>
  </div>

  <?php if ($logged_in && $owner) { ?>
		<script type="text/html" id="title-input">
			<input type="text" id="title-input" />
			<input type="submit" value="Save" id="save-title" />
			<input type="button" value="Cancel" id="cancel-title" />
		</script>
  <?php }?>

  <div class="pagination top">
    <?php echo $pagination; ?>
  </div>

<?php

$i = 0;
$previous_created = "";

// loop through and print out all the comments
foreach($comment_result->result() as $row) {
	//checking if last post was older than 36 hours ago

  $created = strtotime($row->created);
  $comment_owner = $row->user_id == $this->meta['user_id'];

  if ($i > 0 && $created - $previous_created > 129600)
  {
?>
    <div class="comment later-comment">
      <div class="comment-container">
        <?php echo timespan($previous_created, strtotime($row->created), 'later');; ?>
      </div>
    </div>
<?php
  }

	$previous_created = strtotime($row->created);

  // if the comment has been deleted,
  // go ahead and just put the deleted thing and start the loop over
  if ($row->deleted == '1') {
?>
  <div class="comment deleted">
    comment by <?php echo $row->username; ?> deleted
  </div>
<?php

    continue;
  }

  $url_safe_username = url_title($row->username, 'dash');

  switch($row->acq_type) {
  case 1:
    $acq = ' buddy';
    break;
  case 2:
    $acq = ' enemy';
    break;
  default:
    $acq = null;
  }

  // if the comment belongs to someone you've enemied
  if ($row->acq_type == 2)
  { ?>
  <div id="ignore-for-<?php echo $row->comment_id; ?>" class="ignore-container"
    onclick="$('#comment-container-<?php echo $row->comment_id; ?>').toggle();"></div>
<?php } ?>

  <div id="comment-<?php echo $row->comment_id; ?>" class="comment userid-<?php echo $row->user_id, $acq; echo $comment_owner ? ' mycomment' : ''; ?>">
    <div id="comment-container-<?php echo $row->comment_id; ?>" class="comment-container">
      <div class="cmd-bar">
        <span>
          <a class="view-source" onclick="thread.view_source(<?php echo $row->comment_id; ?>); return false;">
          <?php echo $comment_owner && ((strtotime($row->created) > time() - (60 * 60 * 24)) || $i == 0)
            ? 'Edit Post'
            : 'View Source'; ?>
          </a>
        </span>
      <?php if ($logged_in) { ?>
        <a class="quote" onclick="thread.quote(<?php echo $row->comment_id; ?>);">
          Quote
        </a>
      <?php } ?>
      </div>
      <div class="user-block">
        <div class="username<?php echo $acq; ?>">
          <a href="/user/<?php echo $url_safe_username; ?>">
            <?php echo $row->username;?>
          </a>
        </div>
        <div class="time"><?php echo timespan(strtotime($row->created), time()) ?></div>
        <div class="user-information" style="background: url(/img/emoticons/<?php echo (int)$row->emoticon === 1 ? $row->id : '0'; ?>.gif);">
          <ul>
          <?php if ($logged_in) { ?>
            <li><a href="/buddies/<?php echo $url_safe_username; ?>"><?php echo ($acq)? "Your $acq!" : 'BUDDY? ENEMY?'; ?></a></li>
            <li><a href="/message/send/<?php echo $url_safe_username; ?>">SEND A MESSAGE</a></li>
          <?php } else { ?>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
          <?php } ?>
          </ul>
        </div>

        <?php if ($owner && $i === 0 && $starting === 0) { ?>
          <div id="thread-control">
            <p>THREAD ADMIN</p>
            <ul>
              <li id="control-nsfw">&middot; <span><?php echo $info['nsfw'] === '1' ? 'Unmark Naughty' : 'Mark Naughty'; ?></span></li>
              <li id="control-closed">&middot; <span><?php echo $info['closed'] === '1' ? 'Open Thread' : 'Close Thread'; ?></span></li>
            <?php if($info['editable']) {?>
              <li id="control-delete">&middot; <span>Delete Thread</span></li>
            <?php }?>
            </ul>
          </div>

          <script type="text/javascript">
            $('#control-nsfw span').bind('click', function(){
              thread.set_status(<?php echo $row->thread_id; ?>, 'nsfw', <?php echo $info['nsfw'] === '1' ? 0 : 1; ?>, session_id)
            });
            $('#control-closed span').bind('click', function(){
              thread.set_status(<?php echo $row->thread_id; ?>, 'closed', <?php echo $info['closed'] === '1' ? 0 : 1; ?>, session_id);
            });
            $('#control-delete span').bind('click', function(){
              thread.set_status(<?php echo $row->thread_id; ?>, 'deleted', 1, session_id);
            });
          </script>

        <?php } // end ($owner && $i === 0 && $starting === 0) ?>

      </div>

      <div class="content-block">
        <div class="content">
<?php
$view_html = ($this->session->userdata('view_html') === '1' ||
              $this->session->userdata('view_html') === false);

if ($row->content === '' && $row->original_content !== '') {
  $content = _process_post($row->original_content);
  $thread_model->update_comment_cache($row->comment_id, $content);
} else {
  $content = $row->content;
}

echo $view_html ? $content : nl2br(htmlentities($content));

?>
        </div>
      </div>
      <div style="clear: both;"></div>
    </div>
  </div>
<?php ++$i; } ?>

<div class="pagination bottom">
  <?php echo $pagination; ?>
</div>

<div class="dotted-bar replypad"></div>
<?php

if (!$logged_in || $info['closed'] === '1' || $info['acq_type'] === 2) {
  if ($info['closed'] === '1') {
?>
  <h3>This thread is closed</h3>
<?php
  } elseif ($info['acq_type'] === 2) {
?>
  <h3>You cannot post in this thread</h3>
<?php
}
} else {

// and now the reply form
$content = array(
  'tabindex' => 1,
  'name'	=> 'content',
  'id'	=> 'thread-content-input',
  'value' => set_value('content')
);

?>

</div>

<div id="reply-lc">
  <h4>Post A Reply</h4>
  <div id="post-shortcuts">
    <p>SHORTCUTS!</p>
    <ul>
      <li>&middot; <a href="javascript:insertAtCaret('thread-content-input', '<a href=%22%22></a>')">URL</a></li>
      <li>&middot; <a href="javascript:insertAtCaret('thread-content-input', '<img src=%22%22 />')">Image</a></li>
      <li>&middot; <a href="javascript:insertAtCaret('thread-content-input', '<spoiler></spoiler>')">Spoiler</a></li>
      <li>&middot; <a href="javascript:insertAtCaret('thread-content-input', '<code></code>')">Code</a></li>

      <!--<li>&middot; <a href="#">Spoiler</a></li>-->
    </ul>
  </div>
</div>
<div id="reply-rc">
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

<form method="post" action="<?php echo uri_string(); ?>" id="comment-form">
  <div class="input textarea">
<?php echo form_textarea($content); ?>
  </div>
  <p>I, <?php echo $this->session->userdata('username'); ?>,
    do solemnly swear that in posting this comment I promise to be nice.</p>

  <button type="submit" id="submit-button" tabindex='2'>
    Agree &amp; Post
  </button>
  <button type="button" id="preview-button">Preview</button>
</form>

</div>

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

<?php

// start notifier
if ($logged_in && (int) $this->session->userdata('new_post_notification') === 1) {

?>
  <div id="notifications">
    <a id="closenotify"></a>
  </div>

  <script type="text/javascript">
    var originalTitle = document.title,
      currentNotification;

    function thread_notifier() {
      $.ajax({
        url: '/ajax/thread_notifier/<?php echo $thread_id; ?>/<?php echo $total_comments; ?>',
        success: function(data)
        {
          if (data)
          {
            var text = $(data).text();
            document.title = text.replace(" added", "") + " | " + originalTitle;
            if (text !== currentNotification)
            {
              $("#notifier").remove();
              currentNotification = text;
              $('#notifications').append(data).show();
            }
          }
        }
      });
    }

    $("#closenotify").live("click", function() {
      $('#notifications').remove();
      clearTimeout(notification);
      document.title = originalTitle;
    });

    var notification = setInterval("thread_notifier()", 10000);
  </script>

<?php } // end notifier ?>
<?php } ?>

  <script type="text/javascript" src="/js/thread.js"></script>

<?php if ($logged_in) { ?>
  <script type="text/javascript">
     $('.favourite').bind('click', function(){
       button = $(this);
       if (!$(this).hasClass('added')) {
         $.get('/ajax/favorite_thread/'+ $(this).attr('rel') +
               session_id, function(data) {
           if (data == 1) button.addClass('added');
         });
       } else {
         $.get('/ajax/unfavorite_thread/'+ $(this).attr('rel') +
               '/'+session_id, function(data) {
           if (data == 1) button.removeClass('added');
         }
      );
    }
    return false;
  });
</script>
<?php } ?>
</div>
