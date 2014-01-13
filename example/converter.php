<?php
include '../lib/Webit/Pdf/PdfLatex/Util.php';
include '../lib/Webit/Pdf/PdfLatex/HtmlConverter/ConverterInterface.php';
include '../lib/Webit/Pdf/PdfLatex/HtmlConverter/ConverterSimple.php';
include '../lib/Webit/Pdf/PdfLatex/HtmlConverter/LatexNode.php';


use Webit\Pdf\PdfLatex\Util;
use Webit\Pdf\PdfLatex\HtmlConverter\ConverterSimple;

$c = new ConverterSimple();

$inputs = array(
	'<p><del>Line &gt; &lt; &amp;</del> <strong><em>1</em></strong></p><hr><p><em>Lini% "" \\ // &lt; &a 2</em></p><p><span style="color: rgb(79, 129, 189);"><span style="background-color: rgb(255, 255, 0);">Linia 3</span></span></p>'
);

foreach($inputs as $html) {
	echo 'Input: '. $html."\n";
	$output = $c->convert($html);
	echo 'Output: '.$output ."\n\n";
}
