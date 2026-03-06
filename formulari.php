<?php

/**
 * Template Name: Formulario
 * @package InDret
 */

get_header(); ?>

<div id="content" class="site-content container-fluid editorial-interior">
	<div id="primary" class="content-area container">
		<main id="main" class="site-main col-md-12" role="main">
			<div class="fitxa">
				<?php while (have_posts()) : the_post(); ?>
					<div class="entry-content">

						<div class="row">
							<div class="col-md-2">
							</div>
							<div class="col-md-8">
								<h1 class="entry-title-interior">
									<?php
									if ($language == "es") {
										printf(esc_html__('Env&iacute;o de originales'));
									} else if ($language == "ca") {
										printf(esc_html__('Enviament d&#39;originals'));
									} else if ($language == "en") {
										printf(esc_html__('Submission form'));
									} ?>
								</h1>
								<div class="separa-petit"></div>
							</div>
							<div class="col-md-2">
							</div>
						</div>

						<div class="row">
							<div class="col-md-2">
								<div class="dates">
								</div>
							</div>
							<div class="col-md-8">
								<div class="costext">
									<?php
									if ($language == "es") {
										echo do_shortcode("[contact-form-7 id='20749' title='Formulario de contacto es']");
									} else if ($language == "ca") {
										echo do_shortcode("[contact-form-7 id='21029' title='Formulario de contacto ca']");
									} else if ($language == "en") {
										echo do_shortcode("[contact-form-7 id='21030' title='Formulario de contacto en']");
									} ?>
								</div>
							</div>
							<div class="col-md-2">
							</div>
						</div>

						<div class="row">
							<div class="col-md-2">
							</div>
							<div class="col-md-8">
								<div class="costext">
									<?php
									if ($language == "es") {
										the_field('nota_es');
									} else if ($language == "ca") {
										the_field('nota_ca');
									} else if ($language == "en") {
										the_field('nota_en');
									} ?>
								</div>
							</div>
							<div class="col-md-2">
							</div>
						</div>

					</div><!-- .entry-content -->
				<?php endwhile; // end of the loop. 
				?>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->
</div>

<?php get_footer(); ?>

<script type="text/javascript">



</script>