## Monitoring Site Activity

The Activities module provides a simple way to record activity from any module.

### log_activity() helper function

To log activities you can use the <tt>log_activity()</tt> helper function that will always be available. This helper function checks the value of the config item <tt>enable_activity_logging</tt> and, if enabled, will autoload the activity_model and log the activity. This allows you to quickly disable logging in your application without needing to rewrite portions of your code.

The first parameter is the ID of the user the activity is being recorded about. The second parameter is the activity message that you would like logged. The third parameter is the name of the module. Any spaces should be converted to and underscore.

    $status = 'just turned the lights out.';

    log_activity( $user_id, $status, 'my_module');

### Initializing the Model

Like most other models in CodeIgniter, the Activities Model is initialized in your controller by using the <tt>$this->load->model</tt> function:

    $this->load->model('activities/activity_model');

Once loaded, the Activities features will be avialable by using <tt>$this->activity_model</tt>.

### log_activity()

To record an activity for later viewing, use the <tt>log_activity</tt> method. The first parameter is the ID of the user the activity is being recorded about. The second parameter is the activity message that you would like logged. The third parameter is the name of the module. Any spaces should be converted to and underscore.

    $status = 'just turned the lights out.';

    $this->activity_model->log_activity( $user_id, $status, 'my_module');

## Viewing Activities

Once logged, activities can be viewed in the Admin area and filtered by user or module.
