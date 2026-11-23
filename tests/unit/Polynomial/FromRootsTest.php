<?php

/**
 * phpmath / polynomial (https://github.com/phpmath/polynomial)
 *
 * @link https://github.com/phpmath/polynomial for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Polynomial\Tests\Polynomial;

use PHPMath\Polynomial\Exception\InvalidCoefficientException;
use PHPMath\Polynomial\Polynomial;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

use const INF;

#[CoversClass(Polynomial::class)]
#[CoversMethod(Polynomial::class, 'fromRoots')]
final class FromRootsTest extends TestCase
{
    public function testBuildsMonicPolynomialWithGivenRoots(): void
    {
        // Arrange and Act
        $polynomial = Polynomial::fromRoots(2.0, 3.0);

        // Assert
        self::assertSame([6.0, -5.0, 1.0], $polynomial->coefficients());
    }

    public function testWithoutRootsGivesOne(): void
    {
        // Arrange and Act
        $polynomial = Polynomial::fromRoots();

        // Assert
        self::assertSame([1.0], $polynomial->coefficients());
    }

    public function testRepeatedRootGivesMultipleRoot(): void
    {
        // Arrange and Act
        $polynomial = Polynomial::fromRoots(1.0, 1.0);

        // Assert
        self::assertSame([1.0, -2.0, 1.0], $polynomial->coefficients());
    }

    public function testThrowsOnInfiniteRoot(): void
    {
        // Arrange
        $this->expectException(InvalidCoefficientException::class);

        // Act
        Polynomial::fromRoots(1.0, INF);
    }
}
