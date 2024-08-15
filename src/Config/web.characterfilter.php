<?php

return [
    ['name' => 'scopes', 'src' => 'seatcore::fastlookup.scopes', 'path' => 'refresh_token', 'field' => 'scopes', 'label' => 'Scopes'],
    ['name' => 'character', 'src' => 'seatcore::fastlookup.characters', 'path' => '', 'field' => 'character_id', 'label' => 'Character'],
    ['name' => 'title', 'src' => 'seatcore::fastlookup.titles', 'path' => 'titles', 'field' => 'id', 'label' => 'Title'],
    ['name' => 'corporation', 'src' => 'seatcore::fastlookup.corporations', 'path' => 'affiliation', 'field' => 'corporation_id', 'label' => 'Corporation'],
    ['name' => 'alliance', 'src' => 'seatcore::fastlookup.alliances', 'path' => 'affiliation', 'field' => 'alliance_id', 'label' => 'Alliance'],
    ['name' => 'skill', 'src' => 'seatcore::fastlookup.skills', 'path' => 'skills', 'field' => 'skill_id', 'label' => 'Skill'],
    ['name' => 'skill_level', 'src' => [['id' => 1, 'text' => 'Level 1'], ['id' => 2, 'text' => 'Level 2'], ['id' => 3, 'text' => 'Level 3'], ['id' => 4, 'text' => 'Level 4'], ['id' => 5, 'text' => 'Level 5']], 'path' => 'skills', 'field' => 'trained_skill_level', 'label' => 'Skill Level'],
    ['name' => 'type', 'src' => 'seatcore::fastlookup.items', 'path' => 'assets', 'field' => 'type_id', 'label' => 'Item'],
    ['name' => 'role', 'src' => 'seatcore::fastlookup.roles', 'path' => 'corporation_roles', 'field' => 'role', 'label' => 'Role'],
];