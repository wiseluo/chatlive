<?php
namespace App\HttpController\Sys\Datamanager;

use App\HttpController\Sys\Model\SensitiveModel;

class SensitiveDatamanager
{
    public function typeName($type)
    {
        return SensitiveModel::TYPE[$type];
    }

    public function get($id)
    {
        return SensitiveModel::create()->where(['id'=> $id, 'delete_time'=> 0])->get();
    }

    public function find($where)
    {
        return SensitiveModel::create()->where($where)->where('delete_time', 0)->get();
    }

    public function save($data)
    {
        return SensitiveModel::create($data)->save();
    }

    public function update($data, $where)
    {
        $sensitive = SensitiveModel::create();
        $res = $sensitive->update($data, $where);
        if($res) {
            return $sensitive->lastQueryResult()->getAffectedRows();
        }else{
            return false;
        }
    }

    public function delete($where)
    {
        return SensitiveModel::create()->destroy($where);
    }

    public function softDelete($where)
    {
        $sensitive = SensitiveModel::create();
        $res = $sensitive->update(['delete_time'=> time()], $where);
        if($res) {
            return $sensitive->lastQueryResult()->getAffectedRows();
        }else{
            return false;
        }
    }

    public function select()
    {
        return SensitiveModel::create()->field('word')->where('delete_time', 0)->all();
    }

    public function list($param)
    {
        $where['delete_time'] = 0;
        $model = SensitiveModel::create()->page($param['page'], $param['page_size']);
        // 列表数据
        $list = $model->where($where)->order('id', 'DESC')->withTotalCount()->all();
        $total = $model->lastQueryResult()->getTotalCount();
        return ['total'=> $total, 'list'=> $list];
    }

}
