<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;

class Person extends Model
{
    protected $table = 'persons';

    protected $fillable = [
        'last_name',
        'first_name',
        'last_name_kana',
        'first_name_kana',
        'email',
        'phone',
        'birth_date',
        'gender',
        'zipcode',
        'prefecture_code',
        'city',
        'address_line',
    ];

    protected $appends = ['gender_label'];

    protected $hidden = ['genderCode'];

    protected $casts = [
        'birth_date'      => 'date',
        'gender'          => 'integer',
        'prefecture_code' => 'integer',
    ];

    public function genderCode(): BelongsTo
    {
        return $this->belongsTo(Code::class, 'gender', 'code')
            ->where('code_type', 'gender');
    }

    public function getGenderLabelAttribute(): ?string
    {
        return $this->genderCode?->name;
    }

    public static function validationRules(?int $ignoreId = null): array
    {
        $emailRule = $ignoreId
            ? Rule::unique('persons', 'email')->ignore($ignoreId)
            : 'unique:persons,email';

        return [
            'last_name'       => ['required', 'string', 'max:50'],
            'first_name'      => ['required', 'string', 'max:50'],
            'last_name_kana'  => ['nullable', 'string', 'max:100'],
            'first_name_kana' => ['nullable', 'string', 'max:100'],
            'email'           => ['required', 'email', 'max:254', $emailRule],
            'phone'           => ['nullable', 'string', 'max:20'],
            'birth_date'      => ['nullable', 'date'],
            'gender'          => ['nullable', 'integer', 'in:0,1,2,9'],
            'zipcode'         => ['nullable', 'string', 'size:7'],
            'prefecture_code' => ['nullable', 'integer', 'between:0,47'],
            'city'            => ['nullable', 'string', 'max:50'],
            'address_line'    => ['nullable', 'string', 'max:100'],
        ];
    }
}
