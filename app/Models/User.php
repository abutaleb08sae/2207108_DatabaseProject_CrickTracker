<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Explicitly target your Oracle USERS table
    protected $table = 'USERS';
    
    // Explicitly target your custom Primary Key
    protected $primaryKey = 'USER_ID';
    
    // Set to false since USER_ID uses a custom format or manual sequence assignment
    public $incrementing = false;
    protected $keyType = 'string'; // Ensures Laravel treats the ID as a string if it contains letters

    // Custom Timestamp configurations for Oracle
    const CREATED_AT = 'CREATED_AT';
    const UPDATED_AT = 'UPDATED_AT';

    protected $fillable = [
        'USER_ID',       
        'USERNAME',
        'EMAIL',
        'PASSWORD_HASH',
        'ROLE',
        'IS_ADMIN',
    ];

    /**
     * Hidden fields from arrays or serialization blocks
     */
    protected $hidden = [
        'PASSWORD_HASH',
    ];

    /**
     * CRITICAL FIX: Overrides default 'password' column lookup for Laravel Auth
     */
    public function getAuthPassword()
    {
        return $this->PASSWORD_HASH;
    }

    /**
     * Overrides default primary identifier column name mapping
     */
    public function getAuthIdentifierName()
    {
        return 'USER_ID';
    }

    /**
     * Returns the actual primary key value of the user record for the session identifier
     */
    public function getAuthIdentifier()
    {
        return $this->getAttribute($this->getAuthIdentifierName());
    }

    /**
     * CRITICAL ORACLE FIX: Intercepts and completely disables remember_token management 
     * to completely avoid ORA-00904: invalid identifier crashes.
     */
    public function getRememberToken()
    {
        return null; // Always return null since there is no column tracking this
    }

    public function setRememberToken($value)
    {
        // Do nothing silently when Laravel tries to update the model session instance
    }

    public function getRememberTokenName()
    {
        return null; // Return null so Laravel never drops it into dynamic SQL statements
    }

    protected $casts = [
        'CREATED_AT' => 'datetime',
        'UPDATED_AT' => 'datetime',
        'IS_ADMIN'   => 'integer', // Ensures 1 or 0 doesn't get messed up as a raw string type
    ];
}