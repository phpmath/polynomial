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
#[CoversMethod(Polynomial::class, 'derivative')]
final class DerivativeTest extends TestCase
{
    public function testDifferentiatesTermByTerm(): void
    {
        // Arrange
        $polynomial = Polynomial::of(5.0, -1.0, 0.0, 2.0);

        // Act
        $result = $polynomial->derivative();

        // Assert
        self::assertSame([-1.0, 0.0, 6.0], $result->coefficients());
    }

    public function testDerivativeOfConstantIsZero(): void
    {
        // Arrange
        $polynomial = Polynomial::of(5.0);

        // Act
        $result = $polynomial->derivative();

        // Assert
        self::assertTrue($result->isZero());
    }

    public function testZeroCoefficientIsPositiveZero(): void
    {
        // Arrange
        $polynomial = Polynomial::of(1.0, 2.0, 0.0, 3.0);

        // Act
        $result = $polynomial->derivative()->coefficient(1);

        // Assert
        self::assertSame(INF, fdiv(1.0, $result));
    }
}
