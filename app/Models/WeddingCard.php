<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Template;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\WeddingRsvp;
use App\Models\WeddingTable;
use App\Models\WeddingGuest;

class WeddingCard extends Model
{
    use HasFactory;

    protected $table = 'wedding_cards';

    protected $fillable = [
        'template_id', 'slug',
        'groom_name', 'groom_phone', 'groom_father', 'groom_mother', 'groom_avatar', 'groom_bio',
        'bride_name', 'bride_phone', 'bride_father', 'bride_mother', 'bride_avatar', 'bride_bio',
        'wedding_date', 'lunar_date', 'wedding_time', 'wedding_location', 'map_link',
        'invitation_msg', 'cover_img', 'album_imgs', 'voice_invite', 'voice_thanks',
        'bg_music', 'wedding_video', 'thank_msg',
        'time_welcome', 'time_ceremony', 'time_party',
        'groom_bank_name', 'groom_bank_acc', 'groom_bank_owner', 'groom_qr_code',
        'bride_bank_name', 'bride_bank_acc', 'bride_bank_owner', 'bride_qr_code',
        'user_id', 'is_paid',
        'is_vip',
        'package_type', 
    ];

    protected $casts = [
        'album_imgs' => 'array',
        'is_paid' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id', 'id');
    }

    public function rsvps(): HasMany
    {
        return $this->hasMany(WeddingRsvp::class, 'wedding_card_id');
    }

    // Quan hệ lấy danh sách Bàn tiệc
    public function tables(): HasMany
    {
        return $this->hasMany(WeddingTable::class, 'wedding_card_id');
    }

    // Quan hệ lấy danh sách Khách mời
    public function guests(): HasMany
    {
        return $this->hasMany(WeddingGuest::class, 'wedding_card_id');
    }
    public function orders()
{
    return $this->hasMany(Order::class);
}
}