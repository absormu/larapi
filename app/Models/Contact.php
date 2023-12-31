<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = "contacts";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public  $timestamps = true;
    public  $incrementing = true;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
    ];

    public function users(): BelongsTo
    {
        return $this->belongTo(Contact::class, "user_id", "id");
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, "contact_id", "id");
    }
}
