jQuery(function ($) {
    let page = 1;
    let loading = false;
    const $loadMoreButton = $('#load-more-posts');
    const $container = $('.thumbnail-container-accueil');

    function getFilterValues() {
        return {
            category: $('#category-filter').val(),
            format: $('#format-filter').val(),
            date_order: $('#date-sort').val()
        };
    }

    function searchPhoto() {
        const { category, format, date_order } = getFilterValues();

        $.ajax({
            url: wp_data.ajax_url,
            type: 'POST',
            data: {
                action: 'filter_photos',
                category: category,
                format: format,
                date_order: date_order,
                page: page
            },
            success: function (response) {
                if (response.success) {
                    if (page === 1) {
                        $container.html(response.data);
                    } else {
                        $container.append(response.data);
                    }
                } else {
                    $container.html('<p>Une erreur est survenue.</p>');
                }
                loading = false;
            },
            error: function () {
                $container.html('<p>Erreur lors de la requête AJAX.</p>');
                loading = false;
            }
        });
    }

    // Événement déclenché quand un filtre change
    $('#category-filter, #format-filter, #date-sort').on('change', function () {
        page = 1;
        searchPhoto();
    });

    // Événement déclenché quand on clique sur "Charger plus"
    $loadMoreButton.on('click', function () {
        if (loading) return;
        loading = true;
        page++;
        searchPhoto();
    });
});
