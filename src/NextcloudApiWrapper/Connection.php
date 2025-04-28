<?php

namespace NextcloudApiWrapper;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Client;

class Connection
{
    const string GET    = 'GET';
    const string POST   = 'POST';
    const string PUT    = 'PUT';
    const string DELETE = 'DELETE';

    protected Client $guzzle;
    protected string $username;
    protected string $password;

    /**
     * @param string $basePath  The base path to the nextcloud api, IE. 'http://nextcloud.mydomain.com/ocs/'
     * @param string $username  The username of the user performing api calls
     * @param string $password  The password of the user performing api calls
     */
    public function __construct(string $basePath, string $username, string $password)
    {
        $this->guzzle   = new Client(['base_uri' => $basePath]);
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Performs a simple request
     * @param string $verb
     * @param string $path
     * @param null|array $params
     * @return NextcloudResponse
     * @throws GuzzleException
     * @throws NCException
     */
    public function request(string $verb, string $path, ?array $params = null): NextcloudResponse
    {

        $params     = $params === null ? $this->getBaseRequestParams() : $params;
        $response   = $this->guzzle->request($verb, $path, $params);

        return new NextcloudResponse($response);
    }

    /**
     * Performs a request adding the application/x-www-form-urlencoded header
     * @param $verb
     * @param $path
     * @param array $params
     * @return NextcloudResponse
     */
    public function pushDataRequest($verb, $path, array $params = []): NextcloudResponse
    {

        $params = empty($params) ? $this->getBaseRequestParams() : $params;
        $params['headers']['Content-Type'] = 'application/x-www-form-urlencoded';

        return $this->request($verb, $path, $params);
    }

    /**
     * Performs a request sending form data.
     * Required header automatically added by CURl
     * @param $verb
     * @param $path
     * @param array $formParams
     * @return NextcloudResponse
     */
    public function submitRequest($verb, $path, array $formParams): NextcloudResponse
    {

        return $this->request($verb, $path, array_merge($this->getBaseRequestParams(), [
            RequestOptions::FORM_PARAMS => $formParams
        ]));
    }

    /**
     * Returns the base request parameters required by nextcloud to
     * answer api calls
     * @return array
     */
    protected function getBaseRequestParams(): array {

        return [
            RequestOptions::HEADERS => [
                'OCS-APIRequest'    => 'true'
            ],

            RequestOptions::AUTH    => [
                $this->username,
                $this->password,
                'basic'
            ]
        ];
    }
}