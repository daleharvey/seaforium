			</div>
			
		</div>
		
	</div>
	
	<a name="bottom"></a>
	
	<div id="bottom">
	</div>

	<script type="text/javascript" src="/js/<?php echo $js; ?>"></script>
<?php
  if ($this->agent->is_mobile()) { 
    echo 	'<script type="text/javascript" src="/js/mobile.js"></script>';
  }
?>
	
</body>
</html>