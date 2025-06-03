<?php

uses(Tests\TestCase::class)->in('Feature', 'Unit');

// Global expectations
expect()->extend('toBeValid', function () {
    return $this->value === true;
});

// Dataset for common test data
dataset('commonData', [
    'valid data' => ['data' => true],
    'invalid data' => ['data' => false],
]);
