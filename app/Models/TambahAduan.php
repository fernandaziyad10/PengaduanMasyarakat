<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TambahAduan extends Model
{
    //
    protected $fillable = [
        'id','description', 'type', 'province', 'regency', 'village', 'voting', 'viewers', 'image', 'statement','status','created_at','user_id'
    ];


    public function user()
{
    return $this->belongsTo(User::class);
}

    
}
