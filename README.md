Nette adapter for Fluent localization
=====================================

This is a Nette adapter for PHP implementation of Project Fluent, a localization framework
designed to unleash the entire expressive power of natural language
translations.

Project Fluent keeps simple things simple and makes complex things possible.
The syntax used for describing translations is easy to read and understand.  At
the same time it allows, when necessary, to represent complex concepts from
natural languages like gender, plurals, conjugations, and others.


Learn the FTL syntax
--------------------

FTL is a localization file format used for describing translation resources.
FTL stands for _Fluent Translation List_.

FTL is designed to be simple to read, but at the same time allows to represent
complex concepts from natural languages like gender, plurals, conjugations, and
others.

    hello-user = Hello, { $username }!

[Read the Fluent Syntax Guide][] in order to learn more about the syntax.  If
you're a tool author you may be interested in the formal [EBNF grammar][].

[Read the Fluent Syntax Guide]: http://projectfluent.org/fluent/guide/
[EBNF grammar]: https://github.com/projectfluent/fluent/tree/master/spec


Installation
------------

The recommended way to install is via Composer:

        composer require tacoberu/nette-fluent-translator



Usage
-----

Sample flt localation file
```ini

-brand-name = Foo 3000
welcome = Welcome, {$name}, to {-brand-name}!
greet-by-name = Hello, { $name }!
form-title = Title
```


neon configuration
```ini

extensions:
    translation: Taco\NetteFluentTranslator\Extension(%appDir%/locales)

translation:
	defaultLocale: cs_CZ
	supportedLocales:
		- cs_CZ
		- fr_FR
		- en_GB
```


In php code:
```php

$translator = $container->getService('translator');

dump($translator->translate('-brand-name'));
// "Foo 3000"

dump($translator->translate('welcome', ['name' => 'Anne']));
// "Welcome, Anne, to Foo 3000!"

dump($translator->translate('greet-by-name', ['name' => 'Anne']));
// "Hello, Anne!"

This means, for example:

```php
function beforeRender()
{
	$this->template->setTranslator($this->context->getByType(Nette\Localization\ITranslator::class));
}
```
and

```php
$form = new UI\Form($this, $name);
$form->setTranslator($this->context->getByType(Localization\ITranslator::class));
$form->addText('title', 'form-title');
```

In Latte template:
```html
{_"-brand-name"}
{_"welcome", ["name" => "Anne"]}
{_"greet-by-name", ["name" => "Anne"]}
```
