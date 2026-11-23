<?php

/**
 * phpmath / polynomial (https://github.com/phpmath/polynomial)
 *
 * @link https://github.com/phpmath/polynomial for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Polynomial\Tests\Polynomial;

use PHPMath\Polynomial\Exception\DivisionByZeroException;
use PHPMath\Polynomial\Polynomial;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversClass(Polynomial::class)]
#[CoversMethod(Polynomial::class, 'divide')]
final class DivideTest extends TestCase
{
    public function testDividesExactly(): void
    {
        // Arrange
        $dividend = Polynomial::of(-6.0, 11.0, -6.0, 1.0);
        $divisor = Polynomial::of(-1.0, 1.0);

        // Act
        $result = $dividend->divide($divisor);

        // Assert
        self::assertSame([6.0, -5.0, 1.0], $result->quotient->coefficients());
    }

    public function testExactDivisionHasZeroRemainder(): void
    {
        // Arrange
        $dividend = Polynomial::of(-6.0, 11.0, -6.0, 1.0);
        $divisor = Polynomial::of(-1.0, 1.0);

        // Act
        $result = $dividend->divide($divisor);

        // Assert
        self::assertTrue($result->remainder->isZero());
    }

    public function testReturnsRemainder(): void
    {
        // Arrange
        $dividend = Polynomial::of(1.0, 0.0, 1.0);
        $divisor = Polynomial::of(-1.0, 1.0);

        // Act
        $result = $dividend->divide($divisor);

        // Assert
        self::assertSame([2.0], $result->remainder->coefficients());
    }

    public function testReturnsQuotientWhenRemainderIsNotZero(): void
    {
        // Arrange
        $dividend = Polynomial::of(1.0, 0.0, 1.0);
        $divisor = Polynomial::of(-1.0, 1.0);

        // Act
        $result = $dividend->divide($divisor);

        // Assert
        self::assertSame([1.0, 1.0], $result->quotient->coefficients());
    }

    public function testDividesByNonMonicPolynomial(): void
    {
        // Arrange
        $dividend = Polynomial::of(1.0, 3.0, 2.0);
        $divisor = Polynomial::of(1.0, 2.0);

        // Act
        $result = $dividend->divide($divisor);

        // Assert
        self::assertSame([1.0, 1.0], $result->quotient->coefficients());
    }

    public function testLowerDegreeDividendIsItsOwnRemainder(): void
    {
        // Arrange
        $dividend = Polynomial::of(3.0, 1.0);
        $divisor = Polynomial::of(0.0, 0.0, 1.0);

        // Act
        $result = $dividend->divide($divisor);

        // Assert
        self::assertSame($dividend, $result->remainder);
    }

    public function testLowerDegreeDividendHasZeroQuotient(): void
    {
        // Arrange
        $dividend = Polynomial::of(3.0, 1.0);
        $divisor = Polynomial::of(0.0, 0.0, 1.0);

        // Act
        $result = $dividend->divide($divisor);

        // Assert
        self::assertTrue($result->quotient->isZero());
    }

    public function testThrowsOnZeroDivisor(): void
    {
        // Arrange
        $dividend = Polynomial::of(1.0, 1.0);
        $this->expectException(DivisionByZeroException::class);

        // Act
        $dividend->divide(Polynomial::zero());
    }
}
