<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\NetteFluentTranslator;

use Taco\FluentIntl\FluentFormaterResolver;


class StaticIntlResolver implements FluentFormaterResolver
{

	private $items;


	function __construct(array $items)
	{
		$this->items = $items;
	}



	function fetchFor($locale) : array
	{
		$xs = [];
		foreach ($this->items as $x) {
			list($name, $inst) = self::createIntl($x, $locale);
			$xs[$name] = $inst;
		}
		return $xs;
	}



	private static function createIntl($className, $locale)
	{
		$parts = explode('\\', $className);
		$name = strtoupper(substr(end($parts), 0, -4));
		return [$name, new $className($locale)];
	}
}
