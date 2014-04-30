<?php

namespace Besanek\Threads\Executables;

use Besanek\Threads\IExecutable;

/**
 * @author Robert Jelen
 */
abstract class BaseExecutable implements IExecutable {

    protected $executable;
    protected $file;
    protected $arguments;

    /**
     * @return string
     */
    public function getExecutable()
    {
        return $this->executable;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->executable . ' ' . $this->file . ' ' . $this->arguments;
    }

    /**
     * @return string
     */
    public function getWorkdir()
    {
        return dirname($this->file);
    }

    /**
     * @return string[]
     */
    public function getEnviroment()
    {
        return array();
    }

    /**
     * @return bool
     */
    public function isValidReturnCode($returnCode)
    {
        return $returnCode === 0;
    }

}