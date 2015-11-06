# Console

The Console library is used by Bonfire's Profiler to manage Console logging.

## log($data)

Logs a variable (`$data`) to the console.
Returns false if `$data` is a non-zero empty value.

## log_memory([$object[, $name = 'PHP']])

Logs the memory usage of a single variable or the entire script.
- If an `$object` is passed, the memory usage of that object is logged.
- If `$name` is passed, the string is used to indicate the name of the `$object` in the console.

## get_logs()

Returns an array of logs.

## add_to_console($log, $item)

Internal method used by `log()` and `log_memory()` to add log entries.
This is not intended to be called directly (despite being a public method).

## init()

This method is called by the constructor to allow the library to be initialized when loaded by CodeIgniter.
This is not intended to be called directly (despite being a public method).
