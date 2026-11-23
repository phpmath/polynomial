<?php

/**
 * phpmath / polynomial (https://github.com/phpmath/polynomial)
 *
 * @link https://github.com/phpmath/polynomial for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Polynomial\Tests\DivisionResult;

use PHPMath\Polynomial\DivisionResult;
use PHPMath\Polynomial\Polynomial;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversClass(DivisionResult::class)]
#[CoversMethod(DivisionResult::class, '__construct')]
final class ConstructorTest extends TestCase
{
    public function testExposesQuotientAndRemainder(): void
    {
        // Arrange
        $quotient = Polynomial::of(1.0, 1.0);
        $remainder = Polynomial::of(2.0);

        // Act
        $result = new DivisionResult($quotient, $remainder);

        // Assert
        self::assertSame([$quotient, $remainder], [$result->quotient, $result->remainder]);
    }
}
