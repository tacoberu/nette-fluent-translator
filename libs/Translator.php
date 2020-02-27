<?php

namespace Taco\NetteFluentTranslator;

use Taco\FluentIntl\FluentTranslator;


class Translator implements Nette\Localization\ITranslator
{
	private $provider;

	function __construct(FluentTranslator $provider)
	{
		$this->provider = $provider;
	}



	function translate($id, array $args = [])
	{
		$msg = $this->provider->getMessage($id);
		list($msg, $_) = $this->provider->formatPattern($msg->value, $args);
		return $msg;
	}
}
