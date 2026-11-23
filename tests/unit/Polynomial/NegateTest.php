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

#[CoversClass(Polynomial::class)]
#[CoversMethod(Polynomial::class, 'negate')]
final class NegateTest extends TestCase
{
    public function testFlipsSignOfEveryCoefficient(): void
    {
        // Arrange
        $polynomial = Polynomial::of(1.0, -2.0, 3.0);

        // Act
        $result = $polynomial->negate();

        // Assert
        self::assertSame([-1.0, 2.0, -3.0], $result->coefficients());
    }

    public function testNegatingZeroGivesZero(): void
    {
        // Arrange
        $polynomial = Polynomial::zero();

        // Act
        $result = $polynomial->negate();

        // Assert
        self::assertTrue($result->isZero());
    }
}
