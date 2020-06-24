<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\NetteFluentTranslator;

use Nette;
use LogicException;


/**
 * extensions:
 *     translation: Taco\NetteFluentTranslator\Extension(%appDir%/locales)
 * translation:
 *     defaultLocale: cs_CZ
 *     supportedLocales:
 *         - cs_CZ
 *
 * @author Martin Takáč <martin@takac.name>
 */
class Extension extends Nette\DI\CompilerExtension
{

	/**
	 * @var array
	 */
	private $default = [
		//~ 'defaultLocale' => 'cs_CZ',
		//~ 'supportedLocales' => ['cs_CZ', 'en_GB'],
	];


	/** @var string */
	private $dataDir;


	function __construct($dataDir)
	{
		self::assertReadable($dataDir, 'dataDir');
		$this->dataDir = $dataDir;
	}



	function loadConfiguration()
	{
		$config = $this->getConfig($this->default);
		$config['supportedLocales'] = array_unique($config['supportedLocales']);

		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('localeResolver'))
			->setFactory(LocaleResolver::class);
		$builder->addDefinition($this->prefix('loader'))
			->setFactory(MessageLoader::class, [$this->dataDir]);
		$builder->addDefinition($this->prefix('translator'))
			->setFactory(Translator::class, [$this->prefix('@localeResolver'), $this->prefix('@loader'), $config['defaultLocale'], $config['supportedLocales']]);
	}



	private static function assertReadable($path, $label)
	{
		if ( ! file_exists($path)) {
			throw new LogicException("Path $label: '{$path}' is not found.");
		}
		if ( ! is_readable($path)) {
			throw new LogicException("Path $label: '{$path}' is not readable.");
		}
	}
}
