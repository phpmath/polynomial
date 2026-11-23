# polynomial

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![CI][ico-ci]][link-ci]
[![Total Downloads][ico-downloads]][link-downloads]

A PHP library to work with immutable univariate polynomials with float coefficients.

## Documentation

The full documentation is at https://phpmath.github.io/polynomial/. Every change is listed in the [changelog](CHANGELOG.md) and every release on the [releases page](https://github.com/phpmath/polynomial/releases).

## Install

```bash
composer require phpmath/polynomial
```

## Requirements

- PHP 8.4 or newer

## Usage

Every polynomial is an immutable value object. Operations never change the instance they are
called on, they return a new instance.

Coefficients are given in **ascending power order**: the first coefficient belongs to `x^0`.
Trailing zeros are trimmed and the zero polynomial has no coefficients and degree -1.

``` php
use PHPMath\Polynomial\Polynomial;

$p = Polynomial::of(5, -1, 0, 2);   // 2x^3 - x + 5
echo $p;                            // "2x^3 - x + 5"
echo $p->degree();                  // 3
echo $p->evaluate(2);               // 19

$q = Polynomial::fromRoots(1, 2);   // x^2 - 3x + 2
$sum = $p->add($q);
$product = $p->multiply($q);
$composed = $p->compose($q);

$division = $p->divide($q);
$division->quotient;                // Polynomial
$division->remainder;               // Polynomial

$p->derivative();                   // 6x^2 - 1
$p->integral(7);                    // antiderivative with constant term 7

Polynomial::fromRoots(-2, 1, 3)->realRoots(); // [-2.0, 1.0, 3.0]
```

## Features

* Construction with `of()`, `fromRoots()`, `zero()`, `one()` and `x()`.
* `degree()`, `coefficient()`, `coefficients()` and `isZero()`.
* `add()`, `subtract()`, `multiply()`, `scale()`, `negate()` and `pow()`.
* Long division with `divide()`, which returns a `DivisionResult` (quotient and remainder).
* Composition with `compose()` and evaluation with `evaluate()` (Horner's method).
* `derivative()` and `integral()`.
* `realRoots()`: the distinct real roots in ascending order.
* `equals()` with a tolerance and `__toString()`.

### Real roots

Degrees 0 to 2 use closed forms. For higher degrees the roots of the derivative split the real
line into intervals on which the polynomial is monotonic, so each interval holds at most one
root, which is refined by bisection. The tolerance (default `1e-9`) is relative: a polynomial
value counts as zero when it is at most the tolerance times the sum of the absolute values of the
terms at that point. A multiple root is reported once. Roots of even multiplicity are only
located to about the square root of the floating point precision. Roots that differ by at most
the tolerance are merged into their mean, and a tolerance of `0.0` never merges roots.

### Exceptions

All exceptions live in `PHPMath\Polynomial\Exception` and implement `PolynomialException`:
`InvalidCoefficientException` (NaN or infinity), `InvalidPowerException` (negative power or
exponent), `InvalidToleranceException`, `DivisionByZeroException` and `ZeroPolynomialException`
(roots of the zero polynomial, a `DomainException`).

## Development

```bash
composer test
composer test-coverage
composer analyse
composer check-style
composer fix-style
```

## Contributing

All contributions are welcome. Open an issue or a pull request on GitHub. Please follow the coding standard and add tests.

## Security

If you discover a security issue, please create an issue in the issue tracker.

## Credits

- [Walter Tamboer](https://github.com/waltertamboer)
- [All contributors](https://github.com/phpmath/polynomial/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/phpmath/polynomial.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-ci]: https://img.shields.io/github/actions/workflow/status/phpmath/polynomial/ci.yml?branch=master&style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/phpmath/polynomial.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/phpmath/polynomial
[link-ci]: https://github.com/phpmath/polynomial/actions/workflows/ci.yml
[link-downloads]: https://packagist.org/packages/phpmath/polynomial/stats
