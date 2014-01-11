<?php
namespace Webit\Pdf\PdfLatex;

use Webit\Pdf\PdfLatex\HtmlConverter\ConverterInterface;
class TwigExtension extends \Twig_Extension {

	/**
	 * 
	 * @var ConverterInterface
	 */
	private $converter;
	
	public function __construct(ConverterInterface $converter) {
		$this->converter = $converter;
	}
	
    public function getFunctions()
    {
        return array(
            'noBreakSpace' => new \Twig_Function_Method($this, 'noBreakSpace'),
        );
    }
    
    public function getFilters() {
        return array(
            'escapeLatexChars' => new \Twig_Filter_Method($this, 'escapeLatexSpecialChars'),
            'noBreakSpace' => new \Twig_Filter_Method($this, 'noBreakSpace'),
            'newLine' => new \Twig_Filter_Method($this, 'newLine'),
        	'latexCommand' => new \Twig_Filter_Method($this, 'wrapLatexCommand'),
        	'toLatex' => new \Twig_Filter_Method($this, 'htmlToLatex')
        );
    }
    
    public function getName()
    {
        return 'webit_pdflatex';
    }
    
    public function escapeLatexSpecialChars($input) {
        return Util::escapeLatexSpecialChars($input);
    }
    
    public function noBreakSpace($input) {
        return Util::nobreakSpace($input);
    }
    
    public function newLine($input) {
        return Util::newLine($input);
    }
    
    public function wrapLatexCommand($input, $command, array $options = array()) {
    	return Util::command($input, $command, $options);
    }
    
    public function htmlToLatex($input) {
    	return $this->converter->convert($input);
    }
}
