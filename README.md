Fake threads in PHP
============

##Why?
* simple usage
* no need any PHP extension

## Install

The best way to install is using Composer:
```sh
$ composer require besanek/threads:@dev
```

##Usage


### Basic

It's simple!

```php
use Besanek\Threads;

// ...
$executable = new Threads\Executables\PhpExecutable('path/to/script.php');
$job = new Threads\Job($executable);
$job->run();
```

You can write your own Executable that provides you running bash scripts, native apllications, inline PHP code and whatever you want. Just implement the `Besanek\Threads\IExecutable` interface.

### Let's work with the job

Sometimes we read output and sometimes we need write some imput

```php
$executable = new Threads\Executables\PhpExecutable('path/to/repeat.php');
$job = new Threads\Job($executable);
$job->run();
$job->write('Hello');
echo $job->read(); //Hello;
```

> WARNING! The subscript runs in other process, so there can be latency at writing and reading. In this case is not guaranteed that `read()` return 'Hello'. That occurs if subscript is slower than current script. The solution is waiting cycle like this.
> ```php
> while(empty($output = $job->read())) {}
```

### It's OK or NOT?

How to react to end of the subscript? Callbacks!

```php
$ok = function () { echo "OK" };
$fail = function () { echo "FAIL" };
$job = new Threads\Job($executable, $ok, $fail);
```

To both callback are passed 3 parameters. stdout, stderr and exitcode.

```php
$ok = function ($stdout) { echo $stdout };
$fail = function ($stdout, $stderr, $exitcode) {
    echo "FAILS with exitcode: " . $exitcode . " and error: " . $stderr;
};
```

> NOTE: Callbacks are processed in the method `isDone()` witch is by default called on an object destructor. It can be too late. If you need process the callback sooner. Run the method `isDone()` manualy. Ideally in a waiting cycle in case of a subscript is still running.
> ```php
> while($job->isDone() === false) {}
```

### At the end is here management

Imagine, you need resize thousand of pictures. It's ideal for threads! But, if I creates thousand of subprocesses, I wastes all sources on my machine. Is there solution?

**Yes!** `Besanek\Threads\Runner`.

You can add set of jobs and limit the number of threads.

```php
$images = // do some black magic

$runner = new Threads\Runner(50); //Max 50 subprocess
foreach($images as $image) {
    $arguments = '-size=1000x1000 -output output/path/' . $image->name . '--source '.$image->path;
    $executable = new Threads\Executables\PhpExecutable('path/to/resize.php', $arguments);
    $job = new Threads\Job($executable);
    $runner->addJob($job);
    $runner->process();
}
```
> INFO: Method `process()` has one argument, wich determines whether a queue of jobs are done completely. So if you calling `process(true)` the whole queue is gradually runned, but it block main process while all jobs aren't done. This is by default called in destructor of runner.


> WARNING! Only in `process()` method are jobs runned and finished. So if you forget call it, jobs are waiting till end of script.