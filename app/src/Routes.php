<?php declare(strict_types = 1);

return [
    // Non-authenticated Users
    ['GET', '/[activities]', ['App\Controllers\Homepage', 'show']],
    // ['GET', '/activities/{id:\d+}', ['App\Controllers\Activity', 'show']],

    // Authenticated Users - Clients and Vendors
    // ['POST', '/login', ['App\Controllers\Homepage', 'show']],
    // ['POST', '/logout', ['App\Controllers\Homepage', 'show']],
    // ['GET', '/users/me/', ['App\Controllers\Homepage', 'show']],
    // ['GET', '/reservations', ['App\Controllers\Homepage', 'show']],
    // ['GET', '/reservations/{id:\d+}', ['App\Controllers\Homepage', 'show']],
    // ['POST', '/reservations', ['App\Controllers\Homepage', 'show']],
    ['GET', '/{slug}', ['App\Controllers\Page', 'show']],

    // Vendors only
    // ['POST', '/activities', ['App\Controllers\Homepage', 'show']],
    // ['PUT', '/activities/{id:\d+}', ['App\Controllers\Homepage', 'show']],
    // ['PATCH', '/activities/{id:\d+}/archive', ['App\Controllers\Homepage', 'show']],
    // ['GET', '/activities/{id:\d+}/reservations', ['App\Controllers\Homepage', 'show']],
    // ['PATCH', '/reservations/{id:\d+}', ['App\Controllers\Homepage', 'show']],
];