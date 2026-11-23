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
#[CoversMethod(Polynomial::class, 'degree')]
final class DegreeTest extends TestCase
{
    public function testIsHighestPowerWithNonZeroCoefficient(): void
    {
        // Arrange
        $polynomial = Polynomial::of(5.0, -1.0, 0.0, 2.0);

        // Act
        $degree = $polynomial->degree();

        // Assert
        self::assertSame(3, $degree);
    }

    public function testIsZeroForNonZeroConstant(): void
    {
        // Arrange
        $polynomial = Polynomial::of(4.0);

        // Act
        $degree = $polynomial->degree();

        // Assert
        self::assertSame(0, $degree);
    }

    public function testIsMinusOneForZeroPolynomial(): void
    {
        // Arrange
        $polynomial = Polynomial::zero();

        // Act
        $degree = $polynomial->degree();

        // Assert
        self::assertSame(-1, $degree);
    }
}
