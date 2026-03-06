<?php
/**
 * Template Name: Derecho privado
 * @package InDret
 **/

get_header(); ?>
<?php if($language=="en") { ?>
<script>
  jQuery(".titol-branca").html('Private law');
</script>
<?php } else if($language=="es") { ?>
<script>
  jQuery(".titol-branca").html('Privado');
</script>
<?php } else if($language=="ca") { ?>
<script>
  jQuery(".titol-branca").html('Privat');
</script>
<?php } ?>

<div class="fons-interior">
	<div class="container">
		<div class="row" style="padding-top: 30px; margin-bottom:-30px">
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<div class="coordinadors">
					<?php if($language=="es") { ?>
					<?php if (get_field('coordinadors_dir')) { ?>
					<?php echo '<strong>Direcci&oacute;n: </strong> ' . get_field('coordinadors_dir'); ?><br>
					<?php } if (get_field('coordinadors_subdir')) { ?>
					<?php echo '<strong>Subdirecci&oacute;n: </strong> ' . get_field('coordinadors_subdir'); ?><br>
					<?php } if (get_field('coordinadors_cor')) { ?>
					<?php echo '<strong>Coordinaci&oacute;n: </strong> ' . get_field('coordinadors_cor'); ?><br>
					<?php } if (get_field('coordinadors_sec')) { ?>
					<?php echo '<strong>Secretaría de redacci&oacute;n: </strong> ' . get_field('coordinadors_sec'); ?><br>
					<?php } ?>
					<?php } else if($language=="ca") { ?>
					<?php if (get_field('coordinadors_dir')) { ?>
					<?php echo '<strong>Direcci&oacute;: </strong> ' . get_field('coordinadors_dir'); ?><br>
					<?php } if (get_field('coordinadors_subdir')) { ?>
					<?php echo '<strong>Subdirecci&oacute;: </strong> ' . get_field('coordinadors_subdir'); ?><br>
					<?php } if (get_field('coordinadors_cor')) { ?>
					<?php echo '<strong>Coordinaci&oacute;: </strong> ' . get_field('coordinadors_cor'); ?><br>
					<?php } if (get_field('coordinadors_sec')) { ?>
					<?php echo '<strong>Secretariat de redacci&oacute;: </strong> ' . get_field('coordinadors_sec'); ?><br>
					<?php } ?>
					<?php } else if($language=="en") { ?>
					<?php if (get_field('coordinadors_dir')) { ?>
					<?php echo '<strong>Director: </strong> ' . get_field('coordinadors_dir'); ?><br>
					<?php } if (get_field('coordinadors_subdir')) { ?>
					<?php echo '<strong>Assistant director: </strong> ' . get_field('coordinadors_subdir'); ?><br>
					<?php } if (get_field('coordinadors_cor')) { ?>
					<?php echo '<strong>Coordinator: </strong> ' . get_field('coordinadors_cor'); ?><br>
					<?php } if (get_field('coordinadors_sec')) { ?>
					<?php echo '<strong>Editorial secretary: </strong> ' . get_field('coordinadors_sec'); ?><br>
					<?php } ?>
					<?php } ?>
					<br>
				</div>
			</div>
			<div class="col-md-1"></div>
    </div>
    <!--
		<div class="banner invers">
			<h1 class="entry-title-interior">
				<?php if($language=="en") { ?>
				Private law
				<?php } else if($language=="es") { ?>
				Privado
				<?php } else if($language=="ca") { ?>
				Privat
				<?php } ?>
			</h1>
    </div>
    -->
	</div>

	<div id="content" class="site-content container">
		<div id="primary" class="content-area">
			<main id="main" class="site-main col-md-12" role="main">
				<?php
				// query
				$the_query = new WP_Query(array(
					'post_type'		 => 'post',
					'posts_per_page' => -1,
					'meta_key'		 => 'nombre_area',
					'meta_value'	 => 'Derecho privado',
					/*'orderby'		 => 'meta_value',
					'order'			 => 'ASC',*/
					'tax_query' => array(
						'relation' => 'AND',
					array(
						'taxonomy' => 'category',
						'field'    => 'slug',
						'terms'    => array($edicio)
						),
					),
					/*'meta_query' => array(
				    array(
				    	'key'      => 'idioma',
				    	'value'    => 'es'
				    	),
				  	),*/
				));
				?>
				<?php if( $the_query->have_posts() ): ?>
				<?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class('col-md-6'); ?>>
					<div class="entry-header altura_entrada_home">
						<div class="edicion"><?php the_field('edicion_gral'); ?></div>

						<div class="categoria">
							<?php
									$nomcategoria = get_field('nombre_area');
									$nomcategoria = str_replace(' ', '', $nomcategoria);
									$nomcategoria = str_replace('í', 'i', $nomcategoria);
									$nomcategoria = str_replace('ú', 'u', $nomcategoria);
									if($language=="en") {
										if ($nomcategoria=='Derechoprivado') { $nomarea = 'Private law';}
										else if ($nomcategoria=='Criminologia') {$nomarea = 'Criminology';}
										else if ($nomcategoria=='Derechopenal') {$nomarea = 'Criminal law';}
										else if ($nomcategoria=='Publicoyregulatorio') {$nomarea = 'Administrative law';}
									}
									else if($language=="es") {
										if ($nomcategoria=='Derechoprivado') {$nomarea = 'Privado';}
										else if ($nomcategoria=='Criminologia') {$nomarea = 'Criminología';}
										else if ($nomcategoria=='Derechopenal') {$nomarea = 'Penal';}
										else if ($nomcategoria=='Publicoyregulatorio') {$nomarea = 'Público y regulatorio';}
									}
									else if($language=="ca") {
										if ($nomcategoria=='Derechoprivado') {$nomarea = 'Privat';}
										else if ($nomcategoria=='Criminologia') {$nomarea = 'Criminologia';}
										else if ($nomcategoria=='Derechopenal') {$nomarea = 'Penal';}
										else if ($nomcategoria=='Publicoyregulatorio') {$nomarea = 'Públic i regulatori';}
									}
									?>
							<a href="<?php echo $nomcategoria . '/?edicion=' . $edicio; ?>">
								<? echo $nomarea; ?>
								<!--<?php the_field('nombre_area'); ?>--></a>
						</div>

						<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
						<div class="peu_entrada_home">
							<span class="sep">_</span><br>
							<div class="autor">
								<?php $terms = get_field('autor_id');if( $terms ): ?><span
									class="pertallarcoma"><?php foreach( $terms as $term ): ?><a
										href="<?php echo get_term_link( $term ); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?></span><?php endif; ?>
							</div>
							<div class="xarxes_soc"><span class="descargas <?php echo get_the_ID(); ?>">
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
									if($language=="en") {echo 'downloads';}
									else if($language=="es") {echo 'descargas';	}
									else if($language=="ca") {echo 'descÃ rregues';} ?>
							</div>
							<div class="xarxes_soc botons">
								<?php
									if($language=="en") {echo 'Share';}
									else if($language=="es") {echo 'Compartir';	}
									else if($language=="ca") {echo 'Compartir';} ?>
								<ul>
									<li class="xs_face"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>"
											target="new"></a></li>
									<li class="xs_twit"><a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>"
											target="new"></a></li>
									<li class="xs_linked"><a
											href="https://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>&title=Revista%20InDret&source=indret.com"
											target="new"></a></li>
									<li class="xs_mail"><a class="order-button fancybox-inline" href="#contact-form"
											dataTitle="<?php echo get_permalink(); ?>"></a></li>
								</ul>
							</div>
						</div>

					</div><!-- .entry-header -->
				</article><!-- #post-## -->
				<?php endwhile; ?>
				<?php endif; ?>
				<?php wp_reset_query();	 // Restore global post data stomped by the_post(). ?>
				<!--contact form-->
				<div style="display:none" class="fancybox-hidden">
					<div id="contact-form">
						<span class="autor-interior">Enviar artÃ­culo</span>
						<?php echo do_shortcode('[contact-form-7 id="21318" title="Enviar articulo"]'); ?>
					</div>
				</div>
			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!-- #content -->
</div>

<?php get_footer(); ?>

<script type="text/javascript">
jQuery(document).ready(function($) {
	$('.ed-actual').addClass('current_page_item');

	function cuentadescargas() {
		var descargas_old = '<?php the_field('ranking_descargas'); ?>';
		if (descargas_old == '' || descargas_old == 'NULL' || descargas_old == false) {
			descargas_old = 0;
		}
		$(".descargas").html(descargas_old);
	}

	$('.pertallarcoma').html(function(_,txt) {
		return txt.slice(0, -6);
	});
});
</script>
<script>
jQuery(document).ready(function($) {
	$('.order-button').click(function() {
		var title = $(this).attr('dataTitle');
		$(".posturl input").val(title);
		$(".wpcf7-response-output.wpcf7-display-none.wpcf7-mail-sent-ok").hide();
	});
});
</script>
