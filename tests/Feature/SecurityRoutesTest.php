<?php

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;

test('generate sitemap route requires authentication', function () {
    $this->get('/generate-sitemap')->assertUnauthorized();
});

test('blog create route requires authentication', function () {
    $this->get('/blog/create')->assertUnauthorized();
});

test('blog store route requires authentication', function () {
    $this->withoutMiddleware(ValidateCsrfToken::class);

    $this->post('/blog', [
        'title' => 'Judul',
        'summary' => 'Ringkasan',
        'content' => 'Konten',
    ])->assertUnauthorized();
});

test('tracking check endpoint is throttled', function () {
    $this->withoutMiddleware(ValidateCsrfToken::class);

    for ($index = 0; $index < 10; $index++) {
        $this->post('/tracking/check', [
            'tracking_number' => 'abc',
        ])->assertOk();
    }

    $this->post('/tracking/check', [
        'tracking_number' => 'abc',
    ])->assertStatus(429);
});
