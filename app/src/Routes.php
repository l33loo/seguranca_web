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
    ['GET', '/users/me/reservations', ['App\Controllers\UserController', 'reservations']],
    ['GET', '/reservations/new', ['App\Controllers\ReservationController', 'new']],
    ['POST', '/reservations/new', ['App\Controllers\ReservationController', 'reserve']],
    ['GET', '/reservations/{id:\d+}', ['App\Controllers\ReservationController', 'show']],
    ['POST', '/activities/{id:\d+}', ['App\Controllers\ActivityController', 'postComment']],

    // Vendors only
    ['GET', '/users/me/activities', ['App\Controllers\UserController', 'vendorActivities']],
    ['GET', '/users/me/activities/{id:\d+}', ['App\Controllers\UserController', 'vendorActivity']],
    ['GET', '/activities/new', ['App\Controllers\ActivityController', 'new']],
    ['POST', '/activities/new', ['App\Controllers\ActivityController', 'create']],
    ['PUT', '/activities/{id:\d+}', ['App\Controllers\ActivityController', 'update']],
    ['PATCH', '/activities/{id:\d+}/archive', ['App\Controllers\ActivityController', 'archive']],
    ['GET', '/activities/{id:\d+}/reservations', ['App\Controllers\ActivityController', 'listReservations']],
    ['PATCH', '/reservations/{id:\d+}', ['App\Controllers\ReservationController', 'updateStatus']],
];