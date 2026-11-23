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
#[CoversMethod(Polynomial::class, 'compose')]
final class ComposeTest extends TestCase
{
    public function testSubstitutesPolynomialForX(): void
    {
        // Arrange
        $outer = Polynomial::of(1.0, 0.0, 1.0);
        $inner = Polynomial::of(2.0, 1.0);

        // Act
        $result = $outer->compose($inner);

        // Assert
        self::assertSame([5.0, 4.0, 1.0], $result->coefficients());
    }

    public function testComposingWithIndeterminateGivesSamePolynomial(): void
    {
        // Arrange
        $polynomial = Polynomial::of(5.0, -1.0, 0.0, 2.0);

        // Act
        $result = $polynomial->compose(Polynomial::x());

        // Assert
        self::assertSame([5.0, -1.0, 0.0, 2.0], $result->coefficients());
    }

    public function testConstantStaysConstant(): void
    {
        // Arrange
        $polynomial = Polynomial::of(3.0);

        // Act
        $result = $polynomial->compose(Polynomial::of(1.0, 2.0));

        // Assert
        self::assertSame([3.0], $result->coefficients());
    }

    public function testZeroPolynomialStaysZero(): void
    {
        // Arrange
        $polynomial = Polynomial::zero();

        // Act
        $result = $polynomial->compose(Polynomial::of(1.0, 2.0));

        // Assert
        self::assertTrue($result->isZero());
    }
}
