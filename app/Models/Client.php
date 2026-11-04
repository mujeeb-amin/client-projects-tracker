<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    // Fields allowed for mass assignment
    protected $fillable = [
        'name',
        'phone',
        'email',
        'company',
        'address',
    ];

    // Relationship: Client has many Projects
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}