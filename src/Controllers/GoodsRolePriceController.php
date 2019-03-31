<?php

namespace Chunyang\RolePrice\Controllers;

use App\Http\Controllers\Controller;
use Chunyang\RolePrice\Requests\GoodsRolePricePost;
use App\Models\Goods;
use Chunyang\RolePrice\Models\GoodsRolePrice;
use Chunyang\RolePrice\Models\HdRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoodsRolePriceController extends Controller
{
    protected $price;

    public function __construct(GoodsRolePrice $price)
    {
        $this->price = $price;
    }

    public function index(Request $request)
    {
        $result = $this->price->with(['goods', 'role.region', 'role.rank', 'role.user:users.user_id,msn'])
            ->role(intval($request->input('role_id')))
            ->goods(trim($request->input('goods_sn')))
            ->status(intval($request->input('status')))
            ->promote(intval($request->input('is_promote')))
            ->Paginate();
        $tip1 = '商品管理';
        $tip2 = '商品活动价格管理';
        $tip3 = route('goods_role_price.index');
        $roles = HdRole::query()->pluck('name', 'id');
        return view('role-price::goods_role_price.index', compact('result', 'tip1', 'tip2', 'tip3', 'roles'));
    }

    public function create($goodsId)
    {
        $goods = Goods::select('goods_id', 'goods_sn', 'goods_name')->findOrFail($goodsId);
        $prices = $this->price->visiblePrices($goodsId);
        return view('role-price::goods_role_price.create', compact('goods', 'prices'));
    }

    public function store(GoodsRolePricePost $request, $goodsId)
    {
        $role = $request->input('role');
        foreach ($role as $k => $v) {
            $v['goods_id'] = $goodsId;
            $v['role_id'] = $k;
            if (isset($v['is_promote']) && $v['is_promote'] == 1) {
                DB::transaction(function () use ($v) {
                    if ($v['id'] == 0) {
                        $response = $this->price->create($v);
                        if ($response->id <= 0) {
                            DB::rollBack();
                        }
                    } else {
                        $price = $this->price->findOrFail($v['id']);
                        $price->update($v);
                    }
                });
            }
        }
        success_msg('编辑活动价格成功！');
    }

    public function edit($id)
    {
        $info = $this->price->with(['goods', 'role'])->findOrFail($id);
        return view('role-price::goods_role_price.edit', compact('info'));
    }

    public function update(GoodsRolePricePost $request, $id)
    {
        $info = $this->price->findOrFail($id);
        $info->fill($request->input('role')[$info->role_id]);
        DB::transaction(function () use ($info) {
            $info->save();
        });
        success_msg('更新活动价格成功！');
    }

    public function destroy($id)
    {
        $info = $this->price->findOrFail($id);
        DB::transaction(function () use ($info) {
            $info->delete();
        });
        ajax_return('删除活动价格成功！');
    }
}
