<?php
/**
 * Groupie plugin for Craft CMS 3.x
 *
 * Assign users to specific user groups upon registration. Especially useful for front-end signup forms
 *
 * @link      http://www.jesseknowles.com
 * @copyright Copyright (c) 2018 Jesse Knowles
 */

namespace kinoli\groupie\models;

use kinoli\groupie\Groupie;

use Craft;
use craft\base\Model;

/**
 * Groupie Settings Model
 *
 * This is a model used to define the plugin's settings.
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Jesse Knowles
 * @package   Groupie
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Some field model attribute
     *
     * @var string
     */
    public $multipleGroups = false;
    public $publicGroups = [];

    function doFilter($group)
    {
        return (object)array($group->id => $group->name);
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
    }

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // ['multipleGroups', 'boolean'],
            // ['multipleGroups', 'default', 'value' => true],
            // ['publicGroups', 'array'],
            // ['publicGroups', 'default', 'value' => $this->publicGroups],
        ];
    }

}
