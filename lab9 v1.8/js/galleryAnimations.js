// Animations for images in gallery.html
$(document).ready(function() {
    $(".building img").on("click", function(event) {
        event.stopPropagation(); 
        $(".overlay").remove();

        var imgSrc = $(this).attr("src");

        var overlay = $("<div class='overlay'><img src='" + imgSrc + "'></div>").css({
            "position": "fixed",
            "top": "50%",
            "left": "50%",
            "transform": "translate(-50%, -50%) scale(1.5)",
            "z-index": "9999",
            "transition": "transform 0.7s ease"
        }).appendTo("body");

        overlay.find("img").css({
            "max-width": "40%",
            "max-height": "60%",
            "transition": "transform 0.7s ease",
            "filter": "none"
        });

        $(document).one("click", () => overlay.remove());
    });
});