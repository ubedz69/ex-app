<?php

use App\Http\Middleware\MinifyHtmlResponse;
use Illuminate\Http\Request;
use Tests\TestCase;

uses(TestCase::class);

test('it minifies html response when enabled', function () {
    config()->set('app.minify_html', true);

    $middleware = new MinifyHtmlResponse;
    $request = Request::create('/dummy', 'GET');

    $response = $middleware->handle($request, function () {
        return response(
            "<html>\n    <!-- comment -->\n    <body>\n        <div> Hello </div>\n    </body>\n</html>",
            200,
            ['Content-Type' => 'text/html; charset=UTF-8']
        );
    });

    expect($response->getContent())->toBe('<html><body><div> Hello </div></body></html>');
});

test('it does not minify html response when disabled', function () {
    config()->set('app.minify_html', false);

    $middleware = new MinifyHtmlResponse;
    $request = Request::create('/dummy', 'GET');

    $rawHtml = "<html>\n    <body>\n        <div> Hello </div>\n    </body>\n</html>";

    $response = $middleware->handle($request, function () use ($rawHtml) {
        return response(
            $rawHtml,
            200,
            ['Content-Type' => 'text/html; charset=UTF-8']
        );
    });

    expect($response->getContent())->toBe($rawHtml);
});
