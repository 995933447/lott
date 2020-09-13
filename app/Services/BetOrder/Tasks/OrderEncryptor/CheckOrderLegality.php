<?php
namespace App\Services\BetOrder\Tasks\OrderEncryptor;

use App\Models\BetOrder;
use App\Services\BetOrder\Tasks\OrderEncryptor\EncryptOrder;
use App\Services\ServeResult;
use App\Services\TaskServiceContract;
use App\Utils\Encryptor\Encryptor;

class CheckOrderLegality implements TaskServiceContract
{
    private $betOrder;

    private $unserializedSafeIdentifier;

    public function __construct(BetOrder $order)
    {
        $this->betOrder = $order;
    }

    public function run(): ServeResult
    {
        if (!$this->betOrder->safe_identifier) {
            return ServeResult::make(["订单号{$this->betOrder->order_no}缺少安全凭证"]);
        }

        switch ($version = $this->parseEncryptVersion()) {
            case EncryptOrder::VERSION_1:
                return $this->checkLegalityOfEncryptV1();
            default:
                throw new \InvalidArgumentException("Do not support encrypt version:{$version}");
        }
    }

    private function parseEncryptVersion()
    {
        return ($this->unserializedSafeIdentifier = $this->unserializedSafeIdentifier?: unserialize($this->betOrder->safe_identifier))->version;
    }

    private function checkLegalityOfEncryptV1(): ServeResult
    {
        $this->unserializedSafeIdentifier->payload = is_string($this->unserializedSafeIdentifier->payload)?
            Encryptor::unserializeToDecrypt($this->unserializedSafeIdentifier->payload): $this->unserializedSafeIdentifier->payload;

        if ($this->unserializedSafeIdentifier->version != $this->unserializedSafeIdentifier->payload->version) {
            return ServeResult::make(['version' => "订单号{$this->betOrder->order_no}加密版本标记疑似被非法改动"]);
        }

        $reflectionSafeIdentifierPayload = new \ReflectionClass($this->unserializedSafeIdentifier->payload);
        $needCheckProperties = $reflectionSafeIdentifierPayload->getProperties();

        foreach ($needCheckProperties as $needCheckProperty) {
            if (($propertyName = $needCheckProperty->getName()) == 'version')
                continue;

            if ($this->betOrder->$propertyName != $needCheckProperty->getValue($this->unserializedSafeIdentifier->payload)) {
                return ServeResult::make([$propertyName => "订单号{$this->betOrder->order_no} {$propertyName} 字段疑似被非法改动"]);
            }
        }

        return ServeResult::make();
    }
}
