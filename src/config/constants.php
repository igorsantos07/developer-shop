<?php
define('ENV', getenv('ENV')?: 'dev');
define('PROD', ENV == 'prod');

// HTTP codes for success responses
const HTTP_OK         = 200;
const HTTP_CREATED    = 201;
const HTTP_NO_CONTENT = 204;

// HTTP codes for client errors
const HTTP_BAD_REQUEST        = 400;
const HTTP_UNAUTHORIZED       = 401;
const HTTP_FORBIDDEN          = 403;
const HTTP_NOT_FOUND          = 404;
const HTTP_METHOD_NOT_ALLOWED = 405;
const HTTP_NOT_ACCEPTABLE     = 406;
const HTTP_TOO_MANY_REQUESTS  = 429;

// HTTP codes for server errors
const HTTP_INTERNAL_ERROR      = 500;
const HTTP_NOT_IMPLEMENTED     = 501;
const HTTP_SERVICE_UNAVAILABLE = 503;