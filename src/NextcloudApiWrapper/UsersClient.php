<?php

namespace NextcloudApiWrapper;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsersClient extends AbstractClient
{
    const string USER_PART        = 'v1.php/cloud/users';
    const string USER_FIELDS_PART = 'v1.php/cloud/user/fields';

    /**
     * Adds a user.
     * @param string $userName
     * @param array $options
     * @return NextcloudResponse
     * @throws GuzzleException
     * @throws NCException
     */
    public function addUser(string $userName, array $options): NextcloudResponse
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'userid'    => $userName,
        ]);
        $resolver->setRequired(['userid']);
        $resolver->setDefined(['email', 'password' ,'displayName', 'quota', 'language']);
        $params = $resolver->resolve($options);

        return $this->connection->submitRequest(Connection::POST, self::USER_PART, $params);
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
     * @param string $userName
     * @return NextcloudResponse
     * @throws GuzzleException
     * @throws NCException
     */
    public function getUser(string $userName): NextcloudResponse
    {
        return $this->connection->request(Connection::GET, self::USER_PART . '/' . $userName);
    }

    /**
     * Updates a user, sets the value identified by key to value
     * @param string $userName
     * @param $key
     * @param $value
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function editUser(string $userName, $key, $value): NextcloudResponse
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

        return $this->connection->submitRequest(Connection::PUT, self::USER_PART . '/' . $userName, [
            'key'   => $key,
            'value' => $value
        ]);
    }

    /**
     * Disables a user
     * @param string $userName
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function disableUser(string $userName): NextcloudResponse
    {
        return $this->connection->pushDataRequest(Connection::PUT, self::USER_PART . '/' . $userName . '/disable');
    }

    /**
     * Enables a user
     * @param string $userName
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function enableUser(string $userName): NextcloudResponse
    {
        return $this->connection->pushDataRequest(Connection::PUT, self::USER_PART . '/' . $userName . '/enable');
    }

    /**
     * Deletes a user
     * @param string $userName
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function deleteUser(string $userName): NextcloudResponse
    {
        return $this->connection->request(Connection::DELETE, self::USER_PART . '/' . $userName);
    }

    /**
     * Returns user's groups
     * @param string $userName
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function getUserGroups(string $userName): NextcloudResponse
    {
        return $this->connection->request(Connection::GET, self::USER_PART . '/' . $userName . '/groups');
    }

    /**
     * Adds a user to a group
     * @param string $userName
     * @param string $groupName
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function addUserToGroup(string $userName, string $groupName): NextcloudResponse
    {
        return $this->connection->submitRequest(Connection::POST, self::USER_PART . '/' . $userName . '/groups', [
            'groupid'   => $groupName
        ]);
    }

    /**
     * Removes a user from a group
     * @param string $userName
     * @param string $groupName
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function removeUserFromGroup(string $userName, string $groupName): NextcloudResponse
    {
        return $this->connection->submitRequest(Connection::DELETE, self::USER_PART . '/' . $userName . '/groups', [
            'groupid'   => $groupName
        ]);
    }

    /**
     * Makes a user a sub-admin of a group
     * @param string $userName
     * @param string $groupName
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function promoteUserSubAdminOfGroup(string $userName, string $groupName): NextcloudResponse
    {
        return $this->connection->submitRequest(Connection::POST, self::USER_PART . '/' . $userName . '/subadmins', [
            'groupid'   => $groupName
        ]);
    }

    /**
     * Demotes a user sub-admin group
     * @param string $userName
     * @param string $groupName
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function demoteUserSubAdminOfGroup(string $userName, string $groupName): NextcloudResponse
    {
        return $this->connection->submitRequest(Connection::DELETE, self::USER_PART . '/' . $userName . '/subadmins', [
            'groupid'   => $groupName
        ]);
    }

    /**
     * Returns all groups in which this user is sub-admin
     * @param string $userName
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function getUserSubAdminGroups(string $userName): NextcloudResponse
    {
        return $this->connection->request(Connection::GET, self::USER_PART . '/' . $userName . '/subadmins');
    }

    /**
     * Resend the welcome mail
     * @param string $userName
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function resendWelcomeEmail(string $userName): NextcloudResponse
    {
        return $this->connection->request(Connection::POST, self::USER_PART . '/' . $userName . '/welcome');
    }

    /**
     * Gets a list of editable user fields.
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function getUserFields(): NextcloudResponse
    {
        return $this->connection->request(Connection::GET, self::USER_FIELDS_PART);
    }

}