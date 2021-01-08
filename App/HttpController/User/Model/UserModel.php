<?php
namespace App\HttpController\User\Model;

use EasySwoole\ORM\AbstractModel;

class UserModel extends AbstractModel
{
    protected $tableName  = 'pk_user';
    protected $primaryKey = 'id';
    protected $autoTimeStamp = true;
    
}