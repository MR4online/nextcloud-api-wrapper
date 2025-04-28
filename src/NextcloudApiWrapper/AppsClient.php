<?php

namespace NextcloudApiWrapper;

use GuzzleHttp\Exception\GuzzleException;

class AppsClient extends AbstractClient
{
    const string APP_PART  = 'v1.php/cloud/apps';

    /**
     * Gets a list of apps
     * @param null $filter can be either 'enabled' or 'disabled' to filter apps
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function getApps($filter = null): NextcloudResponse
    {
        return $this->connection->request(Connection::GET, self::APP_PART . $this->buildUriParams([
                'filter'    => $filter
            ]));
    }

    /**
     * Returns infos for an app
     * @param string $appName
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function getAppInfo(string $appName): NextcloudResponse
    {
        return $this->connection->request(Connection::GET, self::APP_PART . '/' . $appName);
    }

    /**
     * Enables an app
     * @param string $appName
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function enableApp(string $appName): NextcloudResponse
    {
        return $this->connection->request(Connection::POST, self::APP_PART . '/' . $appName);
    }

    /**
     * Disables an app
     * @param string $appName
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function disableApp(string $appName): NextcloudResponse
    {
        return $this->connection->request(Connection::DELETE, self::APP_PART . '/' . $appName);
    }

}