
(function () {
    var title, tpl = $("#title-input").html();

    $("#main-title.changeling").bind("click", function () {
      if ($(this).is(":not(.editing)")) {
        title = $('h3', this).text();
        $(this).addClass("editing");
        $('h3', this).empty().append(tpl);
        var input = $(this).find("#title-input");
        input.val(title);
        input[0].focus();
        input[0].select();
      }
    });

  $('.youtube_wrapper').live("click", function() {
    var obj = $(this);
    if (!obj.data('active')) {
      obj.data('active', true);
      $(this).html('<iframe width="' + obj.width() + '" height="' +
                   obj.height() + '" src="http://www.youtube.com/embed/' +
                   obj.attr('id') + '?autoplay=1' + obj.data('extra') +
                   '" frameborder="0" allowfullscreen></iframe><br />');
    }
  })

    $("#cancel-title").live("click", function () {
      $('h3', "#main-title")
        .empty()
        .text(title);
	  $("#main-title")
        .removeClass("editing");
    });

    $("#save-title").live("click", function () {
      var newTitle = $("#title-input").val();
	  var data = "title=" + newTitle;
	  data += isThread() ? "&thread_id=" + thread.id() : '';

      $.ajax({
        type: "POST",
        url: "/title/edit",
        data: data,
        success: function(msg){
          $('h3', "#main-title")
            .empty()
            .text(newTitle)
		  $("#main-title")
            .removeClass("editing");
        }
      });
    });
	$('#search-box').submit(function() {
		window.location.href="find/" + $('#search-phrase').val();
		return false;
	});
	$('#search-box-user').submit(function() {
		window.location.href="users/0/" + $('#search-phrase-user').val();
		return false;
	});
  })();

(function () {
  $('#toggle-html').bind('click', function(){
    button = $(this);
    $.get('/ajax/toggle_html/'+ session_id, function(data) {
      button.html(data);
    });
    return;
  });
})();

(function () {
  $('#show_desktop').bind('click', function(){
    button = $(this);
    $.get('/ajax/show_desktop/'+ session_id, function(data) {
      button.html(data);
    });
    return;
  });
})();

(function () {
  $('#show_mobile').bind('click', function(){
    button = $(this);
    $.get('/ajax/show_mobile/'+ session_id, function(data) {
      button.html(data);
    });
    return;
  });
})();




function isThread() {
  return (typeof(window.thread) == "undefined")?  false: true;
}
