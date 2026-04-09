<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_address',
        'company_email',
        'company_mobile',
        'gst_no',
        'pan_no',
        'state_name',
        'state_code',
        'bank_name',
        'bank_account_no',
        'bank_ifsc',
        'bank_branch',
        'agent_name',
        'terms',
    ];

    public static function getCached(): self
    {
        return Cache::rememberForever('app_settings', function (): self {
            return self::query()->first() ?? self::query()->create([]);
        });
    }

    public static function clearCache(): void
    {
        Cache::forget('app_settings');
    }
}
