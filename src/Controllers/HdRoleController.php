<?php

namespace Chunyang\RolePrice\Controllers;

use App\Http\Controllers\Controller;
use Chunyang\RolePrice\Requests\HdRolePost;
use Chunyang\RolePrice\Models\HdRole;
use Chunyang\RolePrice\Models\RankRole;
use Chunyang\RolePrice\Models\RegionRole;
use Chunyang\RolePrice\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HdRoleController extends Controller
{
    protected $role;

    public function __construct(HdRole $role)
    {
        $this->role = $role;
    }

    public function index(Request $request)
    {
        $result = $this->role->with('region', 'rank', 'user:users.user_id,msn')
            ->region(intval($request->input('region_id')))
            ->Paginate();
        $tip1 = '商品管理';
        $tip2 = '商品活动角色管理';
        $tip3 = route('goods_role_price.index');
        $roles = HdRole::query()->pluck('name', 'id');
        return view('role-price::hd_role.index', compact('result', 'tip1', 'tip2', 'tip3', 'roles'));
    }

    public function create()
    {
        return view('role-price::hd_role.create', compact('province'));
    }

    public function store(HdRolePost $request)
    {
        DB::transaction(function () use ($request) {
            if ($info = $this->role->create($request->all())) {
                $this->regionChange(collect(), collect($request->input('region_ids', [])), $info->id);
                $this->rankChange(collect(), collect($request->input('rank_ids', [])), $info->id);
                $this->userChange(collect(), collect($request->input('user_ids', [])), $info->id);
            }
        });
        success_msg('新增活动角色成功！');
    }

    public function edit($id)
    {
        $info = $this->role->with('region', 'rank', 'user:users.user_id,msn')->findOrFail($id);
        return view('role-price::hd_role.edit', compact('info'));
    }

    public function update(HdRolePost $request, $id)
    {
        $info = $this->role->with('region')->findOrFail($id);
        $info->fill($request->all());
        DB::transaction(function () use ($info, $request) {
            if ($info->save()) {
                $this->regionChange($info->region->pluck('region_id'), collect($request->input('region_ids', [])), $info->id);
                $this->rankChange($info->rank->pluck('rank_id'), collect($request->input('rank_ids', [])), $info->id);
                $this->userChange($info->user->pluck('user_id'), collect($request->input('user_ids', [])), $info->id);
            }
        });
        success_msg('更新活动角色成功！');
    }

    public function destroy($id)
    {
        $info = $this->role->with('region')->findOrFail($id);
        DB::transaction(function () use ($info) {
            if ($info->delete()) {
                $delete = $info->region->pluck('region_id');
                if (RegionRole::query()->where('role_id', $info->id)->whereIn('region_id', $delete)->delete()) {
                    admin_log('删除活动角色「' . $info->id . '」：包含区域删除「' . $delete->implode(',') . '」');
                }
            };
        });
        ajax_return('删除活动角色成功！');
    }

    protected function regionChange($from, $to, $id)
    {
        $create = $to->diff($from);
        $delete = $from->diff($to);
        $insert = collect();
        $create->each(function ($item) use ($insert, $id) {
            $insert->push([
                'role_id' => $id,
                'region_id' => $item,
            ]);
        });
        if ($insert->count() > 0) {
            if (RegionRole::insert($insert->toArray())) {
                admin_log('编辑活动角色「' . $id . '」：限制区域新增「' . $create->implode(',') . '」');
            }
        }
        if ($delete->count() > 0) {
            if (RegionRole::query()->where('role_id', $id)->whereIn('region_id', $delete)->delete()) {
                admin_log('编辑活动角色「' . $id . '」：限制区域删除「' . $delete->implode(',') . '」');
            }
        }
    }

    protected function rankChange($from, $to, $id)
    {
        $create = $to->diff($from);
        $delete = $from->diff($to);
        $insert = collect();
        $create->each(function ($item) use ($insert, $id) {
            $insert->push([
                'role_id' => $id,
                'rank_id' => $item,
            ]);
        });
        if ($insert->count() > 0) {
            if (RankRole::insert($insert->toArray())) {
                admin_log('编辑活动角色「' . $id . '」：限制等级新增「' . $create->implode(',') . '」');
            }
        }
        if ($delete->count() > 0) {
            if (RankRole::query()->where('role_id', $id)->whereIn('rank_id', $delete)->delete()) {
                admin_log('编辑活动角色「' . $id . '」：限制等级删除「' . $delete->implode(',') . '」');
            }
        }
    }

    protected function userChange($from, $to, $id)
    {
        $create = $to->diff($from);
        $delete = $from->diff($to);
        $insert = collect();
        $create->each(function ($item) use ($insert, $id) {
            $insert->push([
                'role_id' => $id,
                'user_id' => $item,
            ]);
        });
        if ($insert->count() > 0) {
            if (UserRole::insert($insert->toArray())) {
                admin_log('编辑活动角色「' . $id . '」：限制会员新增「' . $create->implode(',') . '」');
            }
        }
        if ($delete->count() > 0) {
            if (UserRole::query()->where('role_id', $id)->whereIn('user_id', $delete)->delete()) {
                admin_log('编辑活动角色「' . $id . '」：限制会员删除「' . $delete->implode(',') . '」');
            }
        }
    }
}
