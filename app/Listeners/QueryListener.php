<?php
namespace App\Listeners;
use Illuminate\Database\Events\QueryExecuted;
use App\Utils\Log\Logger;

class QueryListener
{
    /**
     * Handle the event.
     *
     * @param  QueryExecuted  $event
     * @return void
     */
    public function handle(QueryExecuted $event)
    {
        if(config('app.debug')) {
            foreach ($event->bindings as $key => $value) {
                if($value instanceof \DateTime) {
                    //单引号性能比双引号更优
                    $event->bindings[$key] = $value->format('\'Y-m-d H:i:s\'');
                } else {
                    if(is_string($value)) {
                        $event->bindings[$key] = '\'$value\'';
                    }
                }
            }
            $sql = str_replace(['?', '%'], ['%s', '%%'], $event->sql);
            $sql = vsprintf($sql, $event->bindings);
            Logger::debug('运行sql:' . $sql . ', args:' . json_encode($event->bindings));
            Logger::debug('sql运行时间:' . $event->time . 'ms');
        }
    }
}
