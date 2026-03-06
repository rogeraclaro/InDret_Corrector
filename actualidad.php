<?php

/**
 * Template Name: Actualidad
 * @package InDret
 */

get_header(); ?>

<div class="container-fluid editorial">
	<div class="container">
		<div class="banner">
			<ul class="bxslider" style="display:none">
				<?php
				// query
				$the_query = new WP_Query(array(
					'post_type'		 => 'post',
					'posts_per_page' => -1,
					'meta_key'		 => 'edicion',
					'meta_value'	 => 'editorial',
					//'orderby'		 => 'meta_value',
					//'order'			 => 'ASC',
					'tax_query' => array(
						'relation' => 'AND',
						/*array(
							'taxonomy' => 'category',
							'field'    => 'slug',
							'terms'    => array('Editorial')
							),*/
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
					'ordre_ed' => array(
						'key' => 'numero',
						'compare' => 'EXISTS',
					),
					'orderby' => 'ordre_ed',
				));
				?>
				<?php if ($the_query->have_posts()) { ?>
					<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
						<li>
							<div class="banner_caixa1">
								<div class="banner-edicio"><?php echo $edicio ?>
									<!--<?php the_field('edicion_gral'); ?>-->
								</div>
								<div class="banner-apartat">Editorial</div>
								<div class="banner-titular">
									<?php the_title(sprintf('<a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a>'); ?></div>
								<div class="banner-separador"></div>
								<div class="banner-autors">
									<?php $terms = get_field('autor_id');
									if ($terms) : ?><?php foreach ($terms as $term) : ?><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?></a>,
									<?php endforeach; ?><?php endif; ?>
								</div>
							</div>
						</li>
					<?php endwhile; ?>
				<?php } else { ?>
					<script type="text/javascript">
						jQuery('.banner').hide();
					</script>
				<?php } ?>
				<?php wp_reset_query();	 // Restore global post data stomped by the_post(). 
				?>
			</ul>
		</div>
	</div>
</div>
<?php
// query
$the_query = new WP_Query(array(
	'post_type'		 => 'post',
	'posts_per_page' => -1,
	'meta_key'		 => 'destacar',
	'orderby'		 => 'meta_value',
	'order'			 => 'ASC',
	'tax_query' => array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'category',
			'field'    => 'slug',
			'terms'    => array('Destacado')
		),
	),
));
?>
<?php if ($the_query->have_posts()) { ?>
	<div class="container">
		<div class="banner_destacat">
			<ul class="bxslider_2" style="display:none">
				<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
					<li>
						<div class="banner_caixa1">
							<div class="destacat-titular">
								<?php
								$pdfartic = get_field('pdf_articulo');
								$url = wp_get_attachment_url($pdfartic);
								?>
								<a href="<?php echo $url; ?>" target="new"><?php the_title(); ?><?php the_field('subtitulo'); ?></a>
							</div>
						</div>
					</li>
				<?php endwhile; ?>
			<?php } else { ?>
				<script type="text/javascript">
					jQuery('.banner_destacat').hide();
				</script>
			<?php } ?>
			</ul>
		</div>
	</div>
	<?php wp_reset_query();	 // Restore global post data stomped by the_post(). 
	?>

	<div class="container">
		<? global $language;
		if ($language == "es") { ?>
			<div class="menu-segon">
				<div class="row">
					<div class="col-md-2 col-md-offset-1 sumario"><a href="<?php echo esc_url(home_url('/')); ?>sumarios/sumario<?php echo '-' . $edicio[0];
																																$resultat = substr($edicio, 2, 3);
																																echo '-' . $resultat; ?>?edicion=<?php echo $edicio; ?>">Índice</a>
					</div>
					<div class="col-md-2 ed-actual"><a href="<?php echo esc_url(home_url('/')); ?>?edicion=<?php echo $edicio; ?>" class="">Análisis</a></div>
					<div class="col-md-2 actualida"><a href="<?php echo esc_url(home_url('/')); ?>actualidad/?edicion=<?php echo $edicio; ?>" class="">Actualidad</a>
					</div>
					<div class="col-md-2 recensions"><a href="<?php echo esc_url(home_url('/')); ?>recensiones/?edicion=<?php echo $edicio; ?>" class="">Recensiones y traducciones</a>
					</div>
					<div class="col-md-2 ed-anteriores"><a href="<?php echo esc_url(home_url('/')); ?>anteriores/?edicion=<?php echo $edicio; ?>" class="">Ediciones
							anteriores</a></div>
				</div>
			</div>
		<? } else if ($language == "ca") { ?>
			<div class="menu-segon">
				<div class="row">
					<div class="col-md-2 col-md-offset-1 sumario"><a href="<?php echo esc_url(home_url('/')); ?>sumarios/sumario<?php echo '-' . $edicio[0];
																																$resultat = substr($edicio, 2, 3);
																																echo '-' . $resultat; ?>?edicion=<?php echo $edicio; ?>">Índex</a>
					</div>
					<div class="col-md-2 ed-actual"><a href="<?php echo esc_url(home_url('/')); ?>?edicion=<?php echo $edicio; ?>" class="">Anàlisi</a></div>
					<div class="col-md-2 actualida"><a href="<?php echo esc_url(home_url('/')); ?>actualidad/?edicion=<?php echo $edicio; ?>" class="">Actualitat</a>
					</div>
					<div class="col-md-2 recensions"><a href="<?php echo esc_url(home_url('/')); ?>recensiones/?edicion=<?php echo $edicio; ?>" class="">Recensions i traduccions</a>
					</div>
					<div class="col-md-2 ed-anteriores"><a href="<?php echo esc_url(home_url('/')); ?>anteriores/?edicion=<?php echo $edicio; ?>" class="">Edicions
							anteriors</a></div>
				</div>
			</div>
		<? } else if ($language == "en") { ?>
			<div class="menu-segon">
				<div class="row">
					<div class="col-md-2 col-md-offset-1 sumario"><a href="<?php echo esc_url(home_url('/')); ?>sumarios/sumario<?php echo '-' . $edicio[0];
																																$resultat = substr($edicio, 2, 3);
																																echo '-' . $resultat; ?>?edicion=<?php echo $edicio; ?>">Table
							of contents</a></div>
					<div class="col-md-2 ed-actual"><a href="<?php echo esc_url(home_url('/')); ?>?edicion=<?php echo $edicio; ?>" class="">Articles</a></div>
					<div class="col-md-2 actualida"><a href="<?php echo esc_url(home_url('/')); ?>actualidad/?edicion=<?php echo $edicio; ?>" class="">Notes</a>
					</div>
					<div class="col-md-2 recensions"><a href="<?php echo esc_url(home_url('/')); ?>recensiones/?edicion=<?php echo $edicio; ?>" class="">Book
							reviews & translations</a>
					</div>
					<div class="col-md-2 ed-anteriores"><a href="<?php echo esc_url(home_url('/')); ?>anteriores/?edicion=<?php echo $edicio; ?>" class="">Past
							Issues</a></div>
				</div>
			</div>
		<? }
		?>
	</div>
	<script>
		jQuery('.actualida a').addClass('actual');
	</script>
	<div id="content" class="site-content container">
		<div id="primary" class="content-area">
			<main id="main" class="site-main col-md-12" role="main">
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
						/*
						array(
							'relation'		=> 'OR',

							array(
								'key'		=> 'nombre_subarea',
								'value'		=> 'Recensions, Book reviews, Recensiones',
							),
						),
						*/
						/*'meta_query' => array(
					    array(
					    	'key'      => 'idioma',
					    	'value'    => 'es'
					    	),
					  	),*/
					),
				));
				//
				?>
				<?php if ($the_query->have_posts()) : ?>
					<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
						<article id="post-<?php the_ID(); ?>" <?php post_class('col-md-6'); ?>>
							<div class="entry-header altura_entrada_home">
								<div class="edicion"><?php echo $edicio ?>
									<!--<?php the_field('edicion_gral'); ?>-->
								</div>
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
											$nomarea = 'Administrative law';
										}
									} else if ($language == "es") {
										if ($nomcategoria == 'Derechoprivado') {
											$nomarea = 'Privado';
										} else if ($nomcategoria == 'Criminologia') {
											$nomarea = 'Criminología';
										} else if ($nomcategoria == 'Derechopenal') {
											$nomarea = 'Penal';
										} else if ($nomcategoria == 'Publicoyregulatorio') {
											$nomarea = 'Público y regulatorio';
										}
									} else if ($language == "ca") {
										if ($nomcategoria == 'Derechoprivado') {
											$nomarea = 'Privat';
										} else if ($nomcategoria == 'Criminologia') {
											$nomarea = 'Criminologia';
										} else if ($nomcategoria == 'Derechopenal') {
											$nomarea = 'Penal';
										} else if ($nomcategoria == 'Publicoyregulatorio') {
											$nomarea = 'Públic i regulatori';
										}
									}
									?>
									<a href="<?php echo $nomcategoria . '/?edicion=' . $edicio; ?>">
										<? echo $nomarea; ?>
										<!--<br><?php the_field('nombre_area'); ?>-->
									</a>
								</div>
								<?php the_title(sprintf('<h1 class="entry-title"><a href="%s?edicion=%s" rel="bookmark">', esc_url(get_permalink()), $edicio), '</a></h1>'); ?>
								<div class="peu_entrada_home">
									<span class="sep">_</span><br>
									<div class="autor">
										<?php $terms = get_field('autor_id');
										if ($terms) : ?><span class="pertallarcoma"><?php foreach ($terms as $term) : ?><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?></span><?php endif; ?>
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
											<li class="xs_mail"><a class="order-button fancybox-inline" href="#contact-form" dataTitle="<?php echo get_permalink(); ?>"></a></li>
										</ul>
									</div>
								</div>
							</div><!-- .entry-header -->
						</article><!-- #post-## -->
					<?php endwhile; ?>
				<?php endif; ?>
				<?php wp_reset_query();	 // Restore global post data stomped by the_post(). 
				?>
				<!--contact form-->
				<div style="display:none" class="fancybox-hidden">
					<div id="contact-form">
						<span class="autor-interior">Enviar artículo</span>
						<?php echo do_shortcode('[contact-form-7 id="21318" title="Enviar articulo"]'); ?>
					</div>
				</div>
			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!-- #content -->

	<div class="container-fluid barra-inferior">
		<div class="container">
			<div class="col-sm-12 col-md-12"><a href="<?php echo esc_url(home_url('/')); ?>los-mas-leidos"><span class="boto-peu autor">
						<?php if ($language == "en") {
							echo 'Most widely read';
						} else if ($language == "es") {
							echo 'Los más leídos';
						} else if ($language == "ca") {
							echo 'Els més llegits';
						} ?></span></a>
			</div>
		</div>
	</div>

	<?php get_footer(); ?>

	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/css/jquery.bxslider.css" />
	<script src="<?php bloginfo('template_directory'); ?>/js/jquery.bxslider.min.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('.bxslider').show().bxSlider({
				mode: 'horizontal',
				easing: 'ease-in',
				slideMargin: 0,
				infiniteLoop: true,
				hideControlOnEnd: true,
				pager: false,
				//pagerType: 'short',
				speed: 500,
				useCSS: true,
				autoStart: true,
				auto: true,
				pause: 7000,
				autoHover: true,
				responsive: true
			});
			$('.bxslider_2').show().bxSlider({
				controls: false,
				mode: 'horizontal',
				easing: 'ease-in',
				slideMargin: 0,
				//infiniteLoop: true,
				//hideControlOnEnd: true,
				pager: true,
				//pagerType: 'short',
				speed: 500,
				useCSS: true,
				autoStart: false,
				auto: true,
				pause: 10000,
				autoHover: true,
				responsive: true
			});

			//$('.ed-actual').addClass('current_page_item');


			function cuentadescargas() {
				var descargas_old = '<?php the_field('
		ranking_descargas '); ?>';
				if (descargas_old == '' || descargas_old == 'NULL') {
					descargas_old = 0;
					$(".descargas").html(descargas_old);
				} else {}
				console.log('descargas:', descargas_old);
			}

			$('.pertallarcoma').html(function(_, txt) {
				console.log(txt);
				return txt.slice(0, -6);
			});

			//cuentadescargas();

			/*
			  	var mobil = false;
			  	checkSize();
			  	$(window).resize(checkSize);
			  	function checkSize(){
					if ($(".midamobil").css("float") == "none" ){
						mobil = true;
						console.log('mobil');
						}
						else {
							mobil = false;
							console.log('no mobil');
						}
				}

				$('div.categoria').each(function(){
				    var text = $(this).text().split(/\s+/);
				    var textfinal = text[0]+text[1]+text[2]+text[3]
				    console.log(textfinal);
				});

				function tallaseccions (textfinal) {
					console.log('nom:', nom);

				}
			*/

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