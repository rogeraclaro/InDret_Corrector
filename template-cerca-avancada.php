<?php
/**
 * Template Name: Cerca Avançada
 */

// Forçar idioma
global $language;
$language = 'ca'; // o 'ca' o 'en' segons el template
setcookie('lang', $language, strtotime('1 day'), "/", false);

get_header(); 
get_template_part('template-parts/advanced-search');
get_footer();
?>