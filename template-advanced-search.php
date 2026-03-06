<?php
/**
 * Template Name: Advanced Search
 */

// Forçar idioma
global $language;
$language = 'en'; // o 'ca' o 'en' segons el template
setcookie('lang', $language, strtotime('1 day'), "/", false);

get_header(); 
get_template_part('template-parts/advanced-search');
get_footer();
?>