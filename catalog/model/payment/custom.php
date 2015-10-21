<?php

class ModelPaymentCustom extends Model
{
    public function getMethod($address, $total)
    {
        $this->load->language('payment/viaphone');

        $method_data = array();
        $status = true;

        if ($status) {
            $method_data = array(
                'code' => 'viaphone',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('cod_sort_order')
            );
        }

        return $method_data;
    }
}