<?php

namespace NextcloudApiWrapper;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsersClient extends AbstractClient
{
    const string USER_PART   = 'v1.php/cloud/users';

    /**
     * Adds a user.
     * @param string $username
     * @param string $password
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function addUser(string $username, string $password): NextcloudResponse
    {
        return $this->connection->submitRequest(Connection::POST, self::USER_PART, [
            'userid'    => $username,
            'password'  => $password
        ]);
    }

    /**
     * Gets a list of users.
     * @param array $params
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function getUsers(array $params = []): NextcloudResponse
    {
        $params = $this->resolve($params, function(OptionsResolver $resolver) {
            $resolver->setDefaults([
                'search',
                'limit',
                'offset'
            ]);
        });

        return $this->connection->request(Connection::GET, self::USER_PART . $this->buildUriParams($params));
    }

    /**
     * Gets data about a given user
     * @param string $username
     * @return NextcloudResponse
     * @throws GuzzleException
     * @throws NCException
     */
    public function getUser(string $username): NextcloudResponse
    {
        return $this->connection->request(Connection::GET, self::USER_PART . '/' . $username);
    }

    /**
     * Updates a user, sets the value identified by key to value
     * @param string $username
     * @param $key
     * @param $value
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function editUser(string $username, $key, $value): NextcloudResponse
    {
        $this->inArray($key, [
            'email',
            'quota',
            'displayname',
            'phone',
            'address',
            'website',
            'twitter',
            'password'
        ]);

        return $this->connection->submitRequest(Connection::PUT, self::USER_PART . '/' . $username, [
            'key'   => $key,
            'value' => $value
        ]);
    }

    /**
     * Disables a user
     * @param $username
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function disableUser($username): NextcloudResponse
    {
        return $this->connection->pushDataRequest(Connection::PUT, self::USER_PART . '/' . $username . '/disable');
    }

    /**
     * Enables a user
     * @param $username
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function enableUser($username): NextcloudResponse
    {
        return $this->connection->pushDataRequest(Connection::PUT, self::USER_PART . '/' . $username . '/enable');
    }

    /**
     * Deletes a user
     * @param $username
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function deleteUser($username): NextcloudResponse
    {
        return $this->connection->request(Connection::DELETE, self::USER_PART . '/' . $username);
    }

    /**
     * Returns user's groups
     * @param $username
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function getUserGroups($username): NextcloudResponse
    {
        return $this->connection->request(Connection::GET, self::USER_PART . '/' . $username . '/groups');
    }

    /**
     * Adds a user to a group
     * @param $username
     * @param $groupname
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function addUserToGroup($username, $groupname): NextcloudResponse
    {
        return $this->connection->submitRequest(Connection::POST, self::USER_PART . '/' . $username . '/groups', [
            'groupid'   => $groupname
        ]);
    }

    /**
     * Removes a user from a group
     * @param $username
     * @param $groupname
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function removeUserFromGroup($username, $groupname): NextcloudResponse
    {
        return $this->connection->submitRequest(Connection::DELETE, self::USER_PART . '/' . $username . '/groups', [
            'groupid'   => $groupname
        ]);
    }

    /**
     * Makes a user a sub-admin of a group
     * @param $username
     * @param $groupname
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function promoteUserSubAdminOfGroup($username, $groupname): NextcloudResponse
    {
        return $this->connection->submitRequest(Connection::POST, self::USER_PART . '/' . $username . '/subadmins', [
            'groupid'   => $groupname
        ]);
    }

    /**
     * Demotes a user sub-admin group
     * @param $username
     * @param $groupname
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function demoteUserSubAdminOfGroup($username, $groupname): NextcloudResponse
    {
        return $this->connection->submitRequest(Connection::DELETE, self::USER_PART . '/' . $username . '/subadmins', [
            'groupid'   => $groupname
        ]);
    }

    /**
     * Returns all groups in which this user is sub-admin
     * @param $username
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function getUserSubAdminGroups($username): NextcloudResponse
    {
        return $this->connection->request(Connection::GET, self::USER_PART . '/' . $username . '/subadmins');
    }

    /**
     * Resend the welcome mail
     * @param $username
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function resendWelcomeEmail($username): NextcloudResponse
    {
        return $this->connection->request(Connection::POST, self::USER_PART . '/' . $username . '/welcome');
    }

}