<?php declare(strict_types = 1);

return [
    // Non-authenticated Users
    ['GET', '/[activities]', ['App\Controllers\ActivityController', 'list']],
    ['GET', '/activities/{id:\d+}', ['App\Controllers\ActivityController', 'show']],
    ['GET', '/login', ['App\Controllers\UserController', 'showLoginForm']],
    ['POST', '/login', ['App\Controllers\UserController', 'login']],

    // Authenticated Users - Clients and Vendors
    ['POST', '/logout', ['App\Controllers\UserController', 'logout']],
    ['GET', '/users/me', ['App\Controllers\UserController', 'showProfile']],
    ['GET', '/reservations', ['App\Controllers\ReservationController', 'list']],
    ['GET', '/reservations/{id:\d+}', ['App\Controllers\ReservationController', 'show']],
    ['POST', '/reservations', ['App\Controllers\ReservationController', 'reserve']],
    ['GET', '/{slug}', ['App\Controllers\Page', 'show']],

    // Vendors only
    ['POST', '/activities', ['App\Controllers\ActivityController', 'create']],
    ['PUT', '/activities/{id:\d+}', ['App\Controllers\ActivityController', 'update']],
    ['PATCH', '/activities/{id:\d+}/archive', ['App\Controllers\ActivityController', 'archive']],
    ['GET', '/activities/{id:\d+}/reservations', ['App\Controllers\ActivityController', 'listReservations']],
    ['PATCH', '/reservations/{id:\d+}', ['App\Controllers\ReservationController', 'updateStatus']],
];