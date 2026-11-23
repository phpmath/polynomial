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
 * Thrown when dividing by the zero polynomial.
 */
final class DivisionByZeroException extends InvalidArgumentException implements PolynomialException
{
}
