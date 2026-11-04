<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    // Fields allowed for mass assignment
    protected $fillable = [
        'project_id',
        'amount',
        'payment_date',
        'note',
    ];

    // Relationship: Payment belongs to a Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}