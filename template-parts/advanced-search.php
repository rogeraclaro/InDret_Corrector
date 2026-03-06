<?php
/**
 * Template part: Advanced Search
 * Guardar com: template-parts/advanced-search.php
 */

global $language;

// Si estem en un template específic, forçar idioma
if (is_page('busqueda-avanzada')) {
    $language = 'es';
} elseif (is_page('cerca-avancada')) {
    $language = 'ca';
} elseif (is_page('advanced-search')) {
    $language = 'en';
}

// Textos segons idioma
$texts = array(
    'es' => array(
        'title' => 'Búsqueda Avanzada',
        'text_search' => 'Texto libre',
        'text_search_placeholder' => 'Buscar en título, contenido o resumen...',
        'section' => 'Sección',
        'subsection' => 'Subsección',
        'author' => 'Autor',
        'title_field' => 'Título',
        'subtitle' => 'Subtítulo',
        'organization' => 'Organización',
        'keywords' => 'Palabras clave',
        'date_from' => 'Fecha desde',
        'date_to' => 'Fecha hasta',
        'edition' => 'Número de publicación',
        'orderby' => 'Ordenar por',
        'order_date' => 'Fecha',
        'order_downloads' => 'Descargas',
        'search_button' => 'Buscar',
        'reset_button' => 'Limpiar',
        'results_found' => 'resultados encontrados',
        'no_results' => 'No se encontraron resultados',
        'select_option' => '-- Seleccionar --',
        'select_option_tags' => '-- Seleccionar (elección múltiple) --',
        'private_law' => 'Derecho privado',
        'criminal_law' => 'Derecho penal',
        'criminology' => 'Criminología',
        'public_law' => 'Público y regulatorio',
        'share' => 'Compartir',
        'downloads' => 'descargas'
    ),
    'ca' => array(
        'title' => 'Cerca Avançada',
        'text_search' => 'Text lliure',
        'text_search_placeholder' => 'Cercar en títol, contingut o resum...',
        'section' => 'Secció',
        'subsection' => 'Subsecció',
        'author' => 'Autor',
        'title_field' => 'Títol',
        'subtitle' => 'Subtítol',
        'organization' => 'Organització',
        'keywords' => 'Paraules clau',
        'date_from' => 'Data des de',
        'date_to' => 'Data fins',
        'edition' => 'Número de publicació',
        'orderby' => 'Ordenar per',
        'order_date' => 'Data',
        'order_downloads' => 'Descàrregues',
        'search_button' => 'Cercar',
        'reset_button' => 'Netejar',
        'results_found' => 'resultats trobats',
        'no_results' => 'No s\'han trobat resultats',
        'select_option' => '-- Seleccionar --',
        'select_option_tags' => '-- Seleccionar (elecció múltiple) --',
        'private_law' => 'Dret privat',
        'criminal_law' => 'Dret penal',
        'criminology' => 'Criminologia',
        'public_law' => 'Públic i regulatori',
        'share' => 'Compartir',
        'downloads' => 'descàrregues'
    ),
    'en' => array(
        'title' => 'Advanced Search',
        'text_search' => 'Free text',
        'text_search_placeholder' => 'Search in title, content or abstract...',
        'section' => 'Section',
        'subsection' => 'Subsection',
        'author' => 'Author',
        'title_field' => 'Title',
        'subtitle' => 'Subtitle',
        'organization' => 'Organization',
        'keywords' => 'Keywords',
        'date_from' => 'Date from',
        'date_to' => 'Date to',
        'edition' => 'Publication number',
        'orderby' => 'Sort by',
        'order_date' => 'Date',
        'order_downloads' => 'Downloads',
        'search_button' => 'Search',
        'reset_button' => 'Clear',
        'results_found' => 'results found',
        'no_results' => 'No results found',
        'select_option' => '-- Select --',
        'select_option_tags' => '-- Select (multiple choice) --',
        'private_law' => 'Private law',
        'criminal_law' => 'Criminal law',
        'criminology' => 'Criminology',
        'public_law' => 'Administrative law',
        'share' => 'Share',
        'downloads' => 'downloads'
    )
);

$t = $texts[$language];

// Obtenir dades per als selects
$authors = indret_get_all_authors();
$editions = indret_get_all_editions();
$tags = indret_get_all_tags();
$organizations = indret_get_all_organizations();
?>

<div class="fons-interior">
    <div class="container">


    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <h1 class="entry-title-interior entry-content" style="padding-top: 0px; margin-bottom: 0px;"><?php echo $t['title']; ?></h1>
            <div class="separa-petit"></div>
        </div>
        <div class="col-md-2">
        </div>
    </div>
    
        <!-- Formulari de cerca -->
        <div class="row" style="padding-top: 60px; margin-bottom: 30px;">
            <div class="col-md-12">                
                <form method="post" id="indret-search-form" class="advanced-search-form">
                    <input type="hidden" name="language" id="search-language" value="<?php echo esc_attr($language); ?>">
                    
                    <div class="search-form-grid">
                        
                        <!-- Text lliure -->
                        <div class="form-group full-width">
                            <label for="text_search"><?php echo $t['text_search']; ?></label>
                            <input 
                                type="text" 
                                id="text_search" 
                                name="text_search" 
                                class="form-control"
                                placeholder="<?php echo $t['text_search_placeholder']; ?>"
                            >
                        </div>

                        <!-- Títol -->
                        <div class="form-group">
                            <label for="title_search"><?php echo $t['title_field']; ?></label>
                            <input 
                                type="text" 
                                id="title_search" 
                                name="title_search" 
                                class="form-control"
                                size="2"
                            >
                        </div>

                        <!-- Subtítol -->
                        <div class="form-group">
                            <label for="subtitulo"><?php echo $t['subtitle']; ?></label>
                            <input 
                                type="text" 
                                id="subtitulo" 
                                name="subtitulo" 
                                class="form-control"
                            >
                        </div>

                        <!-- Espai buit -->
                        <div class="form-group d-none">
                        </div>

                        <!-- Secció -->
                        <div class="form-group">
                            <label for="nombre_area"><?php echo $t['section']; ?></label>
                            <select id="nombre_area" name="nombre_area" class="form-control">
                                <option value=""><?php echo $t['select_option']; ?></option>
                                <option value="Derecho privado"><?php echo $t['private_law']; ?></option>
                                <option value="Derecho penal"><?php echo $t['criminal_law']; ?></option>
                                <option value="Criminología"><?php echo $t['criminology']; ?></option>
                                <option value="Público y regulatorio"><?php echo $t['public_law']; ?></option>
                            </select>
                        </div>

                        <!-- Subsecció -->
                        <div class="form-group">
                            <label for="nombre_subarea"><?php echo $t['subsection']; ?></label>
                            <select id="nombre_subarea" name="nombre_subarea" class="form-control">
                                <option value=""><?php echo $t['select_option']; ?></option>
                                <?php
                                // Definir subseccions segons idioma
                                        if ($language == 'es') {
                                            $subareas = array(
                                                'Actualitat, Recent developments in Law, Actualidad' => 'Actualidad',
                                                'Recensions, Book reviews, Recensiones' => 'Recensiones',
                                                'Comentaris de Jurisprudència, Notes on case law, Comentarios de Jurisprudencia' => 'Comentarios de Jurisprudencia',
                                                'Working papers, Doctrina' => 'Análisis'
                                            );
                                        } elseif ($language == 'ca') {
                                            $subareas = array(
                                                'Actualitat, Recent developments in Law, Actualidad' => 'Actualitat',
                                                'Recensions, Book reviews, Recensiones' => 'Recensions',
                                                'Comentaris de Jurisprudència, Notes on case law, Comentarios de Jurisprudencia' => 'Comentaris de Jurisprudència',
                                                'Working papers, Doctrina' => 'Anàlisi'
                                            );
                                        } else { // en
                                            $subareas = array(
                                                'Actualitat, Recent developments in Law, Actualidad' => 'Recent developments in Law',
                                                'Recensions, Book reviews, Recensiones' => 'Book reviews',
                                                'Comentaris de Jurisprudència, Notes on case law, Comentarios de Jurisprudencia' => 'Notes on case law',
                                                'Working papers, Doctrina' => 'Analysis'
                                            );
                                        }
                                foreach ($subareas as $value => $label) :
                                ?>
                                    <option value="<?php echo esc_attr($value); ?>">
                                        <?php echo esc_html($label); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Espai buit -->
                        <div class="form-group d-none">
                        </div>

                        <!-- Autor -->
                        <div class="form-group">
                            <label for="autor_search"><?php echo $t['author']; ?> (<?php echo count($authors); ?>)</label>
                            <input 
                                type="text" 
                                id="autor_search" 
                                name="autor_search" 
                                class="form-control"
                                placeholder="<?php echo $language == 'es' ? 'Escribe el nombre del autor...' : ($language == 'ca' ? 'Escriu el nom de l\'autor...' : 'Type author name...'); ?>"
                                list="authors-datalist"
                                autocomplete="off"
                            >
                            <datalist id="authors-datalist">
                                <?php foreach ($authors as $author) : ?>
                                    <option value="<?php echo esc_attr($author->name); ?>" data-id="<?php echo $author->term_id; ?>">
                                <?php endforeach; ?>
                            </datalist>
                            <input type="hidden" id="autor_id" name="autor_id" value="">
                        </div>

                        <!-- Organització -->
                        <div class="form-group">
                            <label for="organizacion"><?php echo $t['organization']; ?> (<?php echo count($organizations); ?>)</label>
                            <input 
                                type="text" 
                                id="organizacion" 
                                name="organizacion" 
                                class="form-control"
                                placeholder="<?php echo $language == 'es' ? 'Escribe la organización...' : ($language == 'ca' ? 'Escriu l\'organització...' : 'Type organization...'); ?>"
                                list="organizations-datalist"
                                autocomplete="off"
                            >
                            <datalist id="organizations-datalist">
                                <?php foreach ($organizations as $org) : ?>
                                    <option value="<?php echo esc_attr($org); ?>">
                                <?php endforeach; ?>
                            </datalist>
                        </div>

                        <!-- Espai buit -->
                        <div class="form-group d-none">
                        </div>                        

                        <!-- Paraules clau -->
                        <div class="form-group">
                            <label for="tag"><?php echo $t['keywords']; ?> (<?php echo count($tags); ?>)</label>
                            <script>
                            var indretTagOptions = <?php echo wp_json_encode(array_map(function($tag) {
                                return array('value' => (string)$tag->term_id, 'text' => $tag->name);
                            }, $tags)); ?>;
                            </script>
                            <select id="tag" name="tag[]" class="form-control" multiple size="5">
                                <option value=""><?php echo $t['select_option_tags']; ?></option>
                            </select>
                        </div>

                        <!-- Data des de -->
                        <div class="form-group">
                            <label for="date_from"><?php echo $t['date_from']; ?></label>
                            <input 
                                type="date" 
                                id="date_from" 
                                name="date_from" 
                                class="form-control"
                            >
                        </div>

                        <!-- Data fins -->
                        <div class="form-group">
                            <label for="date_to"><?php echo $t['date_to']; ?></label>
                            <input 
                                type="date" 
                                id="date_to" 
                                name="date_to" 
                                class="form-control"
                            >
                        </div>

                        <!-- Edició -->
                        <div class="form-group">
                            <label for="edicion_gral"><?php echo $t['edition']; ?></label>
                            <select id="edicion_gral" name="edicion_gral" class="form-control">
                                <option value=""><?php echo $t['select_option']; ?></option>
                                <?php foreach ($editions as $edition) : ?>
                                    <option value="<?php echo esc_attr($edition); ?>">
                                        <?php echo esc_html($edition); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Ordenar per -->
                        <div class="form-group">
                            <label for="orderby"><?php echo $t['orderby']; ?></label>
                            <select id="orderby" name="orderby" class="form-control">
                                <option value="date"><?php echo $t['order_date']; ?></option>
                                <option value="downloads"><?php echo $t['order_downloads']; ?></option>
                            </select>
                        </div>

                    </div>

                    <!-- Botons -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" id="search-submit-btn">
                            <span class="btn-text"><?php echo $t['search_button']; ?></span>
                            <span class="btn-loader" style="display:none;">
                                <span class="spinner"></span> <?php echo $language == 'es' ? 'Buscando...' : ($language == 'ca' ? 'Cercant...' : 'Searching...'); ?>
                            </span>
                        </button>
                        <button type="button" class="btn btn-secondary" id="reset-search-btn"><?php echo $t['reset_button']; ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Resultats (carregats amb AJAX) -->
    <div id="search-results-container" class="site-content container" style="display:none;">
        <div id="primary" class="content-area">
            <main id="main" class="site-main col-md-12" role="main">
                <!-- Els resultats es carregaran aquí via AJAX -->
            </main>
        </div>
    </div>
    
    <!--contact form-->
    <div style="display:none" class="fancybox-hidden">
        <div id="contact-form">
            <span class="autor-interior">Enviar artículo</span>
            <?php echo do_shortcode('[contact-form-7 id="21318" title="Enviar articulo"]'); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
    $('.pertallarcoma').html(function(_,txt) {
        return txt.slice(0, -6);
    });
    
    $('.order-button').click(function() {
        var title = $(this).attr('dataTitle');
        $(".posturl input").val(title);
        $(".wpcf7-response-output.wpcf7-display-none.wpcf7-mail-sent-ok").hide();
    });
});
</script>