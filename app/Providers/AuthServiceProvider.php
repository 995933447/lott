<?php

namespace App\Providers;

use App\Repositories\UserRepository;
use App\Services\Auth\Validators\Authorization;
use App\Services\ServiceDispatcher;
use App\Utils\Marker\MarkedKeysEnum;
use App\Utils\Marker\MarkInstance;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
           $validateResult = ServiceDispatcher::dispatch(ServiceDispatcher::VALIDATOR_SERVICE, Authorization::class, $request);
           if ($validateResult->hasErrors()) {
               return null;
           }

           MarkInstance::mark(MarkedKeysEnum::CURRENT_USER_IS_AGENT, $validateResult->getData()['is_agent']);

           return UserRepository::find($validateResult->getData()['uid'], $validateResult->getData()['is_agent']);
        });
    }
}
