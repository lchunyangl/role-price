<form id="search_form" class="layui-form" action="{{$tip3}}">
    <div class="layui-form-item">
        <div class="layui-inline">
            <div class="layui-input-inline" style="width: 150px;">
                <select name="status" lay-verify="">
                    <option value="">状态</option>
                    <option value="1">生效中</option>
                    <option value="2">未开始</option>
                    <option value="3">已结束</option>
                </select>
            </div>
        </div>
        <div class="layui-inline">
            <div class="layui-input-inline" style="width: 150px;">
                <select name="is_promote" lay-verify="">
                    <option value="">是否启用</option>
                    <option value="1">未启用</option>
                    <option value="2">已启用</option>
                </select>
            </div>
        </div>
        <div class="layui-inline">
            <div class="layui-input-inline" style="width: 150px;">
                <select name="role_id" lay-verify="">
                    <option value="">请选择角色</option>
                    @foreach($roles as $k=>$v)
                        <option value="{{$k}}" @if(request('group_type')==$k) selected @endif>{{$v}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-inline">
            <input type="text" name="goods_sn" placeholder="商品货号" autocomplete="off" class="layui-input"
                   value="{{request('goods_sn','')}}">
        </div>
        <div class="layui-inline">
            <button lay-submit lay-filter="*" type="submit" class="layui-btn layui-btn-mini"><i class="layui-icon">&#xe615;</i>搜索
            </button>
        </div>
    </div>
</form>