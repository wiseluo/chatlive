<?php
namespace App\HttpController\Sys\Service;

class SensitiveService extends BaseService
{
    public function listService($param)
    {
        return $this->SensitiveDatamanager->list($param);
    }

    public function readService($id)
    {
        $sensitive = $this->SensitiveDatamanager->get($id);
        if($sensitive) {
            return ['status' => 1, 'msg' => '获取成功', 'data'=> $sensitive];
        }else{
            return ['status' => 0, 'msg' => '敏感词不存在'];
        }
    }

    public function saveService($param)
    {
        $data = [
            'type'=> $param['type'],
            'type_name'=> $this->SensitiveDatamanager->typeName($param['type']),
            'word'=> $param['word'],
        ];
        $res = $this->SensitiveDatamanager->save($data);
        if($res) {
            $this->SensitiveHandle->buildTree();
            return ['status' => 1, 'msg' => '敏感词添加成功', 'data'=> $res];
        }else{
            return ['status' => 0, 'msg' => '敏感词添加失败'];
        }
    }

    public function updateService($param)
    {
        $sensitive = $this->SensitiveDatamanager->get($param['id']);
        if($sensitive == null) {
            return ['status' => 0, 'msg' => '敏感词不存在'];
        }
        $data = [
            'type'=> $param['type'],
            'type_name'=> $this->SensitiveDatamanager->typeName($param['type']),
            'word'=> $param['word'],
        ];
        $res = $this->SensitiveDatamanager->update($data, ['id'=> $param['id']]);
        if($res) {
            $this->SensitiveHandle->buildTree();
            return ['status' => 1, 'msg' => '敏感词修改成功'];
        }else{
            return ['status' => 0, 'msg' => '敏感词修改失败'];
        }
    }

    public function deleteService($id)
    {
        $sensitive = $this->SensitiveDatamanager->get($id);
        if($sensitive == null) {
            return ['status' => 0, 'msg' => '敏感词不存在'];
        }

        $res = $this->SensitiveDatamanager->softDelete(['id'=> $id]);
        if($res) {
            return ['status' => 1, 'msg' => '删除成功'];
        }else{
            return ['status' => 0, 'msg' => '删除失败'];
        }
    }

    public function buildTreeService()
    {
        $res = $this->SensitiveHandle->buildTree();
        return ['status' => 1, 'msg' => '成功', 'data'=> $res];
    }

    public function sensitiveScreenService($param)
    {
        $res = $this->SensitiveHandle->sensitiveScreen($param['content']);
        return ['status' => 1, 'msg' => '成功', 'data'=> $res];
    }
}