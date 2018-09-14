<?php namespace Baoweb\ExtendedMaintenance;

use App;
use Backend;
use Backend\Models\BrandSetting;
use BackendAuth;
use Config;
use October\Rain\Support\Facades\File;
use System\Classes\PluginBase;
use Twig;

/**
 * ExtendedMaintenance Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'ExtendedMaintenance',
            'description' => 'No description provided yet...',
            'author'      => 'Baoweb',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {

        App::after(function ($request) {

            $isInMaintenanceMode = Config::get('baoweb.extendedmaintenance::maintenance_mode');

            if(!$isInMaintenanceMode) {
                return true;
            }

            $user = BackendAuth::getUser();

            if($user && !$user->is_superuser) {

                $template = File::get(__DIR__ . '/templates/closed.htm');

                echo Twig::parse($template);

                die();
            }


        });

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Baoweb\ExtendedMaintenance\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'baoweb.extendedmaintenance.some_permission' => [
                'tab' => 'ExtendedMaintenance',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'extendedmaintenance' => [
                'label'       => 'ExtendedMaintenance',
                'url'         => Backend::url('baoweb/extendedmaintenance/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['baoweb.extendedmaintenance.*'],
                'order'       => 500,
            ],
        ];
    }
}
