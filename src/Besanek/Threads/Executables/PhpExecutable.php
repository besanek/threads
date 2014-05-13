<?php

namespace Besanek\Threads\Executables;

use Besanek\Threads\LogicException;

/**
 * @author Robert Jelen
 */
class PhpExecutable extends SimpleExecutable {

    private $workDir;
    private $phpBinaryArgs;
    private $file;

    /**
     * @param string $file
     * @param string $arguments
     * @param string $phpBinary
     * @param string $phpBinaryArgs
     */
    public function __construct($file, $arguments = '', $phpBinary = 'php', $phpBinaryArgs = '')
    {
        $this->file = $file;
        $this->phpBinaryArgs = $phpBinaryArgs;
        $this->workDir = dirname($file);
        parent::__construct($phpBinary, $this->parseArguments($arguments));
    }

    /**
     * @param string $argumens
     */
    public function setArguments($arguments)
    {
        parent::setArguments($this->parseArguments($arguments));
    }

    /**
     * @return string
     */
    public function getWorkDir()
    {
        return $this->workDir;
    }

     /**
     * @param string $path
     */
    public function setWorkDir($path)
    {
        if(is_file($path)) {
            $path = dirname($path);
        }
        $this->workDir = $path;
    }

    protected function parseArguments($arguments) {
        return implode(' ', array($this->phpBinaryArgs, $this->file, $arguments));
    }

}