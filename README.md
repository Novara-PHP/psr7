<img src="Novarity.png" height="120" align="right">

# PSR7 and PSR17 without variables

![](https://github.com/Novara-PHP/psr7/actions/workflows/tests.yml/badge.svg)
![](https://raw.githubusercontent.com/Novara-PHP/psr7/image-data/coverage.svg)
![](https://img.shields.io/github/v/release/Novara-PHP/psr7)
[![License: MIT](https://img.shields.io/github/license/Novara-PHP/dynamic-readonly-classes)](../../raw/main/LICENSE.txt)

Copy on write? That's right! This is a PSR-7 and PSR-17 implementation without the use of variables.

Proper proof that Novara is more than a simple shitpost.

## What is this

It's just like [guzzle/psr7](https://github.com/guzzle/psr7) but without any variables and way less performant.


## Installation

```bash
composer require novara/psr7
```


## Usage

Use like any other PSR7 / PSR17 implementation.


## Limitations

ConstantStream is read only and cannot use `seek`, `rewind`, `read`, or any... stream functions.

You can use `getContents`.
