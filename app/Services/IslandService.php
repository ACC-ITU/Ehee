<?php

namespace App\Services;

use Chumputy\Ulhandhu\Facades\Ulhandhu;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class IslandService
{
    protected array $islands = [];

    public function __construct()
    {
        $this->fetchIslands();
    }

    public function getIsland(): array
    {
        return $this->getUniqueIslands();
    }

    public function getAtolls(): array
    {
        return $this->getUniqueAtolls();
    }

    protected function fetchIslands(): void
    {
        $this->islands = Cache::remember('islands', now()->addDays(7), function () {
            $islandResponse = Ulhandhu::island()
                ->with('atoll')
                ->perPage(100)
                ->get();

            $results = $islandResponse->items;
            for ($i = 1; $i <= $islandResponse->lastPage; $i++) {
                $result = $islandResponse->getNextPage();

                $results = array_merge($results, $result->items);
            }

            return $results;
        });
    }

    protected function getUniqueAtolls(): array
    {
        $islands = $this->getUniqueIslands();
        $uniqueAtolls = [];
        foreach ($islands as $island) {
            $atollId = $island['atoll']->id;

            // Add atoll to the array if it's not already added
            if (! isset($uniqueAtolls[$atollId])) {
                $uniqueAtolls[$atollId] = $island['atoll'];
            }
        }

        usort($uniqueAtolls, function ($a, $b) {
            return strcmp($a->code, $b->code);
        });

        return array_values($uniqueAtolls);
    }

    protected function getUniqueIslands(): array
    {
        if (empty($this->islands)) {
            $this->fetchIslands();
        }

        return array_map(function ($island) {
            return [
                'id' => $island->id,
                'name' => Str::ucfirst(Str::lower($island->name)),
                'atoll' => $island->atoll,
            ];
        }, $this->islands);
    }
}
