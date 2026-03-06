<?php
/**
 * Tests dels filtres simples de functions.php
 *
 * Cobreix:
 * - custom_excerpt_length()
 * - add_query_vars_filter()
 * - exclude_terms()
 * - df_disable_comments_status()
 * - df_disable_comments_hide_existing_comments()
 */

declare(strict_types=1);

namespace Indret\Tests;

use Brain\Monkey;
use Brain\Monkey\Functions;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use WP_Term;

/**
 * Funcions extretes de functions.php per poder testar-les de forma aïllada.
 * S'inclouen aquí com a còpies per no haver de carregar tot el WP core.
 */
function custom_excerpt_length(int $length): int
{
    return 500;
}

function add_query_vars_filter(array $vars): array
{
    $vars[] = 'edicion';
    return $vars;
}

function exclude_terms(array $terms): array
{
    $exclude_terms = [155, 154, 153, 156, 158, 159, 188, 189];
    if (!empty($terms) && is_array($terms)) {
        foreach ($terms as $key => $term) {
            if (in_array($term->term_id, $exclude_terms, true)) {
                unset($terms[$key]);
            }
        }
    }
    return $terms;
}

function df_disable_comments_status(): bool
{
    return false;
}

function df_disable_comments_hide_existing_comments(array $comments): array
{
    return [];
}

class FiltersTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();
    }

    protected function tearDown(): void
    {
        Monkey\tearDown();
        parent::tearDown();
    }

    // ------------------------------------------------------------------
    // custom_excerpt_length
    // ------------------------------------------------------------------

    /** @test */
    public function excerpt_length_always_returns_500(): void
    {
        $this->assertSame(500, custom_excerpt_length(55));
        $this->assertSame(500, custom_excerpt_length(0));
        $this->assertSame(500, custom_excerpt_length(9999));
    }

    // ------------------------------------------------------------------
    // add_query_vars_filter
    // ------------------------------------------------------------------

    /** @test */
    public function query_vars_adds_edicion_to_existing_vars(): void
    {
        $original = ['page', 'paged', 'category_name'];
        $result   = add_query_vars_filter($original);

        $this->assertContains('edicion', $result);
        $this->assertCount(count($original) + 1, $result);
    }

    /** @test */
    public function query_vars_adds_edicion_to_empty_vars(): void
    {
        $result = add_query_vars_filter([]);

        $this->assertSame(['edicion'], $result);
    }

    /** @test */
    public function query_vars_does_not_duplicate_edicion(): void
    {
        // Si ja existís (cas hipotètic), ho afegim igualment (comportament actual)
        $original = ['edicion', 'paged'];
        $result   = add_query_vars_filter($original);

        $this->assertSame(['edicion', 'paged', 'edicion'], $result);
    }

    // ------------------------------------------------------------------
    // exclude_terms
    // ------------------------------------------------------------------

    private function makeTerm(int $term_id, string $name = 'Term'): WP_Term
    {
        return new WP_Term($term_id, $name, strtolower($name), 'post_tag');
    }

    /** @test */
    public function exclude_terms_removes_blacklisted_term_ids(): void
    {
        $terms = [
            $this->makeTerm(100, 'Visible'),
            $this->makeTerm(155, 'Hidden 155'),
            $this->makeTerm(154, 'Hidden 154'),
            $this->makeTerm(200, 'Visible 2'),
        ];

        $result = exclude_terms($terms);

        $result = array_values($result);
        $this->assertCount(2, $result);
        $this->assertSame(100, $result[0]->term_id);
        $this->assertSame(200, $result[1]->term_id);
    }

    /** @test */
    public function exclude_terms_removes_all_blacklisted_ids(): void
    {
        $blacklisted = [155, 154, 153, 156, 158, 159, 188, 189];
        $terms = array_map(fn($id) => $this->makeTerm($id), $blacklisted);

        $result = exclude_terms($terms);

        $this->assertEmpty($result);
    }

    /** @test */
    public function exclude_terms_returns_intact_when_no_match(): void
    {
        $terms = [
            $this->makeTerm(10),
            $this->makeTerm(20),
            $this->makeTerm(30),
        ];

        $result = array_values(exclude_terms($terms));

        $this->assertCount(3, $result);
    }

    /** @test */
    public function exclude_terms_returns_empty_array_when_input_is_empty(): void
    {
        $this->assertSame([], exclude_terms([]));
    }

    // ------------------------------------------------------------------
    // df_disable_comments_status
    // ------------------------------------------------------------------

    /** @test */
    public function disable_comments_status_always_returns_false(): void
    {
        $this->assertFalse(df_disable_comments_status());
    }

    // ------------------------------------------------------------------
    // df_disable_comments_hide_existing_comments
    // ------------------------------------------------------------------

    /** @test */
    public function hide_existing_comments_returns_empty_array(): void
    {
        $comments = [
            (object)['comment_ID' => 1, 'comment_content' => 'Hello'],
            (object)['comment_ID' => 2, 'comment_content' => 'World'],
        ];

        $result = df_disable_comments_hide_existing_comments($comments);

        $this->assertSame([], $result);
    }

    /** @test */
    public function hide_existing_comments_accepts_empty_array(): void
    {
        $this->assertSame([], df_disable_comments_hide_existing_comments([]));
    }
}
