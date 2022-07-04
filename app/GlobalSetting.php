<?php

namespace App;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;

class GlobalSetting extends Model
{
    protected $settings;
    public $employee_menus;
    public $cache_expire_in;

    public function __construct()
    {
        $settings = Setting::all();
        $this->employee_menus = [
            'employee', 'division', 'department', 'job-title', 'grade-title', 'level-title', 'company-region', 'leave-quota'
        ];
        foreach ($settings as $setting) {
            $this->settings[$setting->key] = $setting->value;
        }
        /**
         * Expire in 12 hours
         */
        $this->cache_expire_in = 60*60*12;
    }
    
    public function has($key)
    {
        return array_key_exists($key, $this->settings);
    }

    public function get($key)
    {
        if ($this->has($key)) {
            return $this->settings[$key];
        }
        return null;
    }
}
