<?php
namespace App\Services\BetOrder\Tasks\OrderEncryptor;

use App\Models\BetOrder;
use App\Services\ServeResult;
use App\Services\TaskServiceContract;
use App\Utils\Encryptor\Encryptor;

class EncryptOrder implements TaskServiceContract
{
    const VERSION_1 = 'v1.0.0';

    private $betOrder;

    private $version;

    public function __construct(BetOrder $order, $version)
    {
        $this->betOrder = $order;
        $this->version = $version;
    }

    public function run(): ServeResult
    {
        switch ($this->version) {
            case static::VERSION_1:
                $encryptedPayload = $this->encryptV1();
                break;
            default:
                throw new \InvalidArgumentException("Do not support encrypt version:{$this->version}");
        }

        $this->markEncryptVersion($encryptedPayload);

        return ServeResult::make();
    }

    private function encryptV1(): string
    {
        $encryptHashPayload = new \stdClass();
        $encryptHashPayload->version = static::VERSION_1;
        $encryptHashPayload->codes = $this->betOrder->codes;
        $encryptHashPayload->bet_type_code = $this->betOrder->bet_type_code;
        $encryptHashPayload->user_id = $this->betOrder->user_id;
        $encryptHashPayload->lottery_code = $this->betOrder->lottery_code;
        $encryptHashPayload->play_face = $this->betOrder->play_face;
        $encryptHashPayload->issue = $this->betOrder->issue;
        $encryptHashPayload->bet_money = $this->betOrder->bet_money;
        $encryptHashPayload->odds = $this->betOrder->odds;
        if (!empty($this->betOrder->reward_money)) {
            $encryptHashPayload->reward_money = $this->betOrder->reward_money;
        }
        if (!empty($this->betOrder->reward_status)) {
            $encryptHashPayload->reward_status = $this->betOrder->reward_status;
        }
        if (!empty($this->betOrder->reward_codes)) {
            $encryptHashPayload->reward_codes = $this->betOrder->reward_codes;
        }

        return Encryptor::serializeToEncrypt($encryptHashPayload);
    }

    private function markEncryptVersion(string $encryptedPayload)
    {
        $safeIdentifier = new \stdClass();
        $safeIdentifier->version = static::VERSION_1;
        $safeIdentifier->payload = $encryptedPayload;

        $this->betOrder->safe_identifier = serialize($safeIdentifier);
    }
}
