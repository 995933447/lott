<?php
namespace App\Services\BetOrder\Tasks;

use App\Models\BetOrder;
use App\Models\BetType;
use App\Models\Lottery;
use App\Models\UserBalance;
use App\Providers\EventServiceProvider;
use App\Services\ServeResult;
use App\Services\TaskServiceContract;
use Illuminate\Support\Facades\Event;

class CommitOrder implements TaskServiceContract
{
    private $face;

    private $userId;

    private $betTypeCode;

    private $issue;

    private $money;

    private $codes;

    private $odds;

    private $lotteryCode;

    public function __construct(int $userId, string $lotteryCode, int $face, string $betTypeCode, string $issue, string $money, array $codes, float $odds)
    {
        $this->userId = $userId;
        $this->lotteryCode = $lotteryCode;
        $this->face = $face;
        $this->betTypeCode = $betTypeCode;
        $this->issue = $issue;
        $this->money = $money;
        $this->codes = $codes;
        $this->odds = $odds;
    }

    public function run(): ServeResult
    {
        Event::dispatch(EventServiceProvider::BEGIN_COMMIT_ORDER_TRANSACTION_EVENT);
        try {
            $userBalance = UserBalance::lockForUpdate()->where(UserBalance::USER_ID_FIELD, $this->userId)->first();
            $userBalance->balance = bcsub($userBalance->balance, $this->money);
            if ($userBalance->balance < 0) {
                Event::dispatch(EventServiceProvider::ROLLBACK_COMMIT_ORDER_TRANSACTION_EVENT);
                return ServeResult::make(['用户余额不足']);
            }
            $userBalance->save();

            $betOrder = new BetOrder();
            $betOrder->user_id = $this->userId;
            $betOrder->issue = $this->issue;
            $betOrder->lottery_code = $this->lotteryCode;
            $betOrder->lottery_id = ($lottery = (new Lottery()))->where(Lottery::CODE_FIELD, $this->lotteryCode)->value($lottery->getPrimaryKey());
            $betOrder->play_face = $this->face;
            $betOrder->bet_type_code = $this->betTypeCode;
            $betOrder->bet_type_id = ($betType = (new BetType()))->where(BetType::CODE_FIELD, $this->betTypeCode)->value($betType->getPrimaryKey());
            $betOrder->odds = $this->odds;
            $betOrder->codes = $this->codes;
            $betOrder->status = BetOrder::SUCCESS_STATUS;
            $betOrder->bet_money = $this->money;
            $betOrder->order_no = $this->createOrderNo();
            $betOrder->save();

            Event::dispatch(new \App\Events\CommitOrder($betOrder));

            Event::dispatch(EventServiceProvider::COMMIT_COMMIT_ORDER_TRANSACTION_EVENT);
        } catch (\Exception $e) {
            Event::dispatch(EventServiceProvider::ROLLBACK_COMMIT_ORDER_TRANSACTION_EVENT);

            throw $e;
        }

        return ServeResult::make([], $betOrder->toArray());
    }

    private function createOrderNo(): string
    {
        return str_replace('.', '-', microtime(true)) . mt_rand(10, 99) . $this->userId;
    }
}
