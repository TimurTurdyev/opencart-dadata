<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

class ControllerCatalogDadataOpencart extends Controller
{
    const COOPERATE = 'Created by Timur T.R / version[ 3.0.0 ]';
    const APP_PATH = 'view/javascript/dadata-opencart/';
    const APP_CONFIG = 'dadata-opencart/';
    private static $MODULE_NAME;

    public function __construct($registry)
    {
        parent::__construct($registry);
        self::$MODULE_NAME = (float)VERSION > 2.3 ? 'module_dadata_opencart' : 'dadata_opencart';
        $this->load->model('setting/setting');
    }

    public function index()
    {
        if (file_exists(DIR_APPLICATION . self::APP_PATH . 'index.html')) {
            $index_html = file_get_contents(DIR_APPLICATION . self::APP_PATH . 'index.html');
        } else {
            $index_html = 'Не найден файл шаблона!';
        }

        $this->response->setOutput($index_html);
    }

    public function changeStatus()
    {
        $setting = array('dadata_opencart_status' => 0, 'dadata_opencart_status' => []);
        $config = $this->model_setting_setting->getSetting(self::$MODULE_NAME);
        $setting[self::$MODULE_NAME . '_status'] = isset($this->request->get['status']) ? $this->request->get['status'] : 0;
        $setting[self::$MODULE_NAME . '_route'] = $config[self::$MODULE_NAME . '_route'];
        $this->model_setting_setting->editSetting(self::$MODULE_NAME, $setting);
        $setting['notification'] = $setting[self::$MODULE_NAME . '_status'] ? 'Модуль включен' : 'Модуль отключен!';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($setting));
    }

    public function settingRoute()
    {
        $json = array();

        if (isset($this->request->get['setting_name'])) {
            $file_name = str_replace('/', '_', $this->request->get['setting_name']) . '.json';
            if (file_exists(DIR_CONFIG . self::APP_CONFIG . $file_name)) {
                $json = json_decode(file_get_contents(DIR_CONFIG . self::APP_CONFIG . $file_name));
            }
        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function deleteSettingRoute()
    {
        $notification = '';
        $setting = $this->model_setting_setting->getSetting(self::$MODULE_NAME);

        if (isset($this->request->get['setting_name']) && $page_name = str_replace('/', '_', $this->request->get['setting_name'])) {
            if (file_exists(DIR_CONFIG . self::APP_CONFIG . $page_name . '.json')) {
                unlink(DIR_CONFIG . self::APP_CONFIG . $page_name . '.json');
            }

            if (file_exists(DIR_CATALOG . self::APP_PATH . $page_name . '.js')) {
                unlink(DIR_CATALOG . self::APP_PATH . $page_name . '.js');
            }

            if (file_exists(DIR_CATALOG . self::APP_PATH . $page_name . '.css')) {
                unlink(DIR_CATALOG . self::APP_PATH . $page_name . '.css');
            }

            if (isset($setting['dadata_opencart_route'][$this->request->get['setting_name']])) {
                unset($setting['dadata_opencart_route'][$this->request->get['setting_name']]);
                $this->model_setting_setting->editSetting(self::$MODULE_NAME, $setting);
                $notification = 'Вы успешно удалили настройку';
            }
        }

        $this->getRoutes($notification);
    }

    public function getRoutes($notification = '')
    {
        $json = array(
            'status' => 0,
            'routers' => []
        );
        $setting = $this->model_setting_setting->getSetting(self::$MODULE_NAME);
        $routers = $setting[self::$MODULE_NAME . '_route'];

        foreach ($routers as $route => $status) {
            array_push($json['routers'], $route);
        }

        $json['status'] = isset($setting[self::$MODULE_NAME . '_status']) ? $setting[self::$MODULE_NAME . '_status'] : 0;
        if ($notification) {
            $json['notification'] = $notification;
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function saveSettingRoute()
    {
        $json = array();
        $request_json = $this->getJson();

        if ($request_json &&
            !empty($request_json['name']) &&
            isset($request_json['before']) &&
            isset($request_json['after']) &&
            isset($request_json['css'])) {

            $page_name = str_replace('/', '_', $request_json['name']);

            file_put_contents(DIR_CONFIG . self::APP_CONFIG . $page_name . '.json', json_encode($request_json, 1));

            file_put_contents(DIR_CATALOG . self::APP_PATH . $page_name . '.js', ';function ' . $page_name . '(){' . $request_json['before'] . '};' . $request_json['after'] . ';');
            file_put_contents(DIR_CATALOG . self::APP_PATH . $page_name . '.css', $request_json['css']);

            $config = $this->model_setting_setting->getSetting(self::$MODULE_NAME);

            if (empty($config[self::$MODULE_NAME . '_route'])) {
                $config[self::$MODULE_NAME . '_route'] = [];
            }

            $config[self::$MODULE_NAME . '_route'][$request_json['name']] = isset($request_json['status']) ? $request_json['status'] : 0;
            $this->model_setting_setting->editSetting(self::$MODULE_NAME, $config);
            $json['notification'] = 'Настройки успешно сохраненны!';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function getJson()
    {
        if (($this->request->server['REQUEST_METHOD'] === 'POST')
            && isset($this->request->server['CONTENT_TYPE'])
            && (stristr($this->request->server['CONTENT_TYPE'], 'application/json;') !== false)
            && ($request = file_get_contents('php://input'))) {

            return json_decode($request, 1);
        }

        return array();
    }
}