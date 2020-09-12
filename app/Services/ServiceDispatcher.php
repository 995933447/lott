<?php
namespace App\Services;

use Illuminate\Http\Request;

final class ServiceDispatcher
{
    const TASK_SERVICE = 'task';
    const VALIDATOR_SERVICE = 'validator';

    public static function dispatch(string $serviceType, $service, ...$data): ServeResult
    {
        $concrete = 'call' . ucfirst($serviceType) . 'ServiceDispatcher';
        return static::$concrete($service, ...$data);
    }

    private static function callTaskServiceDispatcher($service, ...$data): ServeResult
    {
        if (is_string($service))
            $service = empty($data)? new $service: new $service(...$data);

        return static::dispatchTaskService($service);
    }

    private static function callValidatorServiceDispatcher($service, Request $request)
    {
        if (is_string($service))
            $service = new $service();

        return static::dispatchValidatorService(new $service, $request);
    }

    private static function dispatchTaskService(TaskServiceContract $service): ServeResult
    {
        return $service->run();
    }

    private static function dispatchValidatorService(ValidatorServiceContract $service, Request $request): ServeResult
    {
        return $service->validate($request);
    }
}
