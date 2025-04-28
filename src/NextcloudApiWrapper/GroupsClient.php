<?php

namespace NextcloudApiWrapper;

use GuzzleHttp\Exception\GuzzleException;

class GroupsClient extends AbstractClient
{
    const string GROUP_PART   = 'v1.php/cloud/groups';

    /**
     * Search for groups
     * @param string $search
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function searchGroups(string $search = ''): NextcloudResponse
    {
        return $this->connection->request(Connection::GET, self::GROUP_PART . $this->buildUriParams([
                'search'    => $search
            ]));
    }

    /**
     * Creates a new group
     * @param string $groupName
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function createGroup(string $groupName): NextcloudResponse
    {
        return $this->connection->submitRequest(Connection::POST, self::GROUP_PART, [
            'groupid'   => $groupName
        ]);
    }

    /**
     * Return a group's members
     * @param string $groupName
     * @return NextcloudResponse
     * @throws GuzzleException
     * @throws NCException
     */
    public function getGroupUsers(string $groupName): NextcloudResponse
    {
        return $this->connection->request(Connection::GET, self::GROUP_PART . '/' . $groupName);
    }

    /**
     * Returna group's sub-admins
     * @param string $groupName
     * @return NextcloudResponse
     * @throws GuzzleException
     * @throws NCException
     */
    public function getGroupSubAdmins(string $groupName): NextcloudResponse
    {
        return $this->connection->request(Connection::GET, self::GROUP_PART . '/' . $groupName . '/subadmins');
    }

    /**
     * Deletes a group
     * @param string $groupName
     * @return NextcloudResponse
     * @throws GuzzleException
     * @throws NCException
     */
    public function deleteGroup(string $groupName): NextcloudResponse
    {
        return $this->connection->request(Connection::DELETE, self::GROUP_PART . '/' . $groupName);
    }
}