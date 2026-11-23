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
 * Thrown when a coefficient or scalar is not a finite number (NaN or infinity).
 */
final class InvalidCoefficientException extends InvalidArgumentException implements PolynomialException
{
}
