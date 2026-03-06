/**
 * Setup global per als tests Jest d'InDret
 *
 * - Carrega jQuery com a global (el tema el fa servir via $)
 * - Defineix el global indretSearch que WP injecta via wp_localize_script
 */

const $ = require('jquery');
global.$ = $;
global.jQuery = $;

// Simula l'objecte que WordPress injecta amb wp_localize_script
global.indretSearch = {
  ajaxurl: 'http://localhost/wp-admin/admin-ajax.php',
  nonce: 'test-nonce-abc123',
};
