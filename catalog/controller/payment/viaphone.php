<?php

class ControllerPaymentViaphone extends Controller
{
    public function index()
    {
        $this->load->language('payment/viaphone');

        $data['first_url'] = $this->url->link('payment/viaphone/make', '', 'SSL');
        $data['second_url'] = $this->url->link('payment/viaphone/confirm', '', 'SSL');

        $data['button_confirm'] = $this->language->get('button_confirm');
        $data['phone_label'] = $this->language->get('text_phone');
        $data['text_error'] = $this->language->get('text_error');
        $data['text_smscode'] = $this->language->get('text_smscode');

        $this->load->model('checkout/order');

        $data['confirm_url'] = $this->url->link('payment/viaphone/confirm', '', 'SSL');

        $data['cart_order_id'] = $this->session->data['order_id'];

        $data['success_url'] = $this->url->link('checkout/success');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/viaphone.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/viaphone.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/viaphone.tpl', $data);
        }
    }

    public function make()
    {
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($this->request->post['cart_order_id']);

        $products = $this->cart->getProducts();

        $curl_params = array(
            "token" => $this->config->get('viaphone_storetoken'),
            "merchant" => $this->config->get('viaphone_storeid'),
            "phone" => $this->request->post['phone'],
            "price" => $order_info['total'],
            "currency" => $order_info['currency_code'] . "_" . $order_info['currency_value'],
            "details" => serialize($products)
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://136.243.98.42:8080/viaphone/api/make-payment");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($curl_params));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);
        $result = @json_decode($server_output);

        if (isset($result->status)) {
            if ($result->status == 'OK') {
                $this->model_checkout_order->addOrderHistory($this->request->post['cart_order_id'], 1);
            }
            echo json_encode(array(
                "status" => $result->status,
                "payment" => $result->paymentId,
                "error" => $result->comment
            ));
        } else {
            $this->load->language('payment/viaphone');
            echo json_encode(array(
                "status" => 'error',
                "error" => $this->language->get('text_systemerror')
            ));
        }
    }

    public function confirm()
    {
        $this->load->model('checkout/order');
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://136.243.98.42:8080/viaphone/api/confirm-payment");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
            "token" => $this->config->get('viaphone_storetoken'),
            "merchant" => $this->config->get('viaphone_storeid'),
            "payment" => $this->request->post['payment'],
            "code" => $this->request->post['code'],
        )));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);
        curl_close($ch);
        $result = @json_decode($server_output);

        if (isset($result->status)) {
            if ($result->status == 'OK') {
                $this->model_checkout_order->addOrderHistory($this->request->post['cart_order_id'], $this->config->get('viaphone_order_status'));
            }
            echo json_encode(array(
                "status" => $result->status,
                "payment" => $result->paymentId,
                "error" => $result->comment
            ));
        } else {
            $this->load->language('payment/viaphone');
            echo json_encode(array(
                "status" => 'error',
                "error" => $this->language->get('text_systemerror')
            ));
        }
    }
}