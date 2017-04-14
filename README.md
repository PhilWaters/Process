# Process

Process allows command line commands to be run asynchronously.

## Usage

```
$process = new Process();

$waiter = $process
    ->cwd("/working/directory") // Set the working directory
    ->cmd("find")               // Set to command to run
    ->arg(".")                  // Add an argument
    ->option("-type", "f")      // Add an option and value
    ->log("/tmp/files.log")     // Set the location of the log file
    ->async()                   // Set the process to run asynchronously
    ->run();                    // Run the command

// do some other stuff

$waiter->wait(5);               // Wait for the process to complete with a timeout of 5 seconds
```

## Process Methods

### cwd

    void PhilWaters\Process\Process::cwd(string $cwd)

Sets working directory



* Visibility: **public**


#### Arguments
* $cwd **string** - &lt;p&gt;Working directory&lt;/p&gt;



### cmd

    \PhilWaters\Process\Process PhilWaters\Process\Process::cmd(string $cmd)

Sets the command to run



* Visibility: **public**


#### Arguments
* $cmd **string** - &lt;p&gt;Command to run&lt;/p&gt;



### option

    \PhilWaters\Process\Process PhilWaters\Process\Process::option(string $option, string $value, string $separator)

Adds a command option



* Visibility: **public**


#### Arguments
* $option **string** - &lt;p&gt;Option&lt;/p&gt;
* $value **string** - &lt;p&gt;Value&lt;/p&gt;
* $separator **string** - &lt;p&gt;Separator&lt;/p&gt;



### arg

    \PhilWaters\Process\Process PhilWaters\Process\Process::arg(string $arg)

Adds an argument



* Visibility: **public**


#### Arguments
* $arg **string** - &lt;p&gt;Argument&lt;/p&gt;



### args

    \PhilWaters\Process\Process PhilWaters\Process\Process::args($arg)

Adds arguments



* Visibility: **public**


#### Arguments
* $arg **mixed**



### async

    \PhilWaters\Process\Process PhilWaters\Process\Process::async()

Configures the process to run asynchronously. The run method will return a waiter instead of the output.



* Visibility: **public**




### log

    \PhilWaters\Process\Process PhilWaters\Process\Process::log(string $path, \PhilWaters\Process\number $mode)

Sets the path to a log file



* Visibility: **public**


#### Arguments
* $path **string** - &lt;p&gt;Log file path&lt;/p&gt;
* $mode **PhilWaters\Process\number** - &lt;p&gt;Log write mode (FILE_APPEND)&lt;/p&gt;



### stdin

    \PhilWaters\Process\Process PhilWaters\Process\Process::stdin(mixed $stdin, string $type)

Sets STDIN source (file or pipe)



* Visibility: **public**


#### Arguments
* $stdin **mixed** - &lt;p&gt;File path or value to pass to STDIN&lt;/p&gt;
* $type **string** - &lt;p&gt;file or pipe&lt;/p&gt;



### stdout

    \PhilWaters\Process\Process PhilWaters\Process\Process::stdout(string $path, string $mode)

Sets STDOUT path



* Visibility: **public**


#### Arguments
* $path **string** - &lt;p&gt;File path&lt;/p&gt;
* $mode **string** - &lt;p&gt;Write mode (FILE_APPEND)&lt;/p&gt;



### stderr

    \PhilWaters\Process\Process PhilWaters\Process\Process::stderr(string $path, string $mode)

Sets STDERR path



* Visibility: **public**


#### Arguments
* $path **string** - &lt;p&gt;File path&lt;/p&gt;
* $mode **string** - &lt;p&gt;Write mode (FILE_APPEND)&lt;/p&gt;


### env

    \PhilWaters\Process\Process PhilWaters\Process\Process::env(array $env)

Sets array of environment variables



* Visibility: **public**


#### Arguments
* $env **array** - &lt;p&gt;Array of environment variables&lt;/p&gt;



### run

    \PhilWaters\Process\Waiter|array PhilWaters\Process\Process::run()

Runs the command



* Visibility: **public**




### buildCommand

    string PhilWaters\Process\Process::buildCommand()

Builds command string



* Visibility: **public**





## Waiter Methods

### wait

    boolean PhilWaters\Process\Waiter::wait(\PhilWaters\Process\number $timeout)

Wait for the process to finish



* Visibility: **public**


#### Arguments
* $timeout **PhilWaters\Process\number** - &lt;p&gt;Number of seconds to wait. Pass null to wait indefinitely or until PHP process exits based on max_execution_time&lt;/p&gt;



### terminate

    void PhilWaters\Process\Waiter::terminate(integer $signal)

Terminates process



* Visibility: **public**


#### Arguments
* $signal **integer** - &lt;p&gt;Kill signal&lt;/p&gt;