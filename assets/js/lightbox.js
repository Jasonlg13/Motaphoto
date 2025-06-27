jQuery(document).ready(function($) {
    $('.right-container').on('click', 'a.photo', function(e){
        e.preventDefault();

        $('.modal-container').addClass('opened');

        const imageSrc = $(this).data('href');                  // Récupère les données depuis le lien cliqué          
        const reference = $(this).data('reference');
        const category = $(this).data('category');

        $('#modal-reference').html(reference);
        $('#modal-category').html(category);
        $('.middle-image').attr('src', imageSrc);

        const prevArrow = $('#prev-arrow-link').clone();        // Gestion des flèches de navigation     
        const nextArrow = $('#next-arrow-link').clone();

        $('.left-arrow').html(prevArrow);
        $('.right-arrow').html(nextArrow);

        const refLeft = $('.left-arrow > a').attr('href');       // Ajout des liens dans les flèches  
        const refRight = $('.right-arrow > a').attr('href');

        $('.left-arrow > a').attr('href', refLeft + '?modal=1');
        $('.right-arrow > a').attr('href', refRight + '?modal=1');

        $('.left-arrow > a').html('<img src="' + themeVars.themeUrl + '/assets/images/photo-prev-arrow.png" alt="Flèche gauche" class="arrow-icon">');      // Ajout des flèches
        $('.right-arrow > a').html('<img src="' + themeVars.themeUrl + '/assets/images/photo-next-arrow.png" alt="Flèche droite" class="arrow-icon">');      
    });

    $('.btn-close').click(function(e){    // Fermeture modale
        e.preventDefault();
        $('.modal-container').removeClass('opened');
    });


    const searchParams = new URLSearchParams(window.location.search);   // Si on clique sur la fleche, on ouvre la modale du lien automatiquement                         
    const modal = searchParams.get('modal');
    if(modal){
        $('.right-container a.photo').first().trigger('click'); 
    }
});
