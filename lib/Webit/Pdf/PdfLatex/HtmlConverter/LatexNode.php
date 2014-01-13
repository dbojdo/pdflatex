<?php
namespace Webit\Pdf\PdfLatex\HtmlConverter;

use Webit\Pdf\PdfLatex\Util;
class LatexNode  {
    
    /**
     * 
     * @var string
     */
    private $value;
    
    /**
     * 
     * @var string
     */
    private $start;
    
    /**
     * 
     * @var string
     */
    private $end;
    
    /**
     * 
     * @var string
     */
    private $options = array();
    
    private $nodes = array();
    
    public function __construct($value, $start = '', $end = '', array $options = array()) {
        $this->value = $value;
        $this->start = $start;
        $this->end = $end;
        $this->options = $options;
    }
    
    /**
     * 
     * @param string $value
     * @param string $start
     * @param string $end
     * @param array $options
     * @return \Webit\Pdf\PdfLatex\HtmlConverter\LatexNode
     */
    static public function create($value, $start = '', $end = '', array $options = array()) {
        return new self($value, $start, $end, $options);
    }
    
    /**
     * 
     * @param LatexNode $node
     */
    public function addNode(LatexNode $node) {
        $this->nodes[] = $node;
    }
    
    public function addNodeAsLeaf(LatexNode $node) {
        if(count($this->nodes) > 0) {
            $this->nodes[0]->addNodeAsLeaf($node);
        } else {
            $this->addNode($node);
        }
    }
    
    public function __toString() {
        $str = '';
        if($this->start) {
            $str .= ($this->start);
        }
        
        if($this->value) {
            $str .= $this->value;  
        } 
        
        foreach($this->nodes as $node) {
            $str.= (string)$node;
        }
        
        if($this->end) {
            $str.= (string)$this->end;
        }
        
        return $str;
    }
}