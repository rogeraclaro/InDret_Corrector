<?php
/**
 * Template part: Advanced Search
 * Guardar com: template-parts/advanced-search.php
 * O utilitzar com a page template
 */

global $language;

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
        'order_relevance' => 'Relevancia',
        'order_date' => 'Fecha',
        'order_downloads' => 'Descargas',
        'search_button' => 'Buscar',
        'reset_button' => 'Limpiar',
        'results_found' => 'resultados encontrados',
        'no_results' => 'No se encontraron resultados',
        'select_option' => '-- Seleccionar --',
        'all_areas' => 'Todas las áreas',
        'private_law' => 'Derecho privado',
        'criminal_law' => 'Derecho penal',
        'criminology' => 'Criminología',
        'public_law' => 'Público y regulatorio',
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
        'order_relevance' => 'Rellevància',
        'order_date' => 'Data',
        'order_downloads' => 'Descàrregues',
        'search_button' => 'Cercar',
        'reset_button' => 'Netejar',
        'results_found' => 'resultats trobats',
        'no_results' => 'No s\'han trobat resultats',
        'select_option' => '-- Seleccionar --',
        'all_areas' => 'Totes les àrees',
        'private_law' => 'Dret privat',
        'criminal_law' => 'Dret penal',
        'criminology' => 'Criminologia',
        'public_law' => 'Públic i regulatori',
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
        'order_relevance' => 'Relevance',
        'order_date' => 'Date',
        'order_downloads' => 'Downloads',
        'search_button' => 'Search',
        'reset_button' => 'Clear',
        'results_found' => 'results found',
        'no_results' => 'No results found',
        'select_option' => '-- Select --',
        'all_areas' => 'All areas',
        'private_law' => 'Private law',
        'criminal_law' => 'Criminal law',
        'criminology' => 'Criminology',
        'public_law' => 'Administrative law',
    )
);

$t = $texts[$language];

// Obtenir dades per als selects
$authors = indret_get_all_authors();
$editions = indret_get_all_editions();
$tags = indret_get_all_tags();

// Processar cerca si s'ha enviat el formulari
$search_results = null;
$search_performed = false;

if (isset($_GET['do_search'])) {
    $search_performed = true;
    $search_params = array(
        'text_search' => isset($_GET['text_search']) ? $_GET['text_search'] : '',
        'nombre_area' => isset($_GET['nombre_area']) ? $_GET['nombre_area'] : '',
        'nombre_subarea' => isset($_GET['nombre_subarea']) ? $_GET['nombre_subarea'] : '',
        'autor_id' => isset($_GET['autor_id']) ? $_GET['autor_id'] : '',
        'title_search' => isset($_GET['title_search']) ? $_GET['title_search'] : '',
        'subtitulo' => isset($_GET['subtitulo']) ? $_GET['subtitulo'] : '',
        'organizacion' => isset($_GET['organizacion']) ? $_GET['organizacion'] : '',
        'tag' => isset($_GET['tag']) ? $_GET['tag'] : '',
        'date_from' => isset($_GET['date_from']) ? $_GET['date_from'] : '',
        'date_to' => isset($_GET['date_to']) ? $_GET['date_to'] : '',
        'edicion_gral' => isset($_GET['edicion_gral']) ? $_GET['edicion_gral'] : '',
        'orderby' => isset($_GET['orderby']) ? $_GET['orderby'] : 'date',
    );
    
    $search_results = indret_advanced_search_query($search_params);
}
?>

<div class="indret-advanced-search-wrapper">
    <div class="container">
        <h1 class="search-title"><?php echo $t['title']; ?></h1>
        
        <!-- Formulari de cerca -->
        <form method="get" action="" class="advanced-search-form">
            <input type="hidden" name="do_search" value="1">
            
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
                        value="<?php echo isset($_GET['text_search']) ? esc_attr($_GET['text_search']) : ''; ?>"
                    >
                </div>

                <!-- Secció -->
                <div class="form-group">
                    <label for="nombre_area"><?php echo $t['section']; ?></label>
                    <select id="nombre_area" name="nombre_area" class="form-control">
                        <option value=""><?php echo $t['select_option']; ?></option>
                        <option value="Derecho privado" <?php selected(isset($_GET['nombre_area']) ? $_GET['nombre_area'] : '', 'Derecho privado'); ?>><?php echo $t['private_law']; ?></option>
                        <option value="Derecho penal" <?php selected(isset($_GET['nombre_area']) ? $_GET['nombre_area'] : '', 'Derecho penal'); ?>><?php echo $t['criminal_law']; ?></option>
                        <option value="Criminología" <?php selected(isset($_GET['nombre_area']) ? $_GET['nombre_area'] : '', 'Criminología'); ?>><?php echo $t['criminology']; ?></option>
                        <option value="Público y regulatorio" <?php selected(isset($_GET['nombre_area']) ? $_GET['nombre_area'] : '', 'Público y regulatorio'); ?>><?php echo $t['public_law']; ?></option>
                    </select>
                </div>

                <!-- Subsecció -->
                <div class="form-group">
                    <label for="nombre_subarea"><?php echo $t['subsection']; ?></label>
                    <select id="nombre_subarea" name="nombre_subarea" class="form-control">
                        <option value=""><?php echo $t['select_option']; ?></option>
                        <?php
                        $subareas = array(
                            'Actualitat, Recent developments in Law, Actualidad' => 'Actualitat',
                            'Recensions, Book reviews, Recensiones' => 'Recensions',
                            'Comentaris de Jurisprudència, Notes on case law, Comentarios de Jurisprudencia' => 'Comentaris de Jurisprudència',
                            'Working papers, Doctrina' => 'Anàlisi'
                        );
                        foreach ($subareas as $value => $label) :
                        ?>
                            <option value="<?php echo esc_attr($value); ?>" <?php selected(isset($_GET['nombre_subarea']) ? $_GET['nombre_subarea'] : '', $value); ?>>
                                <?php echo esc_html($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Autor -->
                <div class="form-group">
                    <label for="autor_id"><?php echo $t['author']; ?></label>
                    <select id="autor_id" name="autor_id" class="form-control">
                        <option value=""><?php echo $t['select_option']; ?></option>
                        <?php foreach ($authors as $author) : ?>
                            <option value="<?php echo $author->term_id; ?>" <?php selected(isset($_GET['autor_id']) ? $_GET['autor_id'] : '', $author->term_id); ?>>
                                <?php echo esc_html($author->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Títol -->
                <div class="form-group">
                    <label for="title_search"><?php echo $t['title_field']; ?></label>
                    <input 
                        type="text" 
                        id="title_search" 
                        name="title_search" 
                        class="form-control"
                        value="<?php echo isset($_GET['title_search']) ? esc_attr($_GET['title_search']) : ''; ?>"
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
                        value="<?php echo isset($_GET['subtitulo']) ? esc_attr($_GET['subtitulo']) : ''; ?>"
                    >
                </div>

                <!-- Organització -->
                <div class="form-group">
                    <label for="organizacion"><?php echo $t['organization']; ?></label>
                    <input 
                        type="text" 
                        id="organizacion" 
                        name="organizacion" 
                        class="form-control"
                        value="<?php echo isset($_GET['organizacion']) ? esc_attr($_GET['organizacion']) : ''; ?>"
                    >
                </div>

                <!-- Paraules clau -->
                <div class="form-group">
                    <label for="tag"><?php echo $t['keywords']; ?></label>
                    <select id="tag" name="tag" class="form-control">
                        <option value=""><?php echo $t['select_option']; ?></option>
                        <?php foreach ($tags as $tag) : ?>
                            <option value="<?php echo $tag->term_id; ?>" <?php selected(isset($_GET['tag']) ? $_GET['tag'] : '', $tag->term_id); ?>>
                                <?php echo esc_html($tag->name); ?>
                            </option>
                        <?php endforeach; ?>
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
                        value="<?php echo isset($_GET['date_from']) ? esc_attr($_GET['date_from']) : ''; ?>"
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
                        value="<?php echo isset($_GET['date_to']) ? esc_attr($_GET['date_to']) : ''; ?>"
                    >
                </div>

                <!-- Edició -->
                <div class="form-group">
                    <label for="edicion_gral"><?php echo $t['edition']; ?></label>
                    <select id="edicion_gral" name="edicion_gral" class="form-control">
                        <option value=""><?php echo $t['select_option']; ?></option>
                        <?php foreach ($editions as $edition) : ?>
                            <option value="<?php echo esc_attr($edition); ?>" <?php selected(isset($_GET['edicion_gral']) ? $_GET['edicion_gral'] : '', $edition); ?>>
                                <?php echo esc_html($edition); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Ordenar per -->
                <div class="form-group">
                    <label for="orderby"><?php echo $t['orderby']; ?></label>
                    <select id="orderby" name="orderby" class="form-control">
                        <option value="date" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'date'); ?>><?php echo $t['order_date']; ?></option>
                        <option value="downloads" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'downloads'); ?>><?php echo $t['order_downloads']; ?></option>
                    </select>
                </div>

            </div>

            <!-- Botons -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?php echo $t['search_button']; ?></button>
                <a href="<?php echo get_permalink(); ?>" class="btn btn-secondary"><?php echo $t['reset_button']; ?></a>
            </div>
        </form>

        <!-- Resultats -->
        <?php if ($search_performed) : ?>
            <div class="search-results">
                <div class="results-header">
                    <h2>
                        <?php if ($search_results->have_posts()) : ?>
                            <?php echo $search_results->found_posts; ?> <?php echo $t['results_found']; ?>
                        <?php else : ?>
                            <?php echo $t['no_results']; ?>
                        <?php endif; ?>
                    </h2>
                </div>

                <?php if ($search_results->have_posts()) : ?>
                    <div class="results-list">
                        <?php while ($search_results->have_posts()) : $search_results->the_post(); ?>
                            <article class="result-item">
                                <div class="result-header">
                                    <span class="result-edition"><?php the_field('edicion_gral'); ?></span>
                                    <?php if (get_field('nombre_area')) : ?>
                                        <span class="result-area"><?php the_field('nombre_area'); ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <h3 class="result-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php 
                                        if ($language == 'en' && get_field('titoleng')) {
                                            the_field('titoleng');
                                        } else {
                                            the_title();
                                        }
                                        ?>
                                    </a>
                                </h3>

                                <?php if (get_field('subtitulo')) : ?>
                                    <p class="result-subtitle"><?php the_field('subtitulo'); ?></p>
                                <?php endif; ?>

                                <div class="result-authors">
                                    <?php 
                                    $terms = get_field('autor_id');
                                    if ($terms) :
                                        $author_names = array();
                                        foreach ($terms as $term) {
                                            $author_names[] = $term->name;
                                        }
                                        echo implode(', ', $author_names);
                                    endif;
                                    ?>
                                </div>

                                <?php if (get_field('organizacion')) : ?>
                                    <div class="result-organization"><?php the_field('organizacion'); ?></div>
                                <?php endif; ?>

                                <div class="result-excerpt">
                                    <?php 
                                    if ($language == 'en' && get_field('extracto_eng')) {
                                        echo wp_trim_words(get_field('extracto_eng'), 40);
                                    } else {
                                        the_excerpt();
                                    }
                                    ?>
                                </div>

                                <div class="result-meta">
                                    <?php if (get_field('fecha_aceptacion')) : ?>
                                        <span class="result-date"><?php the_field('fecha_aceptacion'); ?></span>
                                    <?php endif; ?>
                                    <?php if (get_field('ranking_descargas')) : ?>
                                        <span class="result-downloads">
                                            <?php the_field('ranking_descargas'); ?> <?php echo strtolower($t['order_downloads']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>

                    <!-- Paginació -->
                    <?php if ($search_results->max_num_pages > 1) : ?>
                        <div class="search-pagination">
                            <?php
                            echo paginate_links(array(
                                'total' => $search_results->max_num_pages,
                                'current' => max(1, get_query_var('paged')),
                                'prev_text' => '&laquo;',
                                'next_text' => '&raquo;',
                            ));
                            ?>
                        </div>
                    <?php endif; ?>

                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php
wp_reset_postdata();
?>