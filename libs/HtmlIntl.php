<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\NetteFluentTranslator;



class HtmlIntl
{

	private $locale;


	function __construct($locale)
	{
		$this->locale = $locale;
	}



	function format($val, array $args)
	{
		return (string)$val;
	}

}
