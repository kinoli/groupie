<?php
/**
 * Groupie plugin for Craft CMS 3.x
 *
 * Assign users to specific user groups upon registration. Especially useful for front-end signup forms
 *
 * @link      http://www.jesseknowles.com
 * @copyright Copyright (c) 2018 Jesse Knowles
 */

namespace kinoli\groupie;

use kinoli\groupie\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\UserAssignGroupEvent;
use craft\services\Users;

use yii\base\Event;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://craftcms.com/docs/plugins/introduction
 *
 * @author    Jesse Knowles
 * @package   Groupie
 * @since     1.0.0
 *
 * @property  Settings $settings
 * @method    Settings getSettings()
 */
class Groupie extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * Groupie::$plugin
     *
     * @var Groupie
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * Groupie::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            Users::class,
            Users::EVENT_AFTER_ASSIGN_USER_TO_DEFAULT_GROUP,
            function(UserAssignGroupEvent $event) {
        
                // get your hidden input value, 1 for true, 0 for false
                $requestedUserGroups = Craft::$app->getRequest()->post('groups');

                if (is_string($requestedUserGroups)) {
                    $requestedUserGroups = [$requestedUserGroups];
                }

                $settings = $this->getSettings();

                if ($settings->multipleGroups || (!$settings->multipleGroups && count($requestedUserGroups) === 1)) {

                    if ($settings->publicGroups) 
                    {
                        $userId = $event->user->id;
                        $legitGroups = array_intersect($requestedUserGroups, $settings->publicGroups);
                        
                        // make sure group still exists
                        $nonGroups = [];
                        foreach($legitGroups as $group) {
                            if (!Craft::$app->userGroups->getGroupById($group))
                            {
                                $nonGroups[] = $group;
                            }
                        }

                        if (count($nonGroups)) {
                            $legitGroups = array_diff($legit_groups, $nonGroups);
                        }

                        if ($legitGroups && $userId) {
                            Craft::$app->getUsers()->assignUserToGroups($userId, $legitGroups);
                        }
                    }

                }
                
            }
        );

/**
 * Logging in Craft involves using one of the following methods:
 *
 * Craft::trace(): record a message to trace how a piece of code runs. This is mainly for development use.
 * Craft::info(): record a message that conveys some useful information.
 * Craft::warning(): record a warning message that indicates something unexpected has happened.
 * Craft::error(): record a fatal error that should be investigated as soon as possible.
 *
 * Unless `devMode` is on, only Craft::warning() & Craft::error() will log to `craft/storage/logs/web.log`
 *
 * It's recommended that you pass in the magic constant `__METHOD__` as the second parameter, which sets
 * the category to the method (prefixed with the fully qualified class name) where the constant appears.
 *
 * To enable the Yii debug toolbar, go to your user account in the AdminCP and check the
 * [] Show the debug toolbar on the front end & [] Show the debug toolbar on the Control Panel
 *
 * http://www.yiiframework.com/doc-2.0/guide-runtime-logging.html
 */
        Craft::info(
            Craft::t(
                'groupie',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'groupie/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
