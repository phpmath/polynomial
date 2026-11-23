---
title: Arithmetic
intro: Add, subtract, multiply, scale, raise to a power and compose polynomials.
group: Guides
order: 30
---

## Adding and subtracting

`add()` and `subtract()` take another `Polynomial` and combine the coefficients of equal powers. The polynomials may have different degrees.

```php
use PHPMath\Polynomial\Polynomial;

$p = Polynomial::of(5, -1, 0, 2); // 2x^3 - x + 5
$q = Polynomial::of(1, 1);        // x + 1

echo $p->add($q);       // 2x^3 + 6
echo $p->subtract($q);  // 2x^3 - 2x + 4
```

Terms can cancel. The result is trimmed, so its degree can be lower than both inputs.

## Multiplying

`multiply()` multiplies two polynomials. The degree of the result is the sum of the degrees. Multiplying by the zero polynomial gives the zero polynomial.

```php
use PHPMath\Polynomial\Polynomial;

$p = Polynomial::of(5, -1, 0, 2);
$q = Polynomial::of(1, 1);

echo $p->multiply($q); // 2x^4 + 2x^3 - x^2 + 4x + 5
```

## Scaling and negating

`scale()` multiplies every coefficient by a number. `negate()` is the same as scaling by `-1`. Both take or need plain floats, not polynomials.

```php
use PHPMath\Polynomial\Polynomial;

$p = Polynomial::of(5, -1, 0, 2);

echo $p->scale(0.5); // x^3 - 0.5x + 2.5
echo $p->negate();   // -2x^3 + x - 5
```

Scaling by `0` gives the zero polynomial. A factor that is NaN or infinite throws [`InvalidCoefficientException`](errors.md).

## Powers

`pow()` raises a polynomial to a whole-number power. Any polynomial to the power `0` is `1`, including the zero polynomial. A negative exponent throws [`InvalidPowerException`](errors.md).

```php
use PHPMath\Polynomial\Polynomial;

$q = Polynomial::of(1, 1);

echo $q->pow(3);                  // x^3 + 3x^2 + 3x + 1
echo Polynomial::zero()->pow(0);  // 1
```

## Composition

`compose()` substitutes another polynomial for x. For `p` and `q`, the result is `p(q(x))`. The order matters.

```php
use PHPMath\Polynomial\Polynomial;

$p = Polynomial::of(5, -1, 0, 2); // 2x^3 - x + 5
$q = Polynomial::of(1, 1);        // x + 1

echo $p->compose($q); // 2x^3 + 6x^2 + 5x + 6
echo $q->compose($p); // 2x^3 - x + 6
```
