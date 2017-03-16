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
