---
title: Division
intro: Divide one polynomial by another and read the quotient and remainder.
group: Guides
order: 40
---

## Long division

`divide()` performs polynomial long division. It returns a `DivisionResult` with two polynomials, `quotient` and `remainder`, so that `dividend = divisor * quotient + remainder`. The remainder is what is left over, and its degree is always lower than the degree of the divisor.

```php
use PHPMath\Polynomial\Polynomial;

$p = Polynomial::of(5, -1, 0, 2); // 2x^3 - x + 5
$division = $p->divide(Polynomial::of(-1, 1)); // divide by x - 1

echo $division->quotient;   // 2x^2 + 2x + 1
echo $division->remainder;  // 6
```

`DivisionResult` is a `final readonly` class with two public properties. You read them directly, there are no getters. A `DivisionResult` is created by `divide()`. Its constructor is marked `@internal` and is not part of the API, so do not create instances yourself.

## Exact division

When the divisor is a factor of the dividend, the remainder is the zero polynomial. Use `isZero()` to check this.

```php
use PHPMath\Polynomial\Polynomial;

$division = Polynomial::of(-1, 0, 1)->divide(Polynomial::of(-1, 1)); // (x^2 - 1) / (x - 1)

echo $division->quotient;                      // x + 1
var_dump($division->remainder->isZero());      // bool(true)
```

Division works with floats, so rounding noise can leave a tiny remainder instead of exact zero. To test for a zero remainder with a margin, compare it with `equals()` and a tolerance. See [Comparison and output](comparison.md).

## A divisor of higher degree

When the dividend has a lower degree than the divisor, the quotient is zero and the remainder is the dividend itself.

```php
use PHPMath\Polynomial\Polynomial;

$division = Polynomial::of(1, 1)->divide(Polynomial::of(1, 0, 1)); // (x + 1) / (x^2 + 1)

echo $division->quotient;   // 0
echo $division->remainder;  // x + 1
```

## Dividing by a constant

A divisor of degree 0 is a plain number. The remainder is then always zero.

```php
use PHPMath\Polynomial\Polynomial;

$division = Polynomial::of(3)->divide(Polynomial::of(2));

echo $division->quotient;   // 1.5
echo $division->remainder;  // 0
```

> [!WARNING]
> Dividing by the zero polynomial throws [`DivisionByZeroException`](errors.md).
