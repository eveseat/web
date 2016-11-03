<?php
/*
This file is part of SeAT

Original SeAT Copyright (C) 2015, 2016  Leon Jacobs
This file Copyright (C) 2016 Tor Livar Flugsrud

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use Seat\Web\Models;
use Seat\Eveapi\Models\Eve;

$factory->define(Seat\Web\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Seat\Eveapi\Models\Eve\ApiKey::class, function (Faker\Generator $faker) {
    return [
        'key_id' => $faker->unique()->randomNumber(7),  // random key_id
        'v_code' => $faker->regexify('[A-Za-z0-9]{64}'),      // random vKey
    ];
});

$factory->define(Seat\Web\Models\Acl\Role::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->word,
        'invertedAffiliations' => false
    ];
});

$factory->defineAs(Seat\Web\Models\Acl\Role::class, 'inverted', function (Faker\Generator $faker) {
    return [
        'title' => $faker->word,
        'invertedAffiliations' => true
    ];
});

$factory->define(Seat\Web\Models\Acl\RoleUser::class, function (Faker\Generator $faker) {
    return [
        
    ];
});

$factory->define(Seat\Eveapi\Models\Account\ApiKeyInfoCharacters::class, function (Faker\Generator $faker) {
   return [
        'characterID' => $faker->unique()->randomNumber(8),
        'characterName' => $faker->name,
   ];
   // 'keyID', 'characterID', 'characterName', 'corporationID', 'corporationName'];
});

$factory->define(Seat\Eveapi\Models\Account\ApiKeyInfo::class, function (Faker\Generator $faker) {
    return [
        'accessMask' => 8388608, 
        'type' => 'Account', 
        'expires' => $faker->dateTimeBetween($startDate = '+1 week', $endDate = '+1 year')
    ];
    // ['keyID', 'accessMask', 'type', 'expires'];
    
});

$factory->define(Seat\Eveapi\Models\Eve\CharacterInfo::class, function (Faker\Generator $faker) {
    return [
        'race' => $faker->randomElement(['Minmatar', 'Amarr', 'Caldari', 'Gallente']),
        'bloodline' => $faker->randomElement(['Vherokior','Sebiestor','Deteis','Gallente','Khanid','Amarr','Intaki','Jin-Mei','Ni-Kunni','Achura','Brutor','Civire']),
        'bloodlineID' => $faker->numberBetween(1,50),
        'ancestry' => $faker->randomElement(['Retailers','Tinkerers','Scientists','Immigrants','Cyber Knights','Liberal Holders','Activists','Reborn','Sang Do Caste','Navy Veterans','Mystics','Inventors','Free Merchants','Traders','Tribal Traditionalists','Stargazers','Artists','Tube Child','Rebels',                                    'Diplomats','Border Runners','Merchandisers','Entrepreneurs','Miners','Dissenters','Mercs','Religious Reclaimers','Wealthy Commoners','Jing Ko Caste','Monks','Drifters','Zealots','Slave Child','Saan Go Caste','Unionists','Workers']),
        'ancestryID' => $faker->numberBetween(1,50),
        'corporationDate' => $faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now'),
        'securityStatus' => $faker->randomFloat(NULL, -5,5)

        
    ];
    /*
        'characterID', 'characterName', 'race', 'bloodline', 'bloodlineID',
        'ancestry', 'ancestryID', 'corporationID', 'corporation', 'corporationDate',
        'securityStatus',

        // Nullable values
        'accountBalance', 'skillPoints', 'nextTrainingEnds', 'shipName', 'shipTypeID',
        'shipTypeName', 'allianceID', 'alliance', 'allianceDate', 'lastKnownLocation'
    */
});