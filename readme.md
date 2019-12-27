# Pimcore DevKit
[![Analysis Actions](https://github.com/DivanteLtd/pimcore-pimcoredevkit/workflows/Analysis/badge.svg?branch=master)](https://github.com/DivanteLtd/pimcore-pimcoredevkit/actions)
[![Tests Actions](https://github.com/DivanteLtd/pimcore-pimcoredevkit/workflows/Tests/badge.svg?branch=master)](https://github.com/DivanteLtd/pimcore-pimcoredevkit/actions)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/867f5904381d4e3a86bf33f2b5a99401)](https://www.codacy.com/app/Divante/pimcore-devkit?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=DivanteLtd/pimcore-devkit&amp;utm_campaign=Badge_Grade)
[![Latest Stable Version](https://poser.pugx.org/divanteltd/pimcoredevkit/v/stable)](https://packagist.org/packages/divanteltd/pimcoredevkit)
[![Total Downloads](https://poser.pugx.org/divanteltd/pimcoredevkit/downloads)](https://packagist.org/packages/divanteltd/pimcoredevkit)
[![License](https://poser.pugx.org/divanteltd/pimcoredevkit/license)](https://packagist.org/packages/divanteltd/pimcoredevkit)

Pimcore DevKit is a set of tools that helps developing Pimcore applications. It is intended to wrap Pimcore classess 
to enable easy use of them.

**Table of Contents**

- [Pimcore DevKit](#pimcore-devkit)
	- [Compatibility](#compatibility)
	- [Installing/Getting started](#installinggetting-started)
	- [Features](#features)
		- [Commands](#commands)
	- [Contributing](#contributing)
	- [Licensing](#licensing)
	- [Standards & Code Quality](#standards--code-quality)
	- [About Authors](#about-authors)

## Compatibility

This module is compatible with Pimcore 5.3.0 and higher.

## Installing/Getting started

```bash
composer require divanteltd/pimcoredevkit
```

## Features

### Commands

#### `devkit:asset:synchronize`

Synchronizes assets tree with filesystem changes

#### `devkit:delete_by_id`

Deletes object, document, asset from given tree

#### `devkit:deletefolder`

Deletes folder from given tree

#### `devkit:classes:update`

Updates class definition from json

#### `devkit:bricks:update`

Updates bricks definition from json

## Contributing

If you'd like to contribute, please fork the repository and use a feature branch. Pull requests are warmly welcome.

## Licensing

GPL-3.0-or-later

## Standards & Code Quality

This module respects all Pimcore 5 code quality rules and our own PHPCS and PHPMD rulesets.

## About Authors

![Divante-logo](http://divante.com/logo-HG.png "Divante")

We are a Software House from Europe, existing from 2008 and employing about 150 people. Our core competencies are 
built around Magento, Pimcore and bespoke software projects (we love Symfony3, Node.js, Angular, React, Vue.js). 
We specialize in sophisticated integration projects trying to connect hardcore IT with good product design and UX.

We work for Clients like INTERSPORT, ING, Odlo, Onderdelenwinkel and CDP, the company that produced The Witcher game. 
We develop two projects: [Open Loyalty](http://www.openloyalty.io/ "Open Loyalty") - an open source loyalty program 
and [Vue.js Storefront](https://github.com/DivanteLtd/vue-storefront "Vue.js Storefront").

We are part of the OEX Group which is listed on the Warsaw Stock Exchange. Our annual revenue has been growing at a 
minimum of about 30% year on year.

Visit our website [Divante.co](https://divante.com/ "Divante.com") for more information.
