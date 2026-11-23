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
#[CoversMethod(Polynomial::class, 'coefficients')]
final class CoefficientsTest extends TestCase
{
    public function testReturnsAscendingList(): void
    {
        // Arrange
        $polynomial = Polynomial::of(5.0, -1.0, 0.0, 2.0);

        // Act
        $coefficients = $polynomial->coefficients();

        // Assert
        self::assertSame([5.0, -1.0, 0.0, 2.0], $coefficients);
    }
}
