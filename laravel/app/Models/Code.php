<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Code extends Model
{
    protected $table = 'codes';

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'code_type',
        'code',
        'name',
        'sort_order',
    ];

    protected $casts = [
        'code' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * @param string|null $codeType null のとき新規登録用、指定時は更新用
     */
    public static function validationRules(?string $codeType = null): array
    {
        if ($codeType !== null) {
            return [
                'name'       => ['required', 'string', 'max:50'],
                'sort_order' => ['nullable', 'integer', 'between:0,255'],
            ];
        }

        return [
            'code_type'  => ['required', 'string', 'max:50'],
            'code'       => ['required', 'integer', 'between:0,255'],
            'name'       => ['required', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer', 'between:0,255'],
        ];
    }
}
