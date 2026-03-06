<?php
/**
 * Template Name: Analisis
 * @package InDret
 **/

get_header(); ?>

<div class="fons-interior">
<div class="container">
	<div class="banner invers">
		<h1 class="entry-title-interior">Análisis</h1>
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
					'meta_key'		 => 'nombre_subarea',
					'meta_value'	 => 'Working papers, Doctrina',
					'orderby'		 => 'meta_value',
					'order'			 => 'ASC',
					'tax_query' => array(
						'relation' => 'AND',
						array(
							'taxonomy' => 'category',
							'field'    => 'slug',
							'terms'    => array($edicio)
							),
					),
				));
				?>
					<?php if( $the_query->have_posts() ): ?>
						<?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
							<article id="post-<?php the_ID(); ?>" <?php post_class('col-md-6'); ?>>
								<div class="entry-header altura_entrada_home">
									<div class="edicion"><?php echo $edicio ?><!--<?php the_field('edicion_gral'); ?>--></div>

									<div class="categoria">
									<?php the_field('nombre_area'); ?>
									</div>
									
									<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
									<div class="peu_entrada_home">
										_<br>
										<div class="autor">
											<?php $terms = get_field('autor_id');if( $terms ): ?><?php foreach( $terms as $term ): ?><a href="<?php echo get_term_link( $term ); ?>"><?php echo $term->name; ?></a>, <?php endforeach; ?><?php endif; ?>
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
</div>

<?php get_footer(); ?>

<script type="text/javascript">
jQuery(document).ready(function($) {
	$('.ed-actual').addClass('current_page_item');
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
*/
});
</script>