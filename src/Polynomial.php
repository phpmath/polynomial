<?php

/**
 * phpmath / polynomial (https://github.com/phpmath/polynomial)
 *
 * @link https://github.com/phpmath/polynomial for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Polynomial;

use PHPMath\Polynomial\Exception\DivisionByZeroException;
use PHPMath\Polynomial\Exception\InvalidCoefficientException;
use PHPMath\Polynomial\Exception\InvalidPowerException;
use PHPMath\Polynomial\Exception\InvalidToleranceException;
use PHPMath\Polynomial\Exception\ZeroPolynomialException;
use Stringable;

use function abs;
use function array_fill;
use function array_key_last;
use function array_map;
use function array_pop;
use function array_reverse;
use function array_slice;
use function array_sum;
use function array_values;
use function count;
use function end;
use function is_finite;
use function max;
use function sort;
use function sprintf;
use function sqrt;

/**
 * An immutable univariate polynomial with float coefficients. Operations never change the
 * instance they are called on, they return a new instance.
 *
 * Coefficients are stored in ascending power order: the first coefficient belongs to x^0.
 * Trailing zeros are trimmed, so the representation is canonical. The zero polynomial has no
 * coefficients and a degree of -1.
 */
final readonly class Polynomial implements Stringable
{
    /** The default relative tolerance of realRoots(). */
    public const float DEFAULT_ROOT_TOLERANCE = 1e-9;

    /** @var list<float> */
    private array $coefficients;

    /** @param array<int, float> $coefficients Ascending power order */
    private function __construct(array $coefficients)
    {
        while ($coefficients !== [] && end($coefficients) === 0.0) {
            array_pop($coefficients);
        }

        // Adding 0.0 turns negative zero (for example from an underflow) into positive zero.
        $this->coefficients = array_map(static fn (float $c): float => $c + 0.0, array_values($coefficients));
    }

    /**
     * Creates a polynomial from its coefficients in ascending power order, so
     * Polynomial::of(5, -1, 0, 2) is 2x^3 - x + 5. Trailing zeros are trimmed and calling
     * this without arguments (or with only zeros) gives the zero polynomial.
     *
     * @throws InvalidCoefficientException When a coefficient is NaN or infinite.
     */
    public static function of(float ...$coefficients): self
    {
        $normalized = [];

        foreach ($coefficients as $coefficient) {
            self::assertFinite($coefficient);
            // Adding 0.0 turns negative zero into positive zero.
            $normalized[] = $coefficient + 0.0;
        }

        return new self($normalized);
    }

    /**
     * Creates the monic polynomial (x - r1)(x - r2)... with the given roots. Repeated roots
     * give a multiple root. Without roots this is the constant polynomial 1.
     *
     * @throws InvalidCoefficientException When a root is NaN or infinite.
     */
    public static function fromRoots(float ...$roots): self
    {
        $result = self::one();

        foreach ($roots as $root) {
            self::assertFinite($root);
            $result = $result->multiply(self::of(-$root, 1.0));
        }

        return $result;
    }

    /** The zero polynomial. */
    public static function zero(): self
    {
        return new self([]);
    }

    /** The constant polynomial 1. */
    public static function one(): self
    {
        return new self([1.0]);
    }

    /** The polynomial x. */
    public static function x(): self
    {
        return new self([0.0, 1.0]);
    }

    /** The degree, or -1 for the zero polynomial. */
    public function degree(): int
    {
        return count($this->coefficients) - 1;
    }

    public function isZero(): bool
    {
        return $this->coefficients === [];
    }

    /**
     * Whether every coefficient is finite. Input is always checked, but the results of
     * operations are not, so add(), multiply() and scale() can overflow to infinity (or give NaN).
     * The zero polynomial is finite.
     */
    public function isFinite(): bool
    {
        foreach ($this->coefficients as $coefficient) {
            if (!is_finite($coefficient)) {
                return false;
            }
        }

        return true;
    }

    /**
     * The coefficient of x^$power. Powers above the degree give 0.0.
     *
     * @throws InvalidPowerException When the power is negative.
     */
    public function coefficient(int $power): float
    {
        if ($power < 0) {
            throw new InvalidPowerException(sprintf('The power must not be negative, %d given.', $power));
        }

        return $this->coefficients[$power] ?? 0.0;
    }

    /**
     * All coefficients in ascending power order, without trailing zeros. The zero polynomial gives
     * an empty list.
     *
     * @return list<float>
     */
    public function coefficients(): array
    {
        return $this->coefficients;
    }

    public function add(self $other): self
    {
        $result = [];
        $length = max(count($this->coefficients), count($other->coefficients));

        for ($i = 0; $i < $length; $i++) {
            $result[] = ($this->coefficients[$i] ?? 0.0) + ($other->coefficients[$i] ?? 0.0);
        }

        return new self($result);
    }

    public function subtract(self $other): self
    {
        return $this->add($other->negate());
    }

    public function multiply(self $other): self
    {
        if ($this->isZero() || $other->isZero()) {
            return self::zero();
        }

        $result = array_fill(0, count($this->coefficients) + count($other->coefficients) - 1, 0.0);

        foreach ($this->coefficients as $i => $a) {
            foreach ($other->coefficients as $j => $b) {
                $result[$i + $j] += $a * $b;
            }
        }

        return new self($result);
    }

    /**
     * Multiplies every coefficient by the factor.
     *
     * @throws InvalidCoefficientException When the factor is NaN or infinite.
     */
    public function scale(float $factor): self
    {
        self::assertFinite($factor);

        return new self(array_map(static fn (float $c): float => $c * $factor, $this->coefficients));
    }

    public function negate(): self
    {
        return $this->scale(-1.0);
    }

    /**
     * Raises the polynomial to a non-negative integer power. Any polynomial to the power 0 is 1,
     * including the zero polynomial.
     *
     * @throws InvalidPowerException When the exponent is negative.
     */
    public function pow(int $exponent): self
    {
        if ($exponent < 0) {
            throw new InvalidPowerException(sprintf('The exponent must not be negative, %d given.', $exponent));
        }

        $result = self::one();
        $base = $this;

        while ($exponent > 0) {
            if (($exponent & 1) === 1) {
                $result = $result->multiply($base);
            }

            $exponent >>= 1;

            if ($exponent <= 0) {
                continue;
            }

            $base = $base->multiply($base);
        }

        return $result;
    }

    /**
     * Polynomial long division, so that this = divisor * quotient + remainder and the degree of
     * the remainder is lower than the degree of the divisor.
     *
     * @throws DivisionByZeroException When the divisor is the zero polynomial.
     */
    public function divide(self $divisor): DivisionResult
    {
        if ($divisor->isZero()) {
            throw new DivisionByZeroException('Cannot divide by the zero polynomial.');
        }

        $divisorDegree = $divisor->degree();

        if ($this->degree() < $divisorDegree) {
            return new DivisionResult(self::zero(), $this);
        }

        $remainder = $this->coefficients;
        $quotient = array_fill(0, $this->degree() - $divisorDegree + 1, 0.0);
        $lead = $divisor->coefficients[$divisorDegree];

        for ($i = $this->degree() - $divisorDegree; $i >= 0; $i--) {
            $factor = $remainder[$i + $divisorDegree] / $lead;
            $quotient[$i] = $factor;

            for ($j = 0; $j <= $divisorDegree; $j++) {
                $remainder[$i + $j] -= $factor * $divisor->coefficients[$j];
            }

            // The leading term cancels by construction, remove rounding noise.
            $remainder[$i + $divisorDegree] = 0.0;
        }

        return new DivisionResult(new self($quotient), new self(array_slice($remainder, 0, $divisorDegree)));
    }

    /** Substitutes the other polynomial for x: this(other(x)). */
    public function compose(self $other): self
    {
        $result = self::zero();

        foreach (array_reverse($this->coefficients) as $coefficient) {
            $result = $result->multiply($other)->add(new self([$coefficient]));
        }

        return $result;
    }

    /** Evaluates the polynomial at x using Horner's method. */
    public function evaluate(float $x): float
    {
        $result = 0.0;

        foreach (array_reverse($this->coefficients) as $coefficient) {
            $result = $result * $x + $coefficient;
        }

        return $result;
    }

    public function derivative(): self
    {
        $result = [];

        foreach ($this->coefficients as $power => $coefficient) {
            if ($power <= 0) {
                continue;
            }

            $result[] = $coefficient * $power;
        }

        return new self($result);
    }

    /**
     * The antiderivative with the given constant term.
     *
     * @throws InvalidCoefficientException When the constant is NaN or infinite.
     */
    public function integral(float $constant = 0.0): self
    {
        self::assertFinite($constant);

        $result = [$constant];

        foreach ($this->coefficients as $power => $coefficient) {
            $result[] = $coefficient / ($power + 1);
        }

        return new self($result);
    }

    /**
     * Finds the distinct real roots in ascending order.
     *
     * Degrees 0 to 2 use closed forms (the quadratic uses the numerically stable variant). For
     * higher degrees the real roots of the derivative are found recursively. Between two
     * consecutive roots of the derivative the polynomial is monotonic, so each such interval
     * (the outer ones are bounded by the Cauchy bound) holds at most one root, which is
     * refined by bisection down to floating point resolution.
     *
     * The tolerance is relative: a value v of the polynomial at x counts as zero when
     * |v| <= tolerance * (the sum of |a_i| * |x|^i). Multiple roots are reported once, because a
     * multiple root is also a root of the derivative and is detected by that zero test. Two
     * roots that differ by at most the tolerance (relative to their magnitude, never less than
     * 1.0 as the reference) are merged into one root, which is the mean of the cluster: nearby
     * roots are numerically one root of higher multiplicity, so the mean is the best single
     * estimate of it. With a tolerance of 0.0 distinct roots are never merged. A quadratic
     * with a discriminant that is zero within the tolerance counts as a double root. Negative
     * zero is reported as 0.0. Roots of even multiplicity are located with an accuracy of about
     * the square root of the floating point precision, as is inherent to floating point
     * arithmetic.
     *
     * @return list<float>
     *
     * @throws ZeroPolynomialException When this is the zero polynomial (every number is a root).
     * @throws InvalidToleranceException When the tolerance is negative or not finite.
     */
    public function realRoots(float $tolerance = self::DEFAULT_ROOT_TOLERANCE): array
    {
        if (!is_finite($tolerance) || $tolerance < 0.0) {
            throw new InvalidToleranceException('The tolerance must be a finite, non-negative number.');
        }

        if ($this->isZero()) {
            throw new ZeroPolynomialException('The zero polynomial has infinitely many roots.');
        }

        // Adding 0.0 turns negative zero into positive zero.
        return array_map(static fn (float $root): float => $root + 0.0, $this->findRoots($tolerance));
    }

    /**
     * Whether both polynomials have the same coefficients, where each pair of coefficients
     * may differ by at most the (absolute) tolerance.
     *
     * @throws InvalidToleranceException When the tolerance is negative or not finite.
     */
    public function equals(self $other, float $tolerance = 0.0): bool
    {
        if (!is_finite($tolerance) || $tolerance < 0.0) {
            throw new InvalidToleranceException('The tolerance must be a finite, non-negative number.');
        }

        $length = max(count($this->coefficients), count($other->coefficients));

        for ($i = 0; $i < $length; $i++) {
            $difference = abs(($this->coefficients[$i] ?? 0.0) - ($other->coefficients[$i] ?? 0.0));

            if ($difference > $tolerance) {
                return false;
            }
        }

        return true;
    }

    /** For example "2x^3 - x + 5". The zero polynomial is "0". */
    public function __toString(): string
    {
        if ($this->isZero()) {
            return '0';
        }

        $result = '';

        for ($power = $this->degree(); $power >= 0; $power--) {
            $coefficient = $this->coefficients[$power];

            if ($coefficient === 0.0) {
                continue;
            }

            $magnitude = abs($coefficient);
            $sign = $coefficient < 0.0 ? '-' : '+';
            $number = $power > 0 && $magnitude === 1.0 ? '' : (string) $magnitude;
            $term = $number . ($power === 0 ? '' : ($power === 1 ? 'x' : 'x^' . $power));

            if ($result === '') {
                $result = ($sign === '-' ? '-' : '') . $term;
            } else {
                $result .= ' ' . $sign . ' ' . $term;
            }
        }

        return $result;
    }

    /** @return list<float> */
    private function findRoots(float $tolerance): array
    {
        $degree = $this->degree();

        if ($degree === 0) {
            return [];
        }

        if ($degree === 1) {
            return [-$this->coefficients[0] / $this->coefficients[1]];
        }

        if ($degree === 2) {
            return $this->quadraticRoots($tolerance);
        }

        $critical = $this->derivative()->findRoots($tolerance);
        $bound = $this->cauchyBound();
        $points = [-$bound, ...$critical, $bound];
        $roots = [];

        for ($i = 0, $last = count($points) - 1; $i < $last; $i++) {
            $low = $points[$i];
            $high = $points[$i + 1];
            $lowIsRoot = $this->isNearZero($low, $tolerance);
            $highIsRoot = $this->isNearZero($high, $tolerance);

            if ($lowIsRoot) {
                $roots[] = $low;
            }

            if ($highIsRoot) {
                $roots[] = $high;
            }

            if ($lowIsRoot || $highIsRoot || ($this->evaluate($low) < 0.0) === ($this->evaluate($high) < 0.0)) {
                continue;
            }

            $roots[] = $this->bisect($low, $high);
        }

        return self::mergeClose($roots, $tolerance);
    }

    /** @return list<float> */
    private function quadraticRoots(float $tolerance): array
    {
        [$c, $b, $a] = $this->coefficients;
        $discriminant = $b * $b - 4.0 * $a * $c;

        if (abs($discriminant) <= $tolerance * ($b * $b + abs(4.0 * $a * $c))) {
            return [-$b / (2.0 * $a)];
        }

        if ($discriminant < 0.0) {
            return [];
        }

        if ($b === 0.0) {
            // Exact mirror images, which the general formula does not guarantee.
            $root = sqrt(abs($c)) / sqrt(abs($a));

            return [-$root, $root];
        }

        $q = -0.5 * ($b + ($b < 0.0 ? -1.0 : 1.0) * sqrt($discriminant));
        $roots = [$q / $a, $c / $q];
        sort($roots);

        return $roots;
    }

    /** All real roots lie strictly inside (-bound, bound). */
    private function cauchyBound(): float
    {
        $degree = $this->degree();
        $lead = abs($this->coefficients[$degree]);
        $largest = 0.0;

        for ($i = 0; $i < $degree; $i++) {
            $largest = max($largest, abs($this->coefficients[$i]) / $lead);
        }

        return 1.0 + $largest;
    }

    private function isNearZero(float $x, float $tolerance): bool
    {
        $scale = 0.0;

        foreach (array_reverse($this->coefficients) as $coefficient) {
            $scale = $scale * abs($x) + abs($coefficient);
        }

        return abs($this->evaluate($x)) <= $tolerance * $scale;
    }

    /** Bisection on an interval where the polynomial has opposite signs at both ends. */
    private function bisect(float $low, float $high): float
    {
        $lowIsNegative = $this->evaluate($low) < 0.0;

        for ($i = 0; $i < 2200; $i++) {
            $middle = $low + ($high - $low) / 2.0;

            if ($middle <= $low || $middle >= $high) {
                break;
            }

            if (($this->evaluate($middle) < 0.0) === $lowIsNegative) {
                $low = $middle;
            } else {
                $high = $middle;
            }
        }

        return $low + ($high - $low) / 2.0;
    }

    /**
     * @param list<float> $roots
     *
     * @return list<float>
     */
    private static function mergeClose(array $roots, float $tolerance): array
    {
        sort($roots);
        $clusters = [];

        foreach ($roots as $root) {
            $last = $clusters === [] ? null : end($clusters);

            if ($last !== null && abs($root - end($last)) <= $tolerance * max(1.0, abs($root))) {
                $clusters[array_key_last($clusters)][] = $root;

                continue;
            }

            $clusters[] = [$root];
        }

        return array_map(static fn (array $cluster): float => array_sum($cluster) / count($cluster), $clusters);
    }

    private static function assertFinite(float $value): void
    {
        if (!is_finite($value)) {
            throw new InvalidCoefficientException('Values must be finite numbers, NaN and infinity are not allowed.');
        }
    }
}
