function cloneObj(obj) {
  return jQuery.extend(true, {}, obj);
}

jQuery.fn.reverse = function() {
  return this.pushStack(this.get().reverse(), arguments);
};

$.fn.selectRange = function(start, end) {
  if (!end) {
    end = start;
  }

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
      '</div>';
    $(this).replaceWith(html);
  });

  var ytube = new RegExp('(?:")?http(?:s)?://(?:www.)?youtu(?:be)?.(?:[a-z]){2,3}' +
                         '(?:[a-z/?=]+)([a-zA-Z0-9-_]{11})(?:[a-z0-9?&-_=]+)?');
  var vimeo = new RegExp('http(?:s)?://(?:www.)?vimeo.com/([0-9]+)(?:#[a-z0-9?&-_=]*)?');

  $(element).each(function(){

    var text = $(this).find("*").andSelf().contents().each(function () {

      if (this.nodeType !== 3 || this.parentNode.nodeName === 'A') {
        return;
      }

      var tmp = this.textContent;

      tmp = tmp.replace(ytube, function(a, b) {

        var url_items = a.substring(a.indexOf('?') + 1).split('&');
        var youtube_param = [];
        var video_width = 425;
        var video_height = 349;
        var video_url_append = [];
        var video_url_autoplay = "?autoplay=1";
        var video_url_append_string = "";

        $.each(url_items, function(index, val) {

          var element = val.split('=');
          if (element[0] == 't') {
            video_url_append.push("t=" + element[1]);
          }

          if (element[0] == 'size') {
            var width_height_params = element[1].split("x");
            video_width = width_height_params[0];
            video_height = width_height_params[1];
          }
        });

        for (var m=0;m<video_url_append.length;m++) {
          video_url_append_string += "#";
          video_url_append_string += video_url_append[m];
        }

        var youtube_html;
        if (a.indexOf("\"") != -1) {
          youtube_html = a;
        } else {
          youtube_html = "<div style='width:" + video_width + "px; height:" +
            video_height + "px' id='" + b + "' class='youtube_wrapper' " +
            "data-extra='" + video_url_append_string + "'></div><br/>";
        }
        return youtube_html;
      });

      tmp = tmp.replace(vimeo, function(a, b){
        return (a.indexOf("\"") != -1) ? a :
          '<iframe src="http://player.vimeo.com/video/' + b +
          '?title=0&amp;byline=0&amp;portrait=0" width="400" height="225" ' +
          'frameborder="0" webkitAllowFullScreen allowFullScreen></iframe><br />';
      });

      if (tmp !== this.textContent && $('#toggle-html').data('active')) {
        $(this).replaceWith(tmp);
      }

    });

    // Reverse so we handle nested quotes
    $(this).find('blockquote').reverse().each(function(){
      var user = $(this).attr('title') || 'Someone';
      $(this).after(
	$('<div>', {
	  'class': 'tquote',
	  'html': '<div class="tqname">' + user + ' said:</div>'+$(this).html()
	})
      ).remove();
    });

    $(this).find('.youtube_wrapper').each(function() {
      var self = this;
      $.ajax({
        url: 'http://gdata.youtube.com/feeds/api/videos/' + $(this).attr('id') + '?v=2&alt=jsonc',
        dataType: "jsonp",
        success: function(data) {
          $(self).append('<img src="' + data.data.thumbnail.hqDefault +
                         '" class="youtube_placeholder" title="Click to play this video" /><h2>' +
                         data.data.title + '</h2><div class="youtube_playbutton"></div>');
        }
      });
    });
  });
}

format_special('.comment .content, .recent-post-content');

$('#preview-button').live('click', function(e){
  e.preventDefault();
  var post = $("#thread-content-input").val();
  $.post('/ajax/preview', {content: post}).then(function(data) {
    $("#comment-preview .content").html(data);
    format_special("#comment-preview .content");
    prettyPrint();
    $("#comment-preview").show();
  });
});

$("#comment-form").live("submit", function() {
  if ($("#thread-content-input").val().length === 0) {
    return false;
  }
  $("#submit-button").attr('disabled', 'disabled');
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
  } else if (document.selection &&
             document.selection.createRange &&
             document.selection.type != "None") {
    // IE case
    selected.html = document.selection.createRange().htmlText;
  }
});

thread = {
  status_text: {'nsfw': ['Unmark Naughty', 'Mark Naughty'],
                'closed': ['Open Thread', 'Close Thread']},
  comments: [],

  get_comment_details: function(comment_id, callback)
  {
    $.ajax({
      url: '/ajax/view_source/'+comment_id,
      success: function(data) {
	if (data) {
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
    if (thread.comments[comment_id] !== undefined) {
      if(selected.comment_id && selected.comment_id != comment_id || !selected.html) {
	content = thread.comments[comment_id].data.content;
      } else {
	content = selected.html;
      }

      selected.html = null;
      selected.comment_id = null;

      html = "<blockquote title=\"" + $.trim(thread.comments[comment_id].author) +
        "\">\n" + content + "\n</blockquote>";

      $("#thread-content-input").val($("#thread-content-input").val() + html);

      $(window).scrollTop($("#thread-content-input").offset().top);
      $("#thread-content-input").focus();
      $("#thread-content-input").scrollTop($("#thread-content-input")[0].scrollHeight -
                                           $("#thread-content-input").height());
      $("#thread-content-input").selectRange($("#thread-content-input").val().length);
    } else {
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
    };

    $.ajax({
      type: 'POST',
      url: '/ajax/comment_save/'+comment_id,
      data: post_data,
      success: function(data){
	format_special($('#comment-'+comment_id+' .content').html(data));
        delete thread.comments[comment_id];
        prettyPrint();
      }
    });
  },

  set_status: function(thread_id, keyword, status, key)
  {
    $.get(
      '/ajax/set_thread_status/'+ thread_id +'/'+ keyword +'/'+ status +'/'+ key,
      function(data) {
	if (data == 1) {
	  if(keyword == 'deleted') {
	    location = '/';
	  } else {
	    status = status == 1 ? 0 : 1;

	    $('#control-'+ keyword +' span').unbind('click').bind('click', function(){
	      thread.set_status(thread_id, keyword, status, key);
	      return false;
	    }).html(thread.status_text[keyword][status]);
	  }
	}
      }
    );
  },

  view_original: function(comment_id)
  {
    if (thread.comments[comment_id] !== undefined) {
      $('#comment-' + comment_id + ' .content')
        .html(thread.comments[comment_id].rendered);
    }
  },

  view_source: function(comment_id)
  {
    if (thread.comments[comment_id] !== undefined) {

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
};

function insertAtCaret(areaId,text) {
  var txtarea = document.getElementById(areaId);
  var scrollPos = txtarea.scrollTop;
  var strPos = 0;
  var selection;
  var removeOffset;
  var range;
  var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
	    "ff" : (document.selection ? "ie" : false ) );

  if (br == "ie") {
    txtarea.focus();
    range = document.selection.createRange();
    selection = range.text;
    range.moveStart ('character', -txtarea.value.length);
    strPos = range.text.length;
  } else if (br == "ff") {
    strPos = txtarea.selectionStart;
    selection = (txtarea.value).substring(strPos, txtarea.selectionEnd);
  }

  var cursorOffset = text.indexOf('"');
  if(cursorOffset == -1 && !selection) {
	cursorOffset = text.indexOf('<', 1) - 1;
  }
  cursorOffset = cursorOffset == -1 ? 0 : text.length - cursorOffset - 1;

  var closeTag = text.indexOf("<", 1);
  if(closeTag == -1) {
    removeOffset = 0;
  } else {
    text = text.substring(0, closeTag) + selection + text.substring(closeTag);
    if(cursorOffset) {
      cursorOffset += selection.length;
    }
    removeOffset = selection.length;
  }

  var front = (txtarea.value).substring(0,strPos);
  var back = (txtarea.value).substring(strPos + removeOffset);
  txtarea.value=front+text+back;
  strPos = strPos + text.length - cursorOffset;

  if (br == "ie") {
    txtarea.focus();
    range = document.selection.createRange();
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