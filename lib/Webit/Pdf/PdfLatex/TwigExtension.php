<?php
namespace Webit\Pdf\PdfLatex;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Webit\Pdf\PdfLatex\HtmlConverter\ConverterInterface;

class TwigExtension extends AbstractExtension {

    /** @var ConverterInterface*/
    private $converter;
	
    public function __construct(ConverterInterface $converter) {
        $this->converter = $converter;
    }
	
    public function getFunctions()
    {
        return array(
            'noBreakSpace' => new TwigFunction('noBreakSpace', [$this, 'noBreakSpace']),
        );
    }
    
    public function getFilters() {
        return array(
            'escapeLatexChars' => new TwigFilter('escapeLatexChars', [$this, 'escapeLatexSpecialChars']),
            'noBreakSpace' => new TwigFilter('noBreakSpace', [$this, 'noBreakSpace']),
            'newLine' => new TwigFilter('newLine', [$this, 'newLine']),
            'latexCommand' => new TwigFilter('latexCommand', [$this, 'wrapLatexCommand']),
            'toLatex' => new TwigFilter('toLatex', [$this, 'htmlToLatex'])
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
