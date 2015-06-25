# Events

Bonfire includes an events library which can be used to configure event handling for your application (and to handle events triggered by Bonfire, as well).
See [System Events](developer/system_events) for additional documentation.

## trigger($event_name[, &$payload])

Trigger an event.
- `$event_name`: the name of the event to trigger.
- `$payload`: (optional) A reference to the data to send to the event method.

Note: Since the `$payload` is passed by reference, the original data may be modified by the event handler.

When this method is called, any subscribers registered for `$event_name` in `/application/config/events.php` will be called.

## init()

This method is called by the constructor to allow the library to be initialized when loaded by CodeIgniter.
This is not intended to be called directly (despite being a public method).
