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
#[CoversMethod(Polynomial::class, '__toString')]
final class ToStringTest extends TestCase
{
    public function testFormatsTermsFromHighestPower(): void
    {
        // Arrange
        $polynomial = Polynomial::of(5.0, -1.0, 0.0, 2.0);

        // Act
        $string = (string) $polynomial;

        // Assert
        self::assertSame('2x^3 - x + 5', $string);
    }

    public function testFormatsZeroPolynomial(): void
    {
        // Arrange
        $polynomial = Polynomial::zero();

        // Act
        $string = (string) $polynomial;

        // Assert
        self::assertSame('0', $string);
    }

    public function testFormatsConstant(): void
    {
        // Arrange
        $polynomial = Polynomial::of(-3.0);

        // Act
        $string = (string) $polynomial;

        // Assert
        self::assertSame('-3', $string);
    }

    public function testFormatsNegativeLeadingTerm(): void
    {
        // Arrange
        $polynomial = Polynomial::of(1.0, 0.0, -1.0);

        // Act
        $string = (string) $polynomial;

        // Assert
        self::assertSame('-x^2 + 1', $string);
    }

    public function testFormatsFractionalCoefficients(): void
    {
        // Arrange
        $polynomial = Polynomial::of(-2.5, 0.5);

        // Act
        $string = (string) $polynomial;

        // Assert
        self::assertSame('0.5x - 2.5', $string);
    }

    public function testFormatsLinearTermWithoutExponent(): void
    {
        // Arrange
        $polynomial = Polynomial::of(0.0, -2.0);

        // Act
        $string = (string) $polynomial;

        // Assert
        self::assertSame('-2x', $string);
    }
}
