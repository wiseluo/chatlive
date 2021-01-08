<?php
namespace App\HttpController;

use App\Utility\PlatesRender;
use EasySwoole\EasySwoole\Config;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Template\Render;

/**
 * 基础控制器
 * Class Base
 * @package App\HttpController
 */
class Base extends Controller
{
    function index()
    {
        $this->actionNotFound('index');
    }

    /**
     * 分离式渲染
     * @param $template
     * @param $vars
     */
    function render($template, array $vars = [])
    {
        $engine = new PlatesRender(EASYSWOOLE_ROOT . '/App/Views');
        $render = Render::getInstance();
        $render->getConfig()->setRender($engine);
        $content = $engine->render($template, $vars);
        $this->response()->write($content);
    }

    /**
     * 获取配置值
     * @param $name
     * @param null $default
     * @return array|mixed|null
     */
    function cfgValue($name, $default = null)
    {
        $value = Config::getInstance()->getConf($name);
        return is_null($value) ? $default : $value;
    }
    /**
     * 获取用户的真实IP
     * @param string $headerName 代理服务器传递的标头名称
     * @return string
     */
    protected function clientRealIP($headerName = 'x-real-ip')
    {
        $server = ServerManager::getInstance()->getSwooleServer();
        $client = $server->getClientInfo($this->request()->getSwooleRequest()->fd);
        $clientAddress = $client['remote_ip'];
        $xri = $this->request()->getHeader($headerName);
        $xff = $this->request()->getHeader('x-forwarded-for');
        if ($clientAddress === '127.0.0.1') {
            if (!empty($xri)) {  // 如果有xri 则判定为前端有NGINX等代理
                $clientAddress = $xri[0];
            } elseif (!empty($xff)) {  // 如果不存在xri 则继续判断xff
                $list = explode(',', $xff[0]);
                if (isset($list[0])) $clientAddress = $list[0];
            }
        }
        return $clientAddress;
    }

    protected function writeJson($statusCode = 200, $msg = null,$result = null)
    {
        if (!$this->response()->isEndResponse()) {
            $data = Array(
                "code" => $statusCode,
                "data" => $result,
                "msg" => $msg
            );
            $this->response()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
            $this->response()->withStatus(200);
            return true;
        } else {
            return false;
        }
    }

    protected function json_list($data_list=[],$data_num=0,$page=1,$page_size=10,$extra_data=[]){
        $statusCode = 200;
        $pager                 = [];
        $pager['total']        = $data_num;
        $pager['page_size']    = $page_size;
        $pager['current_page'] = $page;
        $pager['last_page']    = ceil($data_num/$page_size) > 0 ? ceil($data_num/$page_size) : 1;
        if($extra_data){
            $pager['extra_data']   = $extra_data;
        }
        if (!$this->response()->isEndResponse()) {
            $data = Array(
                "code"  => $statusCode,
                "data"  => $data_list,
                "msg"   => '成功',
                "pager" => $pager
            );
            $this->response()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
            $this->response()->withStatus(200);
            return true;
        } else {
            return false;
        }
    }

    protected function json_res($data){
        if (!$this->response()->isEndResponse()) {
            $this->response()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
            $this->response()->withStatus(200);
            return true;
        } else {
            return false;
        }
    }

}