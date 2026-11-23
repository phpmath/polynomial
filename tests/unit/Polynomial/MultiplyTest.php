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
#[CoversMethod(Polynomial::class, 'multiply')]
final class MultiplyTest extends TestCase
{
    public function testMultipliesPolynomials(): void
    {
        // Arrange
        $a = Polynomial::of(1.0, 1.0);
        $b = Polynomial::of(1.0, -1.0);

        // Act
        $result = $a->multiply($b);

        // Assert
        self::assertSame([1.0, 0.0, -1.0], $result->coefficients());
    }

    public function testMultipliesByHigherDegree(): void
    {
        // Arrange
        $a = Polynomial::of(1.0, 1.0);
        $b = Polynomial::of(3.0, 2.0, 1.0);

        // Act
        $result = $a->multiply($b);

        // Assert
        self::assertSame([3.0, 5.0, 3.0, 1.0], $result->coefficients());
    }

    public function testMultiplyingByZeroGivesZero(): void
    {
        // Arrange
        $a = Polynomial::of(1.0, 2.0);

        // Act
        $result = $a->multiply(Polynomial::zero());

        // Assert
        self::assertTrue($result->isZero());
    }

    public function testUnderflowToNegativeZeroIsNormalised(): void
    {
        // Arrange
        $polynomial = Polynomial::of(0.0, -5.0e-324, 1.0);

        // Act
        $result = $polynomial->multiply(Polynomial::of(0.5, 1.0))->coefficient(1);

        // Assert
        self::assertSame(INF, fdiv(1.0, $result));
    }
}
