<?php

/**
 * phpmath / polynomial (https://github.com/phpmath/polynomial)
 *
 * @link https://github.com/phpmath/polynomial for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Polynomial\Tests\Polynomial;

use PHPMath\Polynomial\Exception\InvalidToleranceException;
use PHPMath\Polynomial\Exception\ZeroPolynomialException;
use PHPMath\Polynomial\Polynomial;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

use function fdiv;
use function sqrt;

use const INF;
use const NAN;

#[CoversClass(Polynomial::class)]
#[CoversMethod(Polynomial::class, 'realRoots')]
final class RealRootsTest extends TestCase
{
    public function testConstantHasNoRoots(): void
    {
        // Arrange
        $polynomial = Polynomial::of(5.0);

        // Act
        $roots = $polynomial->realRoots();

        // Assert
        self::assertSame([], $roots);
    }

    public function testLinearRoot(): void
    {
        // Arrange
        $polynomial = Polynomial::of(-4.0, 2.0);

        // Act
        $roots = $polynomial->realRoots();

        // Assert
        self::assertSame([2.0], $roots);
    }

    public function testQuadraticRootsAreSorted(): void
    {
        // Arrange
        $polynomial = Polynomial::of(2.0, -3.0, 1.0);

        // Act
        $roots = $polynomial->realRoots();

        // Assert
        self::assertEqualsWithDelta([1.0, 2.0], $roots, 1e-12);
    }

    public function testQuadraticWithNegativeLeadingCoefficient(): void
    {
        // Arrange
        $polynomial = Polynomial::of(3.0, 2.0, -1.0);

        // Act
        $roots = $polynomial->realRoots();

        // Assert
        self::assertEqualsWithDelta([-1.0, 3.0], $roots, 1e-12);
    }

    public function testQuadraticWithoutRealRoots(): void
    {
        // Arrange
        $polynomial = Polynomial::of(1.0, 0.0, 1.0);

        // Act
        $roots = $polynomial->realRoots();

        // Assert
        self::assertSame([], $roots);
    }

    public function testQuadraticDoubleRootIsReportedOnce(): void
    {
        // Arrange
        $polynomial = Polynomial::fromRoots(1.0, 1.0);

        // Act
        $roots = $polynomial->realRoots();

        // Assert
        self::assertEqualsWithDelta([1.0], $roots, 1e-12);
    }

    public function testCubicWithThreeRoots(): void
    {
        // Arrange
        $polynomial = Polynomial::fromRoots(-2.0, 1.0, 3.0);

        // Act
        $roots = $polynomial->realRoots();

        // Assert
        self::assertEqualsWithDelta([-2.0, 1.0, 3.0], $roots, 1e-9);
    }

    public function testCubicWithOneRoot(): void
    {
        // Arrange
        $polynomial = Polynomial::of(-2.0, 0.0, 0.0, 1.0);

        // Act
        $roots = $polynomial->realRoots();

        // Assert
        self::assertEqualsWithDelta([1.2599210498948732], $roots, 1e-12);
    }

    public function testCubicWithDoubleAndSimpleRoot(): void
    {
        // Arrange
        $polynomial = Polynomial::of(2.0, -3.0, 0.0, 1.0);

        // Act
        $roots = $polynomial->realRoots();

        // Assert
        self::assertEqualsWithDelta([-2.0, 1.0], $roots, 1e-7);
    }

    public function testTripleRootIsReportedOnce(): void
    {
        // Arrange
        $polynomial = Polynomial::of(0.0, 0.0, 0.0, 1.0);

        // Act
        $roots = $polynomial->realRoots();

        // Assert
        self::assertEqualsWithDelta([0.0], $roots, 1e-7);
    }

    public function testQuarticWithFourRoots(): void
    {
        // Arrange
        $polynomial = Polynomial::fromRoots(-3.0, -1.0, 2.0, 4.0);

        // Act
        $roots = $polynomial->realRoots();

        // Assert
        self::assertEqualsWithDelta([-3.0, -1.0, 2.0, 4.0], $roots, 1e-9);
    }

    public function testQuarticWithoutRealRoots(): void
    {
        // Arrange
        $polynomial = Polynomial::of(1.0, 0.0, 0.0, 0.0, 1.0);

        // Act
        $roots = $polynomial->realRoots();

        // Assert
        self::assertSame([], $roots);
    }

    public function testQuarticWithMultipleRootsReportsEachOnce(): void
    {
        // Arrange
        $polynomial = Polynomial::fromRoots(1.0, 1.0, 2.0, 3.0);

        // Act
        $roots = $polynomial->realRoots();

        // Assert
        self::assertEqualsWithDelta([1.0, 2.0, 3.0], $roots, 1e-7);
    }

    public function testQuinticSymmetricAroundZero(): void
    {
        // Arrange
        $polynomial = Polynomial::fromRoots(-2.0, -1.0, 0.0, 1.0, 2.0);

        // Act
        $roots = $polynomial->realRoots();

        // Assert
        self::assertEqualsWithDelta([-2.0, -1.0, 0.0, 1.0, 2.0], $roots, 1e-9);
    }

    public function testThrowsOnZeroPolynomial(): void
    {
        // Arrange
        $polynomial = Polynomial::zero();
        $this->expectException(ZeroPolynomialException::class);

        // Act
        $polynomial->realRoots();
    }

    public function testThrowsOnNegativeTolerance(): void
    {
        // Arrange
        $polynomial = Polynomial::x();
        $this->expectException(InvalidToleranceException::class);

        // Act
        $polynomial->realRoots(-1.0);
    }

    public function testRootsAreNeverNegativeZero(): void
    {
        // Arrange
        $polynomial = Polynomial::of(0.0, 0.0, 0.0, 1.0);

        // Act
        $roots = $polynomial->realRoots();

        // Assert
        self::assertSame(INF, fdiv(1.0, $roots[0]));
    }

    public function testLinearRootAtZeroIsNotNegativeZero(): void
    {
        // Arrange
        $polynomial = Polynomial::of(0.0, 1.0);

        // Act
        $roots = $polynomial->realRoots();

        // Assert
        self::assertSame(INF, fdiv(1.0, $roots[0]));
    }

    public function testQuadraticWithoutLinearTermHasExactMirrorRoots(): void
    {
        // Arrange
        $polynomial = Polynomial::of(-2.0, 0.0, 1.0);

        // Act
        $roots = $polynomial->realRoots();

        // Assert
        self::assertSame([-sqrt(2.0), sqrt(2.0)], $roots);
    }

    public function testQuadraticWithoutLinearTermAndNegativeLeadingCoefficient(): void
    {
        // Arrange
        $polynomial = Polynomial::of(8.0, 0.0, -2.0);

        // Act
        $roots = $polynomial->realRoots();

        // Assert
        self::assertSame([-2.0, 2.0], $roots);
    }

    public function testQuadraticAvoidsCancellation(): void
    {
        // Arrange
        $polynomial = Polynomial::of(1.0, -1.0e8, 1.0);

        // Act
        $roots = $polynomial->realRoots();

        // Assert
        self::assertEqualsWithDelta([1.0e-8, 1.0e8], $roots, 1e-20);
        self::assertEqualsWithDelta(1.0e-8, $roots[0], 1e-23);
    }

    public function testZeroToleranceNeverMergesCloseRoots(): void
    {
        // Arrange
        $polynomial = Polynomial::fromRoots(1.0, 1.0000001, 5.0);

        // Act
        $roots = $polynomial->realRoots(0.0);

        // Assert
        self::assertCount(3, $roots);
        self::assertEqualsWithDelta([1.0, 1.0000001, 5.0], $roots, 1e-6);
    }

    public function testToleranceMergesCloseRootsIntoTheirMean(): void
    {
        // Arrange
        $polynomial = Polynomial::fromRoots(1.0, 1.001, 5.0);

        // Act
        $roots = $polynomial->realRoots(1.0e-2);

        // Assert
        self::assertEqualsWithDelta([1.0005, 5.0], $roots, 1e-6);
    }

    public function testToleranceMergesChainOfRoots(): void
    {
        // Arrange
        $polynomial = Polynomial::fromRoots(1.0, 1.001, 1.002, 5.0);

        // Act
        $roots = $polynomial->realRoots(1.5e-3);

        // Assert
        self::assertEqualsWithDelta([1.001, 5.0], $roots, 1e-6);
    }

    public function testThrowsOnNanTolerance(): void
    {
        // Arrange
        $polynomial = Polynomial::x();
        $this->expectException(InvalidToleranceException::class);

        // Act
        $polynomial->realRoots(NAN);
    }

    public function testThrowsOnInfiniteTolerance(): void
    {
        // Arrange
        $polynomial = Polynomial::x();
        $this->expectException(InvalidToleranceException::class);

        // Act
        $polynomial->realRoots(INF);
    }
}
