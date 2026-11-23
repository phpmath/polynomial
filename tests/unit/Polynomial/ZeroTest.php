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
#[CoversMethod(Polynomial::class, 'zero')]
final class ZeroTest extends TestCase
{
    public function testHasNoCoefficients(): void
    {
        // Arrange and Act
        $polynomial = Polynomial::zero();

        // Assert
        self::assertSame([], $polynomial->coefficients());
    }
}
