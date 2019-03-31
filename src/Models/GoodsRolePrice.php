<?php

namespace Chunyang\RolePrice\Models;

use Carbon\Carbon;

class GoodsRolePrice extends Model
{
    public $logUnable = ['create_time', 'update_time', 'delete_time'];

    const CREATED_AT = 'create_time';

    const UPDATED_AT = 'update_time';

    protected $table = 'goods_role_price';

    protected $fillable = ['goods_id', 'role_id', 'is_promote', 'promote_start_date', 'promote_end_date',
        'goods_price', 'limit_num', 'total_num', 'level'];

    public function goods()
    {
        return $this->belongsTo(config('role-price.goods'), 'goods_id', 'goods_id');
    }

    public function role()
    {
        return $this->belongsTo(HdRole::class, 'role_id');
    }

    public function scopeGoods($query, $goods)
    {
        if (!empty($goods)) {
            $query = $query->leftJoin('goods as g', $this->table . '.goods_id', '=', 'g.goods_id')->where('g.goods_sn', $goods);
        }
        return $query;
    }

    public function scopeRole($query, $roleId)
    {
        if ($roleId > 0) {
            $query = $query->where('role_id', $roleId);
        }
        return $query;
    }

    public function scopeStatus($query, $status)
    {
        $now = Carbon::now()->getTimestamp();
        switch ($status) {
            case 1:
                $query = $query->where('promote_start_date', '<=', $now)->where('promote_end_date', '>', $now);
                break;
            case 2:
                $query = $query->where('promote_start_date', '>', $now);
                break;
            case 3:
                $query = $query->where('promote_end_date', '<=', $now);
                break;
        }
        return $query;
    }

    public function scopePromote($query, $promote)
    {
        if ($promote > 0) {
            $query = $query->where($this->table . '.is_promote', $promote - 1);
        }
        return $query;
    }

    public function getPromoteStartDateAttribute($value)
    {
        return Carbon::createFromTimestamp($value);
    }

    public function setPromoteStartDateAttribute($value)
    {
        $this->attributes['promote_start_date'] = Carbon::parse($value)->getTimestamp();
    }

    public function getPromoteEndDateAttribute($value)
    {
        return Carbon::createFromTimestamp($value);
    }

    public function setPromoteEndDateAttribute($value)
    {
        $this->attributes['promote_end_date'] = Carbon::parse($value)->getTimestamp();
    }

    /**
     * @param $goodsId
     * @return \Illuminate\Support\Collection
     * 获取商品有效的可显示的角色价格
     */
    public function visiblePrices($goodsId)
    {
        $collect = collect();
        $roles = HdRole::query()->where('is_enabled', 1)->get();
        $prices = self::with('role')->where('goods_id', $goodsId)->orderBy('level')->get();
        $roles->each(function ($item) use ($collect, $goodsId, $prices) {
            if (!in_array($item->id, $prices->pluck('role_id')->toArray())) {
                $collect->push((new self([
                    'goods_id' => $goodsId,
                    'role_id' => $item->id,
                    'is_promote' => 0,
                    'promote_start_date' => $item->start_time,
                    'promote_end_date' => $item->end_time,
                    'goods_price' => sprintf('%.2f', 0),
                    'limit_num' => 0,
                    'total_num' => 0,
                    'level' => 0
                ]))->setRelation('role', $item));
            }
        });
        $prices->each(function ($item) use ($collect, $roles) {
            if (in_array($item->role_id, $roles->pluck('id')->toArray())) {
                $collect->push($item);
            }
        });
        return $collect;
    }
}
