<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceSettings extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'message',
        'expected_duration',
        'support_email',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
    ];

    /**
     * Get the maintenance settings instance (singleton pattern)
     */
    public static function getSettings()
    {
        return static::firstOrCreate(
            ['id' => 1],
            [
                'title' => 'We\'ll Be Right Back!',
                'subtitle' => 'System Under Maintenance',
                'message' => 'We\'re currently performing scheduled maintenance to enhance your learning experience. Our team is working diligently to bring the platform back online as soon as possible.',
                'expected_duration' => '1-2 Hours',
            ]
        );
    }
}
