<?php

namespace App\Utility;

use EasySwoole\Component\Singleton;
use Swoole\Table;


class FdManager
{
    use Singleton;

    private $fd_appkey_uid_table; // fd => appkey + uid
    private $appkey_uid_fd_table; // appkey +  uid => fd


    public function __construct(int $size = 1024*256)
    {
        $this->fd_appkey_uid_table = new Table($size);
        $this->fd_appkey_uid_table->column('appkey_uid',Table::TYPE_STRING,25);
        $this->fd_appkey_uid_table->column('appkey',Table::TYPE_STRING,25);
        $this->fd_appkey_uid_table->column('uid',Table::TYPE_INT,11);
        $this->fd_appkey_uid_table->create();
        $this->appkey_uid_fd_table = new Table($size);
        $this->appkey_uid_fd_table->column('fd',Table::TYPE_INT,11);
        $this->appkey_uid_fd_table->create();
    }

    public function bind(string $appkey,int $fd,int $uid){
        $this->fd_appkey_uid_table->set($fd,['appkey_uid'=>$appkey.'_'.$uid,'appkey'=>$appkey,'uid'=>$uid]);
        $this->appkey_uid_fd_table->set($appkey.'_'.$uid,['fd'=>$fd]);
        var_dump($fd.'==>'.$appkey.'_'.$uid.'完成绑定');
    }

    public function getAppkeyUidByFd(int $fd){
        $res = $this->fd_appkey_uid_table->get($fd);
        if($res){
            return $res['appkey_uid'];
        }else{
            return null;
        }
    }

    public function getUidByFd(int $fd){
        $res = $this->fd_appkey_uid_table->get($fd);
        if($res){
            return $res['uid'];
        }else{
            return null;
        }
    }

    public function getFdByAppkeyUid(string $appkey_uid){
        $res = $this->appkey_uid_fd_table->get($appkey_uid);
        if($res){
            return $res['fd'];
        }else{
            return null;
        }
    }
    public function getAppkeyByFd(int $fd){
        $res = $this->fd_appkey_uid_table->get($fd);
        if($res){
            return $res['appkey'];
        }else{
            return null;
        }
    }
    public function delete(int $fd){
        var_dump('fd delete:'.$fd);
        $appkey_uid = $this->getAppkeyUidByFd($fd);
        if($appkey_uid){
            $this->appkey_uid_fd_table->del($appkey_uid);
        }
        $this->fd_appkey_uid_table->del($fd);
    }

}