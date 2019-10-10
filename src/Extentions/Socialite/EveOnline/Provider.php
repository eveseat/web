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

namespace Seat\Web\Extentions\Socialite\EveOnline;

use Illuminate\Support\Arr;
use Laravel\Socialite\Two\InvalidStateException;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

/**
 * Class Provider
 *
 * @package Seat\Web\Extentions\Socialite\EveOnline
 */
class Provider extends AbstractProvider
{
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
     * Get the authentication URL for the provider.
     *
     * @param string $state
     * @return string
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://login.eveonline.com/oauth/authorize', $state);
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
     * Get the raw user for the given access token.
     *
     * @param string $token
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
     * Map the raw user array to a Socialite User instance.
     *
     * @param array $user
     * @return \Laravel\Socialite\Two\User
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'character_id'         => $user['CharacterID'],
            'name'                 => $user['CharacterName'],
            'character_owner_hash' => $user['CharacterOwnerHash'],
            'scopes'               => $user['Scopes'],
            'refresh_token'        => $user['RefreshToken'],
            'expires_on'           => Carbon($user['ExpiresOn']),
            'avatar'               => sprintf('https://image.eveonline.com/Character/%d_128.jpg', $user['CharacterID']),
        ]);
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
        return Arr::add(parent::getTokenFields($code), 'grant_type', 'authorization_code');
    }
}