<?php

/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package InDret
 */

get_header(); ?>

<div class="fons-interior">
	<div class="container">
		<div class="banner invers">
			<h1 class="entry-title-interior"><!--Autor: --><?php the_archive_title(); ?></h1>
		</div>
	</div>
	<div id="content" class="site-content container">
		<div id="primary" class="content-area">
			<main id="main" class="site-main col-md-12" role="main">

				<?php if (have_posts()) : ?>
					<?php /* Start the Loop */ ?>
					<?php while (have_posts()) : the_post(); ?>


						<article id="post-<?php the_ID(); ?>" <?php post_class('col-md-6'); ?>>
							<div class="entry-header altura_entrada_home">
								<div class="edicion"><?php the_field('edicion_gral'); ?></div>
								<div class="categoria">
									<?php
									$nomcategoria = get_field('nombre_area');
									$nomcategoria = str_replace(' ', '', $nomcategoria);
									$nomcategoria = str_replace('í', 'i', $nomcategoria);
									$nomcategoria = str_replace('ú', 'u', $nomcategoria);
									//$nomarea = '';
									if ($language == "en") {
										if ($nomcategoria == 'Derechoprivado') {
											$nomarea = 'Private law';
										} else if ($nomcategoria == 'Criminologia') {
											$nomarea = 'Criminology';
										} else if ($nomcategoria == 'Derechopenal') {
											$nomarea = 'Criminal law';
										} else if ($nomcategoria == 'Publicoyregulatorio') {
											$nomarea = 'Public & regulatory';
										}
									} else if ($language == "es") {
										if ($nomcategoria == 'Derechoprivado') {
											$nomarea = 'Derecho privado';
										} else if ($nomcategoria == 'Criminologia') {
											$nomarea = 'Criminología';
										} else if ($nomcategoria == 'Derechopenal') {
											$nomarea = 'Derecho penal';
										} else if ($nomcategoria == 'Publicoyregulatorio') {
											$nomarea = 'Público y regulatorio';
										}
									} else if ($language == "ca") {
										if ($nomcategoria == 'Derechoprivado') {
											$nomarea = 'Dret privat';
										} else if ($nomcategoria == 'Criminologia') {
											$nomarea = 'Criminologia';
										} else if ($nomcategoria == 'Derechopenal') {
											$nomarea = 'Dret penal';
										} else if ($nomcategoria == 'Publicoyregulatorio') {
											$nomarea = 'Públic i regulatori';
										}
									}
									?>
									<?php echo esc_html($nomarea); ?>
								</div>
								<?php the_title(sprintf('<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h1>'); ?>
								<div class="peu_entrada_home">
									_<br>
									<div class="autor">
										<?php $terms = indret_get_post_authors();
										if (!empty($terms)) : ?><span class="pertallarcoma"><?php foreach ($terms as $term) : ?><a href="<?php echo esc_url(get_term_link($term)); ?>"><?php echo esc_html($term->name); ?>, </a><?php endforeach; ?></span><?php endif; ?>
									</div>
									<div class="xarxes_soc">
										<span class="descargas <?php echo get_the_ID(); ?>">
											<script>
												var descargas_old = '<?php the_field('ranking_descargas'); ?>';
												if (descargas_old == '' || descargas_old == 'NULL') {
													descargas_old = 0;
													//document.write(descargas_old);
													jQuery(".<?php echo get_the_ID(); ?>").html(descargas_old);
												} else {
													jQuery(".<?php echo get_the_ID(); ?>").html(descargas_old);
												}
											</script>
										</span>
										<?php
										if ($language == "en") {
											echo 'downloads';
										} else if ($language == "es") {
											echo 'descargas';
										} else if ($language == "ca") {
											echo 'descàrregues';
										} ?>
									</div>
									<div class="xarxes_soc botons">
										<?php
										if ($language == "en") {
											echo 'Share';
										} else if ($language == "es") {
											echo 'Compartir';
										} else if ($language == "ca") {
											echo 'Compartir';
										} ?>
										<ul>
											<li class="xs_face"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="new"></a></li>
											<li class="xs_twit"><a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>" target="new"></a></li>
											<li class="xs_linked"><a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>&title=Revista%20InDret&source=indret.com" target="new"></a></li>
											<li class="xs_mail"><a href="mailto:?subject=InDret, Revista para el Análisis del Derecho&body=He encontrado este enlace: <?php the_permalink(); ?>"></a>
											</li>
										</ul>
									</div>
								</div>
							</div><!-- .entry-header -->
						</article><!-- #post-## -->


					<?php endwhile; ?>
					<?php start_the_posts_navigation(); ?>
				<?php else : ?>
					<?php get_template_part('template-parts/content', 'none'); ?>
				<?php endif; ?>

				<div class="row estret">
					<div class="col-md-2">
						<!--<div class="wp-pagenavi"><span class="pages">Portada</span></div>-->
					</div>
					<div class="col-md-10 paginador"><?php wp_pagenavi(); ?></div>
				</div>

			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!-- #content -->

	<?php get_footer(); ?>

	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('.pertallarcoma').html(function(_, txt) {
				//console.log(txt);
				return txt.slice(0, -6);
			});
		});
	</script>