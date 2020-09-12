<?php
namespace App\Services;

use Illuminate\Http\Request;

interface ValidatorServiceContract
{
    public function validate(Request $request): ServeResult;
}
