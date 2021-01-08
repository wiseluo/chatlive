<?php
namespace App\HttpController\Sys\Service;

class VersionService extends BaseService
{
    public function listService($param)
    {
        return $this->VersionDatamanager->list($param);
    }

    public function readService($id)
    {
        $version = $this->VersionDatamanager->get($id);
        if($version) {
            return ['status' => 1, 'msg' => '获取成功', 'data'=> $version];
        }else{
            return ['status' => 0, 'msg' => '版本日志不存在'];
        }
    }

    public function saveService($param)
    {
        $data = [
            'type'=> $param['type'],
            'number'=> $param['number'],
            'date'=> $param['date'],
            'detail'=> $param['detail'],
            'address'=> $param['address'],
            'isforce'=> $param['isforce'],
            'size'=> $param['size'],
        ];
        $res = $this->VersionDatamanager->save($data);
        if($res) {
            return ['status' => 1, 'msg' => '版本日志添加成功', 'data'=> $res];
        }else{
            return ['status' => 0, 'msg' => '版本日志添加失败'];
        }
    }

    public function updateService($param)
    {
        $version = $this->VersionDatamanager->get($param['id']);
        if($version == null) {
            return ['status' => 0, 'msg' => '版本日志不存在'];
        }
        $data = [
            'type'=> $param['type'],
            'number'=> $param['number'],
            'date'=> $param['date'],
            'detail'=> $param['detail'],
            'address'=> $param['address'],
            'isforce'=> $param['isforce'],
            'size'=> $param['size'],
        ];
        $res = $this->VersionDatamanager->update($data, ['id'=> $param['id']]);
        if($res) {
            return ['status' => 1, 'msg' => '版本日志修改成功'];
        }else{
            return ['status' => 0, 'msg' => '版本日志修改失败'];
        }
    }

    public function deleteService($id)
    {
        $version = $this->VersionDatamanager->get($id);
        if($version == null) {
            return ['status' => 0, 'msg' => '版本日志不存在'];
        }

        $res = $this->VersionDatamanager->softDelete(['id'=> $id]);
        if($res) {
            return ['status' => 1, 'msg' => '删除成功'];
        }else{
            return ['status' => 0, 'msg' => '删除失败'];
        }
    }

    public function versionCheckService($param)
    {
        $version = $this->VersionDatamanager->getLastVersion($param);
        if($version) {
            if($param['number'] == $version['number']) {
                return ['status' => 1, 'msg' => '成功', 'data'=> ["update"=> "No"]];
            }
            $data = [
                "update"=> "Yes",//有新版本
                "new_version"=> $version['number'],//新版本号
                "apk_file_url"=> $version['address'], //apk下载地址
                "update_log"=> $version['detail'],//更新内容
                "target_size"=> $version['size'],//apk大小
                "new_md5"=> "",
                "constraint"=> $version['isforce'] == 1 ? true : false,//是否强制更新
            ];
            return ['status' => 1, 'msg' => '成功', 'data'=> $data];
        }else{
            return ['status' => 1, 'msg' => '未找到版本'];
        }
    }

    public function versionLogsService($param)
    {
        return $this->VersionDatamanager->getVersionLogs($param);
    }
}