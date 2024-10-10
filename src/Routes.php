<?php declare(strict_types = 1);

return [
    ['GET', '/hello-world', function() {
        echo 'Hello world!';
    }],
    ['GET', '/another-route', function() {
        echo 'This works too';
    }],
];