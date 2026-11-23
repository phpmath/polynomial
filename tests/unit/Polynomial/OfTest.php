<?php

/**
 * phpmath / polynomial (https://github.com/phpmath/polynomial)
 *
 * @link https://github.com/phpmath/polynomial for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Polynomial\Tests\Polynomial;

use PHPMath\Polynomial\Exception\InvalidCoefficientException;
use PHPMath\Polynomial\Polynomial;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

use function fdiv;

use const INF;
use const NAN;

#[CoversClass(Polynomial::class)]
#[CoversMethod(Polynomial::class, 'of')]
final class OfTest extends TestCase
{
    public function testStoresCoefficientsInAscendingPowerOrder(): void
    {
        // Arrange
        $coefficients = [5.0, -1.0, 0.0, 2.0];

        // Act
        $polynomial = Polynomial::of(...$coefficients);

        // Assert
        self::assertSame($coefficients, $polynomial->coefficients());
    }

    public function testTrimsTrailingZeros(): void
    {
        // Arrange
        $coefficients = [1.0, 2.0, 0.0, 0.0];

        // Act
        $polynomial = Polynomial::of(...$coefficients);

        // Assert
        self::assertSame([1.0, 2.0], $polynomial->coefficients());
    }

    public function testKeepsInteriorZeros(): void
    {
        // Arrange
        $coefficients = [1.0, 0.0, 3.0];

        // Act
        $polynomial = Polynomial::of(...$coefficients);

        // Assert
        self::assertSame([1.0, 0.0, 3.0], $polynomial->coefficients());
    }

    public function testWithoutCoefficientsGivesZeroPolynomial(): void
    {
        // Arrange and Act
        $polynomial = Polynomial::of();

        // Assert
        self::assertSame([], $polynomial->coefficients());
    }

    public function testOnlyZerosGiveZeroPolynomial(): void
    {
        // Arrange and Act
        $polynomial = Polynomial::of(0.0, 0.0, 0.0);

        // Assert
        self::assertTrue($polynomial->isZero());
    }

    public function testNormalizesNegativeZero(): void
    {
        // Arrange and Act
        $polynomial = Polynomial::of(1.0, -0.0, 1.0);

        // Assert
        self::assertSame(INF, fdiv(1.0, $polynomial->coefficient(1)));
    }

    public function testThrowsOnNan(): void
    {
        // Arrange
        $this->expectException(InvalidCoefficientException::class);

        // Act
        Polynomial::of(1.0, NAN);
    }

    public function testThrowsOnInfinity(): void
    {
        // Arrange
        $this->expectException(InvalidCoefficientException::class);

        // Act
        Polynomial::of(INF);
    }
}
