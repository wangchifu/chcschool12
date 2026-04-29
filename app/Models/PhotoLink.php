<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoLink extends Model
{
    protected $fillable = [
        'name',
        'url',
        'image',
        'order_by',
        'user_id',
        'photo_type_id'
    ];

    public function getContentAttribute($value)
    {
        if ($value === null) {
            return null;
        }
        // 移除 https://www.xxx.chc.edu.tw 的 www.
		return preg_replace(
			'/(<img\s+[^>]*?)src=["\']https?:\/\/www\.([a-z]{4}\.chc\.edu\.tw[^"\']*)["\']([^>]*>)/i',
			'$1src="https://$2"$3',
			$value
		);        
    }       
}
