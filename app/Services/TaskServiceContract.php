<?php
namespace App\Services;

interface TaskServiceContract
{
    public function run(): ServeResult;
}
