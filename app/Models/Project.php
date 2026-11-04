<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    // Fields allowed for mass assignment
    protected $fillable = [
        'client_id',
        'title',
        'agreed_price',
        'deadline',
        'status',
    ];

    // Relationship: Project belongs to a Client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relationship: Project has many Payments
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}