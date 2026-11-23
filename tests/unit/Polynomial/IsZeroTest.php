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
#[CoversMethod(Polynomial::class, 'isZero')]
final class IsZeroTest extends TestCase
{
    public function testIsTrueForZeroPolynomial(): void
    {
        // Arrange
        $polynomial = Polynomial::zero();

        // Act
        $result = $polynomial->isZero();

        // Assert
        self::assertTrue($result);
    }

    public function testIsFalseForNonZeroPolynomial(): void
    {
        // Arrange
        $polynomial = Polynomial::of(0.0, 1.0);

        // Act
        $result = $polynomial->isZero();

        // Assert
        self::assertFalse($result);
    }
}
