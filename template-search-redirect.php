<?php
/**
 * Template Name: Search Redirect
 */

// Detectar idioma i redirigir
global $language;

// Obtenir idioma de GET, POST o cookie
if (isset($_GET['lang'])) {
    $language = sanitize_text_field($_GET['lang']);
    setcookie('lang', $language, strtotime('1 day'), "/", false);
} elseif (isset($_COOKIE['lang'])) {
    $language = $_COOKIE['lang'];
} else {
    $language = 'es';
}

// Redirigir a la pàgina correcta segons idioma
$redirect_urls = array(
    'es' => home_url('/busqueda-avanzada/'),
    'ca' => home_url('/cerca-avancada/'),
    'en' => home_url('/advanced-search/')
);

$current_url = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$target_url = $redirect_urls[$language];

// Només redirigir si no estem ja a la URL correcta
if (strpos($current_url, parse_url($target_url, PHP_URL_PATH)) === false) {
    wp_redirect($target_url);
    exit;
}

// Si ja estem a la URL correcta, carregar template
get_header();
get_template_part('template-parts/advanced-search');
get_footer();
?>