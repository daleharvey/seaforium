			</div>

		</div>

	</div>

	<a name="bottom"></a>

	<div id="bottom">
	</div>

        <script src="/js/prettify.js"></script>
        <script>prettyPrint();</script>
	<script>
	  session_id = '<?php echo $this->session->userdata('session_id'); ?>';
	</script>
	<script src="/js/global.js?v=<?php echo $this->config->item('version'); ?>"></script>

<?php if (strlen($this->session->userdata('custom_js')) > 0) { ?>
	<script src="<?php echo $this->session->userdata('custom_js'); ?>"></script>
<?php } ?>
      <div id="ad-loader">
<?php if ((int)$this->session->userdata('hide_ads') === 0) { ?>
        <div id="the-deck">
         <script type="text/javascript">
				 //<![CDATA[
				 (function(id) {
					document.write('<script type="text/javascript" src="' +
					 'http://www.northmay.com/deck/deck' + id + '_js.php?' +
					 (new Date().getTime()) + '"></' + 'script>');
         })("YH");
				 //]]>
         </script>
				 <div id="deck-title">
           <a href="http://www.coudal.com/deck">Ads Via The Deck</a> | <a id="hide-ads">Hide ads</a>
         </div>
        </div>
<?php } else { ?>

        <div id="hidden-ads">
          <img src="/img/pinkies/jp.gif" align="absmiddle" /> <a id="unhide-ads">Help support YayHooray!</a>
        </div>

<?php } ?>
      </div>
      <script type="text/javascript">
        $('#ad-loader').appendTo('#ad-space');
      </script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-25609352-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script');
    ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') +
      '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(ga, s);
  })();

</script>

</body>
</html>