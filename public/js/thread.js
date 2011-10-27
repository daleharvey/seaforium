function cloneObj(obj) {
  return jQuery.extend(true, {}, obj);
};

jQuery.fn.reverse = function() {
    return this.pushStack(this.get().reverse(), arguments);
};

$.fn.selectRange = function(start, end) {
	if(!end) end = start;
	
    return this.each(function() {
        if (this.setSelectionRange) {
            this.focus();
            this.setSelectionRange(start, end);
        } else if (this.createTextRange) {
            var range = this.createTextRange();
            range.collapse(true);
            range.moveEnd('character', end);
            range.moveStart('character', start);
            range.select();
        }
    });
};

function format_special(element)
{
  $('spoiler').each(function() {
    var html = '<div class="spoiler">' +
      '<div class="spoiler-disclaimer">Warning! May contain spoilers</div>' +
      '<div class="spoiler-content">' + $(this).html() + '</div>' +
      '</div>'
    $(this).replaceWith(html);
  });

  pattern = new RegExp('(?:")?http(?:s)?://(?:www.)?youtu(?:be)?.(?:[a-z]){2,3}(?:[a-z/?=]+)([a-zA-Z0-9-_]{11})(?:[a-z0-9\?\&\-_=]+)?');

  $(element).each(function(){
    // auto-embed youtube videos
    $(this).html($(this).html().replace(pattern, function(a, b){return (a.indexOf("\"") != -1) ? a : '<iframe width="425" height="349" src="http://www.youtube.com/embed/'+b+'" frameborder="0" allowfullscreen></iframe><br />';}));

    // formatting for nickoislazy style quotes
    children = $(this).find('blockquote');

    if (children.length > 0)
    {
      children.reverse().each(function(){

	$(this).after(
	  $('<div>', {
	    'class': 'tquote',
	    'html': '<div class="tqname">'+$(this).attr('title')+' said:</div>'+$(this).html()
	  })
	);

	$(this).remove();
      });
    }
  });
}
format_special('.comment .content');

$('#preview-button').live('click', function(e){
  e.preventDefault();

  $("#comment-preview .content").html($("#thread-content-input").val());
  format_special("#comment-preview .content");
  $("#comment-preview").show();
});

$("#comment-form").live("submit", function() {
  if ($("#thread-content-input").val().length == 0)
    return false;

  $("#submit-button").attr('disabled', 'disabled')
  this.submit();
});

selected = {
  html: null,
  comment_id: null
};

$('.content').click(function() {
	selected.html = null;
	selected.comment_id = null;

	if(window.getSelection) {
		// not IE case
		selObj = window.getSelection();
		if(selObj.focusNode) {
			selRange = selObj.getRangeAt(0);
			
			p = selRange.commonAncestorContainer;
			while(p.parentNode && !$(p).hasClass("comment-container")) {
				p = p.parentNode;
			}
			
			if(p.id) {
				dash = p.id.lastIndexOf('-');
			
				if(dash != -1) {
					selected.comment_id = p.id.substring(dash+1);
					
					fragment = selRange.cloneContents();
					e = document.createElement('b');
					e.appendChild(fragment);
					selected.html = e.innerHTML;
				}
			}
			
			//selObj.removeAllRanges();
		}
	} else if (document.selection && document.selection.createRange && document.selection.type != "None") {
		// IE case
		selected.html = document.selection.createRange().htmlText;
	}
});

thread = {
  status_text: {'nsfw': ['Unmark Naughty', 'Mark Naughty'], 'closed': ['Open Thread', 'Close Thread']},
  comments: [],

  get_comment_details: function(comment_id, callback)
  {
    $.ajax({
      url: '/ajax/view_source/'+comment_id,
      success: function(data) {
	if (data)
	{
	  container = $('#comment-'+comment_id+' .content');

	  // set the originals
	  thread.comments[comment_id] = {
	    container: container,
	    rendered: container.html(),
	    data: eval(data),
	    author: $('#comment-'+comment_id+' .username a').html()
	  };

	  callback();
	}
      }
    });
  },

  quote: function(comment_id)
  {
    if (thread.comments[comment_id] != undefined)
    {
		if(selected.comment_id && selected.comment_id != comment_id || !selected.html) {
			content = thread.comments[comment_id].data.content;
		} else {
			content = selected.html;
		}
		
		selected.html = null;
		selected.comment_id = null;

		html = "<blockquote title=\"" + $.trim(thread.comments[comment_id].author) + "\">\n" + content + "\n</blockquote>";

		$("#thread-content-input").val($("#thread-content-input").val() + html);
		
		$(window).scrollTop($("#thread-content-input").offset().top);
		$("#thread-content-input").focus();
		$("#thread-content-input").scrollTop($("#thread-content-input")[0].scrollHeight - $("#thread-content-input").height());
		$("#thread-content-input").selectRange($("#thread-content-input").val().length);
    }
    else
    {
      thread.get_comment_details(comment_id, function(){
		thread.quote(comment_id);
      });
    }
  },

  save: function(comment_id)
  {
    var post_data = {
      comment_id: comment_id,
      content: $('#comment-'+comment_id+' .content textarea').val()
    }

    $.ajax({
      type: 'POST',
      url: '/ajax/comment_save/'+comment_id,
      data: post_data,
      success: function(data){
	format_special($('#comment-'+comment_id+' .content').html(data));
        delete thread.comments[comment_id];
      }
    });
  },

  set_status: function(thread_id, keyword, status, key)
  {
    $.get(
      '/ajax/set_thread_status/'+ thread_id +'/'+ keyword +'/'+ status +'/'+ key,
      function(data) {
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
  },

  view_original: function(comment_id)
  {
    if (thread.comments[comment_id] != undefined) {
      $('#comment-' + comment_id + ' .content')
        .html(thread.comments[comment_id].rendered);
    }
  },

  view_source: function(comment_id)
  {
    if (thread.comments[comment_id] != undefined) {

      comment = thread.comments[comment_id];

      // View source is already active, switch back to original
      if (comment.container.find("textarea").length !== 0) {
        thread.view_original(comment_id);
        return;
      }

      comment.container.html($('<textarea>', {
	'id': 'comment-' + comment_id + '-source',
	'val': comment.data.content
      }));

      if (comment.data.owner) {
	comment.container.append(
          $('<button>',{'html': 'Save'})
            .bind('click', function() {thread.save(comment_id);} ));
      }

      comment.container.append(
        $('<button>', {'html': 'Close'})
          .bind('click', function(){thread.view_original(comment_id);} ));

    } else {
      thread.get_comment_details(comment_id, function(){
	thread.view_source(comment_id);
      });
    }
  },

  id: function()
  {
	return $('.favourite').attr('rel');
  }
}

function insertAtCaret(areaId,text) {
  var txtarea = document.getElementById(areaId);
  var scrollPos = txtarea.scrollTop;
  var strPos = 0;
  var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
	    "ff" : (document.selection ? "ie" : false ) );

  if (br == "ie") {
    txtarea.focus();
    var range = document.selection.createRange();
	var selection = range.text;
    range.moveStart ('character', -txtarea.value.length);
    strPos = range.text.length;
  }
  else if (br == "ff") {
	strPos = txtarea.selectionStart;
	var selection = (txtarea.value).substring(strPos, txtarea.selectionEnd);
  }

  var cursorOffset = text.indexOf('"');
  if(cursorOffset == -1 && !selection) {
	cursorOffset = text.indexOf('<', 1) - 1;
  }
  cursorOffset = cursorOffset == -1 ? 0 : text.length - cursorOffset - 1;

  var closeTag = text.indexOf("<", 1);
  if(closeTag == -1) {
	var removeOffset = 0;
  } else {
	text = text.substring(0, closeTag) + selection + text.substring(closeTag);
	if(cursorOffset) {
		cursorOffset += selection.length;
	}
	var removeOffset = selection.length;
  }

  var front = (txtarea.value).substring(0,strPos);
  var back = (txtarea.value).substring(strPos + removeOffset);
  txtarea.value=front+text+back;
  strPos = strPos + text.length - cursorOffset;

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