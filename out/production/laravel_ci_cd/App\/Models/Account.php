<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory, UsesUuid;

    protected $guarded = ["id"];

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
}
