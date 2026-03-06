<?php

/**
 * Template Name: Sumario
 * @package InDret
 */

get_header(); ?>

<style type="text/css" media="screen">
	.edicion {
		font-size: 24px;
	}

	.autor {
		font-size: 18px;
		color: #000;
		height: 95px;
	}
</style>

<div id="content" class="site-content container-fluid editorial-interior sumari">
	<div id="primary" class="content-area container">
		<main id="main" class="site-main col-md-12" role="main">
			<div class="fitxa">
				<div class="entry-content">
					<div class="row">
						<!-- <div class="col-md-2">
						</div> -->
						<div class="col-md-12">
							<h1 class="entry-title-interior">
								<?php if ($language == "en") { ?>
									Table of contents
								<?php } else if ($language == "es") { ?>
									Índice
								<?php } else if ($language == "ca") { ?>
									Index
								<?php } ?> <?php echo $edicio; ?></h1>
							<?php if (get_field('pdf_sumario')) : ?>
								<div class="costext amunt"><a href="<?php the_field('pdf_sumario'); ?>" target="new">
										<?php if ($language == "en") { ?>
											Download full edition PDF
										<?php } else if ($language == "es") { ?>
											Descargar PDF completo
										<?php } else if ($language == "ca") { ?>
											Descarregar PDF complert
										<?php } ?>
									</a></div>
							<?php endif; ?>
						</div>
						<!-- <div class="col-md-2">
						</div> -->
					</div>

					<div class="graella">
						<!-- Editorials -->
						<!--/*****************************************************************/-->
						<!--/*****************************************************************/-->
						<!--/*****************************************************************/-->

						<?php
						// query
						$the_query = new WP_Query(array(
							'post_type'		 => 'post',
							'posts_per_page' => -1,
							'meta_key'		 => 'edicion',
							'meta_value'	 => 'editorial',
							'tax_query' => array(
								'relation' => 'AND',
								array(
									'taxonomy' => 'category',
									'field'    => 'slug',
									'terms'    => array($edicio)
								),
							),
							'meta_query' => array(
								array(
									'key'      => 'idioma',
									'value'    => 'es'
								),
							),
						));
						?>
						<div class="row">
							<?php if ($the_query->have_posts()) : ?>
								<div class="col-md-12">
									<div class="costext_titol">
										<?php if ($language == "en") { ?>
											Editorials
										<?php } else if ($language == "es") { ?>
											Editoriales
										<?php } else if ($language == "ca") { ?>
											Editorials
										<?php } ?>
									</div>
								</div>
								<div class="col-md-12">
									<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
										<div class="article">
											<div class="edicion"><?php the_field('edicion_gral'); ?></div>
											<?php the_title(sprintf('<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h1>'); ?>
											<div class="autor"><?php $terms = get_field('autor_id');
																if ($terms) : ?><span class="pertallarcoma"><?php foreach ($terms as $term) : ?><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?></span><?php endif; ?>
											</div>
										</div>
									<?php endwhile; ?>
								</div>
							<?php endif; ?>
						</div>
						<?php wp_reset_query(); ?>
						<!-- Fi Editorials -->

						<!--/************************************************************************************************************************************************************************************************/-->
						<!--/************************************************************************************************************************************************************************************************/-->
						<!--/************************************************************************************************************************************************************************************************/-->

						<?php
						if (get_field('ordre') == 'ordre1') {
							//echo ('Privat, Penal, Criminal, Public i regulatori');
						?>

							<!-- Derecho privado -->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<?php
							// query
							$the_query = new WP_Query(array(
								'post_type'		 => 'post',
								'posts_per_page' => -1,
								'meta_key'		 => 'nombre_area',
								'meta_value'	 => 'Derecho privado',

								'tax_query' => array(
									array(
										'taxonomy' => 'category',
										'field'    => 'slug',
										'terms'    => array($edicio)
									),
								),
								'meta_query' => array(
									'relation' => 'AND',
									array(
										'key'      => 'idioma',
										'value'    => 'es',
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Recensions, Book reviews, Recensiones',
										'compare' => '!='
									),
									array(
										'key'		 => 'edicion',
										'value'	 => 'editorial',
										'compare' => '!='
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Actualitat, Recent developments in Law, Actualidad',
										'compare' => '!='
									),
								),
							));
							?>
							<?php if ($the_query->have_posts()) : ?>
								<div class="row">
									<div class="col-md-12">
										<div class="costext_titol">
											<?php if ($language == "en") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechoprivado/?edicion=<?php echo $edicio; ?>">Private law</a>
											<?php } else if ($language == "es") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechoprivado/?edicion=<?php echo $edicio; ?>">Privado</a>
											<?php } else if ($language == "ca") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechoprivado/?edicion=<?php echo $edicio; ?>">Privat</a>
											<?php } ?>
										</div>
									</div>
									<div class="col-md-12">
										<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
											<div class="article">
												<div class="edicion"><?php the_field('edicion_gral'); ?></div>
												<?php the_title(sprintf('<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h1>'); ?>
												<div class="autor"><?php $terms = get_field('autor_id');
																	if ($terms) : ?><span class="pertallarcoma"><?php foreach ($terms as $term) : ?><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?></span><?php endif; ?></div>
											</div>
										<?php endwhile; ?>
									</div>
								</div>
							<?php endif; ?>
							<?php wp_reset_query(); ?>
							<!-- Fi Derecho privado -->

							<!-- Derecho penal -->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<?php
							// query
							$the_query = new WP_Query(array(
								'post_type'		 => 'post',
								'posts_per_page' => -1,
								'meta_key'		 => 'nombre_area',
								'meta_value'	 => 'Derecho penal',
								'tax_query' => array(
									array(
										'taxonomy' => 'category',
										'field'    => 'slug',
										'terms'    => array($edicio)
									),
								),
								'meta_query' => array(
									'relation' => 'AND',
									array(
										'key'      => 'idioma',
										'value'    => 'es',
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Recensions, Book reviews, Recensiones',
										'compare' => '!='
									),
									array(
										'key'		 => 'edicion',
										'value'	 => 'editorial',
										'compare' => '!='
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Actualitat, Recent developments in Law, Actualidad',
										'compare' => '!='
									),
								),
							));
							?>
							<?php if ($the_query->have_posts()) : ?>
								<div class="row">
									<div class="col-md-12">
										<div class="costext_titol">
											<?php if ($language == "en") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechopenal/?edicion=<?php echo $edicio; ?>">Criminal law</a>
											<?php } else if ($language == "es") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechopenal/?edicion=<?php echo $edicio; ?>">Penal</a>
											<?php } else if ($language == "ca") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechopenal/?edicion=<?php echo $edicio; ?>">Penal</a>
											<?php } ?>
										</div>
									</div>
									<div class="col-md-12">
										<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
											<div class="article">
												<div class="edicion"><?php the_field('edicion_gral'); ?></div>
												<?php the_title(sprintf('<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h1>'); ?>
												<div class="autor"><?php $terms = get_field('autor_id');
																	if ($terms) : ?><span class="pertallarcoma"><?php foreach ($terms as $term) : ?><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?></span><?php endif; ?></div>
											</div>
										<?php endwhile; ?>
									</div>
								</div>
							<?php endif; ?>
							<?php wp_reset_query(); ?>
							<!-- Fi Derecho penal -->

							<!-- Criminilogía -->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<?php
							// query
							$the_query = new WP_Query(array(
								'post_type'		 => 'post',
								'posts_per_page' => -1,
								'meta_key'		 => 'nombre_area',
								'meta_value'	 => 'Criminologia',
								'tax_query' => array(
									array(
										'taxonomy' => 'category',
										'field'    => 'slug',
										'terms'    => array($edicio)
									),
								),
								'meta_query' => array(
									'relation' => 'AND',
									array(
										'key'      => 'idioma',
										'value'    => 'es',
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Recensions, Book reviews, Recensiones',
										'compare' => '!='
									),
									array(
										'key'		 => 'edicion',
										'value'	 => 'editorial',
										'compare' => '!='
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Actualitat, Recent developments in Law, Actualidad',
										'compare' => '!='
									),
								),
							));
							?>
							<?php if ($the_query->have_posts()) : ?>
								<div class="row">
									<div class="col-md-12">
										<div class="costext_titol">
											<?php if ($language == "en") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Criminologia/?edicion=<?php echo $edicio; ?>">Criminology</a>
											<?php } else if ($language == "es") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Criminologia/?edicion=<?php echo $edicio; ?>">Criminología</a>
											<?php } else if ($language == "ca") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Criminologia/?edicion=<?php echo $edicio; ?>">Criminologia</a>
											<?php } ?>
										</div>
									</div>
									<div class="col-md-12">
										<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
											<div class="article">
												<div class="edicion"><?php the_field('edicion_gral'); ?></div>
												<?php the_title(sprintf('<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h1>'); ?>
												<div class="autor"><?php $terms = get_field('autor_id');
																	if ($terms) : ?><span class="pertallarcoma"><?php foreach ($terms as $term) : ?><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?></span><?php endif; ?></div>
											</div>
										<?php endwhile; ?>
									</div>
								</div>
							<?php endif; ?>
							<?php wp_reset_query(); ?>
							<!-- Fi Criminilogía -->

							<!-- Público y regulatorio -->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<?php
							// query
							$the_query = new WP_Query(array(
								'post_type'		 => 'post',
								'posts_per_page' => -1,
								'meta_key'		 => 'nombre_area',
								'meta_value'	 => 'Público y regulatorio',
								'tax_query' => array(
									array(
										'taxonomy' => 'category',
										'field'    => 'slug',
										'terms'    => array($edicio)
									),
								),
								'meta_query' => array(
									'relation' => 'AND',
									array(
										'key'      => 'idioma',
										'value'    => 'es',
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Recensions, Book reviews, Recensiones',
										'compare' => '!='
									),
									array(
										'key'		 => 'edicion',
										'value'	 => 'editorial',
										'compare' => '!='
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Actualitat, Recent developments in Law, Actualidad',
										'compare' => '!='
									),
								),
							));
							?>
							<?php if ($the_query->have_posts()) : ?>
								<div class="row">
									<div class="col-md-12">
										<div class="costext_titol">
											<?php if ($language == "en") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Publicoyregulatorio/?edicion=<?php echo $edicio; ?>">Administrative law</a>
											<?php } else if ($language == "es") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Publicoyregulatorio/?edicion=<?php echo $edicio; ?>">Público y regulatorio</a>
											<?php } else if ($language == "ca") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Publicoyregulatorio/?edicion=<?php echo $edicio; ?>">Públic i regulatori</a>
											<?php } ?>
										</div>
									</div>
									<div class="col-md-12">
										<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
											<div class="article">
												<div class="edicion"><?php the_field('edicion_gral'); ?></div>
												<?php the_title(sprintf('<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h1>'); ?>
												<div class="autor"><?php $terms = get_field('autor_id');
																	if ($terms) : ?><span class="pertallarcoma"><?php foreach ($terms as $term) : ?><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?></span><?php endif; ?></div>
											</div>
										<?php endwhile; ?>
									</div>
								</div>
							<?php endif; ?>
							<?php wp_reset_query(); ?>
							<!-- Fi Publico y regulatorio -->

						<?php } else if (get_field('ordre') == 'ordre2') {
							//echo ('Penal, Privat, Criminal, Public i regulatori');
						?>

							<!-- Derecho penal -->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<?php
							// query
							$the_query = new WP_Query(array(
								'post_type'		 => 'post',
								'posts_per_page' => -1,
								'meta_key'		 => 'nombre_area',
								'meta_value'	 => 'Derecho penal',
								'tax_query' => array(
									array(
										'taxonomy' => 'category',
										'field'    => 'slug',
										'terms'    => array($edicio)
									),
								),
								'meta_query' => array(
									'relation' => 'AND',
									array(
										'key'      => 'idioma',
										'value'    => 'es',
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Recensions, Book reviews, Recensiones',
										'compare' => '!='
									),
									array(
										'key'		 => 'edicion',
										'value'	 => 'editorial',
										'compare' => '!='
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Actualitat, Recent developments in Law, Actualidad',
										'compare' => '!='
									),
								),
							));
							?>
							<?php if ($the_query->have_posts()) : ?>
								<div class="row">
									<div class="col-md-12">
										<div class="costext_titol">
											<?php if ($language == "en") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechopenal/?edicion=<?php echo $edicio; ?>">Criminal law</a>
											<?php } else if ($language == "es") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechopenal/?edicion=<?php echo $edicio; ?>">Penal</a>
											<?php } else if ($language == "ca") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechopenal/?edicion=<?php echo $edicio; ?>">Penal</a>
											<?php } ?>
										</div>
									</div>
									<div class="col-md-12">
										<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
											<div class="article">
												<div class="edicion"><?php the_field('edicion_gral'); ?></div>
												<?php the_title(sprintf('<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h1>'); ?>
												<div class="autor"><?php $terms = get_field('autor_id');
																	if ($terms) : ?><span class="pertallarcoma"><?php foreach ($terms as $term) : ?><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?></span><?php endif; ?></div>
											</div>
										<?php endwhile; ?>
									</div>
								</div>
							<?php endif; ?>
							<?php wp_reset_query(); ?>
							<!-- Fi Derecho penal -->

							<!-- Derecho privado -->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<?php
							// query
							$the_query = new WP_Query(array(
								'post_type'		 => 'post',
								'posts_per_page' => -1,
								'meta_key'		 => 'nombre_area',
								'meta_value'	 => 'Derecho privado',

								'tax_query' => array(
									array(
										'taxonomy' => 'category',
										'field'    => 'slug',
										'terms'    => array($edicio)
									),
								),
								'meta_query' => array(
									'relation' => 'AND',
									array(
										'key'      => 'idioma',
										'value'    => 'es',
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Recensions, Book reviews, Recensiones',
										'compare' => '!='
									),
									array(
										'key'		 => 'edicion',
										'value'	 => 'editorial',
										'compare' => '!='
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Actualitat, Recent developments in Law, Actualidad',
										'compare' => '!='
									),
								),
							));
							?>
							<?php if ($the_query->have_posts()) : ?>
								<div class="row">
									<div class="col-md-12">
										<div class="costext_titol">
											<?php if ($language == "en") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechoprivado/?edicion=<?php echo $edicio; ?>">Private law</a>
											<?php } else if ($language == "es") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechoprivado/?edicion=<?php echo $edicio; ?>">Privado</a>
											<?php } else if ($language == "ca") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechoprivado/?edicion=<?php echo $edicio; ?>">Privat</a>
											<?php } ?>
										</div>
									</div>
									<div class="col-md-12">
										<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
											<div class="article">
												<div class="edicion"><?php the_field('edicion_gral'); ?></div>
												<?php the_title(sprintf('<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h1>'); ?>
												<div class="autor"><?php $terms = get_field('autor_id');
																	if ($terms) : ?><span class="pertallarcoma"><?php foreach ($terms as $term) : ?><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?></span><?php endif; ?></div>
											</div>
										<?php endwhile; ?>
									</div>
								</div>
							<?php endif; ?>
							<?php wp_reset_query(); ?>
							<!-- Fi Derecho privado -->


							<!-- Criminilogía -->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<?php
							// query
							$the_query = new WP_Query(array(
								'post_type'		 => 'post',
								'posts_per_page' => -1,
								'meta_key'		 => 'nombre_area',
								'meta_value'	 => 'Criminologia',
								'tax_query' => array(
									array(
										'taxonomy' => 'category',
										'field'    => 'slug',
										'terms'    => array($edicio)
									),
								),
								'meta_query' => array(
									'relation' => 'AND',
									array(
										'key'      => 'idioma',
										'value'    => 'es',
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Recensions, Book reviews, Recensiones',
										'compare' => '!='
									),
									array(
										'key'		 => 'edicion',
										'value'	 => 'editorial',
										'compare' => '!='
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Actualitat, Recent developments in Law, Actualidad',
										'compare' => '!='
									),
								),
							));
							?>
							<?php if ($the_query->have_posts()) : ?>
								<div class="row">
									<div class="col-md-12">
										<div class="costext_titol">
											<?php if ($language == "en") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Criminologia/?edicion=<?php echo $edicio; ?>">Criminology</a>
											<?php } else if ($language == "es") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Criminologia/?edicion=<?php echo $edicio; ?>">Criminología</a>
											<?php } else if ($language == "ca") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Criminologia/?edicion=<?php echo $edicio; ?>">Criminologia</a>
											<?php } ?>
										</div>
									</div>
									<div class="col-md-12">
										<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
											<div class="article">
												<div class="edicion"><?php the_field('edicion_gral'); ?></div>
												<?php the_title(sprintf('<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h1>'); ?>
												<div class="autor"><?php $terms = get_field('autor_id');
																	if ($terms) : ?><span class="pertallarcoma"><?php foreach ($terms as $term) : ?><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?></span><?php endif; ?></div>
											</div>
										<?php endwhile; ?>
									</div>
								</div>
							<?php endif; ?>
							<?php wp_reset_query(); ?>
							<!-- Fi Criminilogía -->

							<!-- Público y regulatorio -->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<?php
							// query
							$the_query = new WP_Query(array(
								'post_type'		 => 'post',
								'posts_per_page' => -1,
								'meta_key'		 => 'nombre_area',
								'meta_value'	 => 'Público y regulatorio',
								'tax_query' => array(
									array(
										'taxonomy' => 'category',
										'field'    => 'slug',
										'terms'    => array($edicio)
									),
								),
								'meta_query' => array(
									'relation' => 'AND',
									array(
										'key'      => 'idioma',
										'value'    => 'es',
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Recensions, Book reviews, Recensiones',
										'compare' => '!='
									),
									array(
										'key'		 => 'edicion',
										'value'	 => 'editorial',
										'compare' => '!='
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Actualitat, Recent developments in Law, Actualidad',
										'compare' => '!='
									),
								),
							));
							?>
							<?php if ($the_query->have_posts()) : ?>
								<div class="row">
									<div class="col-md-12">
										<div class="costext_titol">
											<?php if ($language == "en") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Publicoyregulatorio/?edicion=<?php echo $edicio; ?>">Administrative law</a>
											<?php } else if ($language == "es") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Publicoyregulatorio/?edicion=<?php echo $edicio; ?>">Público y regulatorio</a>
											<?php } else if ($language == "ca") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Publicoyregulatorio/?edicion=<?php echo $edicio; ?>">Públic i regulatori</a>
											<?php } ?>
										</div>
									</div>
									<div class="col-md-12">
										<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
											<div class="article">
												<div class="edicion"><?php the_field('edicion_gral'); ?></div>
												<?php the_title(sprintf('<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h1>'); ?>
												<div class="autor"><?php $terms = get_field('autor_id');
																	if ($terms) : ?><span class="pertallarcoma"><?php foreach ($terms as $term) : ?><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?></span><?php endif; ?></div>
											</div>
										<?php endwhile; ?>
									</div>
								</div>
							<?php endif; ?>
							<?php wp_reset_query(); ?>
							<!-- Fi Publico y regulatorio -->

						<?php } else if (get_field('ordre') == 'ordre3') {
							//echo ('Criminal, Privat, Penal, Public i regulatori');
						?>

							<!-- Criminilogía -->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<?php
							// query
							$the_query = new WP_Query(array(
								'post_type'		 => 'post',
								'posts_per_page' => -1,
								'meta_key'		 => 'nombre_area',
								'meta_value'	 => 'Criminologia',
								'tax_query' => array(
									array(
										'taxonomy' => 'category',
										'field'    => 'slug',
										'terms'    => array($edicio)
									),
								),
								'meta_query' => array(
									'relation' => 'AND',
									array(
										'key'      => 'idioma',
										'value'    => 'es',
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Recensions, Book reviews, Recensiones',
										'compare' => '!='
									),
									array(
										'key'		 => 'edicion',
										'value'	 => 'editorial',
										'compare' => '!='
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Actualitat, Recent developments in Law, Actualidad',
										'compare' => '!='
									),
								),
							));
							?>
							<?php if ($the_query->have_posts()) : ?>
								<div class="row">
									<div class="col-md-12">
										<div class="costext_titol">
											<?php if ($language == "en") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Criminologia/?edicion=<?php echo $edicio; ?>">Criminology</a>
											<?php } else if ($language == "es") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Criminologia/?edicion=<?php echo $edicio; ?>">Criminología</a>
											<?php } else if ($language == "ca") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Criminologia/?edicion=<?php echo $edicio; ?>">Criminologia</a>
											<?php } ?>
										</div>
									</div>
									<div class="col-md-12">
										<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
											<div class="article">
												<div class="edicion"><?php the_field('edicion_gral'); ?></div>
												<?php the_title(sprintf('<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h1>'); ?>
												<div class="autor"><?php $terms = get_field('autor_id');
																	if ($terms) : ?><span class="pertallarcoma"><?php foreach ($terms as $term) : ?><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?></span><?php endif; ?></div>
											</div>
										<?php endwhile; ?>
									</div>
								</div>
							<?php endif; ?>
							<?php wp_reset_query(); ?>
							<!-- Fi Criminilogía -->

							<!-- Derecho privado -->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<?php
							// query
							$the_query = new WP_Query(array(
								'post_type'		 => 'post',
								'posts_per_page' => -1,
								'meta_key'		 => 'nombre_area',
								'meta_value'	 => 'Derecho privado',
								'tax_query' => array(
									array(
										'taxonomy' => 'category',
										'field'    => 'slug',
										'terms'    => array($edicio)
									),
								),
								'meta_query' => array(
									'relation' => 'AND',
									array(
										'key'      => 'idioma',
										'value'    => 'es',
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Recensions, Book reviews, Recensiones',
										'compare' => '!='
									),
									array(
										'key'		 => 'edicion',
										'value'	 => 'editorial',
										'compare' => '!='
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Actualitat, Recent developments in Law, Actualidad',
										'compare' => '!='
									),
								),
							));
							?>
							<?php if ($the_query->have_posts()) : ?>
								<div class="row">
									<div class="col-md-12">
										<div class="costext_titol">
											<?php if ($language == "en") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechoprivado/?edicion=<?php echo $edicio; ?>">Private law</a>
											<?php } else if ($language == "es") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechoprivado/?edicion=<?php echo $edicio; ?>">Privado</a>
											<?php } else if ($language == "ca") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechoprivado/?edicion=<?php echo $edicio; ?>">Privat</a>
											<?php } ?>
										</div>
									</div>
									<div class="col-md-12">
										<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
											<div class="article">
												<div class="edicion"><?php the_field('edicion_gral'); ?></div>
												<?php the_title(sprintf('<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h1>'); ?>
												<div class="autor"><?php $terms = get_field('autor_id');
																	if ($terms) : ?><span class="pertallarcoma"><?php foreach ($terms as $term) : ?><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?></span><?php endif; ?></div>
											</div>
										<?php endwhile; ?>
									</div>
								</div>
							<?php endif; ?>
							<?php wp_reset_query(); ?>
							<!-- Fi Derecho privado -->

							<!-- Derecho penal -->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<?php
							// query
							$the_query = new WP_Query(array(
								'post_type'		 => 'post',
								'posts_per_page' => -1,
								'meta_key'		 => 'nombre_area',
								'meta_value'	 => 'Derecho penal',
								'tax_query' => array(
									array(
										'taxonomy' => 'category',
										'field'    => 'slug',
										'terms'    => array($edicio)
									),
								),
								'meta_query' => array(
									'relation' => 'AND',
									array(
										'key'      => 'idioma',
										'value'    => 'es',
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Recensions, Book reviews, Recensiones',
										'compare' => '!='
									),
									array(
										'key'		 => 'edicion',
										'value'	 => 'editorial',
										'compare' => '!='
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Actualitat, Recent developments in Law, Actualidad',
										'compare' => '!='
									),
								),
							));
							?>
							<?php if ($the_query->have_posts()) : ?>
								<div class="row">
									<div class="col-md-12">
										<div class="costext_titol">
											<?php if ($language == "en") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechopenal/?edicion=<?php echo $edicio; ?>">Criminal law</a>
											<?php } else if ($language == "es") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechopenal/?edicion=<?php echo $edicio; ?>">Penal</a>
											<?php } else if ($language == "ca") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechopenal/?edicion=<?php echo $edicio; ?>">Penal</a>
											<?php } ?>
										</div>
									</div>
									<div class="col-md-12">
										<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
											<div class="edicion"><?php the_field('edicion_gral'); ?></div>
											<div class="article">
												<?php the_title(sprintf('<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h1>'); ?>
												<div class="autor"><?php $terms = get_field('autor_id');
																	if ($terms) : ?><span class="pertallarcoma"><?php foreach ($terms as $term) : ?><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?></span><?php endif; ?></div>
											</div>
										<?php endwhile; ?>
									</div>
								</div>
							<?php endif; ?>
							<?php wp_reset_query(); ?>
							<!-- Fi Derecho penal -->


							<!-- Público y regulatorio -->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<?php
							// query
							$the_query = new WP_Query(array(
								'post_type'		 => 'post',
								'posts_per_page' => -1,
								'meta_key'		 => 'nombre_area',
								'meta_value'	 => 'Público y regulatorio',
								'tax_query' => array(
									array(
										'taxonomy' => 'category',
										'field'    => 'slug',
										'terms'    => array($edicio)
									),
								),
								'meta_query' => array(
									'relation' => 'AND',
									array(
										'key'      => 'idioma',
										'value'    => 'es',
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Recensions, Book reviews, Recensiones',
										'compare' => '!='
									),
									array(
										'key'		 => 'edicion',
										'value'	 => 'editorial',
										'compare' => '!='
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Actualitat, Recent developments in Law, Actualidad',
										'compare' => '!='
									),
								),
							));
							?>
							<?php if ($the_query->have_posts()) : ?>
								<div class="row">
									<div class="col-md-12">
										<div class="costext_titol">
											<?php if ($language == "en") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Publicoyregulatorio/?edicion=<?php echo $edicio; ?>">Administrative law</a>
											<?php } else if ($language == "es") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Publicoyregulatorio/?edicion=<?php echo $edicio; ?>">Público y regulatorio</a>
											<?php } else if ($language == "ca") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Publicoyregulatorio/?edicion=<?php echo $edicio; ?>">Públic i regulatori</a>
											<?php } ?>
										</div>
									</div>
									<div class="col-md-12">
										<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
											<div class="article">
												<div class="edicion"><?php the_field('edicion_gral'); ?></div>
												<?php the_title(sprintf('<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h1>'); ?>
												<div class="autor"><?php $terms = get_field('autor_id');
																	if ($terms) : ?><span class="pertallarcoma"><?php foreach ($terms as $term) : ?><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?></span><?php endif; ?></div>

											</div>
										<?php endwhile; ?>
									</div>
								</div>
							<?php endif; ?>
							<?php wp_reset_query(); ?>
							<!-- Fi Publico y regulatorio -->

						<?php } else if (get_field('ordre') == 'ordre4') {
							//echo ('Public i regulatori, Privat, Penal, Criminal');
						?>

							<!-- Público y regulatorio -->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<?php
							// query
							$the_query = new WP_Query(array(
								'post_type'		 => 'post',
								'posts_per_page' => -1,
								'meta_key'		 => 'nombre_area',
								'meta_value'	 => 'Público y regulatorio',
								'tax_query' => array(
									array(
										'taxonomy' => 'category',
										'field'    => 'slug',
										'terms'    => array($edicio)
									),
								),
								'meta_query' => array(
									'relation' => 'AND',
									array(
										'key'      => 'idioma',
										'value'    => 'es',
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Recensions, Book reviews, Recensiones',
										'compare' => '!='
									),
									array(
										'key'		 => 'edicion',
										'value'	 => 'editorial',
										'compare' => '!='
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Actualitat, Recent developments in Law, Actualidad',
										'compare' => '!='
									),
								),
							));
							?>
							<?php if ($the_query->have_posts()) : ?>
								<div class="row">
									<div class="col-md-12">
										<div class="costext_titol">
											<?php if ($language == "en") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Publicoyregulatorio/?edicion=<?php echo $edicio; ?>">Administrative law</a>
											<?php } else if ($language == "es") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Publicoyregulatorio/?edicion=<?php echo $edicio; ?>">Público y regulatorio</a>
											<?php } else if ($language == "ca") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Publicoyregulatorio/?edicion=<?php echo $edicio; ?>">Públic i regulatori</a>
											<?php } ?>
										</div>
									</div>
									<div class="col-md-12">
										<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
											<div class="article">

												<div class="edicion"><?php the_field('edicion_gral'); ?></div>
												<?php the_title(sprintf('<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h1>'); ?>
												<div class="autor"><?php $terms = get_field('autor_id');
																	if ($terms) : ?><span class="pertallarcoma"><?php foreach ($terms as $term) : ?><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?></span><?php endif; ?></div>
											</div>
										<?php endwhile; ?>
									</div>
								</div>
							<?php endif; ?>
							<?php wp_reset_query(); ?>
							<!-- Fi Publico y regulatorio -->

							<!-- Derecho privado -->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<?php
							// query
							$the_query = new WP_Query(array(
								'post_type'		 => 'post',
								'posts_per_page' => -1,
								'meta_key'		 => 'nombre_area',
								'meta_value'	 => 'Derecho privado',
								'tax_query' => array(
									array(
										'taxonomy' => 'category',
										'field'    => 'slug',
										'terms'    => array($edicio)
									),
								),
								'meta_query' => array(
									'relation' => 'AND',
									array(
										'key'      => 'idioma',
										'value'    => 'es',
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Recensions, Book reviews, Recensiones',
										'compare' => '!='
									),
									array(
										'key'		 => 'edicion',
										'value'	 => 'editorial',
										'compare' => '!='
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Actualitat, Recent developments in Law, Actualidad',
										'compare' => '!='
									),
								),
							));
							?>
							<?php if ($the_query->have_posts()) : ?>
								<div class="row">
									<div class="col-md-12">
										<div class="costext_titol">
											<?php if ($language == "en") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechoprivado/?edicion=<?php echo $edicio; ?>">Private law</a>
											<?php } else if ($language == "es") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechoprivado/?edicion=<?php echo $edicio; ?>">Privado</a>
											<?php } else if ($language == "ca") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechoprivado/?edicion=<?php echo $edicio; ?>">Privat</a>
											<?php } ?>
										</div>
									</div>
									<div class="col-md-12">
										<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
											<div class="article">
												<div class="edicion"><?php the_field('edicion_gral'); ?></div>
												<?php the_title(sprintf('<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h1>'); ?>
												<div class="autor"><?php $terms = get_field('autor_id');
																	if ($terms) : ?><span class="pertallarcoma"><?php foreach ($terms as $term) : ?><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?></span><?php endif; ?></div>
											</div>
										<?php endwhile; ?>
									</div>
								</div>
							<?php endif; ?>
							<?php wp_reset_query(); ?>
							<!-- Fi Derecho privado -->

							<!-- Derecho penal -->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<?php
							// query
							$the_query = new WP_Query(array(
								'post_type'		 => 'post',
								'posts_per_page' => -1,
								'meta_key'		 => 'nombre_area',
								'meta_value'	 => 'Derecho penal',
								'tax_query' => array(
									array(
										'taxonomy' => 'category',
										'field'    => 'slug',
										'terms'    => array($edicio)
									),
								),
								'meta_query' => array(
									'relation' => 'AND',
									array(
										'key'      => 'idioma',
										'value'    => 'es',
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Recensions, Book reviews, Recensiones',
										'compare' => '!='
									),
									array(
										'key'		 => 'edicion',
										'value'	 => 'editorial',
										'compare' => '!='
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Actualitat, Recent developments in Law, Actualidad',
										'compare' => '!='
									),
								),
							));
							?>
							<?php if ($the_query->have_posts()) : ?>
								<div class="row">
									<div class="col-md-12">
										<div class="costext_titol">
											<?php if ($language == "en") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechopenal/?edicion=<?php echo $edicio; ?>">Criminal law</a>
											<?php } else if ($language == "es") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechopenal/?edicion=<?php echo $edicio; ?>">Penal</a>
											<?php } else if ($language == "ca") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Derechopenal/?edicion=<?php echo $edicio; ?>">Penal</a>
											<?php } ?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
											<div class="article">
												<div class="edicion"><?php the_field('edicion_gral'); ?></div>
												<?php the_title(sprintf('<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h1>'); ?>
												<div class="autor"><?php $terms = get_field('autor_id');
																	if ($terms) : ?><span class="pertallarcoma"><?php foreach ($terms as $term) : ?><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?></span><?php endif; ?></div>
											</div>
										<?php endwhile; ?>
									</div>
								</div>
							<?php endif; ?>
							<?php wp_reset_query(); ?>
							<!-- Fi Derecho penal -->

							<!-- Criminilogía -->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<!--/*****************************************************************/-->
							<?php
							// query
							$the_query = new WP_Query(array(
								'post_type'		 => 'post',
								'posts_per_page' => -1,
								'meta_key'		 => 'nombre_area',
								'meta_value'	 => 'Criminologia',
								'tax_query' => array(
									array(
										'taxonomy' => 'category',
										'field'    => 'slug',
										'terms'    => array($edicio)
									),
								),
								'meta_query' => array(
									'relation' => 'AND',
									array(
										'key'      => 'idioma',
										'value'    => 'es',
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Recensions, Book reviews, Recensiones',
										'compare' => '!='
									),
									array(
										'key'		 => 'edicion',
										'value'	 => 'editorial',
										'compare' => '!='
									),
									array(
										'key'		=> 'nombre_subarea',
										'value'		=> 'Actualitat, Recent developments in Law, Actualidad',
										'compare' => '!='
									),
								),
							));
							?>
							<?php if ($the_query->have_posts()) : ?>
								<div class="row">
									<div class="col-md-12">
										<div class="costext_titol">
											<?php if ($language == "en") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Criminologia/?edicion=<?php echo $edicio; ?>">Criminology</a>
											<?php } else if ($language == "es") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Criminologia/?edicion=<?php echo $edicio; ?>">Criminología</a>
											<?php } else if ($language == "ca") { ?>
												<a href="<?php echo esc_url(home_url('/')); ?>Criminologia/?edicion=<?php echo $edicio; ?>">Criminologia</a>
											<?php } ?>
										</div>
									</div>
									<div class="col-md-12">
										<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
											<div class="article">
												<div class="edicion"><?php the_field('edicion_gral'); ?></div>
												<?php the_title(sprintf('<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h1>'); ?>
												<div class="autor"><?php $terms = get_field('autor_id');
																	if ($terms) : ?><span class="pertallarcoma"><?php foreach ($terms as $term) : ?><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?></span><?php endif; ?></div>
											</div>
										<?php endwhile; ?>
									</div>
								</div>
							<?php endif; ?>
							<?php wp_reset_query(); ?>
							<!-- Fi Criminilogía -->

						<?php } ?>

						<!--/************************************************************************************************************************************************************************************************/-->
						<!--/************************************************************************************************************************************************************************************************/-->
						<!--/************************************************************************************************************************************************************************************************/-->

						<!-- Actualitat -->
						<!--/*****************************************************************/-->
						<!--/*****************************************************************/-->
						<!--/*****************************************************************/-->

						<?php
						// query
						$the_query = new WP_Query(array(
							'numberposts'	=> -1,
							'post_type'		=> 'post',
							'tax_query' => array(
								array(
									'taxonomy' => 'category',
									'field'    => 'slug',
									'terms'    => array($edicio)
								),
							),
							'meta_query'	=> array(
								'relation'		=> 'AND',
								array(
									'key'     => 'edicion',
									'value'   => 'editorial',
									'compare' => '!=',
								),
								array(
									'key'		=> 'nombre_subarea',
									'value'		=> 'Actualitat, Recent developments in Law, Actualidad',
								),
							),
						));
						//
						?>
						<?php if ($the_query->have_posts()) : ?>
							<div class="row">
								<div class="col-md-12">
									<div class="costext_titol">
										<?php if ($language == "en") { ?>
											<a href="<?php echo esc_url(home_url('/')); ?>actualidad/?edicion=<?php echo $edicio; ?>">Notes</a>
										<?php } else if ($language == "es") { ?>
											<a href="<?php echo esc_url(home_url('/')); ?>actualidad/?edicion=<?php echo $edicio; ?>">Actualidad</a>
										<?php } else if ($language == "ca") { ?>
											<a href="<?php echo esc_url(home_url('/')); ?>actualidad/?edicion=<?php echo $edicio; ?>">Actualitat</a>
										<?php } ?>
									</div>
								</div>
								<div class="col-md-12">
									<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
										<div class="article">
											<div class="edicion"><?php the_field('edicion_gral'); ?></div>
											<?php the_title(sprintf('<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h1>'); ?>
											<div class="autor"><?php $terms = get_field('autor_id');
																if ($terms) : ?><span class="pertallarcoma"><?php foreach ($terms as $term) : ?><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?></span><?php endif; ?></div>
										</div>
									<?php endwhile; ?>
								</div>
							</div>
						<?php endif; ?>
						<?php wp_reset_query(); ?>
						<!-- Fi Actualitat -->


						<!-- Recensions -->
						<!--/*****************************************************************/-->
						<!--/*****************************************************************/-->
						<!--/*****************************************************************/-->

						<?php
						// query
						$the_query = new WP_Query(array(
							'numberposts'	=> -1,
							'post_type'		=> 'post',
							'tax_query' => array(
								array(
									'taxonomy' => 'category',
									'field'    => 'slug',
									'terms'    => array($edicio)
								),
							),
							'meta_query'	=> array(
								'relation'		=> 'AND',
								array(
									'key'     => 'edicion',
									'value'   => 'editorial',
									'compare' => '!=',
								),
								array(
									'key'		=> 'nombre_subarea',
									'value'		=> 'Recensions, Book reviews, Recensiones',
								),
							),
						));
						//
						?>
						<?php if ($the_query->have_posts()) : ?>
							<div class="row">
								<div class="col-md-12">
									<div class="costext_titol">
										<?php if ($language == "en") { ?>
											<a href="<?php echo esc_url(home_url('/')); ?>recensiones/?edicion=<?php echo $edicio; ?>">Book reviews</a>
										<?php } else if ($language == "es") { ?>
											<a href="<?php echo esc_url(home_url('/')); ?>recensiones/?edicion=<?php echo $edicio; ?>">Recensiones</a>
										<?php } else if ($language == "ca") { ?>
											<a href="<?php echo esc_url(home_url('/')); ?>recensiones/?edicion=<?php echo $edicio; ?>">Recensions</a>
										<?php } ?>
									</div>
								</div>
								<div class="col-md-12">
									<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
										<div class="article">
											<div class="edicion"><?php the_field('edicion_gral'); ?></div>
											<?php the_title(sprintf('<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h1>'); ?>
											<div class="autor"><?php $terms = get_field('autor_id');
																if ($terms) : ?><span class="pertallarcoma"><?php foreach ($terms as $term) : ?><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?></span><?php endif; ?></div>
										</div>
									<?php endwhile; ?>
								</div>
							</div>
						<?php endif; ?>
						<?php wp_reset_query(); ?>
						<!-- Fi Recensions -->
					</div>
					<!--- Graella --->
				</div><!-- .entry-content -->
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->
</div>

<?php get_footer(); ?>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('.pertallarcoma').html(function(_, txt) {
			//console.log(txt);
			//return txt.slice(0, -15);
			return txt.slice(0, -6);
		});
	});
</script>