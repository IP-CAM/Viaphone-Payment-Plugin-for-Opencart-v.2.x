<?php

class ControllerPaymentViaphone extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('payment/viaphone');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('viaphone', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_storeid'] = $this->language->get('entry_storeid');
        $data['entry_storetoken'] = $this->language->get('entry_storetoken');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['entry_order_status'] = $this->language->get('entry_order_status');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['storeid'])) {
            $data['error_storeid'] = $this->error['storeid'];
        } else {
            $data['error_storeid'] = '';
        }

        if (isset($this->error['storetoken'])) {
            $data['error_storetoken'] = $this->error['storetoken'];
        } else {
            $data['error_storetoken'] = '';
        }

        if (isset($this->error['token'])) {
            $data['error_token'] = $this->error['token'];
        } else {
            $data['error_token'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/viaphone', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['action'] = $this->url->link('payment/viaphone', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['storeid'])) {
            $data['viaphone_storeid'] = $this->request->post['viaphone_storeid'];
        } else {
            $data['viaphone_storeid'] = $this->config->get('viaphone_storeid');
        }

        if (isset($this->request->post['viaphone_storetoken'])) {
            $data['viaphone_storetoken'] = $this->request->post['viaphone_storetoken'];
        } else {
            $data['viaphone_storetoken'] = $this->config->get('viaphone_storetoken');
        }

        if (isset($this->request->post['viaphone_status'])) {
            $data['viaphone_status'] = $this->request->post['viaphone_status'];
        } else {
            $data['viaphone_status'] = $this->config->get('viaphone_status');
        }

        if (isset($this->request->post['viaphone_order_status'])) {
            $data['viaphone_order_status'] = $this->request->post['viaphone_order_status'];
        } else {
            $data['viaphone_order_status'] = $this->config->get('viaphone_order_status');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/viaphone.tpl', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'payment/viaphone')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['viaphone_storeid']) {
            $this->error['storeid'] = $this->language->get('error_storeid');
        }

        if (!$this->request->post['viaphone_storetoken']) {
            $this->error['storetoken'] = $this->language->get('error_storetoken');
        }

        return !$this->error;
    }
}
