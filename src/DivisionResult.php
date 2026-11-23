<?php

/**
 * phpmath / polynomial (https://github.com/phpmath/polynomial)
 *
 * @link https://github.com/phpmath/polynomial for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Polynomial;

/**
 * The result of a polynomial division: $dividend = $divisor * $quotient + $remainder, where the
 * degree of the remainder is lower than the degree of the divisor.
 */
final readonly class DivisionResult
{
    /** @internal Created by Polynomial::divide(), the constructor is not part of the API. */
    public function __construct(
        public Polynomial $quotient,
        public Polynomial $remainder,
    ) {
    }
}
