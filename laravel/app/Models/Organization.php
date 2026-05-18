<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    protected $table = 'organizations';

    protected $fillable = [
        'parent_id',
        'name',
        'name_kana',
        'email',
        'phone',
        'zipcode',
        'prefecture_code',
        'city',
        'address_line',
        'representative_id',
        'established_date',
        'status',
    ];

    protected $appends = ['representative_label'];

    protected $hidden = ['representative'];

    protected $casts = [
        'parent_id'        => 'integer',
        'prefecture_code'  => 'integer',
        'representative_id' => 'integer',
        'established_date' => 'date',
        'status'           => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Organization::class, 'parent_id');
    }

    public function representative(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'representative_id');
    }

    public function getRepresentativeLabelAttribute(): ?string
    {
        if ($this->representative === null) {
            return null;
        }
        return $this->representative->last_name . $this->representative->first_name;
    }

    public static function validationRules(?int $ignoreId = null): array
    {
        return [
            'parent_id'        => ['nullable', 'integer', 'exists:organizations,id'],
            'name'             => ['required', 'string', 'max:100'],
            'name_kana'        => ['nullable', 'string', 'max:200'],
            'email'            => ['nullable', 'string', 'email', 'max:254'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'zipcode'          => ['nullable', 'string', 'max:7'],
            'prefecture_code'  => ['nullable', 'integer', 'between:0,255'],
            'city'             => ['nullable', 'string', 'max:50'],
            'address_line'     => ['nullable', 'string', 'max:100'],
            'representative_id' => ['nullable', 'integer', 'exists:persons,id'],
            'established_date' => ['nullable', 'date'],
            'status'           => ['nullable', 'integer', 'between:0,255'],
        ];
    }
}
