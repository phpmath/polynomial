---
title: Errors and exceptions
intro: What can go wrong and how to catch it.
group: Reference
order: 90
---

## Library exceptions

Every exception the library throws implements the marker interface `PHPMath\Polynomial\Exception\PolynomialException`, so one `catch` block handles all of them. Each exception also extends an exception from PHP, which says what kind of problem it is:

- `InvalidArgumentException`: an argument has an invalid value.
- `DomainException`: the arguments are fine in general, but the result is mathematically undefined for them.

| Exception | Extends | Thrown when |
|---|---|---|
| `PHPMath\Polynomial\Exception\InvalidCoefficientException` | `InvalidArgumentException` | A coefficient, root, factor or constant is NaN or infinite. |
| `PHPMath\Polynomial\Exception\InvalidPowerException` | `InvalidArgumentException` | A power or an exponent is negative. |
| `PHPMath\Polynomial\Exception\InvalidToleranceException` | `InvalidArgumentException` | A tolerance is negative, NaN or infinite. |
| `PHPMath\Polynomial\Exception\DivisionByZeroException` | `InvalidArgumentException` | The divisor of `divide()` is the zero polynomial. |
| `PHPMath\Polynomial\Exception\ZeroPolynomialException` | `DomainException` | `realRoots()` is called on the zero polynomial. |

```php
use PHPMath\Polynomial\Exception\PolynomialException;
use PHPMath\Polynomial\Polynomial;

try {
    Polynomial::of(0.0)->realRoots();
} catch (PolynomialException $exception) {
    echo get_class($exception) . ': ' . $exception->getMessage();
    // PHPMath\Polynomial\Exception\ZeroPolynomialException: The zero polynomial has infinitely many roots.
}
```

## When each exception is thrown

This table lists every case where a method rejects its input.

| Method | Exception | Condition |
|---|---|---|
| `of()` | `InvalidCoefficientException` | A coefficient is NaN or infinite. |
| `fromRoots()` | `InvalidCoefficientException` | A root is NaN or infinite. |
| `coefficient()` | `InvalidPowerException` | The power is negative. |
| `scale()` | `InvalidCoefficientException` | The factor is NaN or infinite. |
| `pow()` | `InvalidPowerException` | The exponent is negative. |
| `divide()` | `DivisionByZeroException` | The divisor is the zero polynomial. |
| `integral()` | `InvalidCoefficientException` | The constant is NaN or infinite. |
| `realRoots()` | `InvalidToleranceException` | The tolerance is negative, NaN or infinite. |
| `realRoots()` | `ZeroPolynomialException` | The polynomial is the zero polynomial. |
| `equals()` | `InvalidToleranceException` | The tolerance is negative, NaN or infinite. |

The other methods never throw. `evaluate()` accepts any float, and `negate()` is `scale(-1.0)` so it cannot fail.

## Handling untrusted input

```php
use PHPMath\Polynomial\Exception\InvalidCoefficientException;
use PHPMath\Polynomial\Polynomial;

try {
    $polynomial = Polynomial::of(...$coefficients);
} catch (InvalidCoefficientException) {
    // a coefficient was NaN or infinite
}
```

To handle every library error at once, catch `PolynomialException`.
