<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\NetteFluentTranslator;

use Nette\Localization\ITranslator;


class Translator implements ITranslator
{
	private $catalog;
	private $loader;
	private $localeResolver;
	private $default;
	private $currentLocale;
	private $supported;

	function __construct(LocaleResolver $localeResolver, MessageLoader $loader, $default, array $supported)
	{
		$this->loader = $loader;
		$this->localeResolver = $localeResolver;
		$this->default = $default;
		$this->supported = $supported;
	}



	function translate($id, ...$args) : string
	{
		if (empty($args)) {
			$args = [];
		}
		$catalog = $this->getCatalog();
		if ($msg = $catalog->getMessage($id)) {
			list($msg, $_) = $catalog->formatPattern($msg->value, $args);
		}
		return $msg ?: $id;
	}



	function changeLocaleTo(string $code)
	{
		// @TODO Validace
		$this->currentLocale = $code;
		$this->catalog = Null;
	}



	private function getCatalog()
	{
		if (empty($this->catalog)) {
			$this->catalog = $this->loader->loadFor($this->resolveLocaleCode());
		}
		return $this->catalog;
	}



	/**
	 * Vybere kód. Buď je explicintě určen, nebo se automagicky odvodí na základě prostředí.
	 */
	private function resolveLocaleCode() : string
	{
		if ($this->currentLocale) {
			return $this->currentLocale;
		}
		return $this->localeResolver->resolve($this->supported, $this->default);
	}
}
