<form id="search_form" class="layui-form" action="{{$tip3}}">
    <div class="layui-form-item">
        <div class="layui-inline">
            <div class="layui-input-inline" style="width: 100px;">
                <select name="province" lay-verify="" lay-filter="province">
                    <option value="0">请选择省</option>
                    @foreach(get_region_list() as $k=>$v)
                        <option value="{{$k}}">{{$v}}</option>
                    @endforeach
                </select>
            </div>
            <div class="layui-form-mid">-</div>
            <div class="layui-input-inline" style="width: 100px;">
                <select name="city" lay-verify="" lay-filter="city">
                    <option value="0">请选择市</option>
                </select>
            </div>
            <div class="layui-form-mid">-</div>
            <div class="layui-input-inline" style="width: 100px;">
                <select name="district" lay-verify="">
                    <option value="0">请选择区</option>
                </select>
            </div>
        </div>
        <div class="layui-inline">
            <button lay-submit lay-filter="*" type="submit" class="layui-btn layui-btn-mini"><i class="layui-icon">&#xe615;</i>搜索
            </button>
            <button onclick="create_a('{{route('hd_role.create')}}','新建活动角色')" type="button"
                    class="layui-btn create layui-btn-mini"><i
                        class="layui-icon">&#xe608;</i>新建
            </button>
        </div>
    </div>
</form>