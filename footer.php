<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package InDret
 */
?>
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="container">
			<div class="site-info">
				<div class="col-sm-4 col-md-4 peu-logos"><a href="https://www.upf.edu/en/" target="new"><img src="<?php bloginfo('template_directory'); ?>/img/logo_upf.png"></a></div>
				<div class="col-sm-4 "><a href="http://empresa.gencat.cat/ca/inici/" target="new"><img src="<?php bloginfo('template_directory'); ?>/img/logo_generalitat.png"></a></div>
				<div class="col-sm-4 "><a href="http://www.mineco.gob.es/" target="new"><img src="<?php bloginfo('template_directory'); ?>/img/logo_ministeri.png"></a></div>
			</div><!-- .site-info -->
		</div><!-- .container -->
	</footer><!-- #colophon -->
	
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
