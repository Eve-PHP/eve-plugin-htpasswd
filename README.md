![logo](http://eden.openovate.com/assets/images/cloud-social.png) Eve HTPASSWD Plugin
====
HTPASSWD Dialog for the Eve Framework
====
[![Build Status](https://api.travis-ci.org/eve-php/eve-plugin-htpasswd.png)](https://travis-ci.org/eve-php/eve-plugin-htpasswd) [![Latest Stable Version](https://poser.pugx.org/eve-php/eve-plugin-htpasswd/v/stable)](https://packagist.org/packages/eve-php/eve-plugin-htpasswd) [![Total Downloads](https://poser.pugx.org/eve-php/eve-plugin-htpasswd/downloads)](https://packagist.org/packages/eve-php/eve-plugin-htpasswd) [![Latest Unstable Version](https://poser.pugx.org/eve-php/eve-plugin-htpasswd/v/unstable)](https://packagist.org/packages/eve-php/eve-plugin-htpasswd) [![License](https://poser.pugx.org/eve-php/eve-plugin-htpasswd/license)](https://packagist.org/packages/eve-php/eve-plugin-htpasswd)
====

- [Install](#install)
- [Usage](#usage)

====

<a name="install"></a>
## Install

`composer install eve-php/eve-plugin-htpasswd`

====

<a name="usage"></a>
## Usage

1. Add this in public/index.php towards the top of the bootstrap chain.

```
//CSRF
->add(Eve\Plugin\Htpasswd\Setup::i()->import(array(
	'admin' => '123',
	'guest' => 'guest'
)))
```

2. Done ;)