# Activities

The Activities module provides a simple way to record activity from any module.

### log_activity() helper function

To log activities you can use the `log_activity()` helper function that will always be available. This helper function checks the value of the config item `enable_activity_logging` and, if enabled, will autoload the activity_model and log the activity. This allows you to quickly disable logging in your application without needing to rewrite portions of your code.

The first parameter is the ID of the user the activity is being recorded about. The second parameter is the activity message that you would like logged. The third parameter is the name of the module. Any spaces should be converted to and underscore.

    $status = 'just turned the lights out.';

    log_activity($user_id, $status, 'my_module');

## Initializing the Model

Like most other models in CodeIgniter, the Activities Model is initialized in your controller by using the `$this->load->model` function:

    $this->load->model('activities/activity_model');

Once loaded, the Activities features will be avialable by using `$this->activity_model`.

### log_activity()

To record an activity for later viewing, use the `log_activity` method. The first parameter is the ID of the user the activity is being recorded about. The second parameter is the activity message that you would like logged. The third parameter is the name of the module. Any spaces should be converted to and underscore.

    $status = 'just turned the lights out.';

    $this->activity_model->log_activity($user_id, $status, 'my_module');

### find_by_module($modules = array())

Return all activities for one or more modules. $modules is the name of a module or an array of module names for which the activities will be found.

    $this->activity_model->find_by_module('users');

### findTopModules([$limit = 5])

Return the top $limit modules ordered by number of activities per module.

    $this->activity_model->findTopModules(10);

### findTopUsers([$limit = 5])

Return the top $limit users ordered by the number of activities per user.

    $this->activity_model->findTopUsers(10);

## Viewing Activities

Once logged, activities can be viewed in the Admin area and filtered by user or module.
