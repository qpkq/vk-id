<?php

namespace SocialiteProviders\VKID;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use RuntimeException;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider
{
    /**
     * Unique Provider Identifier.
     */
    public const IDENTIFIER = 'VK_ID';

    /**
     * Auth URL.
     */
    public const AUTH_URL = 'https://id.vk.ru/authorize';

    /**
     * Token URL.
     */
    public const TOKEN_URL = 'https://id.vk.ru/oauth2/auth';

    /**
     * User Info URL.
     */
    public const USER_INFO_URL = 'https://id.vk.ru/oauth2/user_info';

    /**
     * Is Stateless.
     *
     * {@inheritdoc}
     */
    protected $stateless = false;

    /**
     * Use PKCE.
     *
     * {@inheritdoc}
     */
    protected $usesPKCE = true;

    /**
     * Scopes.
     *
     * {@inheritdoc}
     */
    protected $scopes = ['email'];

    /**
     * Get Auth URL.
     *
     * {@inheritdoc}
     */
    protected function getAuthUrl($state): string
    {
        return $this->buildAuthUrlFromBase(self::AUTH_URL, $state);
    }

    /**
     * Get Token URL.
     *
     * {@inheritdoc}
     */
    protected function getTokenUrl(): string
    {
        return self::TOKEN_URL;
    }

    /**
     * Get User by Token.
     *
     * {@inheritdoc}
     *
     * @throws GuzzleException
     */
    protected function getUserByToken($token): mixed
    {
        $response = $this->getHttpClient()->post(self::USER_INFO_URL, [
            RequestOptions::HEADERS => ['Accept' => 'application/json'],
            RequestOptions::FORM_PARAMS => [
                'access_token'  => $token,
                'client_id'     => $this->clientId,
            ],
        ]);

        $contents = (string) $response->getBody();

        $response = json_decode($contents, true);

        if (! is_array($response) || ! isset($response['user'])) {
            throw new RuntimeException(sprintf(
                'Invalid JSON response from VK: %s',
                $contents
            ));
        }

        return $response['user'];
    }

    /**
     * Map the User to Object.
     *
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user): User
    {
        return (new User())->setRaw($user)->map([
            'id'       => Arr::get($user, 'user_id'),
            'name'     => trim(Arr::get($user, 'first_name') . ' ' . Arr::get($user, 'last_name')),
            'email'    => Arr::get($user, 'email'),
            'avatar'   => Arr::get($user, 'avatar'),
        ]);
    }

    /**
     * Get Token Fields.
     *
     * {@inheritdoc}
     */
    protected function getTokenFields($code): array
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
            'device_id'  => $this->getDeviceId(),
        ]);
    }

    /**
     * Get the device_id from the request.
     *
     * @return string
     */
    protected function getDeviceId(): string
    {
        return $this->request->input('device_id');
    }
}
