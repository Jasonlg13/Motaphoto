<button id="button-photo">Contact</button>

<div id="popup-single" class="popup-overlay hidden">
	<div class="popup-contact">
		<div class="popup-title">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/contact.png"
			alt="Contact <?php echo bloginfo('contact'); ?>">
			<div class="popup-title"></div>
			<div class="popup-title"></div>
		</div>
		<div class="popup-informations">	
			<?php
				echo do_shortcode('[contact-form-7 id="8f49349" title="Contact-Single"]');
			?>
              <?php
            $reference = get_field('reference');
            if ($reference) {
                ?>
                <script type="text/javascript">
                document.querySelector ("input[name='your-subject']").value= "<?php echo $reference; ?>";
                </script>
                <?php
            }
            ?>
		</div>	
	</div>
</div>