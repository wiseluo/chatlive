<?php
namespace App\HttpController\User\Model;

use EasySwoole\ORM\AbstractModel;

class UserMessageModel extends AbstractModel
{
    protected $tableName  = 'pk_user_message';
    protected $primaryKey = 'id';
}