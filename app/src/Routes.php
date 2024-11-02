<?php declare(strict_types = 1);

abstract class Access {
    public const GUEST = 'guest';
    public const CLIENT = 'client';
    public const VENDOR = 'vendor';
}

return [
    // Non-authenticated Users
    ['GET', '/[activities]', [\App\Controllers\ActivityController::class, 'list', Access::GUEST]],
    ['GET', '/activities/{activityId:\d+}', [\App\Controllers\ActivityController::class, 'show', Access::GUEST]],
    ['GET', '/login', [\App\Controllers\UserController::class, 'showLoginForm', Access::GUEST]],
    ['POST', '/login', [\App\Controllers\UserController::class, 'login', Access::GUEST]],

    // Authenticated Users - Clients and Vendors
    ['POST', '/logout', [\App\Controllers\UserController::class, 'logout', Access::CLIENT]],
    ['GET', '/users/me', [\App\Controllers\UserController::class, 'showProfile', Access::CLIENT]],
    ['GET', '/users/me/reservations', [\App\Controllers\UserController::class, 'reservations', Access::CLIENT]],
    ['GET', '/activities/{activityId:\d+}/reserve', [\App\Controllers\ReservationController::class, 'new', Access::CLIENT]],
    ['POST', '/activities/{activityId:\d+}/reserve', [\App\Controllers\ReservationController::class, 'reserve', Access::CLIENT]],
    ['GET', '/reservations/{reservationId:\d+}', [\App\Controllers\ReservationController::class, 'show', Access::CLIENT]],
    ['POST', '/activities/{activityId:\d+}', [\App\Controllers\ActivityController::class, 'postComment', Access::CLIENT]],

    // Vendors only
    ['GET', '/users/me/activities', [\App\Controllers\UserController::class, 'vendorActivities', Access::VENDOR]],
    // ['GET', '/users/me/activities/{activityId:\d+}', [\App\Controllers\UserController::class, 'vendorActivity', Access::VENDOR]],
    ['GET', '/activities/new', [\App\Controllers\ActivityController::class, 'new', Access::VENDOR]],
    ['POST', '/activities/new', [\App\Controllers\ActivityController::class, 'create', Access::VENDOR]],
    ['GET', '/activities/{activityId:\d+}/edit', [\App\Controllers\ActivityController::class, 'editForm', Access::VENDOR]],
    ['POST', '/activities/{activityId:\d+}/edit', [\App\Controllers\ActivityController::class, 'edit', Access::VENDOR]],
    ['PATCH', '/activities/{activityId:\d+}/archive', [\App\Controllers\ActivityController::class, 'archive', Access::VENDOR]],
    ['POST', '/activities/{activityId:\d+}/delete', [\App\Controllers\ActivityController::class, 'delete', Access::VENDOR]],
    ['GET', '/activities/{activityId:\d+}/reservations', [\App\Controllers\ActivityController::class, 'listReservations', Access::VENDOR]],
    ['PATCH', '/reservations/{reservationId:\d+}', [\App\Controllers\ReservationController::class, 'editStatus', Access::VENDOR]],
];