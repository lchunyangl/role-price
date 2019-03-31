<?php

namespace Chunyang\RolePrice\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HdRolePost extends FormRequest
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
        return [
            'name' => 'required|alpha|unique:hd_role,name,' . $this->route('hd_role'),
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'is_enabled' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute 不能为空',
            'alpha' => ':attribute 只能是汉字和字母',
            'unique' => ':attribute 不能重复',
            'date' => ':attribute 日期时间不正确',
            'after' => ':attribute 不能小于 :date',
            'boolean' => ':attribute 只能是0或者1',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '角色名',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'is_enabled' => 'is_enabled',
        ];
    }
}
