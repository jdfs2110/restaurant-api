<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponsesTrait;
use App\Traits\CloudflareUtilsTrait;

abstract class Controller
{
    use ApiResponsesTrait, CloudflareUtilsTrait;
}
