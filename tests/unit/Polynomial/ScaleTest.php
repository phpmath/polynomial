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
#[CoversMethod(Polynomial::class, 'scale')]
final class ScaleTest extends TestCase
{
    public function testMultipliesEveryCoefficient(): void
    {
        // Arrange
        $polynomial = Polynomial::of(1.0, 2.0, 3.0);

        // Act
        $result = $polynomial->scale(2.0);

        // Assert
        self::assertSame([2.0, 4.0, 6.0], $result->coefficients());
    }

    public function testScalingByZeroGivesZero(): void
    {
        // Arrange
        $polynomial = Polynomial::of(1.0, 2.0, 3.0);

        // Act
        $result = $polynomial->scale(0.0);

        // Assert
        self::assertTrue($result->isZero());
    }

    public function testThrowsOnNan(): void
    {
        // Arrange
        $polynomial = Polynomial::of(1.0);
        $this->expectException(InvalidCoefficientException::class);

        // Act
        $polynomial->scale(NAN);
    }

    public function testScalingZeroCoefficientByNegativeFactorGivesPositiveZero(): void
    {
        // Arrange
        $polynomial = Polynomial::of(0.0, 1.0);

        // Act
        $result = $polynomial->scale(-1.0)->coefficient(0);

        // Assert
        self::assertSame(INF, fdiv(1.0, $result));
    }
}
