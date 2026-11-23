<?php

/**
 * phpmath / polynomial (https://github.com/phpmath/polynomial)
 *
 * @link https://github.com/phpmath/polynomial for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Polynomial\Exception;

use DomainException;

/**
 * Thrown when an operation is undefined for the zero polynomial, such as finding its roots.
 */
final class ZeroPolynomialException extends DomainException implements PolynomialException
{
}
