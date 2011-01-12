var YAY = (function() {
    
    $(".quote").bind("mousedown", function() {
        var cmnt = $(this).parents(".comment"),
            user = cmnt.find(".username").text(),
            post = cmnt.find(".content").html(),
            html = "<quote name=\"" + user + "\">" + post + "</quote>";
        $("#thread-content-input").val($("#thread-content-input").val() + html);
    });
    
})();

