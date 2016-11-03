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

namespace Seat\Web\Tests\Affiliations;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Seat\Web\Tests\Helpers\DataSeeder;
use Seat\Web\Models;

class KeyOwnershipTest extends \Seat\Web\Tests\TestCase
{
    use DataSeeder;
    
    /**
    * Setup the test environment.
    */
    public function setUp()
    {
        parent::setUp();
        $this->seedBasicUsers();
        $this->seedBasicApiKeys();
    }
    
    /**
    * Test that a new user don't see any keys
    */
    public function testUserDontSeeKeysNotOwned() {
        $user = factory(Models\User::class)->create();
        $this->actingAs($user)
             ->assertKeyCount(0, 'No keys should be visible to this user');
    }
    
    /**
    * Test that the number of keys seen is correct
    */
    public function testUserSeeKeysOwned() {
        $this->actingAs($this->fooUser)
             ->assertKeyCount(2, 'User should see two key');
        $this->actingAs($this->barUser)
             ->assertKeyCount(5, 'User should see five key');
        
    }
    
    /**
    * Test that an admin user can see all keys 
    */
    public function testAdminUserSeeAllKeys() {
        $this->actingAs($this->adminUser)
             ->assertKeyCount(7, 'Admin should see all keys');
    }
    
    /**
    * Assertion that we see the right number of keys
    * 
    * @param $expected
    * @param $message
    *
    * @return mixed
    */
    protected function assertKeyCount($expected, $message) {
        $this->visit('/api-key/list')
             ->assertViewHas('keys');
        $keys = $this->response->original->getData()['keys'];
        $this->assertCount($expected, $keys, $message);     
        return $this;
    }
}