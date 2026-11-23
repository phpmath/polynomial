<?php

/**
 * phpmath / polynomial (https://github.com/phpmath/polynomial)
 *
 * @link https://github.com/phpmath/polynomial for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Polynomial\Exception;

use InvalidArgumentException;

/**
 * Thrown when a tolerance is negative or not a finite number.
 */
final class InvalidToleranceException extends InvalidArgumentException implements PolynomialException
{
}
