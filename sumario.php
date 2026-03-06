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
<div id="content" class="site-content container-fluid editorial-interior">
	<div id="primary" class="content-area container">
		<main id="main" class="site-main col-md-12" role="main">
		<div class="fitxa">
			<div class="entry-content">
				<div class="row">
					<div class="col-md-2">
					</div>
					<div class="col-md-8">
						<h1 class="entry-title-interior">Sumario <?php echo $edicio; ?></h1>
						<div class="costext amunt"><a href="<?php the_field('pdf_sumario'); ?>" target="new">Descargar PDF completo</a></div>
					</div>
					<div class="col-md-2">
					</div>
				</div>

				<!-- Editorials -->
				<?php 
				// query
				$the_query = new WP_Query(array( 
					'post_type'		 => 'post',
					'posts_per_page' => -1,
					'category_name'  => 'Editorial',
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
				<?php if( $the_query->have_posts() ): ?>
				<div class="row">
					<div class="col-md-2">
					</div>
					<div class="col-md-8">
						<div class="costext_titol">Editoriales</div>
					</div>
					<div class="col-md-2">
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
					</div>
					<div class="col-md-8">

						<?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
							<div class="edicion"><?php the_field('edicion_gral'); ?></div>
							<?php the_title( sprintf( '<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
							<div class="autor"><?php the_field('autor_id_n'); ?></div>
						<?php endwhile; ?>
					</div>
					<div class="col-md-2">
					</div>
				</div>
				<?php endif; ?>
				<?php wp_reset_query(); ?>
				<!-- Fi Editorials -->

				<!-- Criminilogía -->
				<?php 
				// query
				$the_query = new WP_Query(array(
					'post_type'		 => 'post',
					'posts_per_page' => -1,
					'meta_key'		 => 'nombre_area',
					'meta_value'	 => 'Criminologia',
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
					'meta_query' => array(
				    array(
				    	'key'      => 'idioma',
				    	'value'    => 'es'
				    	),
				  	),
				));
				?>
				<?php if( $the_query->have_posts() ): ?>
				<div class="row">
					<div class="col-md-2">
					</div>
					<div class="col-md-8">
						<div class="costext_titol">Criminología</div>
					</div>
					<div class="col-md-2">
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
					</div>
					<div class="col-md-8">
						<?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
							<div class="edicion"><?php the_field('edicion_gral'); ?></div>
							<?php the_title( sprintf( '<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
							<div class="autor"><?php the_field('autor_id_n'); ?></div>
						<?php endwhile; ?>
					</div>
					<div class="col-md-2">
					</div>
				</div>
				<?php endif; ?>
				<?php wp_reset_query(); ?>
				<!-- Fi Criminilogía -->

				<!-- Derecho penal -->
				<?php 
				// query
				$the_query = new WP_Query(array(
					'post_type'		 => 'post',
					'posts_per_page' => -1,
					'meta_key'		 => 'nombre_area',
					'meta_value'	 => 'Derecho penal',
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
					'meta_query' => array(
				    array(
				    	'key'      => 'idioma',
				    	'value'    => 'es'
				    	),
				  	),
				));
				?>
				<?php if( $the_query->have_posts() ): ?>
				<div class="row">
					<div class="col-md-2">
					</div>
					<div class="col-md-8">
						<div class="costext_titol">Derecho penal</div>
					</div>
					<div class="col-md-2">
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
					</div>
					<div class="col-md-8">
						<?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
							<div class="edicion"><?php the_field('edicion_gral'); ?></div>
							<?php the_title( sprintf( '<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
							<div class="autor"><?php the_field('autor_id_n'); ?></div>
						<?php endwhile; ?>
					</div>
					<div class="col-md-2">
					</div>
				</div>
				<?php endif; ?>
				<?php wp_reset_query(); ?>
				<!-- Fi Derecho penal -->

				<!-- Derecho privado -->
				<?php 
				// query
				$the_query = new WP_Query(array(
					'post_type'		 => 'post',
					'posts_per_page' => -1,
					'meta_key'		 => 'nombre_area',
					'meta_value'	 => 'Derecho privado',
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
					'meta_query' => array(
				    array(
				    	'key'      => 'idioma',
				    	'value'    => 'es'
				    	),
				  	),
				));
				?>
				<?php if( $the_query->have_posts() ): ?>
				<div class="row">
					<div class="col-md-2">
					</div>
					<div class="col-md-8">
						<div class="costext_titol">Derecho privado</div>
					</div>
					<div class="col-md-2">
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
					</div>
					<div class="col-md-8">
						<?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
							<div class="edicion"><?php the_field('edicion_gral'); ?></div>
							<?php the_title( sprintf( '<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
							<div class="autor"><?php the_field('autor_id_n'); ?></div>
						<?php endwhile; ?>
					</div>
					<div class="col-md-2">
					</div>
				</div>
				<?php endif; ?>
				<?php wp_reset_query(); ?>
				<!-- Fi Derecho privado -->

				<!-- Público y regulatorio -->
				<?php 
				// query
				$the_query = new WP_Query(array(
					'post_type'		 => 'post',
					'posts_per_page' => -1,
					'meta_key'		 => 'nombre_area',
					'meta_value'	 => 'Público y regulatorio',
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
					'meta_query' => array(
				    array(
				    	'key'      => 'idioma',
				    	'value'    => 'es'
				    	),
				  	),
				));
				?>
				<?php if( $the_query->have_posts() ): ?>
				<div class="row">
					<div class="col-md-2">
					</div>
					<div class="col-md-8">
						<div class="costext_titol">Público y regulatorio</div>
					</div>
					<div class="col-md-2">
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
					</div>
					<div class="col-md-8">
						<?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
							<div class="edicion"><?php the_field('edicion_gral'); ?></div>
							<?php the_title( sprintf( '<h1 class="entry-title-sumari"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
							<div class="autor"><?php the_field('autor_id_n'); ?></div>
						<?php endwhile; ?>
					</div>
					<div class="col-md-2">
					</div>
				</div>
				<?php endif; ?>
				<?php wp_reset_query(); ?>
				<!-- Fi Derecho privado -->


			</div><!-- .entry-content -->
		</div>
		</main><!-- #main -->
	</div><!-- #primary -->
</div>

<?php get_footer(); ?>

<script type="text/javascript">
jQuery(document).ready(function($) {

/*
function formatBytes(a,b){if(0==a)return"0 Bytes";var c=1024,d=b||0,e=["Bytes","KB","MB","GB","TB","PB","EB","ZB","YB"],f=Math.floor(Math.log(a)/Math.log(c));return parseFloat((a/Math.pow(c,f)).toFixed(d))+" "+e[f]}

var KB = '<?php the_field('tamano_adjunto'); ?>';
var gg = formatBytes(KB);
console.log ('kb:', gg);
$(".pes_en_kb").html(gg);

function cuentadescargas() {
	var descargas_old = '<?php the_field('ranking_descargas'); ?>';
	if (descargas_old == '' || descargas_old == 'NULL') {
		descargas_old = 0;
	}
	console.log ('descargas:', descargas_old);
	$(".descargas").html(descargas_old);
}

cuentadescargas();


var count = document.getElementById('nomsautor').innerHTML.split(' ').length;
if (count == 3) {
	}
else {
}
console.log ('paraules:', count);
*/

	$('span.nomsautor').each(function(){
	    var text = $(this).text().split(/\s+/);
	    //if(text.length < 2)
	    //  return;
	    if(text.length == 2) {
	    text[1] = '<span class="majusc">'+text[1]+'</span>';
	    $(this).html( text.join(' ') );
		}
	    else if(text.length == 3) {
	    text[1] = '<span class="majusc">'+text[1]+'</span>';
	    text[2] = '<span class="majusc">'+text[2]+'</span>';
	    $(this).html( text.join(' ') );
		}
		else if(text.length == 4) {
		text[1] = '<span class="majusc">'+text[1]+'</span>';
	    text[2] = '<span class="majusc">'+text[2]+'</span>';
	    text[3] = '<span class="majusc">'+text[3]+'</span>';
	    $(this).html( text.join(' ') );
		}
	    console.log(text);
	});


});
</script>