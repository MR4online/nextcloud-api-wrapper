<?php

namespace NextcloudApiWrapper;

class Wrapper
{
    /**
     * @var Connection
     */
    protected Connection $connection;

    /**
     * @var AbstractClient
     */
    protected $clients  = [];

    private function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public static function build($baseUri, $username, $password): Wrapper
    {

        $connection = new Connection($baseUri, $username, $password);
        return new Wrapper($connection);
    }

    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {

        return $this->connection;
    }

    /**
     * @return AppsClient
     */
    public function getAppsClient(): AppsClient
    {

        return $this->getClient(AppsClient::class);
    }

    /**
     * @return FederatedCloudSharesClient
     */
    public function getFederatedCloudSharesClient(): FederatedCloudSharesClient
    {

        return $this->getClient(FederatedCloudSharesClient::class);
    }

    /**
     * @return GroupsClient
     */
    public function getGroupsClient(): GroupsClient
    {

        return $this->getClient(GroupsClient::class);
    }

    /**
     * @return SharesClient
     */
    public function getSharesClient(): SharesClient
    {

        return $this->getClient(SharesClient::class);
    }

    /**
     * @return UsersClient
     */
    public function getUsersClient(): UsersClient
    {

        return $this->getClient(UsersClient::class);
    }

    /**
     * @param $class
     * @return mixed
     */
    protected function getClient($class): mixed
    {

        if(!isset($this->clients[$class]))
            $this->clients[$class] = new $class($this->connection);

        return $this->clients[$class];
    }
}