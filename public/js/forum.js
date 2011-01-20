var YAY = (function() {
    
    $(".quote").bind("mousedown", function() {
        var cmnt = $(this).parents(".comment"),
            user = cmnt.find(".username").text(),
            post = cmnt.find(".content").html(),
            html = "<quote name=\"" + user + "\">" + post + "</quote>";
        $("#thread-content-input").val($("#thread-content-input").val() + html);
    });

    (function () {
        
        var title, tpl = $("#title-input").html();
    
        $("#main-title .changeling").bind("click", function () {
            if ($(this).is(":not(.editing)")) {
                title = $(this).text();
                $(this).addClass("editing");
                $(this).empty().append(tpl);
                var input = $(this).find("#title-input");
                input.val(title);
                input[0].focus();
                input[0].select();
            }
        });
        
        $("#cancel-title").live("click", function () {
            $("#main-title")
                .empty()
                .html("<h3>" + title + "</h3>")
                .removeClass("editing");            
        });

        $("#save-title").live("click", function () {
            var newTitle = $("#title-input").val();
            $.ajax({
                type: "POST",
                url: "/title/edit",
                data: "title=" + newTitle,
                success: function(msg){
                    $("#main-title")
                        .empty()
                        .html("<h3>" + newTitle + "</h3>")
                        .removeClass("editing");
                }
            });
        });

    })();
    
})();

