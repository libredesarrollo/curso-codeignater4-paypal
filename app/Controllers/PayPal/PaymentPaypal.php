<?php

namespace App\Controllers\Paypal;

use App\Controllers\BaseController;
use Exception;

class PaymentPaypal extends BaseController
{

    private $clientId = '__';
    private $secret = '__';
    //private $baseURL = 'https://api-m.paypal.com';
    private $baseURL = 'https://api-m.sandbox.paypal.com';


    public function index()
    {

        echo view('shopping/paypal');
    }

    public function process($orderId = null)
    {
        try {

            $accessToken = $this->getAccessToken();

            $curl = curl_init($this->baseURL . "/v2/checkout/orders/$orderId/capture");

            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $accessToken,
                    'Content-Type: application/json'
                )
            ));
            // curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);

            $resp = json_decode(curl_exec($curl));
            curl_close($curl);

            if ($resp) {
                if ($resp->status == 'COMPLETED') {
                    return $this->response->setJSON(array('msj' => 'Orden procesada!'));
                }
            }

            return $this->response->setJSON(array('msj' => 'Orden no procesada!'));
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return $this->response->setJSON(array('msj' => 'Orden no procesada!'));
    }
    // public function processCodeIgniter($orderId = null)
    // {
    //     try {

    //         $accessToken = $this->getAccessToken();

    //         $client = \Config\Services::curlrequest();
    //         $response = $client->request('POST', $this->baseURL . "/v2/checkout/orders/$orderId/capture", [
    //             'headers' => [
    //                 'Authorization' => 'Bearer '.$accessToken,
    //                 'Content-Type' => 'application/json',
    //             ],
    //         ]);

    //         //$res = json_decode($response->getBody());
    //         var_dump($res);
    //         return $res;
    //     } catch (Exception $e) {
    //         return $e->getMessage();
    //     }
    // }

    public function getAccessToken()
    {
        try {
            $client = \Config\Services::curlrequest();
            $response = $client->request('POST', $this->baseURL . '/v1/oauth2/token', [
                'auth' => [$this->clientId, $this->secret],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                ],
            ]);

            $res = json_decode($response->getBody());

            return $res->access_token;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
