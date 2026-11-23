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
#[CoversMethod(Polynomial::class, 'coefficient')]
final class CoefficientTest extends TestCase
{
    public function testReturnsCoefficientOfPower(): void
    {
        // Arrange
        $polynomial = Polynomial::of(5.0, -1.0, 0.0, 2.0);

        // Act
        $coefficient = $polynomial->coefficient(3);

        // Assert
        self::assertSame(2.0, $coefficient);
    }

    public function testReturnsZeroAboveDegree(): void
    {
        // Arrange
        $polynomial = Polynomial::of(5.0, -1.0);

        // Act
        $coefficient = $polynomial->coefficient(10);

        // Assert
        self::assertSame(0.0, $coefficient);
    }

    public function testThrowsOnNegativePower(): void
    {
        // Arrange
        $polynomial = Polynomial::of(5.0);
        $this->expectException(InvalidPowerException::class);

        // Act
        $polynomial->coefficient(-1);
    }
}
