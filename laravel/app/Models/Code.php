<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $table = 'codes';

    public $incrementing = false;
    public $timestamps   = false;

    protected $fillable = [
        'code_type',
        'code',
        'name',
        'sort_order',
    ];

    protected $casts = [
        'code'       => 'integer',
        'sort_order' => 'integer',
    ];
}
