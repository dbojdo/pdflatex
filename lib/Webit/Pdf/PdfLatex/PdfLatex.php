<?php
namespace Webit\Pdf\PdfLatex;

use Knp\Snappy\AbstractGenerator;

class PdfLatex extends AbstractGenerator {
    public function __construct($binary = null, array $options = array())
    {
        $this->setDefaultExtension('pdf');
    
        parent::__construct($binary, $options);
    }
    
    protected function configure()
    {
        $this->addOptions(array(
            'draftmode'                        => null, // Sets \pdfdraftmode so pdfTeX doesn't write a PDF and doesn't read any included images, thus speeding up execution,
            'enc'                              => null, // Enable the encTeX extensions.  This option is only effective in combination with -ini.  For  documentation  of  the  encTeX  extensions  see  http://www.olsak.net/enc-tex.html.
            'etex'                             => null, // Enable the e-TeX extensions.  This option is only effective in combination with -ini.  See etex(1).
            'file-line-error'                  => null, // Print error messages in the form file:line:error which is similar to the way many compilers format them.
            'no-file-line-error'               => null, // Disable printing error messages in the file:line:error style.
            'file-line-error-style'            => null, // This is the old name of the -file-line-error option.
            'ftm'                              => null, // Use format as the name of the format to be used, instead of the name by which pdfTeX was called or a %& line.
            'halt-on-error'                    => null, // Exit with an error code when an error is encountered during processing
            'ini'                              => null, // Start  in  INI  mode, which is used to dump formats.  The INI mode can be used for typesetting, but no format is preloaded, and basic initializations like setting cat codes may be required.
            'interaction'                      => null, // Sets the interaction mode.  The mode can be either batchmode, nonstopmode, scrollmode, and errorstopmode.  The meaning of these modes is the same as that of the corre-sponding \commands.
            'ipc'                              => null, // Send DVI or PDF output to a socket as well as the usual output file.  Whether this option is available is the choice of the installer.
            'ipc-start'                        => null, // As -ipc, and starts the server at the other end as well.  Whether this option is available is the choice of the installer.
            'jobname'                          => null, // Use name for the job name, instead of deriving it from the name of the input file.
            'kpathsea-debug'                   => null, // bitmask
            'mktex'                            => null, // Enable mktexfmt, where fmt must be either tex or tfm.
            'mltex'                            => null, // Enable MLTeX extensions.  Only effective in combination with -ini.
            'no-mktex'                         => null, // Disable mktexfmt, where fmt must be either tex or tfm.
            'output-comment'                   => null, // In DVI mode, use string for the DVI file comment instead of the date.  This option is ignored in PDF mode.
            'output-directory'                 => null, // Write output files in directory instead of the current directory.  Look up input files in directory first, the along the normal search path.
            'output-format'                    => 'pdf', // Set the output format mode, where format must be either pdf or dvi.  This also influences the set of graphics formats understood by pdfTeX
            'parse-first-line'                 => null, // If the first line of the main input file begins with %& parse it to look for a dump name or a -translate-file option.
            'no-parse-first-line'              => null, // Disable parsing of the first line of the main input file.
            'progname'                         => null, // Pretend to be program name.  This affects both the format used and the search paths.
            'recorder'                         => null, // Enable the filename recorder.  This leaves a trace of the files opened for input and output in a file with extension .fls.
            'shell-escape'                     => null, // Enable the \write18{command} construct.  The command can be any shell command.  This construct is normally disallowed for security reasons.
            'no-shell-escape'                  => null, // Disable the \write18{command} construct, even if it is enabled in the texmf.cnf file.
            'src-specials'                     => null, // In DVI mode, insert source specials into the DVI file.  This option is ignored in PDF mode.
            'translate-file'                   => null, // Use the tcxname translation table to set the mapping of input characters and re-mapping of output characters.
            'default-translate-file'           => null, // Like -translate-file except that a %& line can overrule this setting.                  
        ));
    }
    
    /**
     * {@inheritDoc}
     */
    public function generateFromHtml($html, $output, array $options = array(), $overwrite = false)
    {
        $filename = $this->createTemporaryFile($html, 'tex');
    
        $this->generate($filename, $output, $options, $overwrite);
    
        $this->unlink($filename);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOutputFromHtml($html, array $options = array())
    {
        $filename = $this->createTemporaryFile($html, 'tex');
    
        $result = $this->getOutput($filename, $options);
    
        $this->unlink($filename);
    
        return $result;
    }
}