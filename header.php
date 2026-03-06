	<?php
	/**
	 * The header for our theme.
	 *
	 * Displays all of the <head> section and everything up till <div id="content">
	 *
	 * @package InDret
	 */
	?>
	<!DOCTYPE html>
	<html <?php language_attributes(); ?>>

	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<!--<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">-->
		<meta name="author" content="Roger Masellas, roger@lamosca.com">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
		<link rel="preconnect" href="https://www.google.com">
		<link rel="preconnect" href="https://www.gstatic.com" crossorigin>
		<?php wp_head(); ?>
		<meta property="og:type" content="article" />
		<meta property="og:image" content="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo.png" />
		<!-- <script src="https://consent.cookiefirst.com/sites/indret.com-56cf81e6-fbf3-4d7a-aa8f-f3eff2ad9afb/consent.js"></script> -->
	</head>

	<body <?php body_class(); ?>>
		<!--
    Designed and coded by Roger Masellas
    http://www.masellas.info
    roger@lamosca.com
  -->
		<?php
		global $edicio;

		if (get_query_var('edicion')) :
			$edicio = $edicion;
		else :
			$edicio = get_field('edicion_actual', 'option');
		endif;
		?>

		<div id="page" class="hfeed site">
			<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'start'); ?></a>

			<header id="masthead" class="site-header capsalera" role="banner">
				<div class="container">
					<nav id="site-navigation" class="main-navigation col-md-12" role="navigation">
						<div class="col-sm-2 col-md-3 navmenu idiomes">
							<?php global $language;
							$data1 = array('lang' => 'es');
							$data2 = array('lang' => 'ca');
							$data3 = array('lang' => 'en');
							?>
							<div class="menu-idiomas-container">
								<?php global $language;
								if ($language == "es") { ?>
									<script>
										window.language = 'es';
									</script>
									<ul id="idiomes" class="menu nav-menu" aria-expanded="false">
										<li id="menu-item-20702" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-20702" aria-haspopup="true">
											<a href="<?php echo esc_url(add_query_arg($data1)); ?>">Español</a>
											<ul class="sub-menu">
												<li id="menu-item-20703" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-20703"><a href="<?php echo esc_url(add_query_arg($data2)); ?>">Català</a></li>
												<li id="menu-item-20704" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-20704"><a href="<?php echo esc_url(add_query_arg($data3)); ?>">English</a></li>
											</ul>
										</li>
									</ul>
								<?php } else if ($language == "ca") { ?>
									<script>
										window.language = 'ca';
									</script>
									<ul id="idiomes" class="menu nav-menu" aria-expanded="false">
										<li id="menu-item-20702" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-20702" aria-haspopup="true">
											<a href="<?php echo esc_url(add_query_arg($data2)); ?>">Català</a>
											<ul class="sub-menu">
												<li id="menu-item-20703" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-20703"><a href="<?php echo esc_url(add_query_arg($data3)); ?>">English</a></li>
												<li id="menu-item-20704" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-20704"><a href="<?php echo esc_url(add_query_arg($data1)); ?>">Español</a></li>
											</ul>
										</li>
									</ul>
								<?php } else if ($language == "en") { ?>
									<script>
										window.language = 'en';
									</script>
									<ul id="idiomes" class="menu nav-menu" aria-expanded="false">
										<li id="menu-item-20702" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-20702" aria-haspopup="true">
											<a href="<?php echo esc_url(add_query_arg($data3)); ?>">English</a>
											<ul class="sub-menu">
												<li id="menu-item-20703" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-20703"><a href="<?php echo esc_url(add_query_arg($data1)); ?>">Español</a></li>
												<li id="menu-item-20704" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-20704"><a href="<?php echo esc_url(add_query_arg($data2)); ?>">Català</a></li>
											</ul>
										</li>
									</ul>
								<?php }
								?>
							</div>
							<!--<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<?php esc_html_e('Idiomas', 'start'); ?></button>
					<?php wp_nav_menu(array('theme_location' => 'idiomes', 'menu_id' => 'idiomes')); ?>-->
						</div>
						<div class="col-sm-8 col-md-6 navmenu">
							<?php global $language;
							if ($language == "es") { ?>
								<!--<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
							<?php esc_html_e('Primary Menu', 'start'); ?></button>-->
								<?php wp_nav_menu(array('theme_location' => 'primary', 'menu_id' => 'primary-menu')); ?>
							<?php } else if ($language == "ca") { ?>
								<?php wp_nav_menu(array('theme_location' => 'principal_ca', 'menu_id' => 'principal_ca')); ?>
							<?php } else if ($language == "en") { ?>
								<?php wp_nav_menu(array('theme_location' => 'principal_en', 'menu_id' => 'principal_en')); ?>
							<?php }
							?>
						</div>
						<div class="col-sm-2 col-md-3 cerca">
							<?php global $language;
							if ($language == "es") { ?>
								<a href="/busqueda-avanzada/" class="advsearch">Búsqueda Avanzada</a>
							<?php } else if ($language == "ca") { ?>
								<a href="/cerca-avancada/" class="advsearch">Cerca Avançada</a>				
							<?php } else if ($language == "en") { ?>
								<a href="/advanced-search/" class="advsearch">Advanced Search</a>
							<?php }
							?>
						</div>
					</nav><!-- #site-navigation -->
				</div><!-- .container -->
			</header><!-- #masthead -->
			<div class="container-fluid editorial arees">
				<div class="container">
					<div class="row">
						<?php global $language;
						if ($language == "es") { ?>
							<div class="col-xs-6 col-md-3 col-center">
								<a href="<?php echo esc_url(get_home_url() . '/Derechoprivado/?edicion=' . urlencode($edicio)); ?>">Privado</a>
							</div>
							<div class="col-xs-6 col-md-3 col-center">
								<a href="<?php echo esc_url(get_home_url() . '/Derechopenal/?edicion=' . urlencode($edicio)); ?>">Penal</a>
							</div>
							<div class="col-xs-6 col-md-3 col-center">
								<a href="<?php echo esc_url(get_home_url() . '/Criminologia/?edicion=' . urlencode($edicio)); ?>">Criminología</a>
							</div>
							<div class="col-xs-6 col-md-3 col-center">
								<a href="<?php echo esc_url(get_home_url() . '/Publicoyregulatorio/?edicion=' . urlencode($edicio)); ?>">Público y
									regulatorio</a>
							</div>
						<?php } else if ($language == "ca") { ?>
							<div class="col-xs-6 col-md-3 col-center">
								<a href="<?php echo esc_url(get_home_url() . '/Derechoprivado/?edicion=' . urlencode($edicio)); ?>">Privat</a>
							</div>
							<div class="col-xs-6 col-md-3 col-center">
								<a href="<?php echo esc_url(get_home_url() . '/Derechopenal/?edicion=' . urlencode($edicio)); ?>">Penal</a>
							</div>
							<div class="col-xs-6 col-md-3 col-center">
								<a href="<?php echo esc_url(get_home_url() . '/Criminologia/?edicion=' . urlencode($edicio)); ?>">Criminologia</a>
							</div>
							<div class="col-xs-6 col-md-3 col-center">
								<a href="<?php echo esc_url(get_home_url() . '/Publicoyregulatorio/?edicion=' . urlencode($edicio)); ?>">Públic i
									regulatori</a>
							</div>
						<?php } else if ($language == "en") { ?>
							<div class="col-xs-6 col-md-3 col-center">
								<a href="<?php echo esc_url(get_home_url() . '/Derechoprivado/?edicion=' . urlencode($edicio)); ?>">Private law</a>
							</div>
							<div class="col-xs-6 col-md-3 col-center">
								<a href="<?php echo esc_url(get_home_url() . '/Derechopenal/?edicion=' . urlencode($edicio)); ?>">Criminal law</a>
							</div>
							<div class="col-xs-6 col-md-3 col-center">
								<a href="<?php echo esc_url(get_home_url() . '/Criminologia/?edicion=' . urlencode($edicio)); ?>">Criminology</a>
							</div>
							<div class="col-xs-6 col-md-3 col-center">
								<a href="<?php echo esc_url(get_home_url() . '/Publicoyregulatorio/?edicion=' . urlencode($edicio)); ?>">Administrative law</a>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="container marca">
				<div class="logo"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
						<!--<img src="<?php bloginfo('template_directory'); ?>/img/logo.png">--><span class="titol-mod">InDret </span>
					</a><span class="titol-mod titol-branca"></span></div>
				<span class="cap-rev">
					<?php global $language;
					if ($language == "es") {
						echo ("Revista para el Análisis del Derecho");
					} else if ($language == "ca") {
						echo ("Revista per a l'Anàlisi del Dret");
					} else if ($language == "en") {
						echo ("Review on the Analysis of Law");
					}
					?>
				</span>
				<span class="cap-num">Nº<?php echo $edicio[0]; ?> - 20<?php $resultat = substr($edicio, 2, 3);
																		echo $resultat; ?>
					- ISSN 1698-739X</span>
			</div>