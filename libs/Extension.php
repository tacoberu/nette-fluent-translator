<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\NetteFluentTranslator;

use Nette;
use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Nette\Localization\ITranslator;
use Nette\Schema\Expect;
use Latte;
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
	 * Umístění souborů <locale>/*.flt
	 * @var string
	 */
	private $dataDir;


	/**
	 * @var FluentFormaterResolver
	 */
	private $formatersResolver;


	function __construct($dataDir, $formatersResolver = Null)
	{
		self::assertReadable($dataDir, 'dataDir');
		$this->dataDir = $dataDir;
		$this->formatersResolver = $formatersResolver;
	}



	function getConfigSchema(): Nette\Schema\Schema
	{
		return Expect::structure([
			'defaultLocale' => Expect::string(),
			'supportedLocales' => Expect::listOf('string'),
			'injectToLatte' => Expect::bool()->default(false),
		]);
	}



	function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('localeResolver'))
			->setFactory(LocaleResolver::class);
		$builder->addDefinition($this->prefix('loader'))
			->setFactory(MessageLoader::class, [$this->dataDir, $this->formatersResolver]);
		$builder->addDefinition($this->prefix('translator'))
			->setFactory(Translator::class, [$this->prefix('@localeResolver'), $this->prefix('@loader'), $this->config->defaultLocale, array_unique($this->config->supportedLocales)]);
	}



	/**
	 * Translator přidáme do Latte
	 */
	function beforeCompile()
	{
		if ($this->config->injectToLatte) {
			$builder = $this->getContainerBuilder();

			$translator = $builder->getDefinitionByType(ITranslator::class);

			$latteFactory = $builder->getDefinitionByType(ILatteFactory::class);
			if (version_compare(Latte\Engine::VERSION, '3', '<')) {
				$latteFactory->getResultDefinition()
					->addSetup('?->addFilter(?, function(Latte\Runtime\FilterInfo $fi, ...$args) {
						return ?->translate(...$args);
					})', ['@self', 'translate', $translator]);
			}
			else {
				throw new \LogicException('comming soon...');
				//~ $this->latte->addExtension(new Latte\Essential\TranslatorExtension($translator, $language));
			}
		}
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
