(function () {
	$('#pm-inbox .message.header .marker input').bind('click', function(e){
		$('#pm-inbox .message.lineitem .marker input').attr('checked', $(this).attr('checked') == "checked" ? true : false );
	});
	
	$('#inbox-mark-unread').bind('click', function(e){
		e.preventDefault();
		
		$('#message-form-action').val('unread');
		$('#message-form').submit();
	});
	
	$('#inbox-mark-read').bind('click', function(e){
		e.preventDefault();
		
		$('#message-form-action').val('read');
		$('#message-form').submit();
	});
	
	$('#inbox-delete').bind('click', function(e){
		e.preventDefault();
		
		if (confirm('Are you sure you want to delete the selected messages?'))
		{
			$('#message-form-action').val('delete');
			$('#message-form').submit();
		}
	});
})();