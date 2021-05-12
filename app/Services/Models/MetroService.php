<?php


namespace App\Services\Models;

use App\Models\Metro;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;

class MetroService
{

    /**
     * @return Collection|Metro[]
     */
    public function getWithBlocks(): Collection
    {
        return Metro::query()
            ->orWhereIn('id', static function (Builder $builder) {
                $builder->from('buildings')
                    ->select('metro_id')
                    ->whereIn('id', function (Builder $builder) {
                        $builder->from('blocks')->select('building_id');
                    });
            })
            ->orWhereIn('id', static function (Builder $builder) {
                $builder->from('buildings')
                    ->select('metro_id_2')
                    ->whereIn('id', function (Builder $builder) {
                        $builder->from('blocks')->select('building_id');
                    });
            })
            ->orWhereIn('id', static function (Builder $builder) {
                $builder->from('buildings')
                    ->select('metro_id_3')
                    ->whereIn('id', function (Builder $builder) {
                        $builder->from('blocks')->select('building_id');
                    });
            })
            ->get();
    }
}
