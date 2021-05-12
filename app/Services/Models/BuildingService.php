<?php


namespace App\Services\Models;

use App\Models\Building;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder as QueryBuilder;
use \Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class BuildingService
{
    public const SOURCE_BLOCK = 'block';

    public const DEFAULT_LIMIT = 30;

    /**
     * @param string $search
     * @param string|null $source
     * @return EloquentBuilder[]|Collection
     */
    public function getAddressBySearch(string $search, string $source = null): Collection
    {
        $query = Building::query()
            ->select('address')
            ->where('address', 'LIKE', '%' . $search . '%');
        if ($source === self::SOURCE_BLOCK) {
            $query->whereIn('id', static function (QueryBuilder $builder) {
                $builder->from('blocks')
                    ->select('building_id');
            });
        }

        return $this->getAddressSearchQuery($search, $source)->limit(self::DEFAULT_LIMIT)->get();
    }

    /**
     * @param string $search
     * @param string|null $source
     * @return EloquentBuilder
     */
    public function getAddressSearchQuery(string $search, string $source = null): EloquentBuilder
    {
        $query = Building::query()
            ->select('address')
            ->where('address', 'LIKE', '%' . $search . '%');
        if ($source === self::SOURCE_BLOCK) {
            $query->whereIn('id', static function (QueryBuilder $builder) {
                $builder->from('blocks')
                    ->select('building_id');
            });
        }

        return $query;
    }
}
