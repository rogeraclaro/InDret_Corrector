<?php
/**
 * @package InDret
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('col-md-6'); ?>>
	<div class="entry-header altura_entrada_home">
		<div class="edicion"><?php the_field('edicion_gral'); ?></div>
		<div class="categoria"><?php the_field('nombre_area'); ?></div>
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
		<div class="peu_entrada_home">
			_<br>
			<div class="autor">
				<?php $terms = get_field('autor_id');if( $terms ): ?><span class="pertallarcoma"><?php foreach( $terms as $term ): ?><a href="<?php echo get_term_link( $term ); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?></span><?php endif; ?>
			</div>
			<div class="xarxes_soc">
				<span class="descargas <?php echo get_the_ID(); ?>">
					<script>
					var descargas_old = '<?php the_field('ranking_descargas'); ?>';
						if (descargas_old == '' || descargas_old == 'NULL') {
							descargas_old = 0;
							//document.write(descargas_old);
							$(".<?php echo get_the_ID(); ?>").html(descargas_old);
						}
						else {
							$(".<?php echo get_the_ID(); ?>").html(descargas_old);
						}
					</script>
				</span> 
				<?php
				if($language=="en") {echo 'downloads';}
				else if($language=="es") {echo 'descargas';	}
				else if($language=="ca") {echo 'descàrregues';} ?>
			</div>
			<div class="xarxes_soc botons">
				<?php
				if($language=="en") {echo 'Share';}
				else if($language=="es") {echo 'Compartir';	}
				else if($language=="ca") {echo 'Compartir';} ?>
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
<script>
	function cuentadescargas() {
		var descargas_old = '<?php the_field('ranking_descargas'); ?>';
		if (descargas_old == '' || descargas_old == 'NULL') {
			descargas_old = 0;
			$(".descargas").html(descargas_old);
		}
		else {}
		console.log ('descargas:', descargas_old);
	}
/*	
$('.pertallarcoma').html(function (_,txt) {
	console.log(txt);
    return txt.slice(0, -2);
});
*/
</script>
<!-- <a href="https://www.facebook.com/sharer.php?u=<?php echo urlencode(get_permalink($post->ID)); ?>&t=<?php echo urlencode($post->post_title); ?>"> </a> -->