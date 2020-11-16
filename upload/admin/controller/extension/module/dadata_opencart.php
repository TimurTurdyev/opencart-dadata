<?php

class ControllerExtensionModuleDadataOpencart extends Controller {
    public function index() {
        if( (float)VERSION > 2.1 ) {
            if( file_exists(DIR_APPLICATION . 'controller/module/dadata_opencart.php') ) {
                unlink(DIR_APPLICATION . 'controller/module/dadata_opencart.php');
            }
        }

        if( (float)VERSION > 2.3 ) {
            if( isset($this->request->get['user_token']) && isset($this->session->data['user_token']) && ( $this->request->get['user_token'] === $this->session->data['user_token'] ) ) {
                $this->response->redirect($this->url->link('catalog/dadata_opencart', 'user_token=' . $this->session->data['user_token'], true));
            }
        }

        if( isset($this->request->get['token']) && isset($this->session->data['token']) && ( $this->request->get['token'] === $this->session->data['token'] ) ) {
            if( (float)VERSION >= 2.1 ) {
                $this->response->redirect($this->url->link('catalog/dadata_opencart', 'token=' . $this->session->data['token'], true));
            } else {
                $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
            }
        }
    }

    public function install() {
        if( (float)VERSION > 2.1 ) {
            if( file_exists(DIR_APPLICATION . 'controller/module/dadata_opencart.php') ) {
                unlink(DIR_APPLICATION . 'controller/module/dadata_opencart.php');
            }
        }
    }
}
