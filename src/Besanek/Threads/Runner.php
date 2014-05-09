<?php

namespace Besanek\Threads;

/**
 * @author Robert Jelen
 */
class Runner {

    /** @var int Waiting between 2 checking cycles in microseconds*/
    public $waiting = 10000;

    private $jobs = array();
    private $threads;

    /**
     * @param int $maxThreads
     */
    public function __construct($maxThreads = 30)
    {
        for ($i = 0 ; $i<$maxThreads; $i++) {
            $this->threads[$i] = null;
        }
    }

    public function __destruct()
    {
        $this->process(true);
    }

    /**
     * @param Job $job
     */
    public function addJob(Job $job)
    {
        $this->jobs[] = $job;
    }

    /**
     * @param bool $clear
     */
    public function process($clear = false)
    {
        do {
            $complete = true;
            foreach($this->threads as $channel => $job) {
                if (is_null($job) || $job->isDone()) {
                    $this->allocChannel($channel);
                    continue;
                }
                $complete = false;
            }
        } while ($clear && !$this->isClear() && usleep($this->waiting) === null);
    }

    /**
     * @return bool
     */
    public function isClear()
    {
        if(reset($this->jobs) !== false) {
            return false;
        }
        foreach ($this->threads as $thread) {
            if( is_null($thread) === false ) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param int $channel key
     */
    protected function allocChannel($channel)
    {
        $this->threads[$channel] = null;
        $job = reset($this->jobs);
        if($job === false) {
            return;
        }
        $this->threads[$channel] = $job;
        unset($this->jobs[key($this->jobs)]);
        $this->threads[$channel]->run();
    }


}