<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\NetteFluentTranslator;

use Nette\Http\IRequest;


class LocaleResolver
{

	const ACCEPT_LANGUAGE_HEADER = 'Accept-Language';


	/** @var Request */
	private $request;


	function __construct(IRequest $request)
	{
		$this->request = $request;
	}



	/**
	 * @param array ['jp-JP', 'de_DE', 'en_GB']
	 * @param string 'cs_CZ'
	 * @return string 'cs_CZ'
	 */
	function resolve(array $acceptable, $default = Null)
	{
		$header = $this->request->getHeader(self::ACCEPT_LANGUAGE_HEADER);
		if ( ! $header) {
			return $default;
		}

		$userlangs = array_filter(array_map(function($x) {
			return strtr(substr(preg_replace('~q\=\d.\d,?~', '', $x), 0, 5), '_', '-');
		}, explode(';', $header)));

		// fullname cs_CZ
		foreach ($acceptable as $x) {
			if (in_array(strtr($x, '_', '-'), $userlangs, True)) {
				return $x;
			}
		}

		// shortname cs
		$userlangs = array_map(function($x) {
			return substr($x, 0, 2);
		}, $userlangs);
		foreach ($acceptable as $x) {
			if (in_array(substr(strtr($x, '_', '-'), 0, 2), $userlangs, True)) {
				return $x;
			}
		}

		return $default;
	}

}
