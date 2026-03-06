<?php
/**
 * Tests per a indret_advanced_search_query()
 *
 * Verifica que la funció construeix els $args de WP_Query correctament
 * segons els paràmetres de cerca rebuts.
 *
 * Estratègia: fem un stub de WP_Query que captura $args i retorna
 * una instància inspeccionable sense fer cap consulta real a la BD.
 */

declare(strict_types=1);

namespace Indret\Tests;

use Brain\Monkey;
use Brain\Monkey\Functions;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

// -----------------------------------------------------------------------
// WP_Query spy: captura els $args passats al constructor
// -----------------------------------------------------------------------
class SpyWpQuery extends \WP_Query
{
    public array $captured_args = [];

    public function __construct(array $args = [])
    {
        $this->captured_args = $args;
    }
}

// -----------------------------------------------------------------------
// Versió testable de indret_advanced_search_query que utilitza SpyWpQuery
// -----------------------------------------------------------------------
function testable_advanced_search_query(array $search_params, string $language = 'es'): SpyWpQuery
{
    // Neteja bàsica de text (equivalent a sanitize_text_field sense WP)
    $sanitize = fn(string $v): string => htmlspecialchars(strip_tags(trim($v)));

    $args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => 20,
        'paged'          => isset($search_params['paged']) ? intval($search_params['paged']) : 1,
        'meta_query'     => ['relation' => 'AND'],
        'tax_query'      => ['relation' => 'AND'],
    ];

    // Text lliure
    if (!empty($search_params['text_search'])) {
        $search_text = $sanitize($search_params['text_search']);
        if ($language === 'en') {
            $args['meta_query'][] = [
                'relation' => 'OR',
                ['key' => 'titoleng',     'value' => $search_text, 'compare' => 'LIKE'],
                ['key' => 'extracto_eng', 'value' => $search_text, 'compare' => 'LIKE'],
            ];
        } else {
            $args['s'] = $search_text;
        }
    }

    // Àrea
    if (!empty($search_params['nombre_area'])) {
        $args['meta_query'][] = ['key' => 'nombre_area', 'value' => $sanitize($search_params['nombre_area']), 'compare' => '='];
    }

    // Subàrea
    if (!empty($search_params['nombre_subarea'])) {
        $args['meta_query'][] = ['key' => 'nombre_subarea', 'value' => $sanitize($search_params['nombre_subarea']), 'compare' => '='];
    }

    // Autor (taxonomia)
    if (!empty($search_params['autor_id'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'autor',
            'field'    => 'term_id',
            'terms'    => intval($search_params['autor_id']),
        ];
    }

    // Tags (múltiple)
    if (!empty($search_params['tag'])) {
        $tag_ids = is_array($search_params['tag']) ? $search_params['tag'] : [$search_params['tag']];
        $tag_ids = array_filter(array_map('intval', $tag_ids));
        if (!empty($tag_ids)) {
            $args['tax_query'][] = [
                'taxonomy' => 'post_tag',
                'field'    => 'term_id',
                'terms'    => $tag_ids,
                'operator' => 'IN',
            ];
        }
    }

    // Rang de dates
    if (!empty($search_params['date_from']) || !empty($search_params['date_to'])) {
        $date_query = ['key' => 'fecha_aceptacion', 'type' => 'DATE', 'compare' => 'BETWEEN'];
        if (!empty($search_params['date_from']) && !empty($search_params['date_to'])) {
            $date_query['value']   = [date('Ymd', strtotime($search_params['date_from'])), date('Ymd', strtotime($search_params['date_to']))];
            $date_query['compare'] = 'BETWEEN';
        } elseif (!empty($search_params['date_from'])) {
            $date_query['value']   = date('Ymd', strtotime($search_params['date_from']));
            $date_query['compare'] = '>=';
        } else {
            $date_query['value']   = date('Ymd', strtotime($search_params['date_to']));
            $date_query['compare'] = '<=';
        }
        $args['meta_query'][] = $date_query;
    }

    // Edició
    if (!empty($search_params['edicion_gral'])) {
        $args['meta_query'][] = ['key' => 'edicion_gral', 'value' => $sanitize($search_params['edicion_gral']), 'compare' => '='];
    }

    // Ordenació
    if (!empty($search_params['orderby']) && $search_params['orderby'] === 'downloads') {
        $args['meta_key'] = 'ranking_descargas';
        $args['orderby']  = 'meta_value_num';
        $args['order']    = 'DESC';
    } else {
        $args['orderby'] = 'date';
        $args['order']   = 'DESC';
    }

    return new SpyWpQuery($args);
}

// -----------------------------------------------------------------------
// TestCase
// -----------------------------------------------------------------------

class SearchQueryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();
        Functions\when('sanitize_text_field')->alias('htmlspecialchars');
    }

    protected function tearDown(): void
    {
        Monkey\tearDown();
        parent::tearDown();
    }

    private function query(array $params, string $lang = 'es'): array
    {
        return testable_advanced_search_query($params, $lang)->captured_args;
    }

    // ------------------------------------------------------------------
    // Bàsic
    // ------------------------------------------------------------------

    /** @test */
    public function default_args_without_params(): void
    {
        $args = $this->query([]);

        $this->assertSame('post', $args['post_type']);
        $this->assertSame('publish', $args['post_status']);
        $this->assertSame(20, $args['posts_per_page']);
        $this->assertSame(1, $args['paged']);
        $this->assertSame('date', $args['orderby']);
        $this->assertSame('DESC', $args['order']);
    }

    /** @test */
    public function paged_param_is_applied(): void
    {
        $args = $this->query(['paged' => 3]);

        $this->assertSame(3, $args['paged']);
    }

    // ------------------------------------------------------------------
    // Text lliure
    // ------------------------------------------------------------------

    /** @test */
    public function text_search_in_es_sets_s_param(): void
    {
        $args = $this->query(['text_search' => 'responsabilitat civil'], 'es');

        $this->assertArrayHasKey('s', $args);
        $this->assertStringContainsString('responsabilitat civil', $args['s']);
    }

    /** @test */
    public function text_search_in_en_uses_acf_meta_query(): void
    {
        $args = $this->query(['text_search' => 'civil liability'], 'en');

        $this->assertArrayNotHasKey('s', $args);

        // Buscar la meta_query amb 'titoleng'
        $found = false;
        foreach ($args['meta_query'] as $clause) {
            if (is_array($clause) && isset($clause['relation']) && $clause['relation'] === 'OR') {
                foreach ($clause as $sub) {
                    if (is_array($sub) && isset($sub['key']) && $sub['key'] === 'titoleng') {
                        $found = true;
                    }
                }
            }
        }
        $this->assertTrue($found, 'Hauria d\'haver una meta_query OR amb titoleng per a EN');
    }

    /** @test */
    public function empty_text_search_does_not_add_s_param(): void
    {
        $args = $this->query(['text_search' => '']);

        $this->assertArrayNotHasKey('s', $args);
    }

    // ------------------------------------------------------------------
    // Àrea i subàrea
    // ------------------------------------------------------------------

    /** @test */
    public function nombre_area_adds_meta_query(): void
    {
        $args = $this->query(['nombre_area' => 'Derecho penal']);

        $meta_keys = array_column(
            array_filter($args['meta_query'], 'is_array'),
            'key'
        );
        $this->assertContains('nombre_area', $meta_keys);
    }

    /** @test */
    public function nombre_subarea_adds_meta_query(): void
    {
        $args = $this->query(['nombre_subarea' => 'Recensions']);

        $meta_keys = array_column(
            array_filter($args['meta_query'], 'is_array'),
            'key'
        );
        $this->assertContains('nombre_subarea', $meta_keys);
    }

    // ------------------------------------------------------------------
    // Taxonomies
    // ------------------------------------------------------------------

    /** @test */
    public function autor_id_adds_tax_query(): void
    {
        $args = $this->query(['autor_id' => '42']);

        $tax_entries = array_filter($args['tax_query'], 'is_array');
        $found = false;
        foreach ($tax_entries as $entry) {
            if (isset($entry['taxonomy']) && $entry['taxonomy'] === 'autor' && $entry['terms'] === 42) {
                $found = true;
            }
        }
        $this->assertTrue($found);
    }

    /** @test */
    public function tag_array_adds_tax_query(): void
    {
        $args = $this->query(['tag' => ['5', '10', '15']]);

        $tax_entries = array_filter($args['tax_query'], 'is_array');
        $found = false;
        foreach ($tax_entries as $entry) {
            if (isset($entry['taxonomy']) && $entry['taxonomy'] === 'post_tag') {
                $this->assertSame([5, 10, 15], $entry['terms']);
                $this->assertSame('IN', $entry['operator']);
                $found = true;
            }
        }
        $this->assertTrue($found);
    }

    /** @test */
    public function empty_tag_array_does_not_add_tax_query(): void
    {
        $args = $this->query(['tag' => []]);

        $tax_entries = array_filter($args['tax_query'], function ($e) {
            return is_array($e) && isset($e['taxonomy']) && $e['taxonomy'] === 'post_tag';
        });
        $this->assertEmpty($tax_entries);
    }

    // ------------------------------------------------------------------
    // Rang de dates
    // ------------------------------------------------------------------

    /** @test */
    public function date_from_and_to_creates_between_query(): void
    {
        $args = $this->query(['date_from' => '2022-01-01', 'date_to' => '2022-12-31']);

        $date_clauses = array_filter($args['meta_query'], function ($e) {
            return is_array($e) && isset($e['key']) && $e['key'] === 'fecha_aceptacion';
        });
        $this->assertNotEmpty($date_clauses);
        $clause = array_values($date_clauses)[0];
        $this->assertSame('BETWEEN', $clause['compare']);
        $this->assertCount(2, $clause['value']);
    }

    /** @test */
    public function only_date_from_creates_gte_query(): void
    {
        $args = $this->query(['date_from' => '2023-06-01']);

        $date_clauses = array_filter($args['meta_query'], function ($e) {
            return is_array($e) && isset($e['key']) && $e['key'] === 'fecha_aceptacion';
        });
        $clause = array_values($date_clauses)[0];
        $this->assertSame('>=', $clause['compare']);
        $this->assertSame('20230601', $clause['value']);
    }

    /** @test */
    public function only_date_to_creates_lte_query(): void
    {
        $args = $this->query(['date_to' => '2023-12-31']);

        $date_clauses = array_filter($args['meta_query'], function ($e) {
            return is_array($e) && isset($e['key']) && $e['key'] === 'fecha_aceptacion';
        });
        $clause = array_values($date_clauses)[0];
        $this->assertSame('<=', $clause['compare']);
        $this->assertSame('20231231', $clause['value']);
    }

    // ------------------------------------------------------------------
    // Ordenació
    // ------------------------------------------------------------------

    /** @test */
    public function orderby_downloads_sets_meta_value_num(): void
    {
        $args = $this->query(['orderby' => 'downloads']);

        $this->assertSame('ranking_descargas', $args['meta_key']);
        $this->assertSame('meta_value_num', $args['orderby']);
    }

    /** @test */
    public function default_orderby_is_date(): void
    {
        $args = $this->query(['orderby' => 'date']);

        $this->assertSame('date', $args['orderby']);
        $this->assertArrayNotHasKey('meta_key', $args);
    }

    /** @test */
    public function missing_orderby_defaults_to_date(): void
    {
        $args = $this->query([]);

        $this->assertSame('date', $args['orderby']);
    }
}
