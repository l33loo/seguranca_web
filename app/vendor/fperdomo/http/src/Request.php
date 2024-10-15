<?php

namespace Http;

interface Request
{
    public function getParameter(string $key, $defaultValue = null): ?string;
    public function getQueryParameter(string $key, $defaultValue = null): ?string;
    public function getBodyParameter(string $key, $defaultValue = null): ?string;
    public function getFile(string $key, $defaultValue = null): ?string;
    public function getCookie(string $key, $defaultValue = null): ?string;
    public function getParameters(): array;
    public function getQueryParameters(): array;
    public function getBodyParameters(): array;
    public function getRawBody(): string;
    public function getCookies(): array;
    public function getFiles(): array;
    public function getUri(): string;
    public function getPath(): string;
    public function getMethod(): string;
    public function getHttpAccept(): string;
    public function getReferer(): string;
    public function getUserAgent(): string;
    public function getIpAddress(): string;
    public function isSecure(): bool;
    public function getQueryString(): string;
}
