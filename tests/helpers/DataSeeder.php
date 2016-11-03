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

namespace Seat\Web\Tests\Helpers;

use Seat\Web\Acl\Pillow;
use Seat\Web\Models;
use Seat\Eveapi\Models\Eve;
use Seat\Eveapi\Models\Account;


/**
 * Trait DataSeeder
 * @package Seat\Web\Tests\Helpers
 */
trait DataSeeder 
{
    use Pillow;
    
    /**
    * Seed some basic users. Adds three users
    * - fooUser
    * - barUser
    * - adminUser
    * adminUser is superUser, the others have no roles
    *
    */
    public function seedBasicUsers() 
    {
        $this->fooUser = factory(Models\User::class)->create();
        $this->barUser = factory(Models\User::class)->create();

        $this->adminUser = factory(Models\User::class)->create([
            'name' => 'admin'
        ]);
        
        $this->superuserRole = factory(Models\Acl\Role::class)->create([
            'title' => 'Superuser'
        ]);
        
        $this->giveRolePermission($this->superuserRole['id'], 'superuser');
        $this->giveUserRole($this->adminUser->id,  $this->superuserRole->id);
    }
    
    /**
    * Seed some API keys in the database.
    * seedBasicUsers must be called first.
    */
    public function seedBasicApiKeys()
    {
        $this->barApi = factory(\Seat\Eveapi\Models\Eve\ApiKey::class,5)->create([
            'user_id' => $this->barUser['id'],
        ]);
        
        $this->fooApi = factory(\Seat\Eveapi\Models\Eve\ApiKey::class,2)->create([
            'user_id' => $this->fooUser['id'],
        ]);
    }
    
    /**
    * Seed some characters belonging to the api keys created in 
    * seedBasicApiKeys (which must be called first)
    */
    public function seedCharactersForBasicApiKeys()
    {
        
        $faker = \Faker\Factory::create();
        
        // Create a few corps
        $corpCount = 15;
        $this->fakeCorps = array();
        $this->allToons = array();
        for ($i = 0 ; $i < 15; $i++) {
            $this->fakeCorps[$faker->unique()->randomNumber(8)] = $faker->sentence(5, true);
        }
        
        $this->barToons = array();
        $this->fooToons = array();
        
        $this->barApi->each(function($api){
            $r = $this->addToonsToApi($api);
            $this->barToons = array_merge($this->barToons, $r);
        });
        $this->fooApi->each(function($api){
            $r = $this->addToonsToApi($api);
            $this->fooToons = array_merge($this->fooToons, $r);
        });
    }
    
    /**
    * Seed database with some roles and users with these roles
    * Seeds one user $this->fooToonsUser that can see the same users as $this->fooUser
    * Seeds one user $this->allButBarToonsRole that can see all users except those owned by $this->barUser
    */
    public function seedRolesAndUsers()
    {
        $this->fooToonsRole = factory(Models\Acl\Role::class)->create([
            'title' => 'fooToonsViewer'
        ]);
        $this->giveRolePermissions($this->fooToonsRole['id'], ['character.list', 'corporation.listall']);
        $this->fooToonsUser = factory(Models\User::class)->create();
        $this->giveUserRole($this->fooToonsUser->id,  $this->fooToonsRole->id);
        $characters = array_map(function($t) {return $t['characterID'];}, $this->fooToons);        
        $this->giveRoleCharacterAffiliations($this->fooToonsRole->id, $characters);
        
        $this->allButBarToonsRole = factory(Models\Acl\Role::class, 'inverted')->create([
            'title' => 'allButBarToonsViewer'
        ]);
        $this->giveRolePermissions($this->allButBarToonsRole['id'], ['character.list', 'corporation.listall']);
        $this->allButBarToonsUser = factory(Models\User::class)->create();
        $this->giveUserRole($this->allButBarToonsUser->id,  $this->allButBarToonsRole->id);
        $characters = array_map(function($t) {return $t['characterID'];}, $this->barToons);        
        $this->giveRoleCharacterAffiliations($this->allButBarToonsRole->id, $characters);
        
    }
    
    /**
    * Add 1-3 characters to the database connected to the given api
    * 
    * @param $api
    *
    * @return array
    */
    private function addToonsToApi($api) {
        $c = rand(1,3);
        $keyInfo = factory(\Seat\Eveapi\Models\Account\ApiKeyInfo::class)->create([
           'keyID' => $api['key_id'] 
        ]);
        $results = array();
        for ($i=0; $i < $c; $i++) {
            $corpId = array_rand($this->fakeCorps);
            $corpName = $this->fakeCorps[$corpId];
            $apiCharaInfo = factory(\Seat\Eveapi\Models\Account\ApiKeyInfoCharacters::class)->create([
                'keyID' => $api['key_id'],
                'corporationID' => $corpId,
                'corporationName' => $corpName
            ]);
            $this->allToons[$apiCharaInfo['characterID']] = $apiCharaInfo;
            $results[$apiCharaInfo['characterID']] = $apiCharaInfo;
            $eveCharaInfo = factory(\Seat\Eveapi\Models\Eve\CharacterInfo::class)->create([
                'characterID' => $apiCharaInfo['characterID'],
                'characterName' => $apiCharaInfo['characterName'],
                'corporationID' => $apiCharaInfo['corporationID'],
                'corporation' => $apiCharaInfo['corporationName'],
            ]);
            
        }
        return $results;
    }
    
}