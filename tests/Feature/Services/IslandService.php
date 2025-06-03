<?php


use App\Services\IslandService;
use Chumputy\Ulhandhu\Facades\Ulhandhu;
use Illuminate\Support\Facades\Cache;

test('it can get unique islands', function () {
    // Mock the Ulhandhu facade
    Ulhandhu::shouldReceive('island->with->get')
        ->once()
        ->andReturn((object)[
            'items' => [
                (object)[
                    'id' => 1,
                    'name' => 'MALE',
                    'atoll' => (object)['id' => 1, 'code' => 'K']
                ]
            ],
            'lastPage' => 1
        ]);

    $islandService = new IslandService();
    $islands = $islandService->getIsland();

    expect($islands)->toBeArray()
        ->and($islands[0])->toHaveKeys(['id', 'name', 'atoll'])
        ->and($islands[0]['name'])->toBe('Male');
});

test('it can get unique atolls', function () {
    // Similar structure to test getAtolls method
});

test('it caches island data', function () {
    Cache::shouldReceive('remember')
        ->once()
        ->andReturn([/* mock data */]);

    $islandService = new IslandService();
    $islandService->getIsland();
});
