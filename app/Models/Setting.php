<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return self::castValue($setting->value, $setting->type);
    }

    public static function setValue($key, $value, $type = 'string', $group = 'general', $description = null)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => self::prepareValue($value, $type),
                'type' => $type,
                'group' => $group,
                'description' => $description,
            ]
        );
        
        return $setting;
    }

    protected static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return (bool) $value;
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'date':
            case 'datetime':
                return $value ? Carbon::parse($value) : null;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    protected static function prepareValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return $value ? '1' : '0';
            case 'date':
            case 'datetime':
                return $value instanceof Carbon ? $value->toDateTimeString() : $value;
            case 'json':
                return json_encode($value);
            default:
                return $value;
        }
    }

    public static function getIdeaClosureDate()
    {
        return self::getValue('idea_closure_date');
    }

    public static function getFinalClosureDate()
    {
        return self::getValue('final_closure_date');
    }

    public static function getAcademicYear()
    {
        return self::getValue('academic_year', date('Y'));
    }

    public static function isIdeaSubmissionOpen()
    {
        $closureDate = self::getIdeaClosureDate();
        if ($closureDate) {
            return now()->lt($closureDate);
        }
        return true;
    }

    public static function isCommentingOpen()
    {
        $finalClosureDate = self::getFinalClosureDate();
        if ($finalClosureDate) {
            return now()->lt($finalClosureDate);
        }
        return true;
    }

    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }
}
