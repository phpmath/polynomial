<?php

/**
 * phpmath / polynomial (https://github.com/phpmath/polynomial)
 *
 * @link https://github.com/phpmath/polynomial for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Polynomial\Tests\Polynomial;

use PHPMath\Polynomial\Exception\InvalidPowerException;
use PHPMath\Polynomial\Polynomial;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversClass(Polynomial::class)]
#[CoversMethod(Polynomial::class, 'pow')]
final class PowTest extends TestCase
{
    public function testRaisesToPower(): void
    {
        // Arrange
        $polynomial = Polynomial::of(1.0, 1.0);

        // Act
        $result = $polynomial->pow(3);

        // Assert
        self::assertSame([1.0, 3.0, 3.0, 1.0], $result->coefficients());
    }

    public function testPowerZeroGivesOne(): void
    {
        // Arrange
        $polynomial = Polynomial::of(1.0, 1.0);

        // Act
        $result = $polynomial->pow(0);

        // Assert
        self::assertSame([1.0], $result->coefficients());
    }

    public function testZeroPolynomialToPowerZeroGivesOne(): void
    {
        // Arrange
        $polynomial = Polynomial::zero();

        // Act
        $result = $polynomial->pow(0);

        // Assert
        self::assertSame([1.0], $result->coefficients());
    }

    public function testPowerOfIndeterminate(): void
    {
        // Arrange
        $polynomial = Polynomial::x();

        // Act
        $result = $polynomial->pow(5);

        // Assert
        self::assertSame([0.0, 0.0, 0.0, 0.0, 0.0, 1.0], $result->coefficients());
    }

    public function testThrowsOnNegativeExponent(): void
    {
        // Arrange
        $polynomial = Polynomial::x();
        $this->expectException(InvalidPowerException::class);

        // Act
        $polynomial->pow(-1);
    }
}
