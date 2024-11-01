<?php declare(strict_types = 1);

abstract class Access {
    public const GUEST = 'guest';
    public const CLIENT = 'client';
    public const VENDOR = 'vendor';
}

return [
    // Non-authenticated Users
    ['GET', '/[activities]', [\App\Controllers\ActivityController::class, 'list', Access::GUEST]],
    ['GET', '/activities/{id:\d+}', [\App\Controllers\ActivityController::class, 'show', Access::GUEST]],
    ['GET', '/login', [\App\Controllers\UserController::class, 'showLoginForm', Access::GUEST]],
    ['POST', '/login', [\App\Controllers\UserController::class, 'login', Access::GUEST]],

    // Authenticated Users - Clients and Vendors
    ['POST', '/logout', [\App\Controllers\UserController::class, 'logout', Access::CLIENT]],
    ['GET', '/users/me', [\App\Controllers\UserController::class, 'showProfile', Access::CLIENT]],
    ['GET', '/users/me/reservations', [\App\Controllers\UserController::class, 'reservations', Access::CLIENT]],
    ['GET', '/activities/{id:\d+}/reserve', [\App\Controllers\ReservationController::class, 'new', Access::CLIENT]],
    ['POST', '/activities/{id:\d+}/reserve', [\App\Controllers\ReservationController::class, 'reserve', Access::CLIENT]],
    ['GET', '/reservations/{id:\d+}', [\App\Controllers\ReservationController::class, 'show', Access::CLIENT]],
    ['POST', '/activities/{id:\d+}', [\App\Controllers\ActivityController::class, 'postComment', Access::CLIENT]],

    // Vendors only
    ['GET', '/users/me/activities', [\App\Controllers\UserController::class, 'vendorActivities', Access::VENDOR]],
    ['GET', '/users/me/activities/{id:\d+}', [\App\Controllers\UserController::class, 'vendorActivity', Access::VENDOR]],
    ['GET', '/activities/new', [\App\Controllers\ActivityController::class, 'new', Access::VENDOR]],
    ['POST', '/activities/new', [\App\Controllers\ActivityController::class, 'create', Access::VENDOR]],
    ['PUT', '/activities/{id:\d+}', [\App\Controllers\ActivityController::class, 'update', Access::VENDOR]],
    ['PATCH', '/activities/{id:\d+}/archive', [\App\Controllers\ActivityController::class, 'archive', Access::VENDOR]],
    ['GET', '/activities/{id:\d+}/reservations', [\App\Controllers\ActivityController::class, 'listReservations', Access::VENDOR]],
    ['PATCH', '/reservations/{id:\d+}', [\App\Controllers\ReservationController::class, 'updateStatus', Access::VENDOR]],
];