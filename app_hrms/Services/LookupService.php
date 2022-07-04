<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class LookupService
{
    private static $table = 'lookups';

    public static function getByCategory($category)
    {
        return DB::table(self::$table)->where('category', $category)->get();
    }
}