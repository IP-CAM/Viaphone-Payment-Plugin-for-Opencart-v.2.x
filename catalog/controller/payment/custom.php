<?php

class ControllerPaymentCustom extends Controller
{
    public function index()
    {
        $data['button_confirm'] = $this->language->get('button_confirm');

        $data['continue'] = $this->url->link('checkout/success');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/viaphone.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/viaphone.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/viaphone.tpl', $data);
        }
    }
}