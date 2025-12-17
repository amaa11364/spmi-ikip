<?php

namespace App\Helpers;

class UserHelper
{
    public static function getInitials($name)
    {
        $words = explode(' ', $name);
        $initials = '';
        
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        
        return substr($initials, 0, 2);
    }
    
    public static function getAvatarColor($userId)
    {
        $colors = ['avatar-color-0', 'avatar-color-1', 'avatar-color-2', 'avatar-color-3', 'avatar-color-4', 'avatar-color-5'];
        return $colors[$userId % 6];
    }
}