![logo](http://eden.openovate.com/assets/images/cloud-social.png) Eve HTPASSWD Plugin
====
HTPASSWD Dialog for the Eve Framework
====
[![Build Status](https://api.travis-ci.org/eve-php/eve-plugin-htpasswd.png)](https://travis-ci.org/eve-php/eve-plugin-htpasswd)
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