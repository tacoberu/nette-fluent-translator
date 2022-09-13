<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\NetteFluentTranslator;

use Taco\FluentIntl\FluentTranslator;
use Taco\FluentIntl\FluentResource;


class MessageLoader
{
	const DEFAULT_MESSAGE_NAME = 'messages';


	private $baseDir;
	private $formatersResolver;


	function __construct($baseDir, $formatersResolver)
	{
		$this->baseDir = $baseDir;
		$this->formatersResolver = $formatersResolver;
	}



	/**
	 * @param string cs_CZ
	 */
	function loadFor($locale)
	{
		$filename = $this->baseDir . '/' . $locale . '/' . self::DEFAULT_MESSAGE_NAME . '.flt';
		if ( ! file_exists($filename)) {
			throw new \LogicException("Localization file source '{$filename}' is not found.");
		}
		$bundle = new FluentTranslator(strtr($locale, '_', '-'), $this->formatersResolver);
		$bundle->addResource(new FluentResource(file_get_contents($filename)));
		return $bundle;
	}

}
