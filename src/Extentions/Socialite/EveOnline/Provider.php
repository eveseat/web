<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs
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
use Seat\Services\Exceptions\EveImageException;
use Seat\Services\Image\Eve;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

/**
 * Class Provider.
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
     * @return \SocialiteProviders\Manager\OAuth2\User
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
     * @return mixed
     */
    protected function mapUserToObject(array $user)
    {
        $avatar = asset('img/evewho.png');

        try {
            $avatar = (new Eve('characters', 'portrait', $user['CharacterID'], 128))->url(128);
        } catch (EveImageException $e) {
            logger()->error($e->getMessage(), $e->getTrace());
        }

        return (new User)->setRaw($user)->map([
            'id'                   => $user['CharacterID'],
            'name'                 => $user['CharacterName'],
            'nickname'             => $user['CharacterName'],
            'character_owner_hash' => $user['CharacterOwnerHash'],
            'scopes'               => $user['Scopes'],
            'expires_on'           => carbon($user['ExpiresOn']),
            'avatar'               => $avatar,
        ])
        ->setRefreshToken($user['RefreshToken'])
        ->setExpiresIn(carbon($user['ExpiresOn'])->diffInSeconds())
        ->setAccessTokenResponseBody($user);
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
