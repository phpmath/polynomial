<?php

/**
 * phpmath / polynomial (https://github.com/phpmath/polynomial)
 *
 * @link https://github.com/phpmath/polynomial for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Polynomial\Tests\Polynomial;

use PHPMath\Polynomial\Exception\InvalidToleranceException;
use PHPMath\Polynomial\Polynomial;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversClass(Polynomial::class)]
#[CoversMethod(Polynomial::class, 'equals')]
final class EqualsTest extends TestCase
{
    public function testEqualPolynomialsAreEqual(): void
    {
        // Arrange
        $a = Polynomial::of(1.0, 2.0, 3.0);
        $b = Polynomial::of(1.0, 2.0, 3.0);

        // Act
        $result = $a->equals($b);

        // Assert
        self::assertTrue($result);
    }

    public function testDifferentPolynomialsAreNotEqual(): void
    {
        // Arrange
        $a = Polynomial::of(1.0, 2.0, 3.0);
        $b = Polynomial::of(1.0, 2.0, 3.5);

        // Act
        $result = $a->equals($b);

        // Assert
        self::assertFalse($result);
    }

    public function testDifferentDegreesAreNotEqual(): void
    {
        // Arrange
        $a = Polynomial::of(1.0, 2.0);
        $b = Polynomial::of(1.0, 2.0, 0.5);

        // Act
        $result = $a->equals($b);

        // Assert
        self::assertFalse($result);
    }

    public function testDifferenceWithinToleranceIsEqual(): void
    {
        // Arrange
        $a = Polynomial::of(1.0, 2.0, 3.0);
        $b = Polynomial::of(1.0005, 1.9995, 3.0);

        // Act
        $result = $a->equals($b, 0.001);

        // Assert
        self::assertTrue($result);
    }

    public function testToleranceAppliesToMissingHigherCoefficient(): void
    {
        // Arrange
        $a = Polynomial::of(1.0, 2.0);
        $b = Polynomial::of(1.0, 2.0, 0.0005);

        // Act
        $result = $a->equals($b, 0.001);

        // Assert
        self::assertTrue($result);
    }

    public function testDifferenceBeyondToleranceIsNotEqual(): void
    {
        // Arrange
        $a = Polynomial::of(1.0, 2.0);
        $b = Polynomial::of(1.0, 2.01);

        // Act
        $result = $a->equals($b, 0.001);

        // Assert
        self::assertFalse($result);
    }

    public function testThrowsOnNegativeTolerance(): void
    {
        // Arrange
        $a = Polynomial::one();
        $this->expectException(InvalidToleranceException::class);

        // Act
        $a->equals($a, -0.1);
    }
}
