<?php

/**
 * start functions and definitions
 *
 * @package InDret
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if (!isset($content_width)) {
	$content_width = 640; /* pixels */
}

if (!function_exists('start_setup')) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function start_setup()
	{

		/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on start, use a find and replace
	 * to change 'start' to the name of your theme in all the template files
	 */
		load_theme_textdomain('start', get_template_directory() . '/languages');

		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
		add_theme_support('title-tag');

		/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
		add_theme_support('post-thumbnails');

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(array(
			'primary' => esc_html__('Primary Menu', 'start'),
		));

		/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
		add_theme_support('html5', array(
			'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
		));


		// Set up the WordPress core custom background feature.
		add_theme_support('custom-background', apply_filters('start_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		)));
	}
endif; // start_setup
add_action('after_setup_theme', 'start_setup');

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function start_widgets_init()
{
	register_sidebar(array(
		'name'          => esc_html__('Sidebar', 'start'),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	));
}
add_action('widgets_init', 'start_widgets_init');

/**
 * Desactivar emoji JS de WordPress (innecessari)
 */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

/**
 * Favicon SVG
 */
function indret_favicon() {
	echo '<link rel="icon" type="image/svg+xml" href="' . esc_url( get_template_directory_uri() ) . '/img/favicon.svg">' . "\n";
}
add_action( 'wp_head', 'indret_favicon', 1 );

/**
 * Preload fonts crítiques (body + títols) per reduir FOIT i millorar LCP
 */
function indret_preload_fonts() {
	$base = esc_url( get_template_directory_uri() ) . '/tipos/';
	echo '<link rel="preload" href="' . $base . 'indretlect-italic-webfont.woff2" as="font" type="font/woff2" crossorigin>' . "\n";
	echo '<link rel="preload" href="' . $base . 'indret-bold-webfont.woff2" as="font" type="font/woff2" crossorigin>' . "\n";
}
add_action( 'wp_head', 'indret_preload_fonts', 2 );

/**
 * Enqueue scripts and styles.
 */
function start_scripts()
{
	wp_enqueue_style('start-style', get_stylesheet_uri());
	wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css');
	wp_enqueue_style('tooltipster', get_template_directory_uri() . '/css/tooltipster.bundle.min.css');

	wp_enqueue_script('start-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true);
	wp_enqueue_script('start-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true);
	wp_enqueue_script('tooltipster', get_template_directory_uri() . '/js/tooltipster.bundle.min.js', array('jquery'), '20220722', true);
	wp_add_inline_script('tooltipster', "jQuery(document).ready(function($) {
		$('.tooltip').tooltipster({
			theme: 'tooltipster-punk',
			animation: 'fade',
			contentCloning: 'true',
			interactive: 'true',
			side: 'right',
			trackOrigin: 'true',
			viewportAware: 'true',
		});
	});");


	/*if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}*/
}
add_action('wp_enqueue_scripts', 'start_scripts');


function custom_excerpt_length($length)
{
	return 500;
}
add_filter('excerpt_length', 'custom_excerpt_length', 999);


function add_query_vars_filter($vars)
{
	$vars[] = "edicion";
	return $vars;
}
add_filter('query_vars', 'add_query_vars_filter');

function exclude_terms($terms)
{
	$exclude_terms = array(155, 154, 153, 156, 158, 159, 188, 189); //put term ids here to remove!
	if (!empty($terms) && is_array($terms)) {
		foreach ($terms as $key => $term) {
			if (in_array($term->term_id, $exclude_terms)) {
				unset($terms[$key]);
			}
		}
	}
	return $terms;
}
add_filter('get_the_terms', 'exclude_terms');


/* treure comentaris */


// Disable support for comments and trackbacks in post types
function df_disable_comments_post_types_support()
{
	$post_types = get_post_types();
	foreach ($post_types as $post_type) {
		if (post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
}
add_action('admin_init', 'df_disable_comments_post_types_support');

// Close comments on the front-end
function df_disable_comments_status()
{
	return false;
}
add_filter('comments_open', 'df_disable_comments_status', 20, 2);
add_filter('pings_open', 'df_disable_comments_status', 20, 2);

// Hide existing comments
function df_disable_comments_hide_existing_comments($comments)
{
	$comments = array();
	return $comments;
}
add_filter('comments_array', 'df_disable_comments_hide_existing_comments', 10, 2);

// Remove comments page in menu
function df_disable_comments_admin_menu()
{
	remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'df_disable_comments_admin_menu');

// Redirect any user trying to access comments page
function df_disable_comments_admin_menu_redirect()
{
	global $pagenow;
	if ($pagenow === 'edit-comments.php') {
		wp_redirect(admin_url());
		exit;
	}
}
add_action('admin_init', 'df_disable_comments_admin_menu_redirect');

// Remove comments metabox from dashboard
function df_disable_comments_dashboard()
{
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'df_disable_comments_dashboard');

// Remove comments links from admin bar
function df_disable_comments_admin_bar()
{
	if (is_admin_bar_showing()) {
		remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
	}
}
add_action('init', 'df_disable_comments_admin_bar');


function my_theme_archive_title($title)
{
	if (is_category()) {
		$title = single_cat_title('', false);
	} elseif (is_tag()) {
		$title = single_tag_title('', false);
	} elseif (is_author()) {
		$title = '<span class="vcard">' . get_the_author() . '</span>';
	} elseif (is_post_type_archive()) {
		$title = post_type_archive_title('', false);
	} elseif (is_tax()) {
		$title = single_term_title('', false);
	}

	return $title;
}

add_filter('get_the_archive_title', 'my_theme_archive_title');


/* Registra menus */

function register_my_menu()
{
	register_nav_menu('principal_ca', __('principal_ca'));
}
add_action('init', 'register_my_menu');

function register_my_menu2()
{
	register_nav_menu('principal_en', __('principal_en'));
}
add_action('init', 'register_my_menu2');



/* Plana ACF Configuració general */

if (function_exists('acf_add_options_page')) {
	acf_add_options_page('InDret Configuración');
}


/* custom login */

function custom_loginlogo()
{
	echo '<style type="text/css">
	body {background-image: url(' . get_bloginfo('template_directory') . '/img/logo.png) !important; }
	h1 a {background-image: url(' . get_bloginfo('template_directory') . '/img/logo.png) !important; }
	#backtoblog, #nav { 
    margin-top: 20px !important;
    margin-left: 0 !important;
    padding: 10px !important;
    background: #fff !important;
    -webkit-box-shadow: 0 1px 3px rgba(0,0,0,.13) !important;
    box-shadow: 0 1px 3px rgba(0,0,0,.13) !important;
	}
	</style>';
}
add_action('login_head', 'custom_loginlogo');

function my_login_logo_url()
{
	return home_url();
}
add_filter('login_headerurl', 'my_login_logo_url');

function my_login_logo_url_title()
{
	return 'InDret';
}
add_filter('init', 'my_login_logo_url_title');



/* Amagar updates a tots menys admin */

function hide_update_notice_to_all_but_admin_users()
{
	if (!current_user_can('update_core')) {
		remove_action('admin_notices', 'update_nag', 3);
	}
}
add_action('admin_head', 'hide_update_notice_to_all_but_admin_users', 1);


/* Idiomes amb cookies */

///// Set cookie
add_action('init', function () {

	global $language;

	$language = 'es';

	/////set cookie from GET:
	if (isset($_GET['lang'])) {
		$lang = $_GET['lang'];
		setcookie('lang', $lang, strtotime('1 day'), "/", false);
	}
	///receive value from cookie and get	
	if (isset($_COOKIE['lang'])) {
		//echo "<br>cookie:<br>";
		//echo $_COOKIE['lang'];  

		$language = $_COOKIE['lang']; ///sanitize this later… 
	}
	if (isset($_GET['lang'])) {
		//echo "<br>GET:<br>";
		//echo $_GET['lang']; 

		$language = $_GET['lang']; //// sanitize	
	}
	//echo "<br>language global is: ".$language."<br><br>";		
});
//// end set cookie

/// Contador descarregues combinat amb ACF

add_action('wp_ajax_SumaDescarregues', 'SumaDescarregues');
add_action('wp_ajax_nopriv_SumaDescarregues', 'SumaDescarregues');

function SumaDescarregues()
{
	$data = $_POST['variable'];
	$post_id = $_POST['variable2'];
	$data++;
	update_field('ranking_descargas', $data, $post_id);
	die();
}

//Exclude pages from WordPress Search

if (!is_admin()) {
	function wpb_search_filter($query)
	{
		if ($query->is_search) {
			$query->set('post_type', 'post');
		}
		return $query;
	}
	add_filter('pre_get_posts', 'wpb_search_filter');
}

// Excerpts més alts a l'admin

add_action('admin_head', 'excerpt_textarea_height');
function excerpt_textarea_height()
{
	echo '
        <style type="text/css">
                #excerpt{ height:250px; }
        </style>
        ';
}

// Usiari editor amb accés a llistats CFDB7

$role = get_role('editor');
if (!$role->has_cap('cfdb7_access')) {
	$role->add_cap('cfdb7_access');
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/*
add_filter( 'get_terms_args', 'wpse_53094_sort_get_terms_args', 10, 2 );
function wpse_53094_sort_get_terms_args( $args, $taxonomies ) 
{
    global $pagenow;
    if( !is_admin() || ('post.php' != $pagenow && 'post-new.php' != $pagenow) ) 
        return $args;

    $args['orderby'] = 'slug';
    $args['order'] = 'DESC';

    return $args;
}
*/

// ============================================
// CERCA AVANÇADA INDRET
// Afegir aquest codi al final del functions.php
// ============================================

/**
 * Filtre per evitar errors amb taxonomies d'autor en queries
 */
add_action('pre_get_posts', function($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_tax('autor')) {
        // Assegurar que la query utilitza term_id correctament
        $term = get_queried_object();
        if ($term && isset($term->term_id)) {
            $query->set('tax_query', array(
                array(
                    'taxonomy' => 'autor',
                    'field' => 'term_id',
                    'terms' => intval($term->term_id)
                )
            ));
        }
    }
});

/**
 * Funció helper per obtenir autors de forma segura
 * Retorna un array d'objectes WP_Term vàlids o array buit
 */
function indret_get_post_authors($post_id = null) {
    // Intentar obtenir el valor sense cap error
    try {
        // Utilitzar false per evitar que ACF formategi el valor
        $terms = @get_field('autor_id', $post_id, false);
    } catch (Exception $e) {
        // Si hi ha error, retornar array buit
        return array();
    }

    // Si està buit, és false, o és null, retornar array buit
    if (empty($terms) || $terms === false || $terms === null) {
        return array();
    }

    // Si és un string buit, retornar array buit
    if (is_string($terms) && trim($terms) === '') {
        return array();
    }

    // Si no és array, convertir a array
    if (!is_array($terms)) {
        $terms = array($terms);
    }

    // Filtrar només objectes WP_Term vàlids
    $valid_terms = array();
    foreach ($terms as $term_data) {
        // Saltar si és buit, null, o string buit
        if (empty($term_data) || $term_data === null || (is_string($term_data) && trim($term_data) === '')) {
            continue;
        }

        $term_obj = null;

        // Si és un ID (numèric o string numèric)
        if (is_numeric($term_data)) {
            $term_id = intval($term_data);
            if ($term_id > 0) {
                $term_obj = @get_term($term_id, 'autor');
            }
        }
        // Si ja és un objecte WP_Term
        elseif (is_object($term_data) && isset($term_data->term_id)) {
            $term_obj = $term_data;
        }

        // Només afegir si és un objecte vàlid i no és un error
        if ($term_obj && is_object($term_obj) && !is_wp_error($term_obj) && isset($term_obj->term_id) && $term_obj->term_id > 0) {
            $valid_terms[] = $term_obj;
        }
    }

    return $valid_terms;
}

/**
 * Shortcode per a la cerca avançada
 * Ús: [indret_advanced_search]
 */
function indret_advanced_search_shortcode() {
    ob_start();
    get_template_part('template-parts/advanced-search');
    return ob_get_clean();
}
add_shortcode('indret_advanced_search', 'indret_advanced_search_shortcode');

/**
 * Funció per obtenir tots els autors (taxonomia)
 */
function indret_get_all_authors() {
    $authors = get_terms(array(
        'taxonomy' => 'autor',
        'hide_empty' => false,
        'orderby' => 'name',
        'order' => 'ASC'
    ));

    // Ordenar per cognom (assumint format "Nom Cognom" o "Nom Cognom1 Cognom2")
    if (!is_wp_error($authors) && !empty($authors)) {
        usort($authors, function($a, $b) {
            // Extreure el cognom (tot després del primer espai)
            $name_a = trim($a->name);
            $name_b = trim($b->name);

            // Trobar la posició del primer espai
            $space_pos_a = strpos($name_a, ' ');
            $space_pos_b = strpos($name_b, ' ');

            // Si hi ha espai, agafem la part després del primer espai (cognom)
            // Si no hi ha espai, utilitzem el nom complet
            $surname_a = ($space_pos_a !== false) ? substr($name_a, $space_pos_a + 1) : $name_a;
            $surname_b = ($space_pos_b !== false) ? substr($name_b, $space_pos_b + 1) : $name_b;

            // Comparació sense distinció de majúscules/minúscules i accents
            return strcasecmp($surname_a, $surname_b);
        });
    }

    return $authors;
}

/**
 * Funció per obtenir totes les edicions úniques
 */
function indret_get_all_editions() {
    global $wpdb;
    $editions = $wpdb->get_col($wpdb->prepare("
        SELECT DISTINCT meta_value
        FROM {$wpdb->postmeta}
        WHERE meta_key = %s
        AND meta_value != ''
    ", 'edicion_gral'));
    
    // Ordenar per any (descendant) i després per trimestre (descendant)
    usort($editions, function($a, $b) {
        // Separar trimestre i any
        $parts_a = explode('.', $a);
        $parts_b = explode('.', $b);
        
        $trimestre_a = isset($parts_a[0]) ? intval($parts_a[0]) : 0;
        $any_a = isset($parts_a[1]) ? intval($parts_a[1]) : 0;
        
        $trimestre_b = isset($parts_b[0]) ? intval($parts_b[0]) : 0;
        $any_b = isset($parts_b[1]) ? intval($parts_b[1]) : 0;
        
        // Primer comparar per any (descendant)
        if ($any_b != $any_a) {
            return $any_b - $any_a;
        }
        
        // Si l'any és igual, comparar per trimestre (descendant)
        return $trimestre_b - $trimestre_a;
    });
    
    return $editions;
}

/**
 * Funció per obtenir tots els tags únics
 */
function indret_get_all_tags() {
    $tags = get_terms(array(
        'taxonomy' => 'post_tag',
        'hide_empty' => false,
        'orderby' => 'name',
        'order' => 'ASC'
    ));
    return $tags;
}

/**
 * Funció per obtenir totes les organitzacions úniques
 */
function indret_get_all_organizations() {
    global $wpdb;
    $organizations = $wpdb->get_col($wpdb->prepare("
        SELECT DISTINCT meta_value
        FROM {$wpdb->postmeta}
        WHERE meta_key = %s
        AND meta_value != ''
        ORDER BY meta_value ASC
    ", 'organizacion'));
    return $organizations;
}

/**
 * Query personalitzada per a la cerca avançada
 */
function indret_advanced_search_query($search_params) {
    global $language;
    
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 20,
        'paged' => isset($search_params['paged']) ? intval($search_params['paged']) : 1,
        'meta_query' => array('relation' => 'AND'),
        'tax_query' => array('relation' => 'AND'),
    );

    // DEBUG TEMPORAL
    // error_log('Search params rebuts: ' . print_r($search_params, true));

    // Text lliure - cerca en títol i contingut segons idioma
    if (!empty($search_params['text_search'])) {
        $search_text = sanitize_text_field($search_params['text_search']);
        
        if ($language == 'en') {
            // Cerca en camps ACF per anglès
            $args['meta_query'][] = array(
                'relation' => 'OR',
                array(
                    'key' => 'titoleng',
                    'value' => $search_text,
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => 'extracto_eng',
                    'value' => $search_text,
                    'compare' => 'LIKE'
                )
            );
        } else {
            // Cerca en títol i contingut estàndard per ES/CA
            $args['s'] = $search_text;
        }
    }

    // Filtre per Àrea (nombre_area)
    if (!empty($search_params['nombre_area'])) {
        $args['meta_query'][] = array(
            'key' => 'nombre_area',
            'value' => sanitize_text_field($search_params['nombre_area']),
            'compare' => '='
        );
    }

    // Filtre per Subàrea (nombre_subarea)
    if (!empty($search_params['nombre_subarea'])) {
        $args['meta_query'][] = array(
            'key' => 'nombre_subarea',
            'value' => sanitize_text_field($search_params['nombre_subarea']),
            'compare' => '='
        );
    }

    // Filtre per Autor (taxonomia)
    if (!empty($search_params['autor_id'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'autor',
            'field' => 'term_id',
            'terms' => intval($search_params['autor_id'])
        );
    }

    // Filtre per Títol específic
    if (!empty($search_params['title_search'])) {
        if ($language == 'en') {
            $args['meta_query'][] = array(
                'key' => 'titoleng',
                'value' => sanitize_text_field($search_params['title_search']),
                'compare' => 'LIKE'
            );
        } else {
            add_filter('posts_where', function($where) use ($search_params) {
                global $wpdb;
                $title = sanitize_text_field($search_params['title_search']);
                $where .= $wpdb->prepare(
                    " AND {$wpdb->posts}.post_title LIKE %s",
                    '%' . $wpdb->esc_like($title) . '%'
                );
                return $where;
            });
        }
    }

    // Filtre per Subtítol
    if (!empty($search_params['subtitulo'])) {
        $args['meta_query'][] = array(
            'key' => 'subtitulo',
            'value' => sanitize_text_field($search_params['subtitulo']),
            'compare' => 'LIKE'
        );
    }

    // Filtre per Organització
    if (!empty($search_params['organizacion'])) {
        $args['meta_query'][] = array(
            'key' => 'organizacion',
            'value' => sanitize_text_field($search_params['organizacion']),
            'compare' => 'LIKE'
        );
    }

    // Filtre per Tags (múltiple)
    if (!empty($search_params['tag'])) {
        // Assegurar que és un array
        $tag_ids = is_array($search_params['tag']) ? $search_params['tag'] : array($search_params['tag']);
        $tag_ids = array_filter(array_map('intval', $tag_ids)); // Netejar i convertir a int
        
        if (!empty($tag_ids)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'post_tag',
                'field' => 'term_id',
                'terms' => $tag_ids,
                'operator' => 'IN' // Articles que tinguin ALGUN dels tags seleccionats
            );
        }
    }

    // Filtre per rang de dates (fecha_aceptacion)
    if (!empty($search_params['date_from']) || !empty($search_params['date_to'])) {
        $date_query = array(
            'key' => 'fecha_aceptacion',
            'type' => 'DATE',
            'compare' => 'BETWEEN'
        );

        if (!empty($search_params['date_from']) && !empty($search_params['date_to'])) {
            // Convertir a format Ymd per comparació
            $date_from = sanitize_text_field($search_params['date_from']);
            $date_to = sanitize_text_field($search_params['date_to']);

            $date_query['value'] = array(
                date('Ymd', strtotime($date_from)),
                date('Ymd', strtotime($date_to))
            );
            $date_query['compare'] = 'BETWEEN';
        } elseif (!empty($search_params['date_from'])) {
            $date_from = sanitize_text_field($search_params['date_from']);
            $date_query['value'] = date('Ymd', strtotime($date_from));
            $date_query['compare'] = '>=';
        } elseif (!empty($search_params['date_to'])) {
            $date_to = sanitize_text_field($search_params['date_to']);
            $date_query['value'] = date('Ymd', strtotime($date_to));
            $date_query['compare'] = '<=';
        }

        $args['meta_query'][] = $date_query;
    }

    // Filtre per Edició
    if (!empty($search_params['edicion_gral'])) {
        $args['meta_query'][] = array(
            'key' => 'edicion_gral',
            'value' => sanitize_text_field($search_params['edicion_gral']),
            'compare' => '='
        );
    }

    // Ordenació per descarregues
    if (!empty($search_params['orderby']) && $search_params['orderby'] == 'downloads') {
        $args['meta_key'] = 'ranking_descargas';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
    } else {
        // Ordenació per defecte (rellevància o data)
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
    }

    // DEBUG TEMPORAL
    // error_log('Args finals de WP_Query: ' . print_r($args, true));

    return new WP_Query($args);
}

/**
 * Enqueue styles i scripts per a la cerca avançada
 */
function indret_advanced_search_styles() {
    if (is_page(array('busqueda-avanzada', 'cerca-avancada', 'advanced-search'))) {
        wp_enqueue_style('tom-select', get_template_directory_uri() . '/css/tom-select.min.css', array(), '2.3.1');
        wp_enqueue_style('indret-advanced-search', get_template_directory_uri() . '/css/advanced-search.css', array('tom-select'), '1.0.0');
        wp_enqueue_script('tom-select', get_template_directory_uri() . '/js/tom-select.complete.min.js', array(), '2.3.1', true);
        wp_enqueue_script('indret-advanced-search-js', get_template_directory_uri() . '/js/advanced-search.js', array('jquery', 'tom-select'), '1.0.0', true);
        
        // Passar variables a JavaScript
        wp_localize_script('indret-advanced-search-js', 'indretSearch', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('indret_search_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'indret_advanced_search_styles');

/**
 * Handler AJAX per a la cerca avançada
 */
add_action('wp_ajax_indret_advanced_search', 'indret_ajax_search_handler');
add_action('wp_ajax_nopriv_indret_advanced_search', 'indret_ajax_search_handler');

function indret_ajax_search_handler() {
    // Verificar nonce
    check_ajax_referer('indret_search_nonce', 'nonce');
    
    global $language;
    
    // Obtenir idioma de la petició
    $language = isset($_POST['language']) ? sanitize_text_field($_POST['language']) : 'es';

    // IMPORTANT: Forçar idioma també a la cookie per si de cas
    $_COOKIE['lang'] = $language;
    
    // Textos segons idioma
    $texts = array(
        'es' => array(
            'results_found' => 'resultados encontrados',
            'no_results' => 'No se encontraron resultados',
            'share' => 'Compartir',
            'downloads' => 'descargas'
        ),
        'ca' => array(
            'results_found' => 'resultats trobats',
            'no_results' => 'No s\'han trobat resultats',
            'share' => 'Compartir',
            'downloads' => 'descàrregues'
        ),
        'en' => array(
            'results_found' => 'results found',
            'no_results' => 'No results found',
            'share' => 'Share',
            'downloads' => 'downloads'
        )
    );
    
    $t = $texts[$language];
    
    // Obtenir paràmetres de cerca
    $search_params = array(
        'text_search' => isset($_POST['text_search']) ? $_POST['text_search'] : '',
        'nombre_area' => isset($_POST['nombre_area']) ? $_POST['nombre_area'] : '',
        'nombre_subarea' => isset($_POST['nombre_subarea']) ? $_POST['nombre_subarea'] : '',
        'autor_id' => isset($_POST['autor_id']) ? $_POST['autor_id'] : '',
        'title_search' => isset($_POST['title_search']) ? $_POST['title_search'] : '',
        'subtitulo' => isset($_POST['subtitulo']) ? $_POST['subtitulo'] : '',
        'organizacion' => isset($_POST['organizacion']) ? $_POST['organizacion'] : '',
        'tag' => isset($_POST['tag']) ? $_POST['tag'] : array(),
        'date_from' => isset($_POST['date_from']) ? $_POST['date_from'] : '',
        'date_to' => isset($_POST['date_to']) ? $_POST['date_to'] : '',
        'edicion_gral' => isset($_POST['edicion_gral']) ? $_POST['edicion_gral'] : '',
        'orderby' => isset($_POST['orderby']) ? $_POST['orderby'] : 'date',
        'paged' => isset($_POST['paged']) ? intval($_POST['paged']) : 1,
    );
    
    // Executar cerca
    $search_results = indret_advanced_search_query($search_params);
    
    // Preparar resposta HTML
    ob_start();
    
    if ($search_results->have_posts()) :
        ?>
        <div class="results-header" style="padding: 20px 0;">
            <h2><?php echo $search_results->found_posts; ?> <?php echo $t['results_found']; ?></h2>
        </div>
        
        <?php while ($search_results->have_posts()) : $search_results->the_post(); 
            // Calcular nom d'àrea segons idioma
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
            <article id="post-<?php the_ID(); ?>" <?php post_class('col-md-6'); ?>>
                <div class="entry-header altura_entrada_home">
                    <div class="edicion"><?php the_field('edicion_gral'); ?></div>

                    <div class="categoria">
                        <?php echo $nomarea; ?>
                    </div>

                    <h1 class="entry-title">
                        <a href="<?php the_permalink(); ?>" rel="bookmark">
                            <?php 
                            if ($language == 'en' && get_field('titoleng')) {
                                the_field('titoleng');
                            } else {
                                the_title();
                            }
                            ?>
                        </a>
                    </h1>
                    
                    <div class="peu_entrada_home">
                        <span class="sep">_</span><br>
                        <div class="autor">
                            <?php
                            $terms = indret_get_post_authors();
                            if(!empty($terms)) :
                            ?>
                                <span class="pertallarcoma">
                                    <?php foreach($terms as $term) : ?>
                                        <a href="<?php echo esc_url(get_term_link($term)); ?>"><?php echo esc_html($term->name); ?>, </a>
                                    <?php endforeach; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                            <div class="xarxes_soc">
                                <span class="descargas"><?php 
                                    $descargas = get_field('ranking_descargas');
                                    echo ($descargas && $descargas != 'NULL') ? $descargas : '0';
                                ?></span>
                                <?php echo $t['downloads']; ?>
                            </div>

                            <!-- <div clas="xarxes_soc botons">
                                <?php echo $t['share']; ?>
                                <ul>
                                    <li class="xs_face"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="new"></a></li>
                                    <li class="xs_twit"><a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>" target="new"></a></li>
                                    <li class="xs_linked"><a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>&title=Revista%20InDret&source=indret.com" target="new"></a></li>
                                    <li class="xs_mail"><a class="order-button fancybox-inline" href="#contact-form" dataTitle="<?php echo get_permalink(); ?>"></a></li>
                                </ul>
                            </div> -->
                    </div>

                </div><!-- .entry-header -->
            </article><!-- #post-## -->
        <?php endwhile; ?>

        <?php if ($search_results->max_num_pages > 1) : 
            $current_page = isset($search_params['paged']) ? intval($search_params['paged']) : 1; ?>
            <div class="search-pagination" style="clear:both; padding-top: 30px;">
                <?php
                echo paginate_links(array(
                    'total' => $search_results->max_num_pages,
                    'current' => $current_page,
                    'prev_text' => '&laquo;',
                    'next_text' => '&raquo;',
                    'format' => '?paged=%#%',
                ));
                ?>
            </div>
        <?php endif; ?>
                
    <?php else : ?>
        <div class="results-header" style="padding: 20px 0;">
            <h2><?php echo $t['no_results']; ?></h2>
        </div>
    <?php endif;
    
    $html = ob_get_clean();
    wp_reset_postdata();

    // Retornar resposta JSON
    wp_send_json_success(array(
        'html' => $html,
        'found' => $search_results->found_posts
    ));
}

/**
 * Registre del CPT 'sumarios' en codi com a fallback.
 * CPTUI el registra via BD; si la BD local és incompleta o antiga,
 * aquest registre garanteix que el CPT existeix sempre.
 */
add_action( 'init', function() {
    if ( ! post_type_exists( 'sumarios' ) ) {
        register_post_type( 'sumarios', array(
            'labels' => array(
                'name'          => 'Sumarios',
                'singular_name' => 'Sumario',
                'add_new_item'  => 'Añadir sumario',
                'edit_item'     => 'Editar sumario',
                'view_item'     => 'Ver sumario',
            ),
            'public'      => true,
            'has_archive' => false,
            'show_in_rest'=> false,
            'supports'    => array( 'title', 'editor', 'custom-fields' ),
            'rewrite'     => array( 'slug' => 'sumarios' ),
            'menu_icon'   => 'dashicons-list-view',
        ) );
    }

    // Assegurar que existeix la regla de rewrite per a /sumarios/{slug}/
    // (necessari si CPTUI el va registrar amb un slug diferent o sense regles)
    add_rewrite_rule(
        '^sumarios/([^/]+)/?$',
        'index.php?post_type=sumarios&name=$matches[1]',
        'top'
    );

    // Flush una sola vegada
    if ( get_option( 'indret_sumarios_rules_flushed' ) !== '2' ) {
        flush_rewrite_rules( false );
        update_option( 'indret_sumarios_rules_flushed', '2' );
    }
} );

/**
 * Lazy-load reCAPTCHA de Contact Form 7
 *
 * CF7 carrega l'API de Google reCAPTCHA a TOTES les pàgines, fins i tot quan
 * el formulari és dins un div ocult (fancybox). Això suposa 3 peticions externes
 * innecessàries en cada page view.
 *
 * Solució: eliminem l'enqueue automàtic i carreguem reCAPTCHA + el mòdul CF7
 * de forma lazy quan l'usuari clica el botó que obre el formulari.
 */
function indret_cf7_lazy_recaptcha() {
    if ( ! class_exists( 'WPCF7_RECAPTCHA' ) ) {
        return;
    }

    $service = WPCF7_RECAPTCHA::get_instance();
    if ( ! $service || ! $service->is_active() ) {
        return;
    }

    // Eliminar l'enqueue automàtic de CF7 (priority 20)
    remove_action( 'wp_enqueue_scripts', 'wpcf7_recaptcha_enqueue_scripts', 20 );

    $sitekey   = $service->get_sitekey();
    $api_base  = apply_filters( 'wpcf7_use_recaptcha_net', false )
        ? 'https://www.recaptcha.net/recaptcha/api.js'
        : 'https://www.google.com/recaptcha/api.js';
    $api_url   = add_query_arg( array( 'render' => $sitekey ), $api_base );
    $module_url = plugins_url( 'contact-form-7/modules/recaptcha/index.js' );

    $actions = apply_filters( 'wpcf7_recaptcha_actions', array(
        'homepage'    => 'homepage',
        'contactform' => 'contactform',
    ) );

    $data = array(
        'sitekey'    => $sitekey,
        'actions'    => $actions,
        'api_url'    => $api_url,
        'module_url' => $module_url,
    );

    // Passar configuració a JS
    wp_add_inline_script(
        'jquery-core',
        'window._cf7RC = ' . wp_json_encode( $data ) . ';',
        'after'
    );

    // Inline JS: lazy-load en clicar qualsevol trigger del formulari de contacte
    wp_add_inline_script( 'jquery-core', "
(function() {
    var loaded = false;
    function loadCF7Recaptcha() {
        if (loaded || !window._cf7RC) return;
        loaded = true;
        var d = window._cf7RC;
        // Definir l'objecte que el mòdul CF7 espera trobar
        window.wpcf7_recaptcha = { sitekey: d.sitekey, actions: d.actions };
        // Carregar l'API de Google i el mòdul CF7 en paral·lel
        var pending = 2;
        function onLoaded() {
            pending--;
            if (pending === 0 && window.wpcf7 && window.wpcf7.initAll) {
                window.wpcf7.initAll();
            }
        }
        [d.api_url, d.module_url].forEach(function(src) {
            var s = document.createElement('script');
            s.src = src;
            s.async = true;
            s.onload = onLoaded;
            document.head.appendChild(s);
        });
    }
    document.addEventListener('click', function(e) {
        if (e.target && (
            e.target.classList.contains('order-button') ||
            e.target.closest('.order-button, [href=\"#contact-form\"]')
        )) {
            loadCF7Recaptcha();
        }
    }, true);
})();
", 'after' );
}
add_action( 'wp_enqueue_scripts', 'indret_cf7_lazy_recaptcha', 15 );
