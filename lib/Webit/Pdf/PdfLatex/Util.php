<?php
namespace Webit\Pdf\PdfLatex;

class Util {
	
	/**
	 * 
	 * @param string $input
	 * @return string
	 */
    public static function escapeLatexSpecialChars($input) {
        if (is_string($input)) {
            $search = array('\\', '#', '$', '€', '%', '&', '~', '_', '{', '}', '^', '\\$\backslash\\$','>','<','>=','<=');
            $replace = array('$\backslash$', '\#', '\$', '\euro', '\%', '\&', '$\sim$', '\_', '\{', '\}', '$\hat{~}$', '$\backslash$','\textgreater','\textless','\ge','\le');
            return str_replace($search, $replace, $input);
        }
        
        return $input;
    }
    
    /**
     * 
     * @param string $input
     * @return mixed
     */
    public static function nobreakSpace($input) {
        return str_replace(' ', '~', $input);
    }
    
    /**
     * 
     * @param string $input
     * @return string
     */
    public static function newLine($input) {
        return preg_replace('/\n/', '\\\\\\\\', $input);
    }
    
    /**
     * 
     * @param string $input
     * @param string $command
     * @param array $options
     * @return string
     */
    public static function command($input, $command, array $options = array()) {
    	$strOptions = '';
    	if(count($options) > 0) {
    		$arOptions = array();
    		foreach($options as $key => $value) {
    			$arOptions[] = $key.'='.$value;
    		}
    		$strOptions = sprintf('[%s]',implode(',',$arOptions));
    	}
    	$wrapped = sprintf('\%s%s{%s}',$command, $strOptions, self::escapeLatexSpecialChars($input));
    	
    	return $wrapped;
    }
}
