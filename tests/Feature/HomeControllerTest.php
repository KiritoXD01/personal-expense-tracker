<?php

declare(strict_types=1);

test('landing page returns successful response', function () {
    $response = $this->get('/');

    $response->assertSuccessful();
});

test('landing page displays app name', function () {
    $response = $this->get('/');

    $response->assertSee('Architect Finance');
});

test('landing page displays hero headline', function () {
    $response = $this->get('/');

    $response->assertSee('Master Your Money');
});

test('landing page displays call-to-action buttons', function () {
    $response = $this->get('/');

    $response->assertSee('Get Started');
    $response->assertSee('Sign Up');
});
