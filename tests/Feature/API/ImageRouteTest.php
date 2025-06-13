<?php

test('image route returns file when it exists', function () {
    // Create a test image
    $filename = 'test-image.jpg';
    $path = resource_path('images');

    // Create directory if it doesn't exist
    if (! file_exists($path)) {
        mkdir($path, 0777, true);
    }

    // Create a test image file
    file_put_contents("{$path}/{$filename}", 'test image content');

    $response = $this->get("/images/{$filename}");

    $response->assertStatus(200);

    // Clean up
    unlink("{$path}/{$filename}");
});

test('image route returns 404 for non-existent images', function () {
    $response = $this->get('/images/non-existent-image.jpg');

    $response->assertStatus(404);
});
