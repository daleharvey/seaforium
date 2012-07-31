<div id="main-title"><h3>Yay for TinyChat!</h3></div>

<?php 
if ((int) $this->session->userdata('chat_fixed_size'))
{
?>
<style type="text/css">
.tinychat_embed {width: 725px; height: 600px !important;}
</style>
<?php
}
?>

<script type="text/javascript">
var tinychat = {
  room: "yaytwo",
  colorbk: "0x000000",
  join: "auto",
  api: "list"
  };
</script>

<script src="http://tinychat.com/js/embed.js"></script>
<div id="tinychat"><a href="http://tinychat.com">video chat</a> provided by Tinychat</div>