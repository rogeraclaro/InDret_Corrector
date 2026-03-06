/**
 * AJAX Advanced Search for InDret
 * Guardar com: js/advanced-search.js
 */

jQuery(document).ready(function ($) {

    // Inicialitzar Tom Select al camp de paraules clau
    // Les opcions venen com a array JSON (window.indretTagOptions) en lloc de
    // <option> elements al DOM, evitant 4.500+ nodes innecessaris.
    var tomSelectTags = new TomSelect('#tag', {
        plugins: ['remove_button', 'clear_button'],
        options: window.indretTagOptions || [],
        maxOptions: null,
        placeholder: $('#tag option[value=""]').text(),
        onDropdownClose: function() {
            // Purgar nodes DOM del dropdown en tancar per mantenir rendiment.
            // setTimeout(0) difereix el purge fins que TomSelect acabi la seva
            // rutina interna de tancament, evitant bloquejar l'estat del control.
            var content = this.dropdown_content;
            setTimeout(function() {
                content.innerHTML = '';
            }, 0);
        },
        onItemAdd: function() {
            // Cada vegada que s'afegeix un tag, disparar la cerca
            shouldScroll = true;
            $('#indret-search-form').submit();
        },
        onItemRemove: function() {
            // Cada vegada que s'elimina un tag, disparar la cerca
            shouldScroll = true;
            $('#indret-search-form').submit();
        }
    });

    // Variable per controlar si s'ha de fer scroll
    var shouldScroll = true;

    // Submit del formulari
    $('#indret-search-form').on('submit', function (e) {
        e.preventDefault();

        // Obtenir dades del formulari
        var tagValues = $('select[name="tag[]"]').val();

        var formData = {
            action: 'indret_advanced_search',
            nonce: indretSearch.nonce,
            language: $('#search-language').val(),
            text_search: $('input[name="text_search"]').val(),
            nombre_area: $('select[name="nombre_area"]').val(),
            nombre_subarea: $('select[name="nombre_subarea"]').val(),
            autor_id: $('input[name="autor_id"]').val(),
            title_search: $('input[name="title_search"]').val(),
            subtitulo: $('input[name="subtitulo"]').val(),
            organizacion: $('input[name="organizacion"]').val(),
            'tag[]': tagValues,
            date_from: $('input[name="date_from"]').val(),
            date_to: $('input[name="date_to"]').val(),
            edicion_gral: $('select[name="edicion_gral"]').val(),
            orderby: $('select[name="orderby"]').val()
        };

        // Debug: mostrar dades a la consola
        console.log('FormData enviada:', formData);

        // Mostrar loader
        $('#search-submit-btn .btn-text').hide();
        $('#search-submit-btn .btn-loader').show();
        $('#search-submit-btn').prop('disabled', true);

        // Amagar resultats anteriors amb animació
        $('#search-results-container').fadeOut(200);

        // Petició AJAX
        $.ajax({
            url: indretSearch.ajaxurl,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                console.log('Resposta rebuda:', response);

                if (response.success) {
                    // Mostrar resultats
                    $('#search-results-container').html(response.data.html);
                    $('#search-results-container').fadeIn(400);

                    // Scroll suau fins als resultats (segons la variable shouldScroll)
                    if (shouldScroll) {
                        $('html, body').animate({
                            scrollTop: $('#search-results-container').offset().top - 100
                        }, 600);
                    }

                    // Resetar shouldScroll per defecte
                    shouldScroll = true;
                } else {
                    console.error('Error a la resposta:', response);
                    alert('Error en la cerca. Si us plau, torna-ho a intentar.');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error AJAX:', error);
                console.error('XHR:', xhr);
                console.error('Status:', status);
                console.error('Response Text:', xhr.responseText);
                alert('Error de connexió. Si us plau, torna-ho a intentar.');
                $('#search-results-container').html('<div class="results-header"><h2>Error de connexió</h2></div>').fadeIn(400);
            },
            complete: function () {
                // Amagar loader
                $('#search-submit-btn .btn-text').show();
                $('#search-submit-btn .btn-loader').hide();
                $('#search-submit-btn').prop('disabled', false);
            }
        });
    });

    // Botó de reset
    $('#reset-search-btn').on('click', function () {
        // Netejar formulari
        $('#indret-search-form')[0].reset();

        // Netejar Tom Select (paraules clau)
        tomSelectTags.clear(true);

        // Netejar també el camp hidden d'autor
        $('#autor_id').val('');

        // Amagar resultats amb animació
        $('#search-results-container').fadeOut(400, function () {
            $(this).html('');
        });
    });

    // Autocompletat d'autor - actualitzar ID hidden quan selecciona
    $('#autor_search').on('input', function () {
        var authorName = $(this).val();

        // Buscar l'ID de l'autor al datalist
        var matchingOption = $('#authors-datalist option').filter(function () {
            return this.value === authorName;
        });

        if (matchingOption.length > 0) {
            var authorId = matchingOption.attr('data-id');
            $('#autor_id').val(authorId);
        } else {
            $('#autor_id').val('');
        }
    });

    // Detectar Enter en camps de text
    $('#indret-search-form input[type="text"], #indret-search-form input[type="date"]').on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            shouldScroll = true; // Enter SÍ fa scroll
            $('#indret-search-form').submit();
        }
    });

    // Auto-cerca quan canvien els selects
    $('#indret-search-form select').on('change', function () {
        shouldScroll = true; // Els selects SÍ fan scroll
        $('#indret-search-form').submit();
    });

    // Auto-cerca quan canvien les dates
    $('#indret-search-form input[type="date"]').on('change', function () {
        shouldScroll = true; // Les dates SÍ fan scroll
        $('#indret-search-form').submit();
    });

    // Auto-cerca quan s'escriu en camps de text (amb delay per no saturar)
    var searchTimeout;
    $('#indret-search-form input[type="text"]').on('input', function () {
        var fieldId = $(this).attr('id');
        var delay = 800; // Delay per defecte

        // Camps que NO fan scroll (només text_search i autor_search)
        if (fieldId === 'text_search' || fieldId === 'autor_search') {
            shouldScroll = false;
        } else {
            shouldScroll = true; // La resta de camps SÍ fan scroll
        }

        // Delay més llarg per al camp title_search
        if (fieldId === 'title_search') {
            delay = 2000; // 2 segons per deixar escriure més temps
        }

        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function () {
            $('#indret-search-form').submit();
        }, delay);
    });

    // Gestió de la paginació amb AJAX
    $(document).on('click', '.search-pagination a', function (e) {
        e.preventDefault();

        var href = $(this).attr('href');
        var page = 1;

        // Extreure número de pàgina de la URL
        if (href.indexOf('paged=') !== -1) {
            page = href.split('paged=')[1];
        }

        // Afegir el número de pàgina als paràmetres
        var tagValues = $('select[name="tag[]"]').val();

        var formData = {
            action: 'indret_advanced_search',
            nonce: indretSearch.nonce,
            language: $('#search-language').val(),
            text_search: $('input[name="text_search"]').val(),
            nombre_area: $('select[name="nombre_area"]').val(),
            nombre_subarea: $('select[name="nombre_subarea"]').val(),
            autor_id: $('input[name="autor_id"]').val(),
            title_search: $('input[name="title_search"]').val(),
            subtitulo: $('input[name="subtitulo"]').val(),
            organizacion: $('input[name="organizacion"]').val(),
            'tag[]': tagValues,
            date_from: $('input[name="date_from"]').val(),
            date_to: $('input[name="date_to"]').val(),
            edicion_gral: $('select[name="edicion_gral"]').val(),
            orderby: $('select[name="orderby"]').val(),
            paged: page
        };

        // Mostrar loader
        $('#search-submit-btn .btn-text').hide();
        $('#search-submit-btn .btn-loader').show();

        // Petició AJAX
        $.ajax({
            url: indretSearch.ajaxurl,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#search-results-container').html(response.data.html);

                    // Scroll fins als resultats
                    $('html, body').animate({
                        scrollTop: $('#search-results-container').offset().top - 100
                    }, 600);
                }
            },
            complete: function () {
                $('#search-submit-btn .btn-text').show();
                $('#search-submit-btn .btn-loader').hide();
            }
        });
    });

});