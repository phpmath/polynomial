---
title: Core concepts
intro: The few ideas you need to use Polynomial well.
group: Getting started
order: 20
---

## Polynomials are immutable

`Polynomial` is a `final readonly` class. Once created, a polynomial never changes. Methods such as `add()` or `negate()` return a new instance and leave the original untouched, so you can pass polynomials around without defensive copies.

```php
use PHPMath\Polynomial\Polynomial;

$p = Polynomial::of(1, 1);
$q = $p->add(Polynomial::of(1));

echo $p; // x + 1
echo $q; // x + 2
```

## Creating polynomials

A polynomial has no public constructor. Use one of the named constructors. `of()` takes the coefficients, `fromRoots()` takes the roots, and `zero()`, `one()` and `x()` give three common building blocks.

```php
use PHPMath\Polynomial\Polynomial;

echo Polynomial::of(5, -1, 0, 2);   // 2x^3 - x + 5
echo Polynomial::fromRoots(1, 2);   // x^2 - 3x + 2
echo Polynomial::zero();            // 0
echo Polynomial::one();             // 1
echo Polynomial::x();               // x
```

`fromRoots()` builds the product `(x - r1)(x - r2)...`. A root is a value of x for which the polynomial is zero. Repeating a root makes a multiple root, and calling it without arguments gives the constant polynomial `1`.

```php
use PHPMath\Polynomial\Polynomial;

echo Polynomial::fromRoots(2, 2); // x^2 - 4x + 4
echo Polynomial::fromRoots();     // 1
```

You can also build a polynomial from `x()` with the arithmetic methods.

```php
use PHPMath\Polynomial\Polynomial;

$x = Polynomial::x();

echo $x->pow(2)->add($x->scale(3))->add(Polynomial::of(2)); // x^2 + 3x + 2
```

## Coefficient order

Coefficients are given in ascending power order. The first coefficient belongs to `x^0` (the constant term), the second to `x^1`, and so on. This is the reverse of how a polynomial is usually written, so `of(5, -1, 0, 2)` is `2x^3 - x + 5`.

> [!WARNING]
> Do not pass the coefficients in the order you read them. `Polynomial::of(2, 0, -1, 5)` is `5x^3 - x^2 + 2`, not `2x^3 - x + 5`.

Trailing zeros are removed, so every polynomial has exactly one representation. The zero polynomial has no coefficients and a degree of `-1`. Negative zero is turned into positive zero.

```php
use PHPMath\Polynomial\Polynomial;

echo Polynomial::of(1, 2, 0, 0);               // 2x + 1
echo Polynomial::of(0, 0)->degree();           // -1
echo json_encode(Polynomial::of(-0.0)->coefficients()); // []
```

## Reading values

`degree()` returns the highest power. `coefficient()` returns the coefficient of one power, and gives `0.0` above the degree. `coefficients()` returns all of them as a list. `isZero()` tells you whether it is the zero polynomial.

```php
use PHPMath\Polynomial\Polynomial;

$p = Polynomial::of(5, -1, 0, 2);

echo $p->coefficient(3);                // 2
echo $p->coefficient(10);               // 0
echo json_encode($p->coefficients());   // [5,-1,0,2]
var_dump($p->isZero());                 // bool(false)
```

`echo` and string casting give the readable form such as `2x^3 - x + 5`. See [Comparison and output](comparison.md).

## Errors

The library only accepts finite numbers. NaN and infinity throw [`InvalidCoefficientException`](errors.md). Negative powers throw `InvalidPowerException`. Both implement `PolynomialException`, so you can catch that to handle every library error.

```php
use PHPMath\Polynomial\Exception\InvalidCoefficientException;
use PHPMath\Polynomial\Polynomial;

try {
    Polynomial::of(1, NAN);
} catch (InvalidCoefficientException $exception) {
    echo $exception->getMessage(); // Values must be finite numbers, NaN and infinity are not allowed.
}
```

## Results are not checked

Only input is checked, never the result of an operation. This is the policy of all phpmath libraries: a check after every operation would cost time and hide the real cause. A result can therefore overflow to infinity, for example when `multiply()`, `add()` or `scale()` work on very large coefficients. Use `isFinite()` to ask whether every coefficient of a polynomial is finite.

```php
use PHPMath\Polynomial\Polynomial;

$p = Polynomial::of(1e308)->scale(10);

var_dump($p->isFinite());                 // bool(false)
var_dump($p->coefficient(0));             // float(INF)
var_dump(Polynomial::of(1, 2)->isFinite()); // bool(true)
```

Negative zero never appears in a polynomial or in the roots it returns: it is always turned into positive zero.

All exceptions are listed on the [errors page](errors.md).
