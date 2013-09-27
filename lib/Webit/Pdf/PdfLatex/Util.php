<?php
namespace Webit\Pdf\PdfLatex;

class Util {
    public static function escapeLatexSpecialChars($input) {
        if (is_string($string)) {
            $search = array('\\', '#', '$', '€', '%', '&', '~', '_', '{', '}', '^', '\\$\backslash\\$');
            $replace = array('$\backslash$', '\#', '\$', '\euro', '\%', '\&', '$\sim$', '\_', '\{', '\}', '$\hat{~}$', '$\backslash$');
            return str_replace($search, $replace, $var);
        }
        
        return $input;
    }
    
    public static function nobreakSpace($input) {
        return str_replace(' ', '~', $input);
    }
    
    public static function newLine($input) {
        return preg_replace('/\n/', '\\\\\\\\', $input);
    }
}
