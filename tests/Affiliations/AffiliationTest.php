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

use Seat\Web\Models;
use Seat\Web\Models\Acl\Role;
use Seat\Web\Models\User;

use Seat\Eveapi\Models\Eve;
use Seat\Web\Tests\Helpers\DataSeeder;


class AffiliationTest extends \Seat\Web\Tests\TestCase
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
        $this->seedCharactersForBasicApiKeys();
    }
    
    /**
    * Test that admin user can see all characters
    */
    public function testAdminUserSeesAllCharacters() 
    {
        $this->actingAs($this->adminUser)
             ->visit('/character/list')
             ->assertViewHas('characters');
        $toons = $this->response->original->getData()['characters'];
        $this->assertCount(count($this->allToons), $toons, "Superuser should see all characters");
        
    }
    
    /**
    * Test that a user only sees characters he owns
    */
    public function testUserSeeKeysOwned() 
    {
        $this->actingAs($this->fooUser)
             ->visit('/character/list')//->dump()
             ->assertViewHas('characters');
        $toons = $this->response->original->getData()['characters'];
        $this->assertCount(count($this->fooToons), $toons, "User should see own characters (only)");
    }
    
    /**
    * Test that a user sees affilliated characters
    */
    public function testUserSeesAffilliatedCharacters()
    {
        $this->seedRolesAndUsers();
        $this->actingAs($this->fooToonsUser)
             ->visit('/character/list')//->dump()
             ->assertViewHas('characters');
        $toons = $this->response->original->getData()['characters'];
        $this->assertCount(count($this->fooToons), $toons, "User should see the characters owned by the foo user");
    }
    
    /**
    * Test that a user sees all but affilliated characters when role is inverted
    */
    public function testUserDoesntSeeAffilliatedCharacters()
    {
        $this->seedRolesAndUsers();
        $this->actingAs($this->allButBarToonsUser)
             ->visit('/character/list')//->dump()
             ->assertViewHas('characters');
        $toons = $this->response->original->getData()['characters'];
        $this->assertCount(count($this->allToons) - count($this->barToons), $toons, "User should see the characters owned by the foo user");
    }
}