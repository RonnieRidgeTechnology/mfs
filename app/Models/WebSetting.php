<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebSetting extends Model
{
    protected $fillable = [
        'send_member_creation_email',
        'send_admin_creation_email',
        'send_fee_completion_email',
        'send_guest_promoted_email',
        'send_contact_us_email',
        'send_newsletter_email',
        'email1',
        'email2',
        'phone1',
        'phone2',
        'address',
        'address_link',
        'facebook_link',
        'youtube_link',
        'insta_link',
        'linkdin_link',
        'copy_right',
        'favicon_icon',
    ];
}
