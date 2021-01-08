<?php
namespace App\HttpController\Chat\Service;

use EasySwoole\Component\Di;
use App\HttpController\Chat\Datamanager\ChatGroupDatamanager;
use App\HttpController\Chat\Datamanager\ChatGroupMemberDatamanager;
use App\HttpController\Chat\Datamanager\UserMessageDatamanager;
use App\HttpController\User\Service\UserService;

class BaseService
{
    public function __construct()
    {
        Di::getInstance()->set('ChatGroupDatamanager', ChatGroupDatamanager::class);
        Di::getInstance()->set('ChatGroupMemberDatamanager', ChatGroupMemberDatamanager::class);
        Di::getInstance()->set('UserMessageDatamanager', UserMessageDatamanager::class);
        Di::getInstance()->set('UserService', UserService::class);
    }

    public function __get($name)
    {
        return Di::getInstance()->get($name);
    }
}