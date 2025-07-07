jQuery(function ($) {
    let page = 1;
    let loading = false;
    const $loadMoreButton = $('#load-more-posts');
    const $container = $('.thumbnail-container-accueil');

    function getFilterValues() {
        return {
            category: $('#category-filter').val() === 'ALL' ? '' : $('#category-filter').val(),
            format: $('#format-filter').val() === 'ALL' ? '' : $('#format-filter').val(),
            date_order: $('#date-sort').val() === 'ALL' ? '' : $('#date-sort').val()
        };
    }

    function searchPhoto() {
        if (loading) return;
        loading = true;

        // Met à jour le bouton pour indiquer que ça charge
        $loadMoreButton.text('Chargement en cours...').prop('disabled', true);

        const { category, format, date_order } = getFilterValues();

        $.ajax({
            url: wp_data.rest_url + 'custom-api-route/photos', // URL de l'API REST + l'endpoint
            type: 'POST',
            contentType: 'application/json', // Type de contenu au format JSON 
            data: JSON.stringify({ // Objet converti en JSON 
                category: category,
                format: format,
                date_order: date_order,
                page: page
            }),
            success: function (response) {
                if (response && response.html) {
                    if (page === 1) {
                        $container.html(response.html);
                        // Remet le bouton à l’état initial (réactivé)
                        $loadMoreButton.text('Charger plus').prop('disabled', false);
                    } else {
                        $container.append(response.html);
                        // Si on a atteint la dernière page, désactive le bouton
                        if (page >= response.total_pages) {
                            $loadMoreButton.text('Fin des publications').prop('disabled', true);
                        } else {
                            $loadMoreButton.text('Charger plus').prop('disabled', false);
                        }
                    }
                } else {
                    // Pas de résultats (cas rare ici)
                    if (page === 1) {
                        $container.html('<p>Aucune photo trouvée.</p>');
                    }
                    $loadMoreButton.text('Fin des publications').prop('disabled', true);
                }
                loading = false;
            },
            error: function () {
                $container.html('<p>Erreur lors de la requête AJAX.</p>');
                $loadMoreButton.text('Charger plus').prop('disabled', false);
                loading = false;
            }
        });
    }

    // Au changement des filtres, reset la pagination et réactive le bouton
    $('#category-filter, #format-filter, #date-sort').on('change', function () {
        page = 1;
        $loadMoreButton.text('Charger plus').prop('disabled', false);
        searchPhoto();
    });

    // Clique sur "Charger plus"
    $loadMoreButton.on('click', function () {
        if (loading) return;
        page++;
        searchPhoto();
    });

    // Charge initialement la première page
    searchPhoto();
});
console.log('REST URL:', wp_data.rest_url);
console.log('POST URL:', wp_data.rest_url + 'custom-api-route/photos');