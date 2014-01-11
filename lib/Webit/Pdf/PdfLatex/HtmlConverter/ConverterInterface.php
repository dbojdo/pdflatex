<?php
namespace Webit\Pdf\PdfLatex\HtmlConverter;

interface ConverterInterface {
	
	/**
	 * 
	 * @param string $html
	 * @return string
	 */
	public function convert($html);
}
