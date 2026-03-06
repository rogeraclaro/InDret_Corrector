<?php
/**
 * Bootstrap per als tests PHPUnit del tema InDret
 *
 * Carrega Brain Monkey i defineix stubs de les funcions de WordPress
 * necessàries per als tests unitaris (sense carregar WP core).
 */

// Carregar l'autoloader de Composer (des de tests/php/vendor)
$autoloader = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoloader)) {
    die("ERROR: Executa 'composer install' dins de tests/php/ primer.\n");
}
require_once $autoloader;

// Inicialitzar Brain Monkey (mocking de funcions WP)
use Brain\Monkey;

// Definir constants de WordPress necessàries
if (!defined('ABSPATH')) {
    define('ABSPATH', '/tmp/wp/');
}
if (!defined('WPINC')) {
    define('WPINC', 'wp-includes');
}

// Stub de classes WP que necessitem però no es carreguen
if (!class_exists('WP_Term')) {
    class WP_Term {
        public $term_id;
        public $name;
        public $slug;
        public $taxonomy;

        public function __construct($term_id = 0, $name = '', $slug = '', $taxonomy = '') {
            $this->term_id  = $term_id;
            $this->name     = $name;
            $this->slug     = $slug;
            $this->taxonomy = $taxonomy;
        }
    }
}

if (!class_exists('WP_Error')) {
    class WP_Error {
        public function get_error_message() { return 'WP_Error mock'; }
    }
}

if (!class_exists('WP_Query')) {
    class WP_Query {
        public array $query_vars = [];
        public int $found_posts  = 0;
        public int $max_num_pages = 0;

        public function __construct(array $args = []) {
            $this->query_vars = $args;
        }
        public function have_posts(): bool { return false; }
        public function the_post(): void {}
    }
}

// Stub de la classe wpdb
if (!class_exists('wpdb')) {
    class wpdb {
        public string $postmeta = 'wp_postmeta';
        public string $posts    = 'wp_posts';

        public function prepare(string $query, ...$args): string {
            // Substitució bàsica de %s amb cometes
            return vsprintf(str_replace('%s', "'%s'", $query), $args);
        }

        public function get_col(string $query): array { return []; }
        public function esc_like(string $text): string { return addslashes($text); }
    }
}
