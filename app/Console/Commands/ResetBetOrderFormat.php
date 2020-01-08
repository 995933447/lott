<?php
namespace App\Console\Commands;

use App\Models\BetOrder;
use App\Services\BetOrder\Tasks\OrderEncryptor\EncryptOrder;
use App\Services\ServiceDispatcher;
use Illuminate\Support\Facades\DB;

class ResetBetOrderFormat extends ExtendCommand
{
    const SIGNATURE = 'orders:reset-format';

    const PARAMS = '{--v}';

    const OPTIONS = '';

    public function handle()
    {
        $this->fillSafeIdentifierIfNull($this->option('v')?: EncryptOrder::VERSION_1);
    }

    protected function fillSafeIdentifierIfNull($version)
    {
        $table = DB::table(($model = (new BetOrder()))->getTable());
        $orders = $table->where(BetOrder::SAFE_IDENTIFIER_FIELD, '')->orWhereNull(BetOrder::SAFE_IDENTIFIER_FIELD)->get();
        foreach ($orders as $order) {
            foreach ($order as $field => $value) {
                $model->$field = $value;
            }
            ServiceDispatcher::dispatch(ServiceDispatcher::TASK_SERVICE, new EncryptOrder($model, $version));
            $table->where($primaryKey = $model->getPrimaryKey(), $order->$primaryKey)->update([BetOrder::SAFE_IDENTIFIER_FIELD => $model->safe_identifier]);
        }
    }
}
