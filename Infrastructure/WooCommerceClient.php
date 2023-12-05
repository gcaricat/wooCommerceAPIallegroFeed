<?php

use Automattic\WooCommerce\Client;

class WooCommerceClient {

    private $wooClient;

    public function __construct($consumerKey, $consumerSecret, $storeURL) {

        $this->wooClient = new Client(
            $storeURL,
            $consumerKey,
            $consumerSecret,
            [
                'wp_api' => true,
                'version' => 'wc/v3', // Make sure to use the correct version
            ]
        );

    }

    public function getProducts()
    {

        try {
            return $this->wooClient->get(
                'products',
                [
                    'status' => 'publish'
                ]
            );
        } catch (\Automattic\WooCommerce\HttpClient\HttpClientException $e) {
            echo "Error {$e->getMessage()}";
        }
    }

    public function getShippingClass($id)
    {
        try {
            return $this->wooClient->get(
                'products/shipping_classes',
            );
        } catch (\Automattic\WooCommerce\HttpClient\HttpClientException $e) {
            echo "Error {$e->getMessage()}";
        }
    }





}
?>
