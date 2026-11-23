<?php

/**
 * phpmath / polynomial (https://github.com/phpmath/polynomial)
 *
 * @link https://github.com/phpmath/polynomial for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Polynomial\Tests\Polynomial;

use PHPMath\Polynomial\Polynomial;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

use function fdiv;

use const INF;

#[CoversClass(Polynomial::class)]
#[CoversMethod(Polynomial::class, 'add')]
final class AddTest extends TestCase
{
    public function testAddsCoefficientsOfDifferentLength(): void
    {
        // Arrange
        $a = Polynomial::of(1.0, 2.0);
        $b = Polynomial::of(3.0, 4.0, 5.0);

        // Act
        $result = $a->add($b);

        // Assert
        self::assertSame([4.0, 6.0, 5.0], $result->coefficients());
    }

    public function testTrimsCancelledLeadingTerm(): void
    {
        // Arrange
        $a = Polynomial::of(1.0, 0.0, 1.0);
        $b = Polynomial::of(2.0, 0.0, -1.0);

        // Act
        $result = $a->add($b);

        // Assert
        self::assertSame([3.0], $result->coefficients());
    }

    public function testDoesNotChangeOriginal(): void
    {
        // Arrange
        $a = Polynomial::of(1.0, 2.0);

        // Act
        $a->add(Polynomial::of(3.0));

        // Assert
        self::assertSame([1.0, 2.0], $a->coefficients());
    }

    public function testNegativeZeroCoefficientsAreNormalised(): void
    {
        // Arrange
        $polynomial = Polynomial::of(1.0, -1.0, 1.0);

        // Act
        $result = $polynomial->add(Polynomial::of(-1.0, 1.0))->coefficient(0);

        // Assert
        self::assertSame(INF, fdiv(1.0, $result));
    }
}
