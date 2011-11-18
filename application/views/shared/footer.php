			</div>

		</div>

	</div>

	<a name="bottom"></a>

	<div id="bottom">
	</div>

	<?php if ($this->agent->is_mobile()) { ?>
	<a id="show_desktop">Switch to Desktop version</a>
	<a id="show_mobile">Switch to Mobile version</a>
	<?php } ?>

        <script type="text/javascript" src="/js/prettify.js"></script>
        <script>
          prettyPrint();
        </script>
	<script type="text/javascript">
		session_id = '<?php echo $this->session->userdata('session_id'); ?>';
	</script>
	<script type="text/javascript" src="/js/global.js?v=<?php echo $this->config->item('version'); ?>"></script>
<?php
  if ($this->agent->is_mobile()) {
    echo 	'<script type="text/javascript" src="/js/mobile.js"></script>';
  }
?>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-25609352-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</body>
</html>