(function ($) {

 //Photo Contact//

  $("#button-photo").on("click", function () {
    $("#popup-single").removeClass("hidden");
  });
  
  $(".close-photo").on("click", function () {
    $("#popup-single").addClass("hidden");
  });

  $("#popup-single").on("click", function (e) {
    if ($(e.target).is("#popup-single")) {
      $("#popup-single").addClass("hidden");
    }
  });

  //Menu Contact//
  
  $(".contact_btn").on("click", function (e) {
    $("#popup-menu").removeClass("hidden");
  });

    $(".close-contact").on("click", function () {
    $("#popup-menu").addClass("hidden");
  });

    $("#popup-menu").on("click", function (e) {
    if ($(e.target).is("#popup-menu")) {
      $("#popup-menu").addClass("hidden");
    }
  });
})(jQuery);