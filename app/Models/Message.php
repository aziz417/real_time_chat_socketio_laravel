<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['sender_id', 'message', 'seen_status', 'receiver_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}