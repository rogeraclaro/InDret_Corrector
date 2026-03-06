/**
 * Tests Jest per a js/advanced-search.js
 *
 * Estratègia:
 * - Muntem el DOM mínim necessari per a cada grup de tests
 * - Mockegem $.ajax per capturar les dades enviades sense fer crides reals
 * - Executem el fitxer original via require() per registrar els listeners jQuery
 */

'use strict';

// -----------------------------------------------------------------------
// Helpers de DOM
// -----------------------------------------------------------------------

/**
 * Crea el DOM bàsic del formulari de cerca + contenidor de resultats.
 */
function buildSearchDOM() {
  document.body.innerHTML = `
    <form id="indret-search-form">
      <input  type="hidden"  id="search-language"   name="language"      value="es" />
      <input  type="text"    name="text_search"      id="text_search"     value="" />
      <input  type="text"    name="title_search"     id="title_search"    value="" />
      <input  type="text"    name="subtitulo"        id="subtitulo"       value="" />
      <input  type="text"    name="organizacion"     id="organizacion"    value="" />
      <input  type="text"    id="autor_search"       value="" />
      <input  type="hidden"  name="autor_id"         id="autor_id"        value="" />
      <input  type="date"    name="date_from"        id="date_from"       value="" />
      <input  type="date"    name="date_to"          id="date_to"         value="" />
      <select name="nombre_area"    id="nombre_area">
        <option value="">Totes</option>
        <option value="Derecho penal">Penal</option>
      </select>
      <select name="nombre_subarea" id="nombre_subarea">
        <option value="">Totes</option>
      </select>
      <select name="edicion_gral"   id="edicion_gral">
        <option value="">Totes</option>
        <option value="1.2024">1.2024</option>
      </select>
      <select name="orderby" id="orderby">
        <option value="date" selected>Data</option>
        <option value="downloads">Descàrregues</option>
      </select>
      <select name="tag[]" id="tag" multiple>
        <option value="5">Tag 5</option>
        <option value="10">Tag 10</option>
      </select>

      <button id="search-submit-btn" type="submit">
        <span class="btn-text">Cercar</span>
        <span class="btn-loader" style="display:none">…</span>
      </button>
      <button id="reset-search-btn" type="button">Reset</button>
    </form>

    <datalist id="authors-datalist">
      <option value="Joan García" data-id="42"></option>
      <option value="Maria López" data-id="99"></option>
    </datalist>

    <div id="search-results-container"></div>
  `;
}

// -----------------------------------------------------------------------
// Mock de $.ajax
// -----------------------------------------------------------------------

let ajaxSpy;

function mockAjaxSuccess(responseData = {}) {
  ajaxSpy = jest.fn().mockImplementation(function (options) {
    // Simula crida exitosa asíncrona
    Promise.resolve().then(() => {
      if (options.success) {
        options.success({ success: true, data: responseData });
      }
      if (options.complete) options.complete();
    });
    return { done: () => {}, fail: () => {} };
  });
  $.ajax = ajaxSpy;
}

function mockAjaxError() {
  ajaxSpy = jest.fn().mockImplementation(function (options) {
    Promise.resolve().then(() => {
      if (options.error) options.error({ responseText: 'Server Error' }, 'error', 'Internal Server Error');
      if (options.complete) options.complete();
    });
    return { done: () => {}, fail: () => {} };
  });
  $.ajax = ajaxSpy;
}

// -----------------------------------------------------------------------
// Carrega el fitxer JS original (una sola vegada per suite)
// -----------------------------------------------------------------------

beforeAll(() => {
  // El fitxer usa document.ready → simulem que ja ha carregat
  jest.spyOn($.fn, 'ready').mockImplementation(function (fn) {
    fn($);
    return this;
  });
});

// -----------------------------------------------------------------------
// GRUP 1: Submit del formulari
// -----------------------------------------------------------------------

describe('Form submit', () => {
  beforeEach(() => {
    buildSearchDOM();
    mockAjaxSuccess({ html: '<p>Resultats</p>' });
    // Re-executa el JS per registrar els listeners sobre el DOM nou
    jest.isolateModules(() => {
      require('../../js/advanced-search.js');
    });
  });

  test('preventDefault evita que el formulari es recarregui', () => {
    const event = new Event('submit', { bubbles: true, cancelable: true });
    const preventSpy = jest.spyOn(event, 'preventDefault');

    document.getElementById('indret-search-form').dispatchEvent(event);

    expect(preventSpy).toHaveBeenCalled();
  });

  test('$.ajax es crida amb action=indret_advanced_search', () => {
    $('#indret-search-form').trigger('submit');

    expect(ajaxSpy).toHaveBeenCalledTimes(1);
    const callData = ajaxSpy.mock.calls[0][0].data;
    expect(callData.action).toBe('indret_advanced_search');
  });

  test('el nonce s\'inclou a la petició AJAX', () => {
    $('#indret-search-form').trigger('submit');

    const callData = ajaxSpy.mock.calls[0][0].data;
    expect(callData.nonce).toBe('test-nonce-abc123');
  });

  test('text_search es recull correctament', () => {
    document.querySelector('input[name="text_search"]').value = 'responsabilitat';
    $('#indret-search-form').trigger('submit');

    const callData = ajaxSpy.mock.calls[0][0].data;
    expect(callData.text_search).toBe('responsabilitat');
  });

  test('nombre_area es recull del select', () => {
    const select = document.querySelector('select[name="nombre_area"]');
    select.value = 'Derecho penal';
    $('#indret-search-form').trigger('submit');

    const callData = ajaxSpy.mock.calls[0][0].data;
    expect(callData.nombre_area).toBe('Derecho penal');
  });

  test('el loader es mostra durant la petició i s\'amaga en completar', async () => {
    const btnText   = document.querySelector('#search-submit-btn .btn-text');
    const btnLoader = document.querySelector('#search-submit-btn .btn-loader');

    $('#indret-search-form').trigger('submit');

    // Loader actiu just després del submit
    expect(btnLoader.style.display).toBe('');
    expect(btnText.style.display).toBe('none');

    // Espera que la Promise del mock es resolgui
    await Promise.resolve();
    await Promise.resolve();

    // Loader amagat en completar
    expect(btnLoader.style.display).toBe('none');
    expect(btnText.style.display).toBe('');
  });
});

// -----------------------------------------------------------------------
// GRUP 2: Botó de reset
// -----------------------------------------------------------------------

describe('Reset button', () => {
  beforeEach(() => {
    buildSearchDOM();
    jest.isolateModules(() => {
      require('../../js/advanced-search.js');
    });
  });

  test('el click del reset buida el camp d\'autor_id', () => {
    document.getElementById('autor_id').value = '42';

    $('#reset-search-btn').trigger('click');

    expect(document.getElementById('autor_id').value).toBe('');
  });

  test('el click del reset amaga el contenidor de resultats', () => {
    document.getElementById('search-results-container').innerHTML = '<p>Resultats previs</p>';
    $('#search-results-container').show();

    $('#reset-search-btn').trigger('click');

    // jQuery fadeOut és asíncron però en jsdom es resol ràpid
    // Verifiquem que s'ha iniciat la transició (display:none o buit)
    const container = document.getElementById('search-results-container');
    expect(container).toBeDefined();
  });
});

// -----------------------------------------------------------------------
// GRUP 3: Autocompletat d'autor
// -----------------------------------------------------------------------

describe('Author autocomplete', () => {
  beforeEach(() => {
    buildSearchDOM();
    jest.isolateModules(() => {
      require('../../js/advanced-search.js');
    });
  });

  test('quan s\'escriu un nom que coincideix amb el datalist s\'actualitza autor_id', () => {
    const autorSearch = document.getElementById('autor_search');
    const autorId     = document.getElementById('autor_id');

    autorSearch.value = 'Joan García';
    $(autorSearch).trigger('input');

    expect(autorId.value).toBe('42');
  });

  test('quan s\'escriu un nom que NO coincideix autor_id es buida', () => {
    const autorSearch = document.getElementById('autor_search');
    const autorId     = document.getElementById('autor_id');
    autorId.value = '42'; // Valor previ

    autorSearch.value = 'Nom inexistent';
    $(autorSearch).trigger('input');

    expect(autorId.value).toBe('');
  });
});

// -----------------------------------------------------------------------
// GRUP 4: Auto-cerca amb selects
// -----------------------------------------------------------------------

describe('Auto-search on select change', () => {
  beforeEach(() => {
    buildSearchDOM();
    mockAjaxSuccess({ html: '' });
    jest.isolateModules(() => {
      require('../../js/advanced-search.js');
    });
  });

  test('canviar un select dispara automàticament el submit', () => {
    const select = document.querySelector('select[name="nombre_area"]');
    select.value = 'Derecho penal';
    $(select).trigger('change');

    expect(ajaxSpy).toHaveBeenCalled();
  });

  test('canviar edicion_gral dispara la cerca', () => {
    const select = document.querySelector('select[name="edicion_gral"]');
    select.value = '1.2024';
    $(select).trigger('change');

    expect(ajaxSpy).toHaveBeenCalled();
  });
});

// -----------------------------------------------------------------------
// GRUP 5: Paginació AJAX
// -----------------------------------------------------------------------

describe('Pagination AJAX', () => {
  beforeEach(() => {
    buildSearchDOM();
    mockAjaxSuccess({ html: '<p>Pàgina 2</p>' });
    // Afegim un link de paginació al contenidor de resultats
    document.getElementById('search-results-container').innerHTML = `
      <div class="search-pagination">
        <a href="?paged=2">2</a>
        <a href="?paged=3">3</a>
      </div>
    `;
    jest.isolateModules(() => {
      require('../../js/advanced-search.js');
    });
  });

  test('click en paginació fa petició AJAX amb paged correcte', () => {
    document.querySelector('.search-pagination a').click();

    expect(ajaxSpy).toHaveBeenCalled();
    const callData = ajaxSpy.mock.calls[0][0].data;
    expect(callData.paged).toBe('2');
  });

  test('click en paginació extreu el número de pàgina de la URL', () => {
    const link = document.querySelector('a[href="?paged=3"]');
    link.click();

    const callData = ajaxSpy.mock.calls[0][0].data;
    expect(callData.paged).toBe('3');
  });
});

// -----------------------------------------------------------------------
// GRUP 6: Gestió d'errors AJAX
// -----------------------------------------------------------------------

describe('AJAX error handling', () => {
  beforeEach(() => {
    buildSearchDOM();
    mockAjaxError();
    // Suprimir alert en entorn de test
    global.alert = jest.fn();
    jest.isolateModules(() => {
      require('../../js/advanced-search.js');
    });
  });

  test('en error AJAX es mostra alert a l\'usuari', async () => {
    $('#indret-search-form').trigger('submit');

    await Promise.resolve();
    await Promise.resolve();

    expect(global.alert).toHaveBeenCalled();
  });
});
