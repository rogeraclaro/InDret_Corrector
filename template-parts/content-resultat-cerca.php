<?php
/**
 * @package InDret
 */
?>

<style>
.altura_entrada_home {
	position: relative;
	height: 300px;
	border-top: 1px solid Black;
	padding-top: 16px;
	/*background-color: #ff0000;*/
}
.peu_entrada_home {
	position: absolute;
	top: 100%; left: 50%;
	transform: translate(-50%,-100%);
	width: 99%;
	height: 65px;
	/*background-color: #666;*/
}
.entry-title {
	font-size: 28px;
	line-height: 30px;
}
</style>

<article id="post-<?php the_ID(); ?>" <?php post_class('col-md-6'); ?>>
	<header class="entry-header altura_entrada_home">
		<div class="edicion">3.16</div>
		<div class="categoria">DERECHO PRIVADO</div>
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
		<div class="peu_entrada_home">
			_<br>
			<div class="num_descargas">182 descargas</div>
			<div class="xarxes_soc">Compartir (Xarxes socials)</div>
		</div>

		<!--<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<?php start_posted_on(); ?>
		</div> 
		<?php endif; ?>-->
	</header><!-- .entry-header -->

	<!--
	<div class="entry-content">
		<?php
			the_content( sprintf(
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'start' ), array( 'span' => array() ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );
		?>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'start' ),
				'after'  => '</div>',
			) );
		?>
	</div> -->

	<footer class="entry-footer">
		<?php start_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->