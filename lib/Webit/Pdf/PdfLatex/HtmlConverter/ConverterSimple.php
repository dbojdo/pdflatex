<?php
namespace Webit\Pdf\PdfLatex\HtmlConverter;

use Webit\Pdf\PdfLatex\Util;
class ConverterSimple implements ConverterInterface {
	
	/**
	 * Color schema constants
	 */
	const COLOR_HTML = 'HTML';
	const COLOR_RGB = 'RGB';
	
	/**
	 * 
	 * @param string $html
	 * @return string
	 */
	public function convert($html) {
		// strip not supported tags
		$output = $this->escapeLatexSpecialChars($html);
		$output = strip_tags($html,'<p><i><em><u><b><strong><strike><span><del><hr><br>');
		
		// parse paragraphs
		$map = array(
				'/\<p\>/'=>'',
				'/\<\/p\>/'=>"\n\n"
		);
		$output = preg_replace(array_keys($map),array_values($map),$output);
	
		// convert basic tags
		$map = array(
				'/\<b\>/'=>'\\textbf{',
				'/\<strong\>/'=>'\\textbf{',
				'/\<i\>/'=>'\\textit{',
				'/\<em\>/'=>'\\textit{',
				'/\<u\>/'=>'\\underline{',
				'/\<strike\>/'=>'\\sout{',
				'/\<del\>/'=>'\\sout{',
// 				'/\<hr\/?\>/','\\hline',
// 				'/\br\/?\>/','\\\\',
				'/\<\/.*?\>/'=>'}'
		);
		$output = preg_replace(array_keys($map),array_values($map),$output);
		
		// convert span with styles
		$output = preg_replace_callback('/\<span\s*style\=\"(.*?)\"\s*\>/', function($matches) {
			if(count($matches) > 0) {
				$style = trim($matches[1]);
				$arStyle = explode(';',$style);
				
				$string = '';
				foreach($arStyle as $style) {
					$replacement = ConverterSimple::parseColorStyle($style);
					if($replacement) {
						$string .= $replacement;
					}
				}
				
				return empty($string) ? '{' : $string;
			}
		}, $output);
	
		return $output;
	}
	
	private static function parseColorStyle($style) {
		$arProp = explode(':',$style);
		if(count($arProp) == 2) {
			list($prop, $value) = $arProp;
			$prop = trim($arProp[0]);
			$value = trim($arProp[1]);
			if($prop == 'color' || $prop == 'background-color') {
				$schema = $value[0] == '#' ? self::COLOR_HTML : null;
				if($schema == self::COLOR_HTML) {
					$value = substr($value,1);
				} else {
					$schema = substr($value,0,4) == 'rgb(' ? self::COLOR_RGB : null;
				}
				
				if($schema == self::COLOR_RGB) {
					$value = substr($value, 4,-1);
				} else {
					return false;
				}
			}

			if($prop == 'color') {
				return sprintf('\textcolor[%s]{%s}{',$schema, $value);
			}
			if($prop == 'background-color') {
				return sprintf('\colorbox[%s]{%s}{',$schema, $value);
			}
		}
		
		return false;
	}
	
	/**
	 *
	 * @param string $input
	 * @return string
	 */
	private function escapeLatexSpecialChars($input) {
		$tidy = new \tidy();
		$input = $tidy->repairString($input);
	
		$doc = new \DOMDocument('1.0', 'UTF-8');
		$doc->loadHTML($input);
		
		$this->escapeNode($doc);
		$body = $doc->getElementsByTagName('body')->item(0);
	
		$output = $doc->saveHTML($body);
	
		return $output;
	}
	
	/**
	 * 
	 * @param \DOMNode $node
	 */
	private function escapeNode(\DOMNode $node) {
		if($node->nodeType == XML_TEXT_NODE && $node->textContent) {
			$content = Util::escapeLatexSpecialChars($node->textContent);
			$node->nodeValue = $content;
		}
		
		if($node->childNodes) {
			foreach($node->childNodes as $childNode) {
				$this->escapeNode($childNode);
			}
		}
	}
}
