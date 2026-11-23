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
#[CoversMethod(Polynomial::class, 'evaluate')]
final class EvaluateTest extends TestCase
{
    public function testEvaluatesAtPoint(): void
    {
        // Arrange
        $polynomial = Polynomial::of(5.0, -1.0, 0.0, 2.0);

        // Act
        $value = $polynomial->evaluate(2.0);

        // Assert
        self::assertSame(19.0, $value);
    }

    public function testEvaluatesAtNegativePoint(): void
    {
        // Arrange
        $polynomial = Polynomial::of(5.0, -1.0, 0.0, 2.0);

        // Act
        $value = $polynomial->evaluate(-1.0);

        // Assert
        self::assertSame(4.0, $value);
    }

    public function testZeroPolynomialEvaluatesToZero(): void
    {
        // Arrange
        $polynomial = Polynomial::zero();

        // Act
        $value = $polynomial->evaluate(7.5);

        // Assert
        self::assertSame(0.0, $value);
    }

    public function testResultIsPositiveZeroForNegativeInput(): void
    {
        // Arrange
        $polynomial = Polynomial::of(0.0, 1.0);

        // Act
        $result = $polynomial->evaluate(-0.0);

        // Assert
        self::assertSame(INF, fdiv(1.0, $result));
    }

    public function testUnderflowGivesPositiveZero(): void
    {
        // Arrange
        $polynomial = Polynomial::of(0.0, -5.0e-324);

        // Act
        $result = $polynomial->evaluate(0.5);

        // Assert
        self::assertSame(INF, fdiv(1.0, $result));
    }
}
