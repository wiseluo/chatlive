<?php
namespace App\HttpController\Sys\Datamanager;

use App\HttpController\Sys\Model\VersionModel;

class VersionDatamanager
{
    public function get($id)
    {
        return VersionModel::create()->where(['id'=> $id, 'delete_time'=> 0])->get();
    }

    public function find($where)
    {
        return VersionModel::create()->where($where)->where('delete_time', 0)->get();
    }

    public function save($data)
    {
        return VersionModel::create($data)->save();
    }

    public function update($data, $where)
    {
        $version = VersionModel::create();
        $res = $version->update($data, $where);
        if($res) {
            return $version->lastQueryResult()->getAffectedRows();
        }else{
            return false;
        }
    }

    public function delete($where)
    {
        return VersionModel::create()->destroy($where);
    }

    public function softDelete($where)
    {
        $version = VersionModel::create();
        $res = $version->update(['delete_time'=> time()], $where);
        if($res) {
            return $version->lastQueryResult()->getAffectedRows();
        }else{
            return false;
        }
    }

    public function list($param)
    {
        if($param['type'] != '') {
            $where['type'] = $param['type'];
        }
        $where['delete_time'] = 0;
        $model = VersionModel::create()->page($param['page'], $param['page_size']);
        // 列表数据
        $list = $model->where($where)->order('id', 'DESC')->withTotalCount()->all();
        $total = $model->lastQueryResult()->getTotalCount();
        return ['total'=> $total, 'list'=> $list];
    }

    public function getLastVersion($param)
    {
        $where['type'] = $param['type'];
        $where['delete_time'] = 0;
        return VersionModel::create()->where($where)->order('id', 'DESC')->get();
    }

    public function getVersionLogs($param)
    {
        $where['type'] = $param['type'];
        $where['delete_time'] = 0;
        $model = VersionModel::create()->page($param['page'], $param['page_size']);
        // 列表数据
        $list = $model->field('id,number,date,detail')->where($where)->order('id', 'DESC')->withTotalCount()->all();
        $total = $model->lastQueryResult()->getTotalCount();
        return ['total'=> $total, 'list'=> $list];
    }
}
