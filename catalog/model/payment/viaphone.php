<?php
class ModelPaymentViaphone extends Model {
	public function getMethod($address, $total) {
		$this->load->language('payment/viaphone');
        $status = true;

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'viaphone',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => 0
			);
		}

		return $method_data;
	}
}