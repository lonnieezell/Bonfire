# Emailer

## Emailer Library

The Emailer library is the primary interface provided by Bonfire for sending email from custom modules.

### Properties

#### debug_message

Additional information about the running of the script and the sending of an immediate email will be stored in this property, if debugging is enabled for the library.

#### error

Any errors generated during the course of running the script will be stored in this property.

#### queue_emails

If true, the emailer library will queue emails into the database, allowing them to be sent later.
If false, the emailer library will send the email immediately.

### Methods

#### send($data = array()[, $queueOverride = null])

Send an email. Returns true on success, else false. Check the error and debug_message properties for additional information on failure.

The first parameter, $data, contains the email to be sent and the information required to send it, in the form of an array.
The $data array must contain the following keys: `to`, `message`, and `subject`.
While `message` and/or `subject` may be empty strings, `to` must be set to either a string (containing an email address or comma-delimited list of email addresses) or an array of email addresses.
Additionally, if the `from` key is empty, `sender_email` must be set to a valid value in the site's settings.

The library currently supports the following additional keys in the $data parameter:

- `alt_message` to set an alternative email message body (see `set_alt_message()` in the CI email library documentation for more information)
- `attachments` to set an array containing the location (file path/name) of any attachments to be added to the email.

The second (optional) parameter, $queueOverride, can be set to a boolean (true/false) value to force the email to be queued (true) or sent immediately (false).
If $queueOverride is not set (or set to anything other than a boolean value), the behavior will be dependent on the value of the queue_emails property.

#### process_queue([$limit = 33])

Process the email queue, sending the first $limit emails in the queue.
Returns true on success (or if the queue is empty when `process_queue` is called), false if one or more of the emails failed.
This method gets the first $limit emails from the queue, then attempts to send each of those messages, regardless of whether any of the previous messages failed (or how many of them failed).
Only messages which were successfully sent will be removed from the queue.

The $limit defaults to 33, which, if processed every 5 minutes, equals 400/hour, which should be a safe value for most service providers.
Check your terms of service to verify before sending any significant volume of email.

#### enable_debug($enable_debug)

If $enable_debug is true, the debug messages will be set and available via the `debug_message` property.

#### queue_emails($queue)

If $queue is true, messages will be queued. If $queue is false, messages will be sent immediately.

## Emailer Model

The `Emailer_model` is a standard model extending `BF_Model` which can be used to manage the email queue.

To add messages to the queue, use the Emailer library's `send()` method (use the library's `queue_emails()` method to enable use of the queue).
To send messages stored in the queue, use the Emailer library's `process_queue()` method.
