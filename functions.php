<?php

//http://modaphoto.local/wp-admin/admin-ajax.php?action=load_more_posts

function theme_enqueue_styles() {
    wp_enqueue_style( 'motaphoto-style', get_template_directory_uri() . '/assets/css/front.css', array(), '1.0', 'all');  
    wp_enqueue_script( 'motaphoto-scripts', get_template_directory_uri() . '/assets/js/script.js', array('jquery'), '1.0.0', true );
    wp_enqueue_script('filter-pagination', get_template_directory_uri() . '/assets/js/filter-pagination.js', array('jquery'), '1.0', true);
    wp_localize_script('filter-pagination', 'wp_data', array('ajax_url' => admin_url('admin-ajax.php')));
    wp_enqueue_script('lightbox', get_template_directory_uri() . '/assets/js/lightbox.js', array('jquery'), '1.0', true);
    wp_localize_script('lightbox', 'themeVars', array('themeUrl' => get_template_directory_uri()
));
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles',);

add_theme_support( 'post-thumbnails' );

add_theme_support( 'title-tag' );

function register_my_menu(){
    register_nav_menu('main', "Menu principal");
    register_nav_menu('footer', "Menu pied de page");
 }
 add_action('after_setup_theme', 'register_my_menu');

function load_more_posts() {
    $paged = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $category = $_GET['category'] ?? 'ALL';
    $format = $_GET['format'] ?? 'ALL';
    $dateSort = $_GET['dateSort'] ?? 'DESC';

    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => 12,
        'paged' => $paged,
        'orderby' => 'date',
        'order' => $dateSort !== 'ALL' ? $dateSort : 'DESC',
    );

    $tax_query = array();

    if ($category !== 'ALL') {
        $tax_query[] = array(
            'taxonomy' => 'categorie_photo',
            'field' => 'slug',
            'terms' => $category,
        );
    }

    if ($format !== 'ALL') {
        $tax_query[] = array(
            'taxonomy' => 'format_photo',
            'field' => 'slug',
            'terms' => $format,
        );
    }

    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post(); ?>

            <div class="custom-post-thumbnail">
                <a href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="thumbnail-wrapper">
                            <?php the_post_thumbnail(); ?>
                            <div class="thumbnail-overlay">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon_eye.png" alt="Icône de l'œil">
                                <i class="fas fa-expand-arrows-alt fullscreen-icon"></i>
                                <?php
                                $related_reference = get_field('reference');
                                $related_categorie_photo = get_the_terms(get_the_ID(), 'categorie_photo');
                                $related_category_names = array();

                                if ($related_categorie_photo) {
                                    foreach ($related_categorie_photo as $category) {
                                        $related_category_names[] = esc_html($category->name);
                                    }
                                }
                                ?>
                                <div class="photo-info">
                                    <div class="photo-info-left">
                                        <p><?php echo esc_html($related_reference); ?></p>
                                    </div>
                                    <div class="photo-info-right">
                                        <p><?php echo implode(', ', $related_category_names); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </a>
            </div>

        <?php endwhile;
        wp_reset_postdata();
    endif;

    wp_die(); 
}

function filter_photos() {
    ob_start();

    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;

    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => 12,
        'paged' => $paged,
        'orderby' => 'date',
        'order' => $_POST['date_order'] !== 'ALL' ? $_POST['date_order'] : 'DESC',   // Ajout de paged dans filter_photos() //
    );

    $tax_query = array();

    if ($_POST['category'] !== 'ALL') {
        $tax_query[] = array(
            'taxonomy' => 'categorie_photo',
            'field' => 'slug',
            'terms' => $_POST['category'],
        );
    }

    if ($_POST['format'] !== 'ALL') {
        $tax_query[] = array(
            'taxonomy' => 'format_photo',
            'field' => 'slug',
            'terms' => $_POST['format'],
        );
    }

    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post(); ?>

            <div class="custom-post-thumbnail">
                <a href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="thumbnail-wrapper">
                            <?php the_post_thumbnail(); ?>
                            <div class="thumbnail-overlay">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon_eye.png" alt="Icône de l'œil">
                                <i class="fas fa-expand-arrows-alt fullscreen-icon"></i>
                                <?php
                                $related_reference = get_field('reference');
                                $related_categorie_photo = get_the_terms(get_the_ID(), 'categorie_photo');
                                $related_category_names = array();

                                if ($related_categorie_photo) {
                                    foreach ($related_categorie_photo as $category) {
                                        $related_category_names[] = esc_html($category->name);
                                    }
                                }
                                ?>
                                <div class="photo-info">
                                    <div class="photo-info-left">
                                        <p><?php echo esc_html($related_reference); ?></p>
                                    </div>
                                    <div class="photo-info-right">
                                        <p><?php echo implode(', ', $related_category_names); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </a>
            </div>

        <?php endwhile;
        wp_reset_postdata();
    else :
        echo '<p>Aucune photo trouvée.</p>';
    endif;

    wp_send_json_success(ob_get_clean());
}

function get_filtered_photos(WP_REST_Request $request) {  //API//
    $paged = $request->get_param('page') ?? 1;

    $args = [
        'post_type'      => 'photo',
        'posts_per_page' => 12,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    $query = new WP_Query($args);
    $photos = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $photo_id = get_the_ID();

            $photos[] = [
                'id'    => $photo_id,
                'title' => get_the_title(),
                'link'  => get_permalink(),
                'image' => get_the_post_thumbnail_url($photo_id, 'large'),
            ];
        }
        wp_reset_postdata();
    }

    return rest_ensure_response([
        'photos'      => $photos,
        'total'       => $query->found_posts,
        'total_pages' => $query->max_num_pages,
        'current_page'=> (int) $paged,
    ]);
}

function register_photo_api_route() {
    register_rest_route(
        'custom-api-route',  // namespace
        '/photos',           // endpoint
        [
            'methods'  => 'GET',
            'callback' => 'get_filtered_photos',
            'permission_callback' => '__return_true', // accessible sans auth
        ]
    );
}

add_action('rest_api_init', 'register_photo_api_route'); // FIN API //
add_action('wp_ajax_load_more_posts', 'load_more_posts'); /* associe la fonction 'load_more_posts' à l'action AJAX 'wp_ajax_load_more_posts'*/
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts'); /* associe la fonction 'load_more_posts' à l'action AJAX 'wp_ajax_nopriv_load_more_posts' */
add_action('wp_ajax_filter_photos', 'filter_photos');
add_action('wp_ajax_nopriv_filter_photos', 'filter_photos');

?>