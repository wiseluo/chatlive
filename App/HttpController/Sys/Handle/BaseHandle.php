<?php
namespace App\HttpController\Sys\Handle;

use EasySwoole\Component\Di;
use App\HttpController\Sys\Datamanager\SensitiveDatamanager;

class BaseHandle
{
    public function __construct()
    {
        Di::getInstance()->set('SensitiveDatamanager', SensitiveDatamanager::class);
    }

    public function __get($name)
    {
        return Di::getInstance()->get($name);
    }

}