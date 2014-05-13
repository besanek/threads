<?php

namespace Besanek\Threads;

/**
 * @author Robert Jelen
 */
class Job {

    private $executable;
    private $proc;
    private $pipes;

    private $isEnds = false;

    private $onSuccess;
    private $onError;

    /**
     * @param IExecutable $executable
     * @param callable $onSuccess
     * @param callable $onError
     */
    public function __construct(IExecutable $executable, $onSuccess = null, $onError = null)
    {
        $this->executable = clone $executable;
        $this->onSuccess = $onSuccess;
        $this->onError = $onError;
    }

    public function run()
    {
        $this->proc = proc_open(
            $this->executable->getCommand(),
            array(
               0 => array("pipe", "r"),
               1 => array("pipe", "w"),
               2 => array("pipe", "w"),
            ),
            $pipes,
            $this->executable->getWorkdir(),
            $this->executable->getEnviroment()
        );
        $this->pipes = $pipes;
    }

    /**
     * @return bool
     */
    public function isDone()
    {
        if ($this->isEnds) {
            return true;
        }

        if ($this->proc === null) {
            throw new LogicException(sprintf('You can not call isDone() method before run() method.', $channel));
         }
        $stat = proc_get_status($this->proc);
        if($stat['running'] === false) {
            $this->close($stat['exitcode']);
            return true;
        }
        return false;
    }

    /**
     * @param string $input
     */
    public function write($input)
    {
        fwrite($this->pipes[0], $input);
    }

    /**
     * @param int $channel id
     * @return string
     */
    public function read($channel = 1)
    {
        if($channel !== 1 && $channel !== 2) {
            throw new LogicException(sprintf('Only channel 1 and 2 are allowed, %s given.', $channel));
        }
        return stream_get_contents($this->pipes[$channel]);
    }

    /**
     * @param int $returnValue
     */
    protected function close($returnValue)
    {
        $this->isEnds = true;
        $stdout = $this->read(1);
        $stderr = $this->read(2);
        for ($i=0 ; $i<=2 ; $i++) {
            fclose($this->pipes[$i]);
        }

        proc_close($this->proc);
        if($this->executable->isValidReturnCode($returnValue)) {
            if(is_callable($this->onSuccess)){
                 call_user_func_array($this->onSuccess, array($stdout, $stderr, $returnValue));
            }
        } else {
            if(is_callable($this->onError)){
                 call_user_func_array($this->onError, array($stdout, $stderr, $returnValue));
            }
         }
    }


}