<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to present Leon Jacobs
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

return [
    ['name' => 'scopes', 'src' => 'seatcore::fastlookup.scopes', 'path' => 'refresh_token', 'field' => 'scopes', 'label' => 'Scopes'],
    ['name' => 'character', 'src' => 'seatcore::fastlookup.characters', 'path' => '', 'field' => 'character_id', 'label' => 'Character'],
    ['name' => 'title', 'src' => 'seatcore::fastlookup.titles', 'path' => 'titles', 'field' => 'id', 'label' => 'Title'],
    ['name' => 'corporation', 'src' => 'seatcore::fastlookup.corporations', 'path' => 'affiliation', 'field' => 'corporation_id', 'label' => 'Corporation'],
    ['name' => 'factions', 'src' => 'seatcore::fastlookup.factions', 'path' => 'affiliation', 'field' => 'corporation_id', 'label' => 'Faction'],
    ['name' => 'alliance', 'src' => 'seatcore::fastlookup.alliances', 'path' => 'affiliation', 'field' => 'alliance_id', 'label' => 'Alliance'],
    ['name' => 'skill', 'src' => 'seatcore::fastlookup.skills', 'path' => 'skills', 'field' => 'skill_id', 'label' => 'Skill'],
    ['name' => 'skill_level', 'src' => [['id' => 1, 'text' => 'Level 1'], ['id' => 2, 'text' => 'Level 2'], ['id' => 3, 'text' => 'Level 3'], ['id' => 4, 'text' => 'Level 4'], ['id' => 5, 'text' => 'Level 5']], 'path' => 'skills', 'field' => 'trained_skill_level', 'label' => 'Skill Level'],
    ['name' => 'type', 'src' => 'seatcore::fastlookup.items', 'path' => 'assets', 'field' => 'type_id', 'label' => 'Item'],
    ['name' => 'role', 'src' => 'seatcore::fastlookup.roles', 'path' => 'corporation_roles', 'field' => 'role', 'label' => 'Role'],
];
