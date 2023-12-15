# Pimcore DevKit
Pimcore DevKit is a set of tools that helps developing Pimcore applications. It is intended to wrap Pimcore classess to enable easy use of them.

**Table of Contents**

- [Pimcore DevKit](#pimcore-devkit)
    - [Compatibility](#compatibility)
    - [Installing/Getting started](#installinggetting-started)
    - [Features](#features)
        - [Commands](#commands)
    - [Developing](#developing)
    - [Configuration](#configuration)
    - [Contributing](#contributing)
    - [Licensing](#licensing)
    - [Standards & Code Quality](#standards--code-quality)
    - [About Authors](#about-authors)

## Compatibility

This module is compatible with Pimcore 10.0.0 and higher.

## Installing/Getting started

composer require divanteltd/pimcoredevkit

## Features

### Commands

####  devkit:asset:synchronize
Synchronizes assets tree with filesystem changes
#### devkit:delete_by_id
Deletes object, document, asset from given tree
#### devkit:deletefolder
Deletes folder from given tree

## Developing

when developing please make sure code is compatible with both Pimcore 10 and 11.

for test purpose there is `./scripts/test.sh` script, that check code for Pimcore 10.0, Pimcore 10.5 and Pimcore 11-Alpha7.

## Configuration

No configuration is needed.

## Contributing

If you'd like to contribute, please fork the repository and use a feature branch. Pull requests are warmly welcome.

## Licensing

GPL-3.0-or-later

## Standards & Code Quality

This module respects all Pimcore code quality rules and our own PHPCS and PHPMD rulesets.

## About Authors

![Divante-logo](http://www.divante.com/hs-fs/hubfs/Divante_BW.png "Divante")

We are a Software House from Europe, existing from 2008 and employing about 150 people. Our core competencies are built around Magento, Pimcore and bespoke software projects (we love Symfony3, Node.js, Angular, React, Vue.js). We specialize in sophisticated integration projects trying to connect hardcore IT with good product design and UX.

We work for Clients like INTERSPORT, ING, Odlo, Onderdelenwinkel and CDP, the company that produced The Witcher game. We develop two projects: [Open Loyalty](http://www.openloyalty.io/ "Open Loyalty") - an open source loyalty program and [Vue.js Storefront](https://github.com/DivanteLtd/vue-storefront "Vue.js Storefront").

We are part of the OEX Group which is listed on the Warsaw Stock Exchange. Our annual revenue has been growing at a minimum of about 30% year on year.

Visit our website [Divante.co](https://divante.co/ "Divante.co") for more information.

