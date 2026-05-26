<?php

test('canonical link uses configured app url', function (): void {
    config()->set('app.url', 'http://rairakaexpress.com');

    $response = $this->get('http://rairakaexpress.com/about');

    $response->assertOk();
    $response->assertSee('<link rel="canonical" href="http://rairakaexpress.com/about">', false);
});

test('non canonical host is redirected to canonical host', function (): void {
    config()->set('app.url', 'http://rairakaexpress.com');

    $response = $this->get('http://www.rairakaexpress.com/services');

    $response->assertRedirect('http://rairakaexpress.com/services');
    expect($response->status())->toBe(301);
});

test('http request is redirected to https canonical url', function (): void {
    config()->set('app.url', 'https://rairakaexpress.com');

    $response = $this->get('http://rairakaexpress.com/contact');

    $response->assertRedirect('https://rairakaexpress.com/contact');
    expect($response->status())->toBe(301);
});

test('blog create route sends noindex robots header', function (): void {
    config()->set('app.url', 'http://localhost');

    $response = $this->get('/blog/create');

    $response->assertStatus(401);
    $response->assertHeader('X-Robots-Tag', 'noindex, nofollow');
});

test('tracking query route sends noindex robots header', function (): void {
    config()->set('app.url', 'http://localhost');

    $response = $this->get('/tracking?tracking_number=123');

    $response->assertOk();
    $response->assertHeader('X-Robots-Tag', 'noindex, follow');
});
