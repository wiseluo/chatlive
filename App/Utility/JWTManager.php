<?php

namespace App\Utility;

use EasySwoole\Jwt\Jwt;
use EasySwoole\Component\Singleton;

class JWTManager
{
    use Singleton;

    private $secret_key = [
        'sxim' => '609004480662a239155de1a5e4d0262f',
        'sximdev' => '609004480662a239155de1a5e4d0262f',
    ];

    public function decode(string $appkey, string $token)
    {
        return Jwt::getInstance()->setSecretKey($this->secret_key[$appkey])->decode($token);
    }

}