<?php
namespace Webit\Pdf\PdfLatex;

class TwigExtension extends \Twig_Extension {    
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
        	'latexCommand' => new \Twig_Filter_Method($this, 'wrapLatexCommand')
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
}
