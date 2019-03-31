<?php

namespace Chunyang\RolePrice\Models;

use Carbon\Carbon;

class HdRole extends Model
{
    public $logUnable = ['create_time', 'update_time'];

    const CREATED_AT = 'create_time';

    const UPDATED_AT = 'update_time';

    protected $table = 'hd_role';

    protected $fillable = ['name', 'start_time', 'end_time', 'deny_message', 'is_enabled'];

    public function region()
    {
        return $this->belongsToMany(config('role-price.region'), 'region_role', 'role_id', 'region_id', 'region_id');
    }

    public function rank()
    {
        return $this->belongsToMany(config('role-price.rank'), 'rank_role', 'role_id', 'rank_id', 'rank_id');
    }

    public function user()
    {
        return $this->belongsToMany(config('role-price.user'), 'user_role', 'role_id', 'user_id', 'user_id');
    }

    public function scopeRegion($query, $region)
    {
        if ($region > 0) {
            $query = $query->leftJoin('region_role as rr', $this->table . '.role_id', '=', 'rr.role_id')
                ->where('rr.region_id', $region);
        }
        return $query;
    }

    public function getStartTimeAttribute($value)
    {
        return Carbon::createFromTimestamp($value);
    }

    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = Carbon::parse($value)->getTimestamp();
    }

    public function getEndTimeAttribute($value)
    {
        return Carbon::createFromTimestamp($value);
    }

    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] = Carbon::parse($value)->getTimestamp();
    }
}
