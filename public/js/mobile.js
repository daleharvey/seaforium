
$(".thread").each(function () {
    var url = $(this).find(".subject a").attr("href");
    $(this).find("a").removeAttr("href");
    $(this).wrapInner("<a href='" + url + "'></a>");
});