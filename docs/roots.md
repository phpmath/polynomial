---
title: Real roots
intro: Find the real numbers where a polynomial is zero, and understand how close roots are merged.
group: Guides
order: 60
---

## Finding roots

A root is a value of x for which the polynomial is zero. `realRoots()` returns the distinct real roots as a list of floats in ascending order. A polynomial can also have complex roots, but those are not returned.

```php
use PHPMath\Polynomial\Polynomial;

$p = Polynomial::fromRoots(-2, 1, 3);

echo implode(', ', $p->realRoots()); // -2, 1, 3
```

A polynomial without real roots gives an empty list. A non-zero constant has no roots at all.

```php
use PHPMath\Polynomial\Polynomial;

echo count(Polynomial::of(1, 0, 1)->realRoots()); // 0 (x^2 + 1)
echo count(Polynomial::of(5)->realRoots());       // 0
echo implode(', ', Polynomial::of(-6, 2)->realRoots()); // 3
```

Roots are floating point numbers, so they are close to the exact value but not always identical to it. For example the roots of `x^2 - 2` are about `-1.4142135623731` and `1.4142135623731`. Compare them with a margin and not with `===`. A returned root is never negative zero: a root at zero is always `0.0`.

> [!WARNING]
> The zero polynomial has every number as a root. Calling `realRoots()` on it throws [`ZeroPolynomialException`](errors.md). Check `isZero()` first if the polynomial can be zero.

## How roots are found

Degrees 0 to 2 use closed formulas. The quadratic formula is the numerically stable variant, which avoids cancellation when one root is much smaller than the other. A quadratic without a linear term gives exact mirror images, the roots of `x^2 - 2` are exactly `-sqrt(2)` and `sqrt(2)`. For higher degrees the library first finds the real roots of the derivative. Between two neighbouring roots of the derivative the polynomial only goes up or only goes down, so each such stretch holds at most one root. Each root is then narrowed down by repeatedly halving the interval until floating point precision is reached.

## Multiple roots

The multiplicity of a root is how many times it appears as a factor. In `(x - 2)^3` the root 2 has multiplicity 3. A multiple root is reported once.

```php
use PHPMath\Polynomial\Polynomial;

echo implode(', ', Polynomial::fromRoots(2, 2, 2)->realRoots()); // 2
echo implode(', ', Polynomial::of(1, -2, 1)->realRoots());       // 1
```

> [!NOTE]
> Roots of even multiplicity, such as the double root 1 of `(x - 1)^2 (x - 3)(x + 2)`, can be located only to about the square root of the floating point precision (roughly 1e-8). This is a property of floating point arithmetic and not specific to this library. A root of odd multiplicity, where the polynomial changes sign, is found much more precisely.

## The tolerance and merged roots

`realRoots()` takes an optional relative tolerance, a float of zero or more. The default is `1e-9` and is available as `Polynomial::DEFAULT_ROOT_TOLERANCE`. It is used in two places.

First, a value counts as zero when it is small compared to the size of the terms that make it up. This is how multiple roots are recognised. Second, roots that differ by at most the tolerance (relative to their size) are merged into one root. The merged root is the mean of the cluster. Nearby roots are numerically one root of higher multiplicity, so their mean is the best single estimate of it, and it lies between the original roots.

```php
use PHPMath\Polynomial\Polynomial;

$p = Polynomial::fromRoots(1, 1.0000000001, 5); // two roots 1e-10 apart

echo json_encode($p->realRoots()); // [1.00000000005,5]
```

The two roots at 1 and 1.0000000001 are closer than `1e-9`, so they are reported as the single root `1.00000000005`. Roots that are further apart stay separate.

```php
use PHPMath\Polynomial\Polynomial;

$p = Polynomial::fromRoots(1, 1.001, 5);

echo count($p->realRoots()); // 3
```

A larger tolerance merges more roots. Here the roots 1 and 1.001 are merged by a tolerance of `1e-2`.

```php
use PHPMath\Polynomial\Polynomial;

$p = Polynomial::fromRoots(1, 1.001);

echo json_encode($p->realRoots(1e-2)); // [1.0005]
```

With a tolerance of `0.0` roots are never merged. A value only counts as zero when it is exactly zero, so the returned roots can be a little off for nearly double roots, but they are never joined.

```php
use PHPMath\Polynomial\Polynomial;

$p = Polynomial::fromRoots(1, 1.0000001, 5);

echo count($p->realRoots(0.0));  // 3
echo count($p->realRoots());     // 2
echo json_encode($p->realRoots()); // [1.00000005,5]
```

In a quadratic, a discriminant that is zero within the tolerance counts as a double root. The discriminant is the value `b^2 - 4ac` that decides whether a quadratic has two, one or no real roots.

> [!NOTE]
> Even with a tolerance of `0.0` the roots of nearly double roots (here 1 and 1.0000001) are located only to about 1e-8, so do not rely on `realRoots()` for the exact values of roots that are closer than about 1e-6.

A tolerance that is negative, NaN or infinite throws [`InvalidToleranceException`](errors.md).
