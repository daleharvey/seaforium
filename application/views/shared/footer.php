<?php
$js  = $this->agent->is_mobile() ? "mobile.js" : "forum.js";
?>
			</div>
			
		</div>
		
	</div>
	
	<a name="bottom"></a>
	
	<div id="bottom">
		
		stuff at the bottom
		
	</div>

	<script type="text/javascript" src="/js/jquery-1.4.4.min.js"></script>
	<script type="text/javascript" src="/js/<?php echo $js; ?>"></script>
	
</body>
</html>