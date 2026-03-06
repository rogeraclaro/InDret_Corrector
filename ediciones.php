<?php

/**
 * Template Name: Ediciones
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
		height: 50px;
	}

	.tooltip_templates {
		display: none;
	}

	.tooltipster-sidetip .tooltipster-box {
		background: #222;
		border: 2px solid #222;
		border-radius: 8px;
		/* padding: 1.5rem; */
		padding: 2rem 2rem 1rem 2rem;
	}

	.tooltipster-sidetip.tooltipster-right .tooltipster-arrow-background {
		border-right-color: #222;
	}

	.tooltipster-sidetip.tooltipster-right .tooltipster-arrow-border {
		border-right-color: #222;
	}

	.tooltipster-sidetip .tooltipster-content {
		padding: 0;
	}

	.tooltipster-box ul {
		list-style: none;
		margin: 0;
		padding: 0;
	}

	.tooltipster-box ul li {
		padding-bottom: 10px;
	}

	.tooltipster-box ul li a {
		font-size: 18px;
	}

	.tooltipster-box .ed-anterior a {
		color: rgb(255, 255, 255) !important;
	}

	.tooltipster-box .ed-anterior a:hover {
		color: rgb(253, 80, 44) !important;
	}
</style>

<?php $edicio_actual = get_field('edicion_actual'); ?>
<?php echo $edicio_actual; ?>


<div id="content" class="site-content container-fluid editorial-interior">
	<div id="primary" class="content-area container">
		<main id="main" class="site-main col-md-12" role="main">
			<div class="fitxa">
				<div class="entry-content">
					<div class="row">
						<div class="col-md-2">
						</div>
						<div class="col-md-8">
							<h1 class="entry-title-interior">
								<?php
								if ($language == "es") {
									printf(esc_html__('Ediciones anteriores'));
								} else if ($language == "ca") {
									printf(esc_html__('Edicions passades'));
								} else if ($language == "en") {
									printf(esc_html__('Past Editions'));
								} ?>
							</h1>
						</div>
						<div class="col-md-2">
						</div>
					</div>
					<!-- Editorials -->
					<?php if (have_rows('ediciones')) : ?>
						<div class="row">
							<?php while (have_rows('ediciones')) : the_row();
								// vars
								$any = get_sub_field('anyo');
								$edit1 = get_sub_field('ed1');
								$edit2 = get_sub_field('ed2');
								$edit3 = get_sub_field('ed3');
								$edit4 = get_sub_field('ed4');
								$arrel = get_home_url();
								$edicio1 = $arrel . $edit1;
								$edicio2 = $arrel . $edit2;
								$edicio3 = $arrel . $edit3;
								$edicio4 = $arrel . $edit4;
							?>
								<div class="col-xs-6 col-md-2">
									<div class="any-interior"><?php echo $any; ?></div>
									<div class="separa-gran"></div>
									<?php if (!empty($edit1)) { ?><div class="ed-anterior"><a href="<?php echo $edicio1; ?>" class="tooltip" data-tooltip-content="#tooltip_content_num1_<?php echo $any; ?>" />Num. — 1</a></div><?php } ?>
									<?php if (!empty($edit2)) { ?><div class="ed-anterior"><a href="<?php echo $edicio2; ?>" class="tooltip" data-tooltip-content="#tooltip_content_num2_<?php echo $any; ?>" />Num. — 2</a></div><?php } ?>
									<?php if (!empty($edit3)) { ?><div class="ed-anterior"><a href="<?php echo $edicio3; ?>" class="tooltip" data-tooltip-content="#tooltip_content_num3_<?php echo $any; ?>" />Num. — 3</a></div><?php } ?>
									<?php if (!empty($edit4)) { ?><div class="ed-anterior"><a href="<?php echo $edicio4; ?>" class="tooltip" data-tooltip-content="#tooltip_content_num4_<?php echo $any; ?>" />Num. — 4</a></div><?php } ?>
								</div>
								<div class="tooltip_templates">

									<?php
									if ($language == "es") {
									?>
										<span id="tooltip_content_num1_<?php echo $any; ?>">
											<ul>
												<li>
													<div class="ed-anterior"><a href="/Derechoprivado/<?php echo $edit1; ?>" />Privado</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Derechopenal/<?php echo $edit1; ?>" />Penal</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Criminologia/<?php echo $edit1; ?>" />Criminología</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Publicoyregulatorio/<?php echo $edit1; ?>" />Público y regulatorio</a></div>
												</li>
											</ul>
										</span>
										<span id="tooltip_content_num2_<?php echo $any; ?>">
											<ul>
												<li>
													<div class="ed-anterior"><a href="/Derechoprivado/<?php echo $edit2; ?>" />Privado</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Derechopenal/<?php echo $edit2; ?>" />Penal</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Criminologia/<?php echo $edit2; ?>" />Criminología</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Publicoyregulatorio/<?php echo $edit2; ?>" />Público y regulatorio</a></div>
												</li>
											</ul>
										</span>
										<span id="tooltip_content_num3_<?php echo $any; ?>">
											<ul>
												<li>
													<div class="ed-anterior"><a href="/Derechoprivado/<?php echo $edit3; ?>" />Privado</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Derechopenal/<?php echo $edit3; ?>" />Penal</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Criminologia/<?php echo $edit3; ?>" />Criminología</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Publicoyregulatorio/<?php echo $edit3; ?>" />Público y regulatorio</a></div>
												</li>
											</ul>
										</span>
										<span id="tooltip_content_num4_<?php echo $any; ?>">
											<ul>
												<li>
													<div class="ed-anterior"><a href="/Derechoprivado/<?php echo $edit4; ?>" />Privado</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Derechopenal/<?php echo $edit4; ?>" />Penal</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Criminologia/<?php echo $edit4; ?>" />Criminología</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Publicoyregulatorio/<?php echo $edit4; ?>" />Público y regulatorio</a></div>
												</li>
											</ul>
										</span>
									<?php
									} else if ($language == "ca") {
									?>
										<span id="tooltip_content_num1_<?php echo $any; ?>">
											<ul>
												<li>
													<div class="ed-anterior"><a href="/Derechoprivado/<?php echo $edit1; ?>" />Privat</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Derechopenal/<?php echo $edit1; ?>" />Penal</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Criminologia/<?php echo $edit1; ?>" />Criminologia</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Publicoyregulatorio/<?php echo $edit1; ?>" />Públic i regulatori</a></div>
												</li>
											</ul>
										</span>
										<span id="tooltip_content_num2_<?php echo $any; ?>">
											<ul>
												<li>
													<div class="ed-anterior"><a href="/Derechoprivado/<?php echo $edit2; ?>" />Privat</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Derechopenal/<?php echo $edit2; ?>" />Penal</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Criminologia/<?php echo $edit2; ?>" />Criminologia</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Publicoyregulatorio/<?php echo $edit2; ?>" />Públic i regulatori</a></div>
												</li>
											</ul>
										</span>
										<span id="tooltip_content_num3_<?php echo $any; ?>">
											<ul>
												<li>
													<div class="ed-anterior"><a href="/Derechoprivado/<?php echo $edit3; ?>" />Privat</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Derechopenal/<?php echo $edit3; ?>" />Penal</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Criminologia/<?php echo $edit3; ?>" />Criminologia</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Publicoyregulatorio/<?php echo $edit3; ?>" />Públic i regulatori</a></div>
												</li>
											</ul>
										</span>
										<span id="tooltip_content_num4_<?php echo $any; ?>">
											<ul>
												<li>
													<div class="ed-anterior"><a href="/Derechoprivado/<?php echo $edit4; ?>" />Privat</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Derechopenal/<?php echo $edit4; ?>" />Penal</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Criminologia/<?php echo $edit4; ?>" />Criminologia</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Publicoyregulatorio/<?php echo $edit4; ?>" />Públic i regulatori</a></div>
												</li>
											</ul>
										</span>
									<?php
									} else if ($language == "en") {
									?>
										<span id="tooltip_content_num1_<?php echo $any; ?>">
											<ul>
												<li>
													<div class="ed-anterior"><a href="/Derechoprivado/<?php echo $edit1; ?>" />Private law</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Derechopenal/<?php echo $edit1; ?>" />Criminal law</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Criminologia/<?php echo $edit1; ?>" />Criminology</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Publicoyregulatorio/<?php echo $edit1; ?>" />Administrative law</a></div>
												</li>
											</ul>
										</span>
										<span id="tooltip_content_num2_<?php echo $any; ?>">
											<ul>
												<li>
													<div class="ed-anterior"><a href="/Derechoprivado/<?php echo $edit2; ?>" />Private law</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Derechopenal/<?php echo $edit2; ?>" />Criminal law</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Criminologia/<?php echo $edit2; ?>" />Criminology</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Publicoyregulatorio/<?php echo $edit2; ?>" />Administrative law</a></div>
												</li>
											</ul>
										</span>
										<span id="tooltip_content_num3_<?php echo $any; ?>">
											<ul>
												<li>
													<div class="ed-anterior"><a href="/Derechoprivado/<?php echo $edit3; ?>" />Private law</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Derechopenal/<?php echo $edit3; ?>" />Criminal law</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Criminologia/<?php echo $edit3; ?>" />Criminology</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Publicoyregulatorio/<?php echo $edit3; ?>" />Administrative law</a></div>
												</li>
											</ul>
										</span>
										<span id="tooltip_content_num4_<?php echo $any; ?>">
											<ul>
												<li>
													<div class="ed-anterior"><a href="/Derechoprivado/<?php echo $edit4; ?>" />Private law</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Derechopenal/<?php echo $edit4; ?>" />Criminal law</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Criminologia/<?php echo $edit4; ?>" />Criminology</a></div>
												</li>
												<li>
													<div class="ed-anterior"><a href="/Publicoyregulatorio/<?php echo $edit4; ?>" />Administrative law</a></div>
												</li>
											</ul>
										</span>
									<?php
									} ?>

								</div>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
				</div><!-- .entry-content -->
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->
</div>

<?php get_footer(); ?>

<!--
<script type="text/javascript">

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
</script>
-->