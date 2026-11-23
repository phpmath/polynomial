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
#[CoversMethod(Polynomial::class, 'isFinite')]
final class IsFiniteTest extends TestCase
{
    public function testOrdinaryPolynomialIsFinite(): void
    {
        // Arrange
        $polynomial = Polynomial::of(1.0, -2.0, 3.0);

        // Act
        $result = $polynomial->isFinite();

        // Assert
        self::assertTrue($result);
    }

    public function testZeroPolynomialIsFinite(): void
    {
        // Arrange
        $polynomial = Polynomial::zero();

        // Act
        $result = $polynomial->isFinite();

        // Assert
        self::assertTrue($result);
    }

    public function testMultiplyOverflowIsNotFinite(): void
    {
        // Arrange
        $polynomial = Polynomial::of(1.0e200)->multiply(Polynomial::of(1.0e200));

        // Act
        $result = $polynomial->isFinite();

        // Assert
        self::assertFalse($result);
    }

    public function testAddOverflowIsNotFinite(): void
    {
        // Arrange
        $polynomial = Polynomial::of(1.0e308)->add(Polynomial::of(1.0e308));

        // Act
        $result = $polynomial->isFinite();

        // Assert
        self::assertFalse($result);
    }

    public function testScaleOverflowIsNotFinite(): void
    {
        // Arrange
        $polynomial = Polynomial::of(1.0e308)->scale(10.0);

        // Act
        $result = $polynomial->isFinite();

        // Assert
        self::assertFalse($result);
    }

    public function testNanCoefficientIsNotFinite(): void
    {
        // Arrange
        $infinite = Polynomial::of(1.0e308)->add(Polynomial::of(1.0e308));
        $polynomial = $infinite->subtract($infinite);

        // Act
        $result = $polynomial->isFinite();

        // Assert
        self::assertFalse($result);
    }
}
