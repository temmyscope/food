<?php

namespace App\Enums;

enum HttpStatus: int {

    case OK = 200; 
    case CREATED = 201; 
    case DELETED = 204; 
    case UPDATED = 205;

    case BAD_REQUEST = 400; 
    case UNAUTHORIZED = 401; 
    case PAYMENT_REQUIRED = 402; 
    case FORBIDDEN = 403; 
    case NOT_FOUND = 404; 
    case NOT_ALLOWED = 405; 
    case VALIDATION_ERROR = 422; 
    case LIMIT_EXCEED = 429; 

    case INTERNAL_ERROR = 500;
    case SERVER_NOT_READY = 503;

    public function status(): bool { 
        //if value is within the 200 code range, return true, else false
        return ($this->value > 199 && $this->value < 299)? true : false;
    }

    public function message(): string { 
        return match($this) 
        {
            self::OK => 'OK',   
            self::CREATED => 'CREATED',   
            self::DELETED => 'DELETED', 
            self::UPDATED => 'UPDATED',
            self::BAD_REQUEST => 'BAD REQUEST',
            self::UNAUTHORIZED => 'UNAUTHORIZED', 
            self::PAYMENT_REQUIRED => 'PAYMENT REQUIRED',
            self::FORBIDDEN => 'FORBIDDEN',
            self::NOT_FOUND => 'NOT FOUND',
            self::NOT_ALLOWED => 'METHOD NOT ALLOWED',
            self::VALIDATION_ERROR => 'VALIDATION ERROR',
            self::LIMIT_EXCEED => 'LIMIT EXCEEDED',
            self::INTERNAL_ERROR => 'INTERNAL ERROR',
            self::SERVER_NOT_READY => 'SERVER NOT READY',
            default => 'Unknown Error'
        };
    }
    
}
