<div id="popup-menu" class="popup-overlay hidden">
	<div class="popup-contact">
		<div class="popup-title">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/contact.png"
			alt="Contact <?php echo bloginfo('contact'); ?>">
			<div class="popup-title"></div>
			<div class="popup-title"></div>
		</div>
		<div class="popup-informations">	
			<?php
				echo do_shortcode('[contact-form-7 id="b43c68c" title="Formulaire de contact"]');
			?>
		</div>	
	</div>
</div>