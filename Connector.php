<?php

abstract class Connector
{
    /** @var String $connectionString */
    private $connectionString;

    /** @var String $key */
    private $key;

    public function __construct(String $connectionString, String $key)
    {
        $this->connectionString = $connectionString;
        $this->key = $key;
    }

    /**
     * Function that sends a request to be treated.
     *
     * @param String $method Method of the resquest (POST,GET,DELETE)
     * @param String $uri location to send the request
     * @param array $options
     * @return mixed
     * @throws Exception
     **/
    public function send(String $method, String $uri, array $options = [])
    {
        try {
            $curlHandle = curl_init("{$this->connectionString}/{$uri}");

            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curlHandle, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer {$this->key}",
                'Content-Type: application/json',
                'Accept: application/json;version=2021-04-26'
            ]);

            $response = curl_exec($curlHandle);

            $httpStatusCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);

            curl_close($curlHandle);

            if (curl_errno($curlHandle)) {
                return setErrorResponse("cURL Error: " . curl_error($curlHandle));
            }

            if ($httpStatusCode !== 200) {
                return setErrorResponse("API request failed with status code: {$httpStatusCode}");
            }

            return $response;
        } catch (\Throwable $th) {
            return setErrorResponse('An error occurred sending the request. Error code: XC001');
        }
    }
}
