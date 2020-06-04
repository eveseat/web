<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

namespace Seat\Web\Extentions;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

/**
 * Class EveOnlineProvider.
 * @package Seat\Web\Extentions
 */
class EveOnlineProvider extends AbstractProvider implements ProviderInterface
{

    /**
     * Base URL to the Eve Online Image Server.
     *
     * @var string
     */
    protected $imageUrl = 'https://image.eveonline.com/Character/';

    /**
     * The separating character for the requested scopes.
     *
     * @var string
     */
    protected $scopeSeparator = ' ';

    /**
     * Get the User instance for the authenticated user.
     *
     * @return \Laravel\Socialite\Contracts\User
     */
    public function user()
    {

        if ($this->hasInvalidState())
            throw new InvalidStateException;

        $tokens = $this->getAccessTokenResponse($this->getCode());

        $user = $this->mapUserToObject(
            array_merge(
                $this->getUserByToken($tokens['access_token']), [
                    'RefreshToken' => $tokens['refresh_token'],
                ]
            )
        );

        return $user->setToken($tokens['access_token']);
    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param  array $user
     *
     * @return \Laravel\Socialite\Two\User
     */
    protected function mapUserToObject(array $user): User
    {

        return (new User)->setRaw($user)->map([
            'character_id'         => $user['CharacterID'],
            'name'                 => $user['CharacterName'],
            'character_owner_hash' => $user['CharacterOwnerHash'],
            'scopes'               => $user['Scopes'],
            'refresh_token'        => $user['RefreshToken'],
            'expires_on'           => Carbon($user['ExpiresOn']),
            'avatar'               => $this->imageUrl . $user['CharacterID'] . '_128.jpg',
        ]);
    }

    /**
     * Get the raw user for the given access token.
     *
     * @param  string $token
     *
     * @return array
     */
    protected function getUserByToken($token)
    {

        $response = $this->getHttpClient()
            ->get('https://login.eveonline.com/oauth/verify', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Get the authentication URL for the provider.
     *
     * @param  string $state
     *
     * @return string
     */
    protected function getAuthUrl($state)
    {

        return $this->buildAuthUrlFromBase(
            'https://login.eveonline.com/oauth/authorize', $state);
    }

    /**
     * Get the POST fields for the token request.
     *
     * @param  string $code
     *
     * @return array
     */
    protected function getTokenFields($code)
    {

        return array_add(
            parent::getTokenFields($code), 'grant_type', 'authorization_code');
    }

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl()
    {

        return 'https://login.eveonline.com/oauth/token';
    }

    /**
     * Get the access tokens from the token response body.
     *
     * @param  string $body
     *
     * @return array
     */
    protected function parseAccessToken($body): array
    {

        $jsonResponse = json_decode($body, true);

        return [
            'access_token'  => $jsonResponse['access_token'],
            'refresh_token' => $jsonResponse['refresh_token'],
        ];
    }
}
