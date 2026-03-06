<?php

/**
 * The template for displaying all single posts.
 *
 * @package InDret
 */

get_header(); ?>

<?php $nompeltitol = get_field('nombre_area');
$nompeltitol = str_replace(' ', '', $nompeltitol);
if ($language == "en") {
	if ($nompeltitol == 'Derechoprivado') {
		$nomarea = 'Private law';
	} else if ($nompeltitol == 'Criminología') {
		$nomarea = 'Criminology';
	} else if ($nompeltitol == 'Derechopenal') {
		$nomarea = 'Criminal law';
	} else if ($nompeltitol == 'Publicoyregulatorio') {
		$nomarea = 'Administrative law';
	}
} else if ($language == "es") {
	if ($nompeltitol == 'Derechoprivado') {
		$nomarea = 'Privado';
	} else if ($nompeltitol == 'Criminología') {
		$nomarea = 'Criminología';
	} else if ($nompeltitol == 'Derechopenal') {
		$nomarea = 'Penal';
	} else if ($nompeltitol == 'Publicoyregulatorio') {
		$nomarea = 'Público y regulatorio';
	}
} else if ($language == "ca") {
	if ($nompeltitol == 'Derechoprivado') {
		$nomarea = 'Privat';
	} else if ($nompeltitol == 'Criminología') {
		$nomarea = 'Criminologia';
	} else if ($nompeltitol == 'Derechopenal') {
		$nomarea = 'Penal';
	} else if ($nompeltitol == 'Publicoyregulatorio') {
		$nomarea = 'Públic i regulatori';
	}
}
?>
<?php if ($language == "en") { ?>
	<script>
		jQuery(document).ready(function($) { $(".titol-branca").html('<?php echo ($nomarea); ?>'); });
	</script>
<?php } else if ($language == "es") { ?>
	<script>
		jQuery(document).ready(function($) { $(".titol-branca").html('<?php echo ($nomarea); ?>'); });
	</script>
<?php } else if ($language == "ca") { ?>
	<script>
		jQuery(document).ready(function($) { $(".titol-branca").html('<?php echo ($nomarea); ?>'); });
	</script>
<?php } ?>

<div id="content" class="site-content container-fluid editorial-interior">
	<div id="primary" class="content-area container">
		<main id="main" class="site-main col-md-12" role="main">
			<div class="fitxa">
				<?php while (have_posts()) : the_post(); ?>
					<div class="entry-content">

						<div class="row">
							<div class="col-md-2">
							</div>
							<div class="col-md-9">
								<div class="edicion interior">
									<!--<?php echo $edicio ?>--><?php the_field('edicion_gral'); ?>
								</div>
								<div class="categoria interior"><?php if (get_field('edicion') == 'editorial') {
																	echo ('Editorial');
																} else {
																} ?></div>
								<h1 class="entry-title-interior"><?php the_title(); ?><br><span class="entry-title-interior-sub"><?php the_field('subtitulo'); ?></span></h1>
								<div class="xarxes_soc">
									<ul>
										<li class="xs_face"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="new"></a></li>
										<li class="xs_twit"><a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>" target="new"></a></li>
										<li class="xs_linked"><a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>&title=Revista%20InDret&source=indret.com" target="new"></a></li>
										<li class="xs_mail"><a class="order-button fancybox-inline" href="#contact-form" dataTitle="<?php echo get_permalink(); ?>"></a></li>
									</ul>
								</div>
								<div class="separa-petit"></div>
								<div class="autor-interior">
									<?php $terms = get_field('autor_id');
									if ($terms) : ?><span class="pertallarcoma"><?php foreach ($terms as $term) : ?><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?></span><?php endif; ?>
								</div>
								<div class="carrec"><?php the_field('organizacion'); ?></div>
							</div>
							<div class="col-md-1">
							</div>
						</div>

						<div class="row">
							<div class="col-md-2">
								<div class="dates">
									<?php if (get_field('fecha_recepcion')) : ?>
										<?php global $language;
										if ($language == "es") { ?>
											<p>Recepción<br><?php the_field('fecha_recepcion'); ?><span class="separa-mespetit-span"></span>Aceptación<br><?php the_field('fecha_aceptacion'); ?></p>
										<?php } else if ($language == "ca") { ?>
											<p>Recepció<br><?php the_field('fecha_recepcion'); ?><span class="separa-mespetit-span"></span>Acceptació<br><?php the_field('fecha_aceptacion'); ?></p>
										<?php } else if ($language == "en") { ?>
											<p>Reception<br><?php the_field('fecha_recepcion'); ?><span class="separa-mespetit-span"></span>Acceptance<br><?php the_field('fecha_aceptacion'); ?></p>
										<?php } ?>
									<?php endif; ?>
								</div>
							</div>
							<div class="col-md-9">
								<div class="abstract">
									<?php
									global $post;
									if (has_excerpt($post->ID)) {
										the_excerpt();
									} else {
										the_content();
									}
									?>
									<!--<?php if (get_field('edicion') == 'editorial') {
											the_content();
										} else {
											the_excerpt();
										} ?>-->
								</div>
								<div class="abstract">
									<?php global $language;
									if ($language == "es") { ?>
										<?php echo get_the_tag_list('<span class="keywords">Palabras Clave. </span>', ', ', '</span>'); ?>
									<?php } else if ($language == "ca") { ?>
										<?php echo get_the_tag_list('<span class="keywords">Paraules Clau. </span>', ', ', '</span>'); ?>
									<?php } else if ($language == "en") { ?>
										<?php echo get_the_tag_list('<span class="keywords">Keywords. </span>', ', ', '</span>'); ?>
									<?php } ?>
								</div>
								<?php if (get_field('extracto_eng') || get_field('titoleng') || get_the_terms($post->ID, 'etiquetas-eng')) : ?>
									<div class="abstract">
										<p class="separa-abstracts">—</p>
										<h1 class="entry-title-interior-eng"><?php the_field('titoleng'); ?></h1>
										<?php the_field('extracto_eng'); ?>
									</div>
									<div class="abstract">
										<?php if (get_the_terms($post->ID, 'etiquetas-eng')) : ?>
											<span class="keywords">Keywords. </span><?php the_terms($post->ID, 'etiquetas-eng', '', ', ', ' '); ?>
										<?php endif; ?>
									<?php endif; ?>
									<div class="separa-petit-interior"></div>
									<div class="abstract-regular">
										<?php global $language;
										if ($language == "es") { ?>
											Descargar PDF:
										<?php } else if ($language == "ca") { ?>
											Descarregar PDF:
										<?php } else if ($language == "en") { ?>
											PDF download:
										<?php } ?>

										<?php if ($pdfartic = get_field('pdf_articulo')) {
											$url = wp_get_attachment_url($pdfartic);
											$filesize = filesize(get_attached_file($pdfartic));
											$filesize = size_format($filesize);
										?>
											<a href="<?php echo $url; ?>" target="new" class="descarregues">Esp [<span class="pes"><?php echo $filesize; ?></span>]</a>

										<?php }
										if ($pdfartic_cat = get_field('pdf_articulo_cat')) {
											$url_cat = wp_get_attachment_url($pdfartic_cat);
											$filesize = filesize(get_attached_file($pdfartic_cat));
											$filesize = size_format($filesize);
										?>
											| <a href="<?php echo $url_cat; ?>" target="new" class="descarregues">Cat [<span class="pes"><?php echo $filesize; ?></span>]</a>

										<?php }
										if ($pdfartic_eng = get_field('pdf_articulo_eng')) {
											$url_eng = wp_get_attachment_url($pdfartic_eng);
											$filesize = filesize(get_attached_file($pdfartic_eng));
											$filesize = size_format($filesize);
										?>
											| <a href="<?php echo $url_eng; ?>" target="new" class="descarregues">Eng [<span class="pes"><?php echo $filesize; ?></span>]</a>
										<?php } ?>

										<?php if ($pdfartic = !get_field('pdf_articulo') && $pdfartic = !get_field('pdf_articulo_cat') && $pdfartic = !get_field('pdf_articulo_eng')) { ?>
											<a href="<?php bloginfo('template_directory'); ?>/pdf/<?php the_field('adjunto'); ?>" target="new" class="descarregues">Esp [<span class="pes_en_kb"></span>]</a>
										<?php } ?>
									</div>
									<div class="downview"><span class="descargas">
											<?php if ($language == "es") { ?>
										</span> descargas - <?php if (function_exists('the_views')) {
																the_views();
															} ?> visualizaciones</div>
								<?php } else if ($language == "ca") { ?>
									</span> descàrregues - <?php if (function_exists('the_views')) {
																the_views();
															} ?> visualitzacions
									</div>
								<?php } else if ($language == "en") { ?>
									</span> downloads - <?php if (function_exists('the_views')) {
															the_views();
														} ?> views
							</div>
						<?php } ?>
						<div class="separa-mespetit"></div>
						<div class="abstract-bold">
							<?php if (get_field('edicion') == 'editorial') {
							} else { ?>
								<span class="nomsautor">
									<?php $terms = get_field('autor_id');
									if ($terms) : ?><?php foreach ($terms as $term) : ?><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?>, </a><?php endforeach; ?><?php endif; ?>
								</span>
								&laquo;
								<?php if (get_field('subtitulo')) : ?><?php the_title(); ?>. <?php the_field('subtitulo'); ?>
								<?php else : ?><?php the_title(); ?>
							<?php endif; ?>
							&raquo;, <em>InDret</em> <span class="ed_gen"><?php the_field('edicion_gral'); ?></span>
							<?php
								$pps = get_field('paginas_cita');
								if ($pps == '') {
								} else { ?>, pp. <?php the_field('paginas_cita'); ?>.
						<?php } ?>
						<?php
								$doi = get_field('doi');
								if ($doi == '') {
								} else { ?> <a href="https://doi.org/<?php the_field('doi'); ?>" target="_blank"><?php the_field('doi'); ?></a>
						<?php } ?>
					<?php } ?>
						</div>
						</div>
						<div class="col-md-1">
						</div>
					</div>

			</div><!-- .entry-content -->
		<?php endwhile; // end of the loop. 
		?>
	</div>
	<!--contact form-->
	<div style="display:none" class="fancybox-hidden">
		<div id="contact-form">
			<span class="autor-interior">Enviar artículo</span>
			<?php echo do_shortcode('[contact-form-7 id="21197" title="Enviar articulo"]'); ?>
		</div>
	</div>
	</main><!-- #main -->
</div><!-- #primary -->
</div>

<?php get_footer(); ?>

<script type="text/javascript">
	jQuery(document).ready(function($) {
	String.prototype.insert = function(index, string) {
		if (index > 0)
			return this.substring(0, index) + string + this.substring(index, this.length);
		return string + this;
	};

	var str = $(".ed_gen").text();
	str = str.insert(2, 20);
	console.log(str);
	$(".ed_gen").html(str);

	$('.pertallarcoma').text(function(_, txt) {
		console.log(txt);
		return txt.slice(0, -2);
	});

	function formatBytes(a, b) {
		if (0 == a) return "0 Bytes";
		var c = 1024,
			d = b || 0,
			e = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"],
			f = Math.floor(Math.log(a) / Math.log(c));
		return parseFloat((a / Math.pow(c, f)).toFixed(d)) + " " + e[f]
	}

	var KB = '<?php the_field('tamano_adjunto'); ?>';
	var gg = formatBytes(KB);
	console.log('kb:', gg);
	$(".pes_en_kb").html(gg);

	function cuentadescargas() {
		var descargas_old = '<?php the_field('ranking_descargas'); ?>';
		if (descargas_old == '' || descargas_old == 'NULL') {
			descargas_old = 0;
		}
		console.log('descargas:', descargas_old);
		$(".descargas").html(descargas_old);
	}

	cuentadescargas();

	var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";

	$('.descarregues').click(function() {
		var descarregues_ant = '<?php the_field('ranking_descargas'); ?>'
		var postid = '<?php echo get_the_ID(); ?>'
		console.log(descarregues_ant);
		console.log('postid: ', postid);

		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: {
				action: 'SumaDescarregues',
				variable: descarregues_ant,
				variable2: postid
			},
			success: function(data) {
				console.log('success');
			},
			error: function(data) {
				console.log('error');
			}
		});
	});
	}); // end jQuery ready
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