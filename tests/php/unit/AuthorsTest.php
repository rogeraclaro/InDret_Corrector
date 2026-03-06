<?php
/**
 * Tests per a indret_get_post_authors() i indret_get_all_authors()
 *
 * Cobreix:
 * - Retorn buit quan ACF no té dades
 * - Conversió d'IDs numèrics a WP_Term
 * - Filtratge d'objectes invàlids (WP_Error, null, string buit)
 * - Ordenació per cognom d'indret_get_all_authors()
 */

declare(strict_types=1);

namespace Indret\Tests;

use Brain\Monkey;
use Brain\Monkey\Functions;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use WP_Error;
use WP_Term;

// -----------------------------------------------------------------------
// Còpies de les funcions del tema per testar de forma aïllada
// -----------------------------------------------------------------------

function indret_get_post_authors(?int $post_id = null): array
{
    try {
        $terms = @get_field('autor_id', $post_id, false);
    } catch (\Exception $e) {
        return [];
    }

    if (empty($terms) || $terms === false || $terms === null) {
        return [];
    }
    if (is_string($terms) && trim($terms) === '') {
        return [];
    }
    if (!is_array($terms)) {
        $terms = [$terms];
    }

    $valid_terms = [];
    foreach ($terms as $term_data) {
        if (empty($term_data) || $term_data === null || (is_string($term_data) && trim($term_data) === '')) {
            continue;
        }

        $term_obj = null;

        if (is_numeric($term_data)) {
            $term_id = intval($term_data);
            if ($term_id > 0) {
                $term_obj = @get_term($term_id, 'autor');
            }
        } elseif (is_object($term_data) && isset($term_data->term_id)) {
            $term_obj = $term_data;
        }

        if ($term_obj && is_object($term_obj) && !is_wp_error($term_obj) && isset($term_obj->term_id) && $term_obj->term_id > 0) {
            $valid_terms[] = $term_obj;
        }
    }

    return $valid_terms;
}

function indret_get_all_authors(): array|\WP_Error
{
    $authors = get_terms([
        'taxonomy'   => 'autor',
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ]);

    if (!is_wp_error($authors) && !empty($authors)) {
        usort($authors, function (WP_Term $a, WP_Term $b): int {
            $name_a      = trim($a->name);
            $name_b      = trim($b->name);
            $space_pos_a = strpos($name_a, ' ');
            $space_pos_b = strpos($name_b, ' ');
            $surname_a   = ($space_pos_a !== false) ? substr($name_a, $space_pos_a + 1) : $name_a;
            $surname_b   = ($space_pos_b !== false) ? substr($name_b, $space_pos_b + 1) : $name_b;
            return strcasecmp($surname_a, $surname_b);
        });
    }

    return $authors;
}

// -----------------------------------------------------------------------
// TestCase
// -----------------------------------------------------------------------

class AuthorsTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();

        // Stub is_wp_error per a tots els tests
        Functions\when('is_wp_error')->alias(function ($thing): bool {
            return $thing instanceof WP_Error;
        });
    }

    protected function tearDown(): void
    {
        Monkey\tearDown();
        parent::tearDown();
    }

    // ------------------------------------------------------------------
    // indret_get_post_authors
    // ------------------------------------------------------------------

    /** @test */
    public function get_post_authors_returns_empty_when_acf_returns_false(): void
    {
        Functions\when('get_field')->justReturn(false);

        $result = indret_get_post_authors(1);

        $this->assertSame([], $result);
    }

    /** @test */
    public function get_post_authors_returns_empty_when_acf_returns_null(): void
    {
        Functions\when('get_field')->justReturn(null);

        $result = indret_get_post_authors(1);

        $this->assertSame([], $result);
    }

    /** @test */
    public function get_post_authors_returns_empty_when_acf_returns_empty_string(): void
    {
        Functions\when('get_field')->justReturn('');

        $result = indret_get_post_authors(1);

        $this->assertSame([], $result);
    }

    /** @test */
    public function get_post_authors_returns_term_objects_from_numeric_ids(): void
    {
        $term1 = new WP_Term(10, 'Joan García', 'joan-garcia', 'autor');
        $term2 = new WP_Term(11, 'Maria López', 'maria-lopez', 'autor');

        Functions\when('get_field')->justReturn([10, 11]);
        Functions\when('get_term')->alias(function (int $id, string $taxonomy) use ($term1, $term2) {
            return $id === 10 ? $term1 : $term2;
        });

        $result = indret_get_post_authors(5);

        $this->assertCount(2, $result);
        $this->assertSame(10, $result[0]->term_id);
        $this->assertSame(11, $result[1]->term_id);
    }

    /** @test */
    public function get_post_authors_passes_through_wp_term_objects(): void
    {
        $term = new WP_Term(42, 'Pere Puig', 'pere-puig', 'autor');

        Functions\when('get_field')->justReturn([$term]);

        $result = indret_get_post_authors(7);

        $this->assertCount(1, $result);
        $this->assertSame(42, $result[0]->term_id);
    }

    /** @test */
    public function get_post_authors_filters_out_wp_errors(): void
    {
        $error = new WP_Error();
        Functions\when('get_field')->justReturn([99]);
        Functions\when('get_term')->justReturn($error);

        $result = indret_get_post_authors(3);

        $this->assertSame([], $result);
    }

    /** @test */
    public function get_post_authors_filters_out_nulls_and_empty_strings_in_array(): void
    {
        $term = new WP_Term(5, 'Anna Soler', 'anna-soler', 'autor');

        Functions\when('get_field')->justReturn([null, '', 5, '']);
        Functions\when('get_term')->alias(function (int $id) use ($term) {
            return $id === 5 ? $term : null;
        });

        $result = indret_get_post_authors(2);

        $this->assertCount(1, $result);
        $this->assertSame(5, $result[0]->term_id);
    }

    // ------------------------------------------------------------------
    // indret_get_all_authors — ordenació per cognom
    // ------------------------------------------------------------------

    private function makeTerm(int $id, string $name): WP_Term
    {
        return new WP_Term($id, $name, strtolower(str_replace(' ', '-', $name)), 'autor');
    }

    /** @test */
    public function get_all_authors_sorts_by_surname_ascending(): void
    {
        $authors = [
            $this->makeTerm(1, 'Joan Zebra'),
            $this->makeTerm(2, 'Maria Alonso'),
            $this->makeTerm(3, 'Pere Martínez'),
        ];

        Functions\when('get_terms')->justReturn($authors);

        $result = indret_get_all_authors();

        $this->assertSame('Maria Alonso',   $result[0]->name);
        $this->assertSame('Pere Martínez',  $result[1]->name);
        $this->assertSame('Joan Zebra',     $result[2]->name);
    }

    /** @test */
    public function get_all_authors_handles_single_name_without_space(): void
    {
        $authors = [
            $this->makeTerm(1, 'Zzz'),
            $this->makeTerm(2, 'Aaa'),
        ];

        Functions\when('get_terms')->justReturn($authors);

        $result = indret_get_all_authors();

        $this->assertSame('Aaa', $result[0]->name);
        $this->assertSame('Zzz', $result[1]->name);
    }

    /** @test */
    public function get_all_authors_returns_empty_array_on_wp_error(): void
    {
        Functions\when('get_terms')->justReturn(new WP_Error());

        $result = indret_get_all_authors();

        // Quan get_terms retorna WP_Error, la funció retorna l'error sense ordenar
        $this->assertInstanceOf(WP_Error::class, $result);
    }

    /** @test */
    public function get_all_authors_returns_empty_array_when_no_authors(): void
    {
        Functions\when('get_terms')->justReturn([]);

        $result = indret_get_all_authors();

        $this->assertSame([], $result);
    }
}
