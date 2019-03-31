<?php

namespace Chunyang\RolePrice\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoodsRolePricePost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->{strtolower($this->method())}();

    }

    protected function post()
    {
        return [
//            'goods_id' => 'required|integer',
            'role.*.is_promote' => 'integer',
            'role.*.promote_start_date' => 'required_with:role.*.is_promote|date',
            'role.*.promote_end_date' => 'required_with:role.*.is_promote|date|after:role.*.promote_start_date',
            'role.*.goods_price' => 'required_with:role.*.is_promote|numeric',
            'role.*.limit_num' => 'required_with:role.*.is_promote|integer',
//            'role.*.total_num' => 'required_with:role.*.is_promote|integer',
        ];
    }

    protected function put()
    {
        return [
            'role.*.is_promote' => 'integer',
            'role.*.promote_start_date' => 'required|date',
            'role.*.promote_end_date' => 'required|date|after:role.*.promote_start_date',
            'role.*.goods_price' => 'required|numeric',
            'role.*.limit_num' => 'required|integer',
//            'role.*.total_num' => 'required|integer',
            'role.*.level' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute 不能为空',
            'required_with' => ':attribute 不能为空',
            'date' => ':attribute 日期时间不正确',
            'after' => ':attribute 不能小于 :date',
            'numeric' => ':attribute 不是正确的数值',
            'integer' => ':attribute 不是整数',
        ];
    }

    public function attributes()
    {
        return [
            'goods_id' => '商品',
            'role.*.promote_start_date' => '活动开始时间',
            'role.*.promote_end_date' => '活动结束时间',
            'role.*.goods_price' => '活动价格',
            'role.*.limit_num' => '限购数量',
            'role.*.total_num' => '总量',
            'role.*.level' => '优先级',
        ];
    }
}
