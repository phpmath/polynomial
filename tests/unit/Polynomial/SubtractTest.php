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

use function fdiv;

use const INF;

#[CoversClass(Polynomial::class)]
#[CoversMethod(Polynomial::class, 'subtract')]
final class SubtractTest extends TestCase
{
    public function testSubtractsCoefficients(): void
    {
        // Arrange
        $a = Polynomial::of(5.0, 0.0, 3.0);
        $b = Polynomial::of(2.0, 1.0, 3.0);

        // Act
        $result = $a->subtract($b);

        // Assert
        self::assertSame([3.0, -1.0], $result->coefficients());
    }

    public function testSubtractingItselfGivesZero(): void
    {
        // Arrange
        $a = Polynomial::of(5.0, 1.0, 3.0);

        // Act
        $result = $a->subtract($a);

        // Assert
        self::assertTrue($result->isZero());
    }

    public function testEqualCoefficientsGivePositiveZero(): void
    {
        // Arrange
        $polynomial = Polynomial::of(1.0, 2.0, 3.0);

        // Act
        $result = $polynomial->subtract(Polynomial::of(1.0, 5.0, 3.0, 1.0))->coefficient(0);

        // Assert
        self::assertSame(INF, fdiv(1.0, $result));
    }
}
