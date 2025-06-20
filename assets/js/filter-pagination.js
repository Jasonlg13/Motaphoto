jQuery(function ($) {
    let page = 2; // initialise la page à 2
    let loading = false;
    const $loadMoreButton = $('#load-more-posts'); // bouton "Charger plus"
    const $container = $('.thumbnail-container-accueil'); // conteneur des thumbnails

function getFilterValues() {
        return {
            category: $('#category-filter').val(),
            format: $('#format-filter').val(),
            date_order: $('#date-sort').val()
        };
    }

    // Au changement d'un filtre, on fait l'appel AJAX
    $('#category-filter, #format-filter, #date-sort').on('change', function () {
        const { category, format, date_order } = getFilterValues();

        $.ajax({
            url: wp_data.ajax_url,
            type: 'POST',
            data: {
                action: 'filter_photos',  // action côté PHP pour filtrer
                category: category,
                format: format,
                date_order: date_order
            },
            success: function (response) {
                if (response.success) {
                    // Remplacer le contenu des vignettes par la réponse filtrée
                    $('.thumbnail-container-accueil').html(response.data);
                } else {
                    $('.thumbnail-container-accueil').html('<p>Une erreur est survenue.</p>');
                }
            },
            error: function () {
                $('.thumbnail-container-accueil').html('<p>Erreur lors de la requête AJAX.</p>');
            }
        });
    });


    // Gestion du bouton "Charger plus"
    $loadMoreButton.on('click', function () {
        if (!loading) {
            loading = true;
            $loadMoreButton.text('Chargement en cours...');

            const { category, format, date_order } = getFilterValues();

            $.ajax({
                type: 'GET',
                url: wp_data.ajax_url,
                data: {
                    action: 'load_more_posts',
                    page: page,
                    category: category,
                    format: format,
                    dateSort: date_order
                },
                success: function (response) {
                    if (response.trim().length > 0) {
                        $container.append(response);
                        $loadMoreButton.text('Charger plus');
                        page++;
                        loading = false;
                    } else {
                        $loadMoreButton.text('Fin des publications').prop('disabled', true);
                    }
                },
            });
        }
    });
});
