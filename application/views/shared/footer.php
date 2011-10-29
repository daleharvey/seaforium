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
	<script type="text/javascript" src="/js/global.js"></script>
<?php
  if ($this->agent->is_mobile()) {
    echo 	'<script type="text/javascript" src="/js/mobile.js"></script>';
  }
?>

</body>
</html>