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
#[CoversMethod(Polynomial::class, 'x')]
final class XTest extends TestCase
{
    public function testIsTheIndeterminate(): void
    {
        // Arrange and Act
        $polynomial = Polynomial::x();

        // Assert
        self::assertSame([0.0, 1.0], $polynomial->coefficients());
    }
}
