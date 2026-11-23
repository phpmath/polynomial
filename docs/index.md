---
title: Introduction
intro: Immutable polynomials for PHP with float coefficients. Do arithmetic, evaluate, differentiate, divide and find real roots.
group: Getting started
order: 10
---

## Requirements

The library needs a current PHP version. It has no runtime dependencies and does not need any extension.

- PHP 8.4 or newer

## Installation

Install the library with Composer.

```bash
composer require phpmath/polynomial
```

## A first example

A polynomial is a sum of terms such as `2x^3` or `-x`. Create one from its coefficients (the numbers in front of the powers of x), starting with the constant term.

```php
use PHPMath\Polynomial\Polynomial;

$p = Polynomial::of(5, -1, 0, 2);

echo $p;               // 2x^3 - x + 5
echo $p->degree();     // 3
echo $p->evaluate(2);  // 19
```

The `degree` is the highest power of x that has a non-zero coefficient. Calling `evaluate()` or any other method did not change `$p`. Every operation in the library returns a new `Polynomial`.

## Where to go next

Start with the core concepts, or jump straight to the topic you need.

<div class="cards"><a href="concepts.md"><strong>Core concepts</strong><span>Immutability, creating polynomials and coefficient order.</span></a><a href="arithmetic.md"><strong>Arithmetic</strong><span>Add, subtract, multiply, scale, powers and composition.</span></a><a href="division.md"><strong>Division</strong><span>Long division with a quotient and a remainder.</span></a><a href="calculus.md"><strong>Evaluation and calculus</strong><span>Evaluate, derivatives and integrals.</span></a><a href="roots.md"><strong>Real roots</strong><span>Find where a polynomial is zero.</span></a><a href="api.md"><strong>API reference</strong><span>Every method with its parameters and errors.</span></a></div>
