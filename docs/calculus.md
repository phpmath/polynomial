---
title: Evaluation and calculus
intro: Evaluate a polynomial at a point, and find its derivative and integral.
group: Guides
order: 50
---

## Evaluating

`evaluate()` substitutes a number for x and returns the value as a float. It uses Horner's method, which needs one multiplication and one addition per coefficient. The zero polynomial evaluates to `0.0` everywhere.

```php
use PHPMath\Polynomial\Polynomial;

$p = Polynomial::of(5, -1, 0, 2); // 2x^3 - x + 5

echo $p->evaluate(2);                        // 19
echo Polynomial::of(1, 2)->evaluate(0.5);    // 2
echo Polynomial::zero()->evaluate(3);        // 0
```

`evaluate()` does not check its argument. Passing NaN gives NaN.

## Derivatives

The derivative of a polynomial describes how fast its value changes at each x. For a term `c * x^n` it is `n * c * x^(n - 1)`. `derivative()` returns it as a new polynomial. The derivative of a constant is the zero polynomial.

```php
use PHPMath\Polynomial\Polynomial;

$p = Polynomial::of(5, -1, 0, 2); // 2x^3 - x + 5

echo $p->derivative();                   // 6x^2 - 1
echo Polynomial::of(4)->derivative();    // 0
```

Because the derivative is a polynomial too, you can differentiate again and again.

```php
use PHPMath\Polynomial\Polynomial;

$p = Polynomial::of(5, -1, 0, 2);

echo $p->derivative()->derivative(); // 12x
```

## Integrals

The integral (or antiderivative) is the reverse of the derivative. A term `c * x^n` becomes `c / (n + 1) * x^(n + 1)`. Differentiating the result gives back the original polynomial. The reverse is not unique, because any constant can be added, so `integral()` takes the constant term as an optional argument. It defaults to `0.0`.

```php
use PHPMath\Polynomial\Polynomial;

$p = Polynomial::of(5, -1, 0, 2); // 2x^3 - x + 5

echo $p->integral();   // 0.5x^4 - 0.5x^2 + 5x
echo $p->integral(7);  // 0.5x^4 - 0.5x^2 + 5x + 7
```

A constant that is NaN or infinite throws [`InvalidCoefficientException`](errors.md). The integral of the zero polynomial is the constant itself.

```php
use PHPMath\Polynomial\Polynomial;

echo Polynomial::zero()->integral(3); // 3
```
