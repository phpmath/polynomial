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
#[CoversMethod(Polynomial::class, 'integral')]
final class IntegralTest extends TestCase
{
    public function testIntegratesWithConstantTerm(): void
    {
        // Arrange
        $polynomial = Polynomial::of(-1.0, 0.0, 6.0);

        // Act
        $result = $polynomial->integral(7.0);

        // Assert
        self::assertSame([7.0, -1.0, 0.0, 2.0], $result->coefficients());
    }

    public function testConstantTermDefaultsToZero(): void
    {
        // Arrange
        $polynomial = Polynomial::of(-1.0, 0.0, 6.0);

        // Act
        $result = $polynomial->integral();

        // Assert
        self::assertSame([0.0, -1.0, 0.0, 2.0], $result->coefficients());
    }

    public function testIntegralOfZeroIsTheConstant(): void
    {
        // Arrange
        $polynomial = Polynomial::zero();

        // Act
        $result = $polynomial->integral(3.0);

        // Assert
        self::assertSame([3.0], $result->coefficients());
    }

    public function testThrowsOnNonFiniteConstant(): void
    {
        // Arrange
        $polynomial = Polynomial::of(1.0);
        $this->expectException(InvalidCoefficientException::class);

        // Act
        $polynomial->integral(NAN);
    }

    public function testUnderflowToNegativeZeroIsNormalised(): void
    {
        // Arrange
        $polynomial = Polynomial::of(0.0, -5.0e-324, 1.0);

        // Act
        $result = $polynomial->integral()->coefficient(2);

        // Assert
        self::assertSame(INF, fdiv(1.0, $result));
    }
}
