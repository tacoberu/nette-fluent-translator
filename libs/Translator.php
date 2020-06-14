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
	private $supported;

	function __construct(LocaleResolver $localeResolver, MessageLoader $loader, $default, array $supported)
	{
		$this->loader = $loader;
		$this->localeResolver = $localeResolver;
		$this->default = $default;
		$this->supported = $supported;
	}



	function translate($id, $args = Null)
	{
		if (empty($args)) {
			$args = [];
		}
		$catalog = $this->getCatalog();
		$msg = $catalog->getMessage($id);
		list($msg, $_) = $catalog->formatPattern($msg->value, $args);
		return $msg;
	}



	private function getCatalog()
	{
		if (empty($this->catalog)) {
			$this->catalog = $this->loader->loadFor($this->localeResolver->resolve($this->supported, $this->default));
		}
		return $this->catalog;
	}
}
