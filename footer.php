	<footer id="footer">
		<?php 
			wp_nav_menu(array('theme_location' => 'footer')); 
		?>
	</footer>
	<?php 
        get_template_part ( '/template_parts/contact'); 
    ?>
<?php wp_footer(); ?>
</body>
</html>