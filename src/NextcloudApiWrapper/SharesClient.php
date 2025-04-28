<?php

namespace NextcloudApiWrapper;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SharesClient extends AbstractClient
{
    const string SHARE_PART    = 'v2.php/apps/files_sharing/api/v1/shares';

    /**
     * Get all shares from the user
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function getAllShares(): NextcloudResponse
    {
        return $this->connection->request(Connection::GET, self::SHARE_PART);
    }

    /**
     * Get all shares from a given file/folder
     * @param string $path
     * @param array $params , can have keys 'reshares' (bool), 'subfiles' (bool)
     * @return NextcloudResponse
     * @throws GuzzleException
     * @throws NCException
     */
    public function getSharesFromFileOrFolder(string $path, array $params): NextcloudResponse
    {
        $params = $this->resolve($params, function(OptionsResolver $resolver) {
            $resolver->setDefaults([
                'reshares',
                'subfiles'
            ]);
        });

        $params = array_merge($params, ['path' => $path]);

        return $this->connection->request(Connection::GET, self::SHARE_PART . '/' . $this->buildUriParams($params));
    }

    /**
     * Get information about a given share
     * @param string $shareId
     * @return NextcloudResponse
     * @throws GuzzleException
     * @throws NCException
     */
    public function getShareInformation(string $shareId): NextcloudResponse
    {
        return $this->connection->request(Connection::GET, self::SHARE_PART . '/' . $shareId);
    }

    /**
     * Share a file/folder with a user/group or as a public link
     * @param array $params
     * @return NextcloudResponse
     * @throws NCException
     * @throws GuzzleException
     */
    public function createShare(array $params): NextcloudResponse
    {
        $params = $this->resolve($params, function(OptionsResolver $resolver) {
            $resolver->setRequired([
                'path',
                'shareType',
                'shareWith'
            ])->setDefaults([
                'publicUpload'  => null,
                'password'      => null,
                'permissions'   => null
            ]);
        });

        return $this->connection->submitRequest(Connection::POST, self::SHARE_PART, $params);
    }

    /**
     * Remove the given share
     * @param string $shareId
     * @return NextcloudResponse
     * @throws GuzzleException
     * @throws NCException
     */
    public function deleteShare(string $shareId): NextcloudResponse
    {
        return $this->connection->request(Connection::DELETE, self::SHARE_PART . '/' . $shareId);
    }

    /**
     * Update a given share. Only one value can be updated per request
     * @param string $shareId
     * @param string $key
     * @param $value
     * @return NextcloudResponse
     * @throws GuzzleException
     * @throws NCException
     */
    public function updateShare(string $shareId, string $key, $value): NextcloudResponse
    {
        $this->inArray($key, ['permissions', 'password', 'publicUpload', 'expireDate']);

        return $this->connection->pushDataRequest(Connection::PUT, self::SHARE_PART . '/' . $shareId, [
            $key => $value
        ]);
    }
}