<?php
/**
 * Tests per a indret_get_all_editions()
 *
 * L'ordenació és: per any DESC, per trimestre DESC.
 * Format d'edició: "trimestre.any" p.ex. "1.2024", "3.2023"
 */

declare(strict_types=1);

namespace Indret\Tests;

use Brain\Monkey;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

// Còpia de la funció del tema (sense la query a $wpdb, injectable)
function indret_sort_editions(array $editions): array
{
    usort($editions, function (string $a, string $b): int {
        $parts_a = explode('.', $a);
        $parts_b = explode('.', $b);

        $trimestre_a = isset($parts_a[0]) ? intval($parts_a[0]) : 0;
        $any_a       = isset($parts_a[1]) ? intval($parts_a[1]) : 0;
        $trimestre_b = isset($parts_b[0]) ? intval($parts_b[0]) : 0;
        $any_b       = isset($parts_b[1]) ? intval($parts_b[1]) : 0;

        if ($any_b !== $any_a) {
            return $any_b - $any_a;
        }
        return $trimestre_b - $trimestre_a;
    });

    return $editions;
}

class EditionsTest extends TestCase
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

    /** @test */
    public function sorts_editions_by_year_descending(): void
    {
        $editions = ['1.2020', '1.2024', '1.2018', '1.2022'];

        $result = indret_sort_editions($editions);

        $this->assertSame(['1.2024', '1.2022', '1.2020', '1.2018'], $result);
    }

    /** @test */
    public function sorts_editions_by_quarter_descending_within_same_year(): void
    {
        $editions = ['1.2023', '4.2023', '2.2023', '3.2023'];

        $result = indret_sort_editions($editions);

        $this->assertSame(['4.2023', '3.2023', '2.2023', '1.2023'], $result);
    }

    /** @test */
    public function sorts_mixed_years_and_quarters(): void
    {
        $editions = ['2.2021', '4.2023', '1.2023', '3.2022'];

        $result = indret_sort_editions($editions);

        $this->assertSame(['4.2023', '1.2023', '3.2022', '2.2021'], $result);
    }

    /** @test */
    public function returns_single_edition_unchanged(): void
    {
        $result = indret_sort_editions(['2.2024']);

        $this->assertSame(['2.2024'], $result);
    }

    /** @test */
    public function returns_empty_array_unchanged(): void
    {
        $result = indret_sort_editions([]);

        $this->assertSame([], $result);
    }

    /** @test */
    public function handles_edition_without_dot_gracefully(): void
    {
        // Format invàlid: any=0, trimestre=valor complet
        $editions = ['2024', '1.2023'];

        $result = indret_sort_editions($editions);

        // '1.2023' té any=2023 > 0 → va primer
        $this->assertSame('1.2023', $result[0]);
    }

    /** @test */
    public function most_recent_edition_is_first(): void
    {
        $editions = ['1.2015', '4.2024', '2.2024', '1.1999'];

        $result = indret_sort_editions($editions);

        $this->assertSame('4.2024', $result[0]);
        $this->assertSame('1.1999', end($result));
    }
}
