<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreenView extends Model
{
    protected $fillable = ['user_id', 'screen', 'last_viewed_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
