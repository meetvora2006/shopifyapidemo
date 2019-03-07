<?php

//will manage all of the shopify endpoints
class Shopify
{
    private $Myshopify;
    private $dbConnection;
    private $client;

    public function __construct()
    {   $key = "5a1beb57bf536ea950076aef6083####";
        $password = "ed183421ea77b404f324db6#########";
        $url = "#####-#####.myshopify.com";

        $this->client = sprintf("https://%s:%s@%s/admin", $key, $password, $url);


    }

    public function getDbconnection()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "########";

         $conn = new mysqli($servername, $username, $password, $dbname);

        return $conn;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getData($path, array $params = [])
    {
        foreach ($params as $key => $value) {

            if($value !== ''){

                $myParams[] = "$key=$value";
            }

        }

        $stringigyParams = implode("&", $myParams);

        $url = $this->client . $path . '?' . $stringigyParams;

        return $data = $this->get($url);
    }

    private function get($url)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_CAINFO, "C:\wamp64\www\shopifytest\cacert.pem");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));

        $data = json_decode(curl_exec($ch), true);
        curl_close($ch);

         return $data;
    }
}