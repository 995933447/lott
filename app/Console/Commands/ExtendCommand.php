<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExtendCommand extends Command
{
    const SIGNATURE = '';

    const PARAMS = '';

    const OPTIONS = '';

    protected $signature;

    public function __construct()
    {
        $this->signature = !empty($this->signature)? $this->signature: trim(static::SIGNATURE . ' ' . static::PARAMS . ' ' . static::OPTIONS);

        parent::__construct();
    }
}
