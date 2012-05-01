<?php

$logged_in = $this->sauth->is_logged_in();

?>

<div id="thread">
  <div id="main-title"<?=$information->owner && $information->editable ? ' class="changeling"' : '' ?>>
    <h3><?=htmlspecialchars($information->subject) ?></h3>
    
    <?php if ($logged_in) { ?>
      <a class="favourite<?=$information->favorite ? ' added' : '' ?>" rel="<?=$information->thread_id ?>"></a>
      <a class="hide-thread<?=$information->hidden ? ' added' : '' ?>" rel="<?=$information->thread_id ?>"></a>
    <?php } ?>
  </div>

  <?php if ($information->owner): ?>
    <script type="text/html" id="title-input">
      <input type="text" id="title-input" />
      <input type="submit" value="Save" id="save-title" />
      <input type="button" value="Cancel" id="cancel-title" />
    </script>
  <?php endif; ?>

  <div class="pagination top">
    <?=$pagination->links ?>
    
    <span class="paging-text">
      <?=$pagination->lower_limit ?> - <?=$pagination->upper_limit ?> of <?=$information->comment_count ?>
      in <a href="/">Threads</a>
      &gt; <?=$pagination->category ?>
      &gt; <?=$pagination->thread ?>
    </span>
    
    <?php if ($information->enemies > 0) { ?>
      <div class="toggle-enemy">
        <?=$information->enemies ?> ENEMY POST<?=($information->enemies == 1 ? '' : 'S') ?> IGNORED
      </div>
    <?php } ?>
  </div>

<?php

$i = 0;
$previous_created = time();

// loop through and print out all the comments
foreach($comments as $row) {
  //checking if last post was older than 36 hours ago
  if ($row->created - $previous_created > 129600)
  {
?>
    <div class="comment later-comment">
      <div class="comment-container">
        <?=timespan($previous_created, $row->created, 'later'); ?>
      </div>
    </div>
<?php
  }

  $previous_created = $row->created;

  // if the comment has been deleted,
  // go ahead and just put the deleted thing and start the loop over
  if ($row->deleted) {
    ?>
      <div class="comment deleted">comment by <?=$row->author_name ?> deleted</div>
    <?php
    continue;
  }

  // if the comment belongs to someone you've enemied
  if ($row->author_acquaintance_type == 2)
  {
    if ($this->meta['hide_enemy_posts'] === '1')
      continue;
    
  ?>
  <div id="ignore-for-<?=$row->comment_id ?>" class="ignore-container" onclick="$('#comment-container-<?=$row->comment_id ?>').toggle();"></div>
  <?php 
  } ?>

  <div id="comment-<?=$row->comment_id ?>" class="comment userid-<?=$row->author_id, $row->author_acquaintance_name, ($row->owner ? ' mycomment' : '') ?>">
    <div id="comment-container-<?=$row->comment_id; ?>" class="comment-container">
      <div class="cmd-bar">
        <span>
          <a class="view-source" onclick="thread.view_source(<?=$row->comment_id ?>); return false;">
            <?=$row->editable ? 'Edit Post' : 'View Source' ?>
          </a>
        </span>
      <?php if ($logged_in) { ?>
        <a class="quote" onclick="thread.quote(<?=$row->comment_id ?>);">
          Quote
        </a>
      <?php } ?>
      </div>
      <div class="user-block">
        <div class="username<?=$row->author_acquaintance_name; ?>">
          <a href="/user/<?=$row->url_safe_author_name; ?>">
            <?=$row->author_name;?>
          </a>
        </div>
        <div class="time"><?=timespan($row->created, time()) ?></div>
        <div class="user-information" style="background: url(/img/emoticons/<?=$row->emoticon ? $row->author_id : '0'; ?>.gif);">
          <ul>
          <?php if ($logged_in) { ?>
            <li><a href="/buddies/<?=$row->url_safe_author_name ?>"><?php echo ($row->author_acquaintance_name)? "Your $row->author_acquaintance_name!" : 'BUDDY? ENEMY?'; ?></a></li>
            <li><a href="/message/send/<?=$row->url_safe_author_name ?>">SEND A MESSAGE</a></li>
          <?php } else { ?>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
          <?php } ?>
          </ul>
        </div>

        <?php if ($row->show_controls) { ?>
          <div id="thread-control">
            <p>THREAD ADMIN</p>
            <ul>
              <li id="control-nsfw">&middot; <span><?=$information->nsfw ? 'Unm' : 'M';?>ark Naughty</span></li>
              <li id="control-closed">&middot; <span><?=$information->closed ? 'Open' : 'Close' ?> Thread</span></li>
            <?php if($information->editable) {?>
              <li id="control-delete">&middot; <span>Delete Thread</span></li>
            <?php } ?>
            </ul>
          </div>

          <script type="text/javascript">
            $('#control-nsfw span').bind('click', function(){
              thread.set_status(<?=$information->thread_id; ?>, 'nsfw', <?=($information->nsfw ? 0 : 1) ?>, session_id)
            });
            $('#control-closed span').bind('click', function(){
              thread.set_status(<?=$information->thread_id; ?>, 'closed', <?=($information->closed ? 0 : 1) ?>, session_id);
            });
            $('#control-delete span').bind('click', function(){
              thread.set_status(<?=$information->thread_id ?>, 'deleted', 1, session_id);
            });
          </script>

        <?php } ?>

      </div>

      <div class="content-block">
        <div class="content">
          <?php
            if (!$row->author_banned) {
              echo $row->content;
            } else {
              ?>
          <div class="censor">user has been banned, click here to see original content
            <div class="content" style="display: none; text-align: left; background: #FFFFFF;">
                <?=$row->content; ?>
            </div>
          </div>
              <?php
            }
          ?>
        </div>
      </div>
      <div style="clear: both;"></div>
    </div>
  </div>
<?php ++$i; } ?>

  <div class="pagination bottom">
    <?=$pagination->links ?>
    
    <span class="paging-text">
      <?=$pagination->lower_limit ?> - <?=$pagination->upper_limit ?> of <?=$information->comment_count ?>
      in <a href="/">Threads</a>
      &gt; <?=$pagination->category ?>
      &gt; <?=$pagination->thread ?>
    </span>

  <?php if ($information->enemies > 0) { ?>
    <div class="toggle-enemy" id="toggle-enemy">
      <?=$information->enemies ?> ENEMY POST<?=($information->enemies == 1 ? '' : 'S') ?> IGNORED
    </div>

    <script type="text/javascript">
      $('.toggle-enemy').bind('click', function(){$('.ignore-container').click();})
    </script>
  <?php } ?>
</div>

<div class="dotted-bar replypad"></div>
<?php
if (!$logged_in || $information->closed === '1' || $information->author_acquaintance_type === 2) 
{
  if ($information->closed === '1')
  {
    ?><h3>This thread is closed</h3><?php
  }
  elseif ($information->author_acquaintance_type === 2)
  {
    ?><h3>You cannot post in this thread</h3><?php
  }
}
else
{ ?>

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
      <li>&middot; <a href="javascript:insertAtCaret('thread-content-input', '<small>snigger</small>')">Snigger</a></li>
    </ul>
  </div>
</div>
<div id="reply-rc">
<div id="pinkies">
  <?php /*
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
    */?>
 </div>

<form method="post" action="<?=uri_string() ?>" id="comment-form">
  <div class="input textarea">
    <?php echo form_textarea(array(
      'tabindex' => 1,
      'name'  => 'content',
      'id'  => 'thread-content-input',
      'value' => set_value('content')
    )); ?> 
  </div>
  <p>I, <?=$this->meta['username'] ?>, do solemnly swear that in posting this comment I promise to be nice.</p>
  <button type="submit" id="submit-button" tabindex="2">Agree &amp; Post</button>
  <button type="button" id="preview-button" tabindex="3">Preview</button>
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
<?php } ?>

  <script type="text/javascript" src="/js/thread.js?v=<?php echo $this->config->item('version'); ?>"></script>
</div>