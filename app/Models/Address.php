<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = "addresses";
    protected $primaryKey = "id";
    protected $keyType = "int";
    protected $timestamps = true;
    protected $incrementing = true;

    public function contact(): BelongsTo
    {
        return $this->belongTo(Contact::class, "contact_id", "id");
    }
}
