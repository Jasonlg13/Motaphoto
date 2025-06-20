<?php
get_header();
?>

<div class="hero">
    <?php
    $args_related_photos = array(
        'post_type' => 'photo',
        'posts_per_page' => 1,
        'orderby' => 'rand',
    );

    $related_photos_query = new WP_Query($args_related_photos);
    while ($related_photos_query->have_posts()) :
        $related_photos_query->the_post();
        $post_permalink = get_permalink();
    ?>
    <a href="<?php echo esc_url($post_permalink); ?>">
        <div class="hero-image" style="background-image: url('<?php echo get_the_post_thumbnail_url(); ?>');">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/titre-accueil.png" alt="Titre Accueil">
        </div>
    </a>
    <?php endwhile; ?>

    <?php wp_reset_postdata(); ?>
</div>

<div class="filters-and-sort">  
    <label for="category-filter"></label>
    <select name="category-filter" id="category-filter">
        <option value="ALL">CATÉGORIE</option>
        <?php
        $photo_categories = get_terms('categorie_photo');
        foreach ($photo_categories as $category) {
            echo '<option value="' . $category->slug . '">' . $category->name . '</option>';
        }
        ?>
    </select>

    <label for="format-filter"></label>
    <select name="format-filter" id="format-filter">
        <option value="ALL">FORMAT</option>
        <?php
        $photo_formats = get_terms('format_photo');
        foreach ($photo_formats as $format) {
            echo '<option value="' . $format->slug . '">' . $format->name . '</option>';
        }
        ?>
    </select>

    <label for="date-sort"></label>
    <select name="date-sort" id="date-sort">
        <option value="ALL">TRIER PAR</option>
        <option value="DESC">Du plus récent au plus ancien</option>
        <option value="ASC">Du plus ancien au plus récent</option>
    </select>
</div>

<div id="photo-results">
    <?php include get_template_directory() . '/template_parts/photo_block.php'; ?>
</div>

<?php get_footer();

