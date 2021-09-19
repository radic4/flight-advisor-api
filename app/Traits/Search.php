<?php
 
namespace App\Traits;

use Illuminate\Support\Str;

trait Search
{
    public function scopeSearch($query, $column, $search)
    {
        $char = '\\';
        $search = str_replace([$char, '%', '_'], [$char.$char, $char.'%', $char.'_'], $search);

        return $query->where($column, 'LIKE', $search.'%');
    }
}