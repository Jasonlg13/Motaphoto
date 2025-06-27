	<footer id="footer">
		<?php 
			wp_nav_menu(array('theme_location' => 'footer')); 
		?>
	</footer>
	<?php 
        get_template_part ( '/template_parts/contact'); 
		get_template_part ( '/template_parts/lightbox');
    ?>
<?php wp_footer(); ?>
</body>
</html>