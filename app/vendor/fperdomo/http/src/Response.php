<?php

namespace Http;

interface Response
{
    public function setStatusCode($statusCode, $statusText = null): void;
    public function getStatusCode(): int;
    public function addHeader($name, $value): void;
    public function setHeader($name, $value): void;
    public function getHeaders(): array;
    public function addCookie(Cookie $cookie): void;
    public function deleteCookie(Cookie $cookie): void;
    public function setContent($content): void;
    public function getContent(): string;
    public function redirect($url): void;
}
