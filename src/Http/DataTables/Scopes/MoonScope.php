<?php
namespace Seat\Web\Http\DataTables\Scopes;

use Illuminate\Support\Facades\Log;
use Seat\Eveapi\Models\Universe\UniverseMoonContent;
use Yajra\DataTables\Contracts\DataTableScope;

/**
 * Class MoonScope
 * 
 * A specific scope for the moon reporter DataTable.
 * It will limit the returned data based on region, constellation, solar system,
 * moon contents, and sovereignty.
 * 
 * TODO:
 * - Multiple selections for region, contellation, system?
 */
class MoonScope implements DataTableScope {

    const UBIQUITOUS    = 2396;
    const COMMON        = 2397;
    const UNCOMMON      = 2398;
    const RARE          = 2400;
    const EXCEPTIONAL   = 2401;

    public function __construct (
        string $regionID,
        string $constellationID,
        string $systemID,
        array $moonContents,
        string $moonInclusive) {
        $this->region_id = $regionID;
        $this->constellation_id = $constellationID;
        $this->system_id = $systemID;
        $this->moon_contents = $moonContents;
        $this->moon_inclusive = $moonInclusive;
    }

    /**
     * Apply a query scope
     * 
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     **/
    public function apply ($query) {
        if (!empty($this->region_id)) $query->where('regionID', $this->region_id);
        if (!empty($this->constellation_id)) $query->where('constellationID', $this->constellation_id);
        if (!empty($this->system_id)) $query->where('solarSystemID', $this->system_id);
        if (!empty($this->moon_contents)) {
            $query->whereHas('moon_contents', function ($content) {
                foreach ($this->moon_contents as $oreType) {
                    switch ($oreType) {
                        case 'ubiquitous' : {
                            $query->whereHas('type', function ($type) {
                                $type->where('marketGroupID', self::UBIQUITOUS);
                            });
                            break;
                        }
                        case 'common' : {
                            $query->whereHas('type', function ($type) {
                                $type->where('marketGroupID', self::COMMON);
                            });
                            break;
                        }
                        case 'uncommon' : {
                            $query->whereHas('type', function ($type) {
                                $type->where('marketGroupID', self::UNCOMMON);
                            });
                            break;
                        }
                        case 'rare' : {
                            $query->whereHas('type', function ($type) {
                                $type->where('marketGroupID', self::RARE);
                            });
                            break;
                        }
                        case 'exceptional' : {
                            $query->whereHas('type', function ($type) {
                                $type->where('marketGroupID', self::EXCEPTIONAL);
                            });
                            break;
                        }
                    }
                }
            });
        }
        return $query;
    }

    private function moonContentsToIDs () {
        $ids = [];
        foreach ($this->moon_contents as $oreType) {
            switch ($oreType) {
                case 'ubiquitous' :
                    array_push($ids, self::UBIQUITOUS);
                break;
                case 'common' :
                    array_push($ids, self::COMMON);
                break;
                case 'uncommon' : 
                    array_push($ids, self::UNCOMMON);
                break;
                case 'rare' : 
                    array_push($ids, self::RARE);
                break;
                case 'exceptional' :
                    array_push($ids, self::EXCEPTIONAL);
                break;
            }
        }
        return $ids;
    }

    /**
     * Private function to refine the Scope's query
     * based on selected moon_contents
     * @param query
     */
    private function refineQuery($query) {
        foreach ($this->moon_contents as $oreType) {
            $id = 0;
            switch ($oreType) {
                case 'ubiquitous' :
                    $id = self::UBIQUITOUS;
                break;
                case 'common' :
                    $id = self::COMMON;
                break;
                case 'uncommon' : 
                    $id = self::UNCOMMON;
                break;
                case 'rare' : 
                    $id = self::RARE;
                break;
                case 'exceptional' :
                    $id = self::EXCEPTIONAL;
                break;
            }

            $query->whereHas('type', function ($type) use ($id) {
                $type->where('marketGroupID', $id);
            });
        }
    }
}
