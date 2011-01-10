function thread_notifier()
{
	$.ajax({
		url: '/ajax/thread_notifier/'+thread_id+'/'+total_comments,
		success: function(data) {
			if (data)
			{
				$('#thread').append(data);
			}
		}
	});
}

i = setInterval("thread_notifier()",10000);