<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Settings Library
 *
 * Provides methods to retrieve settings from the database and/or config files,
 * and update settings in the database.
 *
 * @todo sort out the handling of module-specific settings, especially in the cache.
 *
 * @todo If someone retrieves a config value with item(), then attempts to change
 * the value with set(), it will attempt to update the value in the database...
 *
 * @package    Bonfire\Modules\Settings\Libraries\Settings_lib
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/developer/settings#settings_lib
 */
class Settings_lib
{
    /** @var object A pointer to the CodeIgniter instance. */
    protected static $ci;

    /** @var array Settings cache. */
    private static $cache = array();

    /** @var array Hold observers to handle settings drivers */
    private static $observers = array();

    private static $settingsModelLoaded = false;

    /**
     * This constructor facilitates loading through CI.
     *
     * @return void
     */
    public function __construct()
    {
        self::init();
        self::find_all();
    }

    /**
     * Initialize the library.
     *
     * @return void
     */
    public static function init()
    {
        if (! is_object(self::$ci)) {
            self::$ci =& get_instance();
        }

        if (! class_exists('settings_model') && isset(self::$ci->load)) {
            self::$ci->load->model('settings/settings_model');
        }
        if (! self::$settingsModelLoaded
            && class_exists('settings_model')
            && isset(self::$ci->db)
            && ! empty(self::$ci->db->database)
            && self::$ci->db->table_exists(self::$ci->settings_model->get_table())
        ) {
            self::$settingsModelLoaded = true;
        }
    }

    /**
     * Get the requested settings value.
     *
     * @param string $name The name of the setting record to retrieve.
     *
     * @return mixed
     */
    public function __get($name)
    {
        return self::get($name);
    }

    /**
     * Set the requested setting value.
     *
     * @param string $name  The name of the setting.
     * @param string $value The value to save.
     *
     * @return bool
     */
    public function __set($name, $value)
    {
        return self::set($name, $value);
    }

    /**
     * Retrieve a setting.
     *
     * @param string $name The name of the item to retrieve.
     *
     * @return bool
     */
    public static function item($name)
    {
        if (isset(self::$cache[$name])) {
            return self::$cache[$name];
        }

        self::init();

        $value = null;
        if (self::$settingsModelLoaded) {
            $setting = self::$ci->settings_model->find_by('name', $name);
            if ($setting) {
                $value = $setting->value;
            }
        }

        // Setting doesn't exist, maybe it's a config option.
        if (is_null($value)) {
            $value = config_item($name);
        }

        // Store it for later.
        self::$cache[$name] = $value;

        return $value;
    }

    /**
     * Set a setting in the database.
     *
     * @param string $name   Name of the setting.
     * @param string $value  Value of the setting.
     * @param string $module Name of the module.
     *
     * @return boolean
     */
    public static function set($name, $value, $module = 'core')
    {
        self::init();

        if (! self::$settingsModelLoaded) {
            return false;
        }

        // Since the cache is originally retrieved from the database, the database
        // is updated if the $name is found in the cache.
        if (isset(self::$cache[$name])) {
            $setting = self::$ci->settings_model->update_where('name', $name, array('value' => $value));
        } else {
            // If $name was not found in the cache, insert the data into the database.
            $setting = self::$ci->settings_model->insert(
                array(
                    'name'   => $name,
                    'value'  => $value,
                    'module' => $module,
                )
            );
        }

        self::$cache[$name] = $value;

        return true;
    }

    /**
     * Delete a setting in the database.
     *
     * @param string $name   Name of the setting.
     * @param string $module Name of the module.
     *
     * @return bool
     */
    public static function delete($name, $module = 'core')
    {
        self::init();

        if (self::$settingsModelLoaded && isset(self::$cache[$name])) {
            if (self::$ci->settings_model->delete_where(array('name' => $name, 'module' => $module))) {
                unset(self::$cache[$name]);

                return true;
            }
        }

        return false;
    }

    /**
     * Get all of the settings.
     *
     * @return array
     */
    public function find_all()
    {
        if (self::$cache) {
            return self::$cache;
        }

        self::init();

        if (! self::$settingsModelLoaded) {
            return null;
        }

        $settings = self::$ci->settings_model->find_all();
        foreach ($settings as $setting) {
            self::$cache[$setting->name] = $setting->value;
        }

        return self::$cache;
    }

    /**
     * Get a setting for specific search criteria.
     *
     * @see find_all_by for multiple matches.
     *
     * @param string $field Setting column name.
     * @param string $value Value ot match.
     *
     * @return  array
     */
    public function find_by($field = null, $value = null)
    {
        self::init();

        if (! self::$settingsModelLoaded) {
            return null;
        }

        $settings = self::$ci->settings_model->find_by($field, $value);
        foreach ($settings as $setting) {
            self::$cache[$setting['name']] = $setting['value'];
        }

        return $settings;
    }

    /**
     * Get all of the settings based on search criteria.
     *
     * @see find_by for a single setting match.
     *
     * @param string $field Setting column name.
     * @param string $value Value ot match.
     *
     * @return array
     */
    public function find_all_by($field = null, $value = null)
    {
        self::init();

        if (! self::$settingsModelLoaded) {
            return null;
        }

        $settings = self::$ci->settings_model->find_all_by($field, $value);
        if (! empty($settings) && is_array($settings)) {
            foreach ($settings as $key => $value) {
                self::$cache[$key] = $value;
            }
        }

        return $settings;
    }

    // -------------------------------------------------------------------------
    // Add/Remove Observers
    // -------------------------------------------------------------------------

    public function attach($observer)
    {
        self::$observers[] = $observer;
    }

    public function detach($observer)
    {
        $remainingObservers = array();
        foreach (self::$observers as $key => $val) {
            if ($val === $observer) {
                // Remove all values from the cache which are attached to this
                // observer.

                continue;
            }

            $remainingObservers[] = $val;
        }
        self::$observers = $remainingObservers;
    }
}

// -----------------------------------------------------------------------------
// ! HELPER METHOD
// -----------------------------------------------------------------------------

if (! function_exists('settings_item')) {
    /**
     * Helper method to retrieve a setting.
     *
     * @param string $name The name of the item to retrieve
     *
     * @return bool|string Returns result of setting or false if none.
     */
    function settings_item($name = null)
    {
        if ($name === null) {
            return false;
        }

        return Settings_lib::item($name);
    }
}
/* end /settings/libraries/settings_lib.php */
