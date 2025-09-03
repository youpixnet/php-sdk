<?php

class YouPix
{

    protected $urlBase = "https://api.youpix.net";
    protected $client_secret;
    protected $token;

    public function __construct($client_secret, $token = null)
    {
        $this->client_secret = $client_secret;
        $this->token = $token;
    }

    public function serviceStatus()
    {
        return $this->request("/", "GET");
    }

    public function generateToken()
    {
        return $this->request("/account/token", "POST", [
            "client_secret" => $this->client_secret
        ]);
    }

    private function request($path, $method, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "{$this->urlBase}$path");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $this->token",
            "Content-type: application/json"
        ]);

        switch (strtoupper($method)) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, true);
                if (!is_null($data)) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                if (!is_null($data)) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;
            case "GET":
                if (!is_null($data)) {
                    curl_setopt($curl, CURLOPT_URL, "{$this->urlBase}$path" . '?' . http_build_query($data));
                }
                break;
        }

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new Exception(curl_error($curl));
        }

        curl_close($curl);
        return json_decode($response, true);
    }

    public function sendPix($key, $fullname, $cpf, $amount)
    {
        return $this->request("/pix/send", "POST", [
            "key" => $key,
            "fullname" => $fullname,
            "document" => $cpf,
            "amount" => $amount
        ]);
    }

    public function createCobPix($product, $amount, $webhook = null)
    {
        return $this->request("/pix/create/cob", "POST", [
            "product" => $product,
            "amount" => $amount,
            "webhook" => $webhook
        ]);
    }

    public function getTransactions($page = 1)
    {
        return $this->request("/extract/transactions?page={$page}", "GET");
    }

    public function getWithdraws($page = 1)
    {
        return $this->request("/extract/withdraws?page={$page}", "GET");
    }

    public function getAccount()
    {
        return $this->request("/account", "GET");
    }

}