<?php
namespace App\Services\Auth\Tasks;

use App\Repositories\CaptchaValueRepository;
use App\Services\ServeResult;
use App\Services\TaskServiceContract;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Carbon;
use Gregwar\Captcha\PhraseBuilder;

class CreateCaptcha implements TaskServiceContract
{
    public function run(): ServeResult
    {
        $captchaBuilder = new CaptchaBuilder(null, new PhraseBuilder(4, '0123456789'));
        $captcha = $captchaBuilder->build();
        CaptchaValueRepository::save($captchaId = $this->getCaptchaId(), $captcha->getPhrase(), Carbon::now()->addMinutes(5));
        return ServeResult::make([], ['captcha' => $captcha->inline(), 'id' => $captchaId]);
    }

    private function getCaptchaId()
    {
        return uniqid();
    }
}
