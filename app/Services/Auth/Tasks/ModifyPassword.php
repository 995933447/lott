<?php
namespace App\Services\Auth\Tasks;

use App\Services\ServeResult;
use App\Services\TaskServiceContract;
use App\Utils\Encryptor\Encryptor;
use Illuminate\Support\Facades\Auth;

class ModifyPassword implements TaskServiceContract
{
    private $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }

    public function run(): ServeResult
    {
        Auth::user()->password = Encryptor::hashPassword($this->password);
        Auth::user()->save();
        return ServeResult::make();
    }
}
