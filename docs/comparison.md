---
title: Comparison and output
intro: Compare polynomials with a tolerance and turn them into readable text.
group: Guides
order: 70
---

## Comparing

`equals()` checks whether two polynomials have the same coefficients. Without a tolerance the coefficients must be exactly equal. A missing coefficient counts as zero, so polynomials of different degree are not equal unless the extra coefficients are within the tolerance.

```php
use PHPMath\Polynomial\Polynomial;

$a = Polynomial::fromRoots(1, 2);
$b = Polynomial::of(2, -3, 1);

var_dump($a->equals($b)); // bool(true)
```

Use `equals()` and not `==` to compare polynomials. Two polynomials built in different ways can be equal, because every polynomial is stored in one canonical form.

## Comparing with a tolerance

Floating point results are rarely exact. Pass an absolute tolerance as the second argument. Each pair of coefficients may then differ by at most that amount.

```php
use PHPMath\Polynomial\Polynomial;

$a = Polynomial::of(1.0000001);
$b = Polynomial::of(1);

var_dump($a->equals($b));        // bool(false)
var_dump($a->equals($b, 1e-6));  // bool(true)
```

A tolerance that is negative, NaN or infinite throws [`InvalidToleranceException`](errors.md).

## Text output

`__toString()` writes the polynomial from the highest power down, so `echo` and string casting work. Terms with a zero coefficient are skipped, a coefficient of 1 is left out, and the zero polynomial is `0`.

```php
use PHPMath\Polynomial\Polynomial;

echo Polynomial::of(5, -1, 0, 2);     // 2x^3 - x + 5
echo Polynomial::of(0.5, -1, 1.5);    // 1.5x^2 - x + 0.5
echo Polynomial::of(0, -1);           // -x
echo Polynomial::of(-3, 0, -1);       // -x^2 - 3
echo Polynomial::zero();              // 0
```

The numbers are written with PHP's normal float to string conversion. Very large or very small values use scientific notation.

```php
use PHPMath\Polynomial\Polynomial;

echo Polynomial::of(1e20, 1); // x + 1.0E+20
```

> [!NOTE]
> The text output is meant for people. To store or transfer a polynomial, use `coefficients()` and rebuild it with `of()`.
