<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
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
						'meta_query' => array(
					    array(
					    	'key'      => 'idioma',
					    	'value'    => 'es'
					    	),
					  	),
					  	'ordre_ed' => array(
            				'key' => 'numero',
            				'compare' => 'EXISTS',
        				),
					  	'orderby' => 'ordre_ed',
					));
					?>
					<?php if( $the_query->have_posts() ) { ?>
						<?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
						<li>
							<div class="banner_caixa1">
								<div class="banner-edicio"><?php the_field('edicion_gral'); ?></div>
								<div class="banner-apartat">Editorial</div>
								<div class="banner-titular"><?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?></div>
								<div class="banner-separador"></div>
								<div class="banner-autors"><?php the_field('autor_id_n'); ?></div>
							</div>
						</li>
						<?php endwhile; ?>
					<?php } else { ?>
						<script type="text/javascript">jQuery(document).ready(function($) { $('.banner').hide(); });</script>
					<?php } ?>
					<?php wp_reset_query();	 // Restore global post data stomped by the_post(). ?>
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
			<?php if( $the_query->have_posts() ) { ?>
				<div class="container">
					<div class="banner_destacat">
						<ul class="bxslider_2" style="display:none">
							<?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
							<li>
								<div class="banner_caixa1">
									<div class="destacat-titular"><a href="<?php bloginfo('template_directory'); ?>/pdf/<?php the_field('adjunto'); ?>" target="new"><?php the_title();?><?php the_field('subtitulo'); ?></a></div>
								</div>
							</li>
							<?php endwhile; ?>
			<?php } else { ?>
				<script type="text/javascript">jQuery(document).ready(function($) { $('.banner_destacat').hide(); });</script>
			<?php } ?>
						</ul>
					</div>
				</div>
			<?php wp_reset_query();	 // Restore global post data stomped by the_post(). ?>

<div class="container">
	<div class="menu-segon">
		<div class="col-md-3 ed-actual"><a href="<?php echo esc_url( home_url( '/' ) ); ?>analisis/?edicion=<?php echo $edicio; ?>">Análisis</a></div>
		<div class="col-md-3 actualida"><a href="<?php echo esc_url( home_url( '/' ) ); ?>actualidad/?edicion=<?php echo $edicio; ?>">Actualidad</a></div>
		<div class="col-md-3 sumario"><a href="<?php echo esc_url( home_url( '/' ) ); ?>sumario/?edicion=<?php echo $edicio; ?>">Sumario</a></div>
		<div class="col-md-3 ed-anteriores"><a href="<?php echo esc_url( home_url( '/' ) ); ?>anteriores/?edicion=<?php echo $edicio; ?>">Ediciones anteriores</a></div>
	</div>
</div>

<div id="content" class="site-content container">
	<div id="primary" class="content-area">
		<main id="main" class="site-main col-md-12" role="main">
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
					'terms'    => array('Portada')
					),
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
									?>
									<a href="<?php echo $nomcategoria . '/?edicion=' . $edicio; ?>"><?php the_field('nombre_area'); ?></a>
								</div>								
								<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
								<div class="peu_entrada_home">
									_<br>
									<div class="autor">
										<!--<?php the_field('autor_id_n'); ?>-->
										<?php the_terms($post->ID, 'autor', '', ', ', ' '); ?>
										<!--<?php the_tags(); ?>-->
										<!--<? $term_list = wp_get_post_terms($post->ID, 'autor', array("fields" => "all"));
										$nom1 = $term_list[0]->name;
										$nom1 = substr($nom1, 0, -2);
										echo $nom1;
										echo $term_list[0]->slug; ?>-->
										<!--substr($string, 0, -2);-->
										<!--the_terms($post->ID, 'autor', '', ', ', ' ');-->
									</div>
									<div class="xarxes_soc"><span class="descargas"><?php the_field('ranking_descargas'); ?></span> descargas</div>
									<div class="xarxes_soc">Compartir
									<ul>
										<li class="xs_face"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="new"></a></li>
										<li class="xs_twit"><a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>" target="new"></a></li>
										<li class="xs_linked"><a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>&title=Revista%20InDret&source=indret.com" target="new"></a></li>
										<li class="xs_mail"><a href="mailto:?subject=InDret, Revista para el Análisis del Derecho&body=He encontrado este enlace: <?php the_permalink(); ?>"></a></li>
									</ul>
									</div>
								</div>
							</div><!-- .entry-header -->
						</article><!-- #post-## -->
						<?php endwhile; ?>
					<?php endif; ?>
			<?php wp_reset_query();	 // Restore global post data stomped by the_post(). ?>
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- #content -->

<div class="container-fluid barra-inferior">
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
		  infiniteLoop: true,
		  hideControlOnEnd: true,
		  pager: true,
		  //pagerType: 'short',
		  speed: 500,
		  useCSS: true,
		  autoStart: true,
		  auto: true,
		  pause: 7000,
		  autoHover: true, 
		  responsive: true
		});

	//$('.ed-actual').addClass('current_page_item');


	function cuentadescargas() {
		var descargas_old = '<?php the_field('ranking_descargas'); ?>';
		if (descargas_old == '' || descargas_old == 'NULL') {
			descargas_old = 0;
		}
		console.log ('descargas:', descargas_old);
		$(".descargas").html(descargas_old);
	}

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