<div id="popup-menu" class="popup-overlay hidden">
    <div class="popup-contact">
        <div class="popup-title">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/contact.png"
                 alt="Contact <?php echo bloginfo('name'); ?>">
        </div>
        <div class="popup-informations">
            <?php echo do_shortcode('[contact-form-7 id="8f49349" title="Formulaire de contact"]'); ?>
        </div>
        <span class="close-contact"></span>
    </div>
</div>