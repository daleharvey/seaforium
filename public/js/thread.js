function format_quotes()
{
	$('.content').each(function(){
		children = $(this).children('quote');
		
		if (children.length > 0)
		{
			children.each(function(){
			
				$(this).after(
					$('<div>', {
						'class': 'tquote',
						'html': '<div class="tqname">'+$(this).attr('name')+' said:</div>'+$(this).html()
					}));
							$(this).remove();
			});
		} 
	});
}
format_quotes();

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

thread = {
	
	status_text: [],
	
	view_source: function(comment_id)
	{
		$.ajax({
			url: '/ajax/comment_source/'+comment_id,
			success: function(data) {
				if (data)
				{
					comment = eval(data);
					container = $('#comment-'+comment_id+' .content');
					
					container.html($('<textarea>', {
						'id': 'comment-'+comment_id+'-source',
						'val': comment.content
					}));
					
					if (comment.owner)
					{
						container.append($('<button>',{'html': 'Save'}).bind('click',
							function() {thread.save(comment_id);}
						))
					}
					
					container.append($('<button>', {'html': 'Close'}).bind('click',
						function(){thread.view_original(comment_id);}
					));
				}
			}
		});
	},
	
	view_original: function(comment_id)
	{
		$.ajax({
			url: '/ajax/comment_source/'+comment_id+'/1',
			success: function(data) {
				if (data)
				{
					comment = eval(data);
					$('#comment-'+comment_id+' .content').html(comment.content);
					format_quotes();
				}
			}
		});
	},
	
	save: function(comment_id)
	{
		data = {
			comment_id: comment_id,
			content: $('#comment-'+comment_id+' .content textarea').val()
		}
		
		$.ajax({
			type: 'POST',
			url: '/ajax/comment_save/'+comment_id,
			data: data,
			success: function(data){
				$('#comment-'+comment_id+' .content').html(data);
			}
		});
	},
	
	set_status: function(thread_id, keyword, status, key)
	{
		$.get(
			'/ajax/set_thread_status/'+ thread_id +'/'+ keyword +'/'+ status +'/'+ key,
			function(data) {
				console.log(data);
				if (data == 1)
				{
					status = status == 1 ? 0 : 1;
					
					$('#control-'+ keyword +' span').unbind('click').bind('click', function(){
						thread.set_status(thread_id, keyword, status, key);
						return false;
					}).html(thread.status_text[keyword][status]);
				}
			}
		);
		
		return false;
	}
}

thread.status_text['nsfw'] = ['Unmark Naughty', 'Mark Naughty'];
thread.status_text['closed'] = ['Open Thread', 'Close Thread'];

function insertAtCaret(areaId,text) {
	var txtarea = document.getElementById(areaId);
	var scrollPos = txtarea.scrollTop;
	var strPos = 0;
	var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ? 
		"ff" : (document.selection ? "ie" : false ) );
	if (br == "ie") { 
		txtarea.focus();
		var range = document.selection.createRange();
		range.moveStart ('character', -txtarea.value.length);
		strPos = range.text.length;
	}
	else if (br == "ff") strPos = txtarea.selectionStart;

	var front = (txtarea.value).substring(0,strPos);  
	var back = (txtarea.value).substring(strPos,txtarea.value.length); 
	txtarea.value=front+text+back;
	strPos = strPos + text.length;
	if (br == "ie") { 
		txtarea.focus();
		var range = document.selection.createRange();
		range.moveStart ('character', -txtarea.value.length);
		range.moveStart ('character', strPos);
		range.moveEnd ('character', 0);
		range.select();
	}
	else if (br == "ff") {
		txtarea.selectionStart = strPos;
		txtarea.selectionEnd = strPos;
		txtarea.focus();
	}
	txtarea.scrollTop = scrollPos;
}


$(".quote").bind("mousedown", function() {
    var cmnt = $(this).parents(".comment"),
        user = cmnt.find(".username").text(),
        post = cmnt.find(".content").html(),
        html = "<quote name=\"" + user + "\">" + post + "</quote>";
    $("#thread-content-input").val($("#thread-content-input").val() + html);
});
