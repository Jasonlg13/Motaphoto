(function ($) {
  $(".contact_btn").click(function () {
    $("#popup-menu").toggleClass("hidden");
    listenclose();
  });
   $(".texte-contact").click(function () {
    $("#popup-single").toggleClass("hidden");
    listenclose();
  });
})(jQuery);

function listenclose() {
  return;
  // Add click listener to document to handle clicks outside .popup-contact
  document.addEventListener('click', function(event) {
    // Check if clicked element or its parents have popup-contact class
    if (!event.target.closest('.popup-contact')) {

      const popupModal = document.querySelector('.popup-overlay');
      if (popupModal && !popupModal.classList.contains('hidden')) {
        popupModal.classList.add('hidden');
      }

    }
  });
}