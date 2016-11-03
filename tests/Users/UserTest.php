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

namespace Seat\Web\Tests\Users;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Seat\Web\Models;


class UserTest extends \Seat\Web\Tests\TestCase
{
    public function testAddingOneUser()
    {
        $user = factory(Models\User::class)->create();        
        $users = Models\User::get();        
        $this->assertTrue($users->count() == 1);
        
        
    }
    
    public function testVisitPageWithoutLoggingIn()
    {
        $this->visit('/home')->see('Welcome, please enter your credentials.');
    }   
    
    public function testVisitPageWithLoggingIn() 
    {
        $user = factory(Models\User::class)->create();        
        $this->actingAs($user)
             -> visit('/home')
             ->see('The Home Page')
             ->see($user['Name']);            
    }
}