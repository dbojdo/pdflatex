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
            'newLine' => new \Twig_Filter_Method($this, 'newLine')
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
    
    public function locate($input) {
        
    }
}
