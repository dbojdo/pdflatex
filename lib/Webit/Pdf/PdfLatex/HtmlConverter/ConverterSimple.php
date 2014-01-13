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
	    $tidy = new \tidy();
	    $html = $tidy->repairString($html,array('doctype'=>'html5'),'utf8');
	    
	    $doc = new \DOMDocument('1.0');
	    $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
	    $doc->loadHTML($html);
	    
	    $latexNode = LatexNode::create('');
	    $body = $doc->getElementsByTagName('body')->item(0);
	    if($body->childNodes) {
	        foreach($body->childNodes as $child) {
	            $latexNode->addNode($this->escapeNode($child));
	        }
	    }
	    
        $output = (string)$latexNode;
	    
	    return $output;
	}
	
	/**
	 * 
	 * @param \DOMNode $node
	 */
	private function escapeNode(\DOMNode $node) {
	    if($node->nodeType == XML_ELEMENT_NODE) {
	       switch($node->nodeName) {
	       	case 'p':
	       	   $latexNode = LatexNode::create('','',"\n\n");
	       	break;
	       	case 'del':
	       	case 'strike':
	       	    $latexNode = LatexNode::create('','\\sout{','}');
	       	break;
	       	case 'em':
	       	case 'i':
	       	    $latexNode = LatexNode::create('','\\textit{','}');
	       	    break;
       	    case 'b':
       	    case 'strong':
       	        $latexNode = LatexNode::create('','\\textbf{','}');
       	        break;
       	    case 'br':
       	        $latexNode = LatexNode::create('','','\\\\');
       	        break;
       	    case 'hr':
   	            $latexNode = LatexNode::create('','\\hline',"\n\n");
   	            break;
       	    case 'span':
       	        $latexNode = LatexNode::create('');
       	        $style = $node->attributes->getNamedItem('style');
       	        if($style) {
       	            $styleStr = $style->textContent;
       	            $latexNodes = $this->parseStyle($styleStr);
       	            foreach($latexNodes as $n) {
       	                $latexNode->addNode($n);
       	            }
       	        }
       	        
       	        break;
       	    default:
       	        $latexNode = LatexNode::create('');
	       }
	    } else if($node->nodeType == XML_TEXT_NODE) {
	        $decoded = html_entity_decode($node->textContent,null,'UTF-8');
	        $escaped = Util::escapeLatexSpecialChars($decoded);
		    $latexNode = LatexNode::create($escaped);
		} else {
		    $latexNode = LatexNode::create('');
		}
		
		if($node->childNodes) {
			foreach($node->childNodes as $childNode) {
				$latexNode->addNodeAsLeaf($this->escapeNode($childNode));
			}
		}

		return $latexNode;
	}
	
	/**
	 * 
	 * @param string $styleStr
	 * @return array
	 */
	private function parseStyle($styleStr) {
	    $arStyle = explode(';',$styleStr);
	    
	    $supported = array('background-color','color');
	    $map = array();
	    foreach($arStyle as $style) {
	        $arProp = explode(':',$style);
	        if(count($arProp) == 2) {
    	        $prop = trim($arProp[0]);
    	        $value = trim($arProp[1]);
    	        if(in_array($prop,$supported)) {
    	            $map[$prop] = $value;
    	        }
	        }
	    }
	    
	    $nodes = array();
	    foreach($supported as $prop) {
	        if(key_exists($prop, $map)) {
	            $nodes[] = $this->handleStyle($prop, $map[$prop]);
	        }
	    }
	    
	    return $nodes;
	}
	
	private function handleStyle($key, $value) {
	    if($key == 'background-color') {
	        $color = $this->getColor($value);
	        if(!$color) {
	            return LatexNode::create('');
	        }
	         
	        return LatexNode::create('',sprintf('\\colorbox[%s]{%s}{',$color['schema'], $color['value']),'}');
	    }
	    
	    if($key == 'color') {
	        $color = $this->getColor($value);
	        if(!$color) {
	            return LatexNode::create('');
	        }
	        
	        return LatexNode::create('',sprintf('\\textcolor[%s]{%s}{',$color['schema'], $color['value']),'}');
	    }
	    
	    return LatexNode::create('');
	}
	
	private function getColor($value) {
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
	    
	    return array('schema'=>$schema,'value'=>$value);
	}
}
