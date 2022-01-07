<?php

namespace Seat\Web\Http\DataTables\Exports;

use Yajra\DataTables\Exports\DataTablesCollectionExport;
use Seat\Eveapi\Models\Corporation\CorporationMemberTracking;

class MembersTrackingDataTableExport extends DataTablesCollectionExport
{
    public function collection()
    {
        $collection = $this->collection->map(function ($serialized_character){
            //for some reason, the model gets serialized again
            $character = CorporationMemberTracking::where("character_id",$serialized_character["character_id"])->with('character', 'refresh_token', 'ship')->first();

            return [
                "token" => self::isValidToken($character->refresh_token),
                "name" => $character->character->name,
                "location" => $character->location->name,
                "ship"=>$character->ship->typeName,
                "joined" => $character->start_date,
                "last_login" => $character->logon_date
            ];
        });
        return $collection;
    }

    public function headings(): array{
        return [
            "token",
            "name",
            "location",
            "ship",
            "joined",
            "last_login"
        ];
    }

    private static function isValidToken($token){
        if ($token==null){
            return "invalid";
        }
        return "valid";
    }
}