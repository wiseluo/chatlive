<?php
namespace App\HttpController\Sys\Service;

use EasySwoole\Component\Di;
use App\HttpController\Sys\Handle\SensitiveHandle;
use App\HttpController\Sys\Datamanager\SensitiveDatamanager;
use App\HttpController\Finance\Datamanager\UserDatamanager;
use App\HttpController\Sys\Datamanager\VersionDatamanager;

class BaseService
{
    public function __construct()
    {
        Di::getInstance()->set('SensitiveHandle', SensitiveHandle::class);
        Di::getInstance()->set('SensitiveDatamanager', SensitiveDatamanager::class);
        Di::getInstance()->set('UserDatamanager', UserDatamanager::class);
        Di::getInstance()->set('VersionDatamanager', VersionDatamanager::class);
    }

    public function __get($name)
    {
        return Di::getInstance()->get($name);
    }
}