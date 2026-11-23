---
title: API reference
intro: Every public class, constant and method of the polynomial library.
group: Reference
order: 80
layout: reference
---

Every method of `Polynomial` returns a new `Polynomial` unless the return type says otherwise. The classes belong to the `PHPMath\Polynomial` namespace, and the exceptions to `PHPMath\Polynomial\Exception`. Coefficients are in ascending power order, see [Core concepts](concepts.md).

## Polynomial

An immutable polynomial in one variable with float coefficients. The class is `final readonly` and `Stringable`. The constructor is private, so use the named constructors below.


### DEFAULT_ROOT_TOLERANCE

```php
public const float DEFAULT_ROOT_TOLERANCE = 1e-9;
```

The default relative tolerance of `realRoots()`.


### of()

```php
public static function of(float ...$coefficients): self
```

Creates a polynomial from its coefficients in ascending power order, so `Polynomial::of(5, -1, 0, 2)` is `2x^3 - x + 5`. Trailing zeros are trimmed. Calling it without arguments, or with only zeros, gives the zero polynomial.

- **Parameters:** `...$coefficients` the coefficients, starting with the constant term
- **Returns:** `Polynomial`
- **Throws:** [InvalidCoefficientException](errors.md) if a coefficient is NaN or infinite

### fromRoots()

```php
public static function fromRoots(float ...$roots): self
```

Creates the monic polynomial `(x - r1)(x - r2)...` with the given roots. Monic means the leading coefficient is 1. A repeated root gives a multiple root. Without roots the result is the constant polynomial 1.

- **Parameters:** `...$roots` the roots
- **Returns:** `Polynomial`
- **Throws:** [InvalidCoefficientException](errors.md) if a root is NaN or infinite

### zero()

```php
public static function zero(): self
```

Creates the zero polynomial, which has no coefficients and degree -1.

- **Returns:** `Polynomial`

### one()

```php
public static function one(): self
```

Creates the constant polynomial 1.

- **Returns:** `Polynomial`

### x()

```php
public static function x(): self
```

Creates the polynomial `x`.

- **Returns:** `Polynomial`

### degree()

```php
public function degree(): int
```

Returns the highest power with a non-zero coefficient, or -1 for the zero polynomial.

- **Returns:** `int`

### isZero()

```php
public function isZero(): bool
```

Tells whether this is the zero polynomial.

- **Returns:** `bool`

### isFinite()

```php
public function isFinite(): bool
```

Tells whether every coefficient is finite. Input is always checked, but the results of `add()`, `multiply()` and `scale()` are not, so they can overflow to infinity (or give NaN). Use this method to check a result when that matters. The zero polynomial is finite. See [Core concepts](concepts.md).

- **Returns:** `bool`

### coefficient()

```php
public function coefficient(int $power): float
```

Returns the coefficient of `x^$power`. Powers above the degree give 0.0.

- **Parameters:** `$power` the power, zero or more
- **Returns:** `float`
- **Throws:** [InvalidPowerException](errors.md) if the power is negative

### coefficients()

```php
public function coefficients(): array
```

Returns all coefficients in ascending power order, without trailing zeros. The zero polynomial gives an empty list.

- **Returns:** `list<float>`

### add()

```php
public function add(Polynomial $other): self
```

Adds a polynomial.

- **Parameters:** `$other` the other operand
- **Returns:** `Polynomial`

### subtract()

```php
public function subtract(Polynomial $other): self
```

Subtracts a polynomial.

- **Parameters:** `$other` the other operand
- **Returns:** `Polynomial`

### multiply()

```php
public function multiply(Polynomial $other): self
```

Multiplies by a polynomial. Multiplying by the zero polynomial gives the zero polynomial.

- **Parameters:** `$other` the other operand
- **Returns:** `Polynomial`

### scale()

```php
public function scale(float $factor): self
```

Multiplies every coefficient by the factor.

- **Parameters:** `$factor` the factor
- **Returns:** `Polynomial`
- **Throws:** [InvalidCoefficientException](errors.md) if the factor is NaN or infinite

### negate()

```php
public function negate(): self
```

Flips the sign of every coefficient. This is the same as `scale(-1.0)`.

- **Returns:** `Polynomial`

### pow()

```php
public function pow(int $exponent): self
```

Raises the polynomial to a whole-number power. Any polynomial to the power 0 is 1, including the zero polynomial.

- **Parameters:** `$exponent` the exponent, zero or more
- **Returns:** `Polynomial`
- **Throws:** [InvalidPowerException](errors.md) if the exponent is negative

### divide()

```php
public function divide(Polynomial $divisor): DivisionResult
```

Polynomial long division, so that `this = divisor * quotient + remainder`. The degree of the remainder is lower than the degree of the divisor. If this polynomial has a lower degree than the divisor, the quotient is zero and the remainder is this polynomial.

- **Parameters:** `$divisor` the polynomial to divide by
- **Returns:** `DivisionResult`
- **Throws:** [DivisionByZeroException](errors.md) if the divisor is the zero polynomial

### compose()

```php
public function compose(Polynomial $other): self
```

Substitutes the other polynomial for x and returns `this(other(x))`.

- **Parameters:** `$other` the polynomial to substitute
- **Returns:** `Polynomial`

### evaluate()

```php
public function evaluate(float $x): float
```

Evaluates the polynomial at `$x` using Horner's method. The zero polynomial gives 0.0. The argument is not checked, so NaN gives NaN.

- **Parameters:** `$x` the point to evaluate at
- **Returns:** `float`

### derivative()

```php
public function derivative(): self
```

Returns the derivative. The derivative of a constant is the zero polynomial.

- **Returns:** `Polynomial`

### integral()

```php
public function integral(float $constant = 0.0): self
```

Returns the antiderivative with the given constant term.

- **Parameters:** `$constant` the constant term of the result, default 0.0
- **Returns:** `Polynomial`
- **Throws:** [InvalidCoefficientException](errors.md) if the constant is NaN or infinite

### realRoots()

```php
public function realRoots(float $tolerance = self::DEFAULT_ROOT_TOLERANCE): array
```

Finds the distinct real roots in ascending order. A multiple root is reported once. Roots that differ by at most the relative tolerance are merged into one root, which is the mean of the cluster. With a tolerance of `0.0` roots are never merged. Negative zero is reported as `0.0`. See [Real roots](roots.md). A polynomial without real roots, or a non-zero constant, gives an empty list.

- **Parameters:** `$tolerance` the relative tolerance, zero or more, default `1e-9`
- **Returns:** `list<float>`
- **Throws:** [InvalidToleranceException](errors.md) if the tolerance is negative, NaN or infinite
- **Throws:** [ZeroPolynomialException](errors.md) if this is the zero polynomial

### equals()

```php
public function equals(Polynomial $other, float $tolerance = 0.0): bool
```

Tells whether both polynomials have the same coefficients. Each pair of coefficients may differ by at most the absolute tolerance. A missing coefficient counts as 0.0.

- **Parameters:** `$other` the polynomial to compare with
- **Parameters:** `$tolerance` the largest allowed difference per coefficient, default 0.0
- **Returns:** `bool`
- **Throws:** [InvalidToleranceException](errors.md) if the tolerance is negative, NaN or infinite

### __toString()

```php
public function __toString(): string
```

Returns a readable form such as `2x^3 - x + 5`. The zero polynomial is `0`.

- **Returns:** `string`

## DivisionResult

The result of `Polynomial::divide()`. It is a `final readonly` class with two public properties, `$quotient` and `$remainder`, both of type `Polynomial`. It holds `dividend = divisor * quotient + remainder`. It is created by `divide()`, its constructor is not part of the API.

### __construct()

```php
public function __construct(
    public Polynomial $quotient,
    public Polynomial $remainder,
)
```

Marked `@internal`: a `DivisionResult` is created by `Polynomial::divide()` and its constructor is not part of the API, so it can change without notice. The constructor does not check that the quotient and the remainder belong together. Do not create instances yourself.

- **Parameters:** `$quotient` the quotient
- **Parameters:** `$remainder` the remainder

## InvalidCoefficientException

Thrown when a coefficient, root, factor or constant is NaN or infinite. It is a `final` class in `PHPMath\Polynomial\Exception` that extends `InvalidArgumentException`, implements `PolynomialException` and has no methods of its own. See [Errors and exceptions](errors.md).


## InvalidPowerException

Thrown when a power or an exponent is negative. It is a `final` class in `PHPMath\Polynomial\Exception` that extends `InvalidArgumentException`, implements `PolynomialException` and has no methods of its own. See [Errors and exceptions](errors.md).


## InvalidToleranceException

Thrown when a tolerance is negative, NaN or infinite. It is a `final` class in `PHPMath\Polynomial\Exception` that extends `InvalidArgumentException`, implements `PolynomialException` and has no methods of its own. See [Errors and exceptions](errors.md).


## DivisionByZeroException

Thrown when dividing by the zero polynomial. It is a `final` class in `PHPMath\Polynomial\Exception` that extends `InvalidArgumentException`, implements `PolynomialException` and has no methods of its own. See [Errors and exceptions](errors.md).


## ZeroPolynomialException

Thrown when an operation is undefined for the zero polynomial, such as finding its roots. It is a `final` class in `PHPMath\Polynomial\Exception` that extends `DomainException`, implements `PolynomialException` and has no methods of its own. See [Errors and exceptions](errors.md).

## PolynomialException

The marker interface implemented by every exception the library throws on purpose. It is an interface in `PHPMath\Polynomial\Exception` that extends `Throwable` and has no methods of its own. See [Errors and exceptions](errors.md).
