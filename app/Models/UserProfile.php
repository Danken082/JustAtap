<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'display_name',
        'display_name_font_size',
        'layout_style',
        'title',
        'bio',
        'avatar_url',
        'avatar_offset_x',
        'avatar_offset_y',
        'logo_url',
        'badge_images',
        'profile_builder_active',
        'profile_view_count',
        'background_color',
        'text_color',
        'accent_color',
        'card_style',
        'background_pattern',
    ];

    protected $casts = [
        'badge_images' => 'array',
        'profile_builder_active' => 'boolean',
        'profile_view_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(ProfileLink::class)->orderBy('sort_order')->orderBy('id');
    }
}
