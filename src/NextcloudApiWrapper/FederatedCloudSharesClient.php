<?php

namespace NextcloudApiWrapper;

use GuzzleHttp\Exception\GuzzleException;

class FederatedCloudSharesClient extends AbstractClient
{
    const string FCS_PART  = 'v2.php/apps/files_sharing/api/v1/remote_shares';

    /**
     * Get all federated cloud shares the user has accepted
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function listAcceptedCloudShares(): NextcloudResponse
    {
        return $this->connection->request(Connection::GET, self::FCS_PART);
    }

    /**
     * Get information about a given received federated cloud that was sent from a remote instance
     * @param $shareId
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function getCloudShareInformation($shareId): NextcloudResponse
    {
        return $this->connection->request(Connection::GET, self::FCS_PART . '/' . $shareId);
    }

    /**
     * Locally delete a received federated cloud share that was sent from a remote instance
     * @param $shareId
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function deleteCloudShare($shareId): NextcloudResponse
    {
        return $this->connection->request(Connection::DELETE, self::FCS_PART . '/' . $shareId);
    }

    /**
     * Get all pending federated cloud shares the user has received
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function listPendingCloudShares(): NextcloudResponse
    {
        return $this->connection->request(Connection::GET, self::FCS_PART . '/pending');
    }

    /**
     * Locally accept a received federated cloud share that was sent from a remote instance
     * @param string $shareId
     * @return NextcloudResponse
     * @throws GuzzleException
     * @throws NCException
     */
    public function acceptPendingCloudShare(string $shareId): NextcloudResponse
    {
        return $this->connection->request(Connection::POST, self::FCS_PART . '/pending/' . $shareId);
    }

    /**
     * Locally decline a received federated cloud share that was sent from a remote instance
     * @param string $shareId
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function declinePendingCloudShare(string $shareId): NextcloudResponse
    {
        return $this->connection->request(Connection::DELETE, self::FCS_PART . '/pending/' . $shareId);
    }
}