<?php

test('the root path redirects to the login page', function () {
    $response = $this->get('/');

    $response->assertRedirect('/login');
});