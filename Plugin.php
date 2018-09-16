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
            'name' => 'ExtendedMaintenance',
            'description' => 'Plugin that allows access only to superadmins.',
            'author' => 'Baoweb',
            'icon' => 'icon-leaf'
        ];
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

            if (!$isInMaintenanceMode) {
                return true;
            }

            if(App::runningInConsole()) {
                return true;
            }

            $user = BackendAuth::getUser();

            if($user && $user->is_superuser) {
                return true;
            }

            // Allowing for login page
            if(App::runningInBackend() && !$user) {
                return true;
            }

            $template = File::get(__DIR__ . '/templates/closed.htm');

            echo Twig::parse($template);

            die();
        });
    }
}