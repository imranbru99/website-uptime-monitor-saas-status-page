<?php

namespace App\Domain\CMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemoRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'company',
        'team_size',
        'interests',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'interests' => 'array',
        ];
    }
}
