<?php
get_header();
?>

<!-- Section | Lightbox Photo -->
<div class='modal-container'>
    <!-- Bouton fermer -->
    <span class="btn-close">X</span>
    <!-- Fleche -->
    <div class="left-arrow"></div>
    <div>
        <!-- Image | Information de la Photo -->
        <img src="" class="middle-image" />
        <div class="info-photo">
            <span id="modal-reference"></span>
            <span id="modal-category"></span>
        </div>
    </div>
    <!-- Fleche -->
    <div class="right-arrow"></div>
</div>

<main id="main" class="content-area">
    <div class="zone-contenu">
        <div class="left-container">
            <div class="left-contenu">
                <h1><?php the_title(); ?></h1>
                <?php
                $reference = get_field('reference'); /*Je récupère la référence*/
                if ($reference) {
                    echo '<p>Référence : ' . esc_html($reference) . '</p>'; /*Je l'affiche avec un paragraphe Référence accompagné de la $reference*/
                }

                $categorie_photo = get_the_terms(get_the_ID(), 'categorie_photo');
                $current_category_slugs = array();
                if ($categorie_photo) {
                    foreach ($categorie_photo as $category) {
                        $current_category_slugs[] = $category->slug; /*Parcourt les catégories et je récupère l'identifiant de la catégorie*/
                    }
                }

                if ($categorie_photo) {
                    echo '<p>Catégorie : ';
                    $category_names = array();
                    foreach ($categorie_photo as $category) {
                        $category_names[] = esc_html($category->name); 
                    }
                    echo implode(', ', $category_names);
                    echo '</p>';
                }

                $format_photo = get_the_terms(get_the_ID(), 'format_photo');
                if ($format_photo) {
                    echo '<p>Format : ';
                    $format_names = array();
                    foreach ($format_photo as $format_term) {
                        $format_names[] = esc_html($format_term->name);
                    }
                    echo implode(', ', $format_names);
                    echo '</p>';
                }

                $type = get_field('type');
                if ($type) {
                    echo '<p>Type : ' . esc_html($type) . '</p>';
                }

                $date = get_the_date('Y'); 
                if ($date) {
                    echo '<p>Année : ' . esc_html($date) . '</p>';
                }

                ?>
            </div>
        </div>
        <div class="right-container">
            <?php while (have_posts()) : the_post();
                $reference = get_field('reference');
                $categories = get_the_terms(get_the_ID(), 'categorie_photo');
                $category_string = $categories ? implode(', ', wp_list_pluck($categories, 'name')) : '';
                $img_large_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large')[0];
            ?>
                <?php if (has_post_thumbnail()) : ?>
                    <a href="#" class="photo" 
                    data-href="<?php echo esc_url($img_large_url); ?>" 
                    data-reference="<?php echo esc_attr($reference); ?>" 
                    data-category="<?php echo esc_attr($category_string); ?>">
                        <?php the_post_thumbnail('full'); ?>
                    </a>
                    <i class="fas fa-expand-arrows-alt fullscreen-icon"></i>
                <?php endif; ?>
            <?php endwhile; ?>
        </div>
    </div>
    <div class="zone-contact">
    <div class="left-contact">
        <div class="texte-contact">
            <p>Cette photo vous intéresse ?</p>
        </div>
              <div class="bouton-contact">
            <?php include get_template_directory() . '/template_parts/contact-photo.php'; ?>
            <?php
            $reference = get_field('reference');
            if ($reference) {
                echo '<script type="text/javascript">';
                echo 'const acfReferencePhoto = "' . esc_js($reference) . '";';
                echo '</script>';
            }
            ?>
        </div>
    </div>
    <div class="right-contact">
        <?php
        $current_post_id = get_the_ID();
        $args = array(
            'post_type' => 'photo',
            'posts_per_page' => -1,
            'order' => 'ASC',
        );
        $all_photo_posts = get_posts($args);
        $current_post_index = array_search($current_post_id, array_column($all_photo_posts, 'ID')); /* Je récupère l'index de la photo actuelle */
        $prev_post_index = $current_post_index - 1;
        $next_post_index = $current_post_index + 1;
        $prev_post = ($prev_post_index >= 0) ? $all_photo_posts[$prev_post_index] : end($all_photo_posts);
        $next_post = ($next_post_index < count($all_photo_posts)) ? $all_photo_posts[$next_post_index] : reset($all_photo_posts);
        $prev_permalink = get_permalink($prev_post);
        $next_permalink = get_permalink($next_post);
        $prev_thumbnail = get_the_post_thumbnail($prev_post, 'thumbnail');
        $next_thumbnail = get_the_post_thumbnail($next_post, 'thumbnail');
        ?>

        <div class="thumbnail-navigation">                   
            <div class="thumbnail-preview" id="thumbnail-preview">
                <img src="<?php echo esc_url(get_the_post_thumbnail_url($next_post, 'thumbnail')); ?>" alt="Miniature" />
            </div>
            <a href="<?php echo esc_url($prev_permalink); ?>" class="arrow-link" id="prev-arrow-link" data-thumbnail="<?php echo esc_url(get_the_post_thumbnail_url($prev_post, 'thumbnail')); ?>">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/fleche-gauche.png" alt="Précédent" class="arrow-img" /></a>
            <a href="<?php echo esc_url($next_permalink); ?>" class="arrow-link" id="next-arrow-link" data-thumbnail="<?php echo esc_url(get_the_post_thumbnail_url($next_post, 'thumbnail')); ?>">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/fleche-droite.png" alt="Suivant" class="arrow-img" />
            </a>
        </div>
    </div>
</div>

<div class="related-images">
    <h3>VOUS AIMEREZ AUSSI</h3>
    <div class="image-container">
        <?php
        $args_related_photos = array(
            'post_type' => 'photo',
            'posts_per_page' => 2,
            'orderby' => 'rand', // récupère aléatoirement deux photos
            'post__not_in' => array(get_the_ID()), // exclut la photo en cours
            'tax_query' => array(
                array(
                    'taxonomy' => 'categorie_photo',
                    'field' => 'slug',
                    'terms' => $current_category_slugs,
                ),
            ),
        );

        $related_photos_query = new WP_Query($args_related_photos);

        while ($related_photos_query->have_posts()) :
            $related_photos_query->the_post();
        ?>
            <div class="related-image">
                <a href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="image-wrapper">
                            <?php the_post_thumbnail(); ?>
                            <div class="thumbnail-overlay-single">
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
        <?php endwhile; ?>

        <?php wp_reset_postdata(); ?>
    </div>
</div>

</main>

<script> // Finir le « précédent / suivant » sur le template single (miniatures)
document.addEventListener('DOMContentLoaded', function () {
    const preview = document.getElementById('thumbnail-preview').querySelector('img');
    const prevLink = document.getElementById('prev-arrow-link');
    const nextLink = document.getElementById('next-arrow-link');

    const originalThumbnail = preview.src;

    prevLink.addEventListener('mouseenter', () => {
        preview.src = prevLink.dataset.thumbnail;
    });

    nextLink.addEventListener('mouseenter', () => {
        preview.src = nextLink.dataset.thumbnail;
    });

    prevLink.addEventListener('mouseleave', () => {
        preview.src = originalThumbnail;
    });

    nextLink.addEventListener('mouseleave', () => {
        preview.src = originalThumbnail;
    });
});
</script>


<?php get_footer(); ?>
