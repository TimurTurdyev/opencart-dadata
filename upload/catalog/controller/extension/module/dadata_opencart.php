<?php

class ControllerExtensionModuleDadataOpencart extends Controller
{
    const APP_PATH = 'view/javascript/dadata-opencart/';
    private static $ROUTE;
    private static $STATUS;
    private static $MODULE_NAME;

    public function __construct($registry)
    {
        parent::__construct($registry);
        self::$MODULE_NAME = (float)VERSION > 2.3 ? 'module_dadata_opencart' : 'dadata_opencart';
        self::$STATUS = (int)$this->config->get(self::$MODULE_NAME . '_status');
        self::$ROUTE = (isset($this->request->get['route'])) ? $this->request->get['route'] : 'common/home';
    }

    public function index()
    {
        if (self::$STATUS
            && $this->config->get(self::$MODULE_NAME . '_route')
            && !empty($this->config->get(self::$MODULE_NAME . '_route')[self::$ROUTE])
            && (int)$this->config->get(self::$MODULE_NAME . '_route')[self::$ROUTE]) {
            $this->document->addStyle('//cdn.jsdelivr.net/npm/suggestions-jquery@20.3.0/dist/css/suggestions.min.css');
            $this->document->addScript('//cdn.jsdelivr.net/npm/suggestions-jquery@20.3.0/dist/js/jquery.suggestions.min.js');
        }
        $file_name = str_replace('/', '_', self::$ROUTE);
        if (file_exists(DIR_APPLICATION . self::APP_PATH . $file_name . '.js')) {
            $this->document->addScript('catalog/' . self::APP_PATH . $file_name . '.js');
        }
        if (file_exists(DIR_APPLICATION . self::APP_PATH . $file_name . '.css')) {
            $this->document->addStyle('catalog/' . self::APP_PATH . $file_name . '.css');
        }
        return '';
    }
}