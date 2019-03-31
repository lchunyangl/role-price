@extends('common.layui2_index')
@push('header')
    <style>
        .layui-form-label {
            width: 200px !important;
        }

        .layui-input-block {
            margin-left: 200px !important;
        }
    </style>
@endpush
@section('content')
    <div style="padding: 30px;">
        {!! Form::open(['route'=>['hd_role.update',$info->id],'method'=>'put','class'=>'layui-form layui-form-pane','autocomplete'=>'off']) !!}
        @foreach($errors->all() as $key=>$message)
            {{$message}}
            <br/>
        @endforeach
        <div class="layui-form-item" id="goods"
             data-config='{"input":"goods_name","select":"search_goods","box":"goods_list",
                 "url":"{{route('goods_zp.search_goods')}}","vue":"0","max":"1"}'>
            <label class="layui-form-label">角色名</label>
            <div class="layui-input-inline">
                <input class="layui-input" type="text" placeholder="" name="name" value="{{$info->name}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">有效期</label>
            <div class="layui-input-block">
                <div class="layui-input-inline">
                    <input type="text" name="start_time" placeholder=""
                           autocomplete="off"
                           class="layui-input datetime-picker" value="{{$info->start_time}}">
                </div>
                <div class="layui-form-mid">-</div>
                <div class="layui-input-inline">
                    <input type="text" name="end_time" placeholder=""
                           autocomplete="off"
                           class="layui-input datetime-picker" value="{{$info->end_time}}">
                </div>
            </div>
        </div>
        <div class="layui-form-item" id="region_ids">
            <label class="layui-form-label">区域限制</label>
            <div class="layui-input-block">
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
                <button onclick="add_region($('#region_ids'),'region_ids')" type="button" class="layui-btn create"><i
                            class="layui-icon">&#xe608;</i>添加
                </button>
                <button onclick="add_region_c($('#region_ids'),'region_ids')" type="button" class="layui-btn create"><i
                            class="layui-icon">&#xe608;</i>添加下级区域
                </button>
                <button onclick="clear_check($('#region_ids'),'add_region')" type="button" class="layui-btn create">清除所有
                </button>
            </div>
            <div class="layui-input-block add_region">
                @foreach($info->region as $v)
                    <input checked type="checkbox" name="region_ids[]" value="{{$v->region_id}}"
                           title="{{$v->region_name}}">
                @endforeach
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">等级限制</label>
            <div class="layui-input-block">
                @foreach(user_rank() as $k=>$v)
                    <input @if(in_array($k,$info->rank->pluck('rank_id')->toArray())) checked @endif type="checkbox"
                           name="rank_ids[]"
                           value="{{$k}}"
                           title="{{$v}}">
                @endforeach
            </div>
        </div>
        <div class="layui-form-item" id="user_ids">
            <label class="layui-form-label">会员限制</label>
            <div class="layui-input-block">
                <div class="layui-input-inline">
                    <input id="user_name" type="text" name="user_name" placeholder="" autocomplete="off"
                           class="layui-input">
                </div>
                <div class="layui-input-inline">
                    <select name="user_name" lay-verify="" lay-search class="search_users">
                        <option value="0">没有结果</option>
                    </select>
                </div>
                <button onclick="search_users($('#user_ids'))" type="button" class="layui-btn create"><i
                            class="layui-icon">&#xe615;</i>搜索
                </button>
                <button onclick="add_users($('#user_ids'),'user_ids')" type="button" class="layui-btn create"><i
                            class="layui-icon">&#xe608;</i>添加
                </button>
            </div>
            <div class="layui-input-block add_users">
                @foreach($info->user as $v)
                    <input checked type="checkbox" name="user_ids[]" value="{{$v->user_id}}"
                           title="{{$v->msn}}">
                @endforeach
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">拒绝提示语</label>
            <div class="layui-input-block">
                <input type="text" name="deny_message" placeholder="" autocomplete="off" class="layui-input"
                       value="{{$info->deny_message}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否启用</label>
            <div class="layui-input-inline">
                <input type="radio" name="is_enabled" value="1" title="启用"
                       @if($info->is_enabled==1) checked @endif>
                <input type="radio" name="is_enabled" value="0" title="关闭"
                       @if($info->is_enabled==0) checked @endif>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="*">立即提交</button>
                <button onclick="layer_close()" type="button" class="layui-btn layui-btn-primary">关闭</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
@push('footer')
    <script type="text/javascript" src="{{path('js/area.js')}}"></script>
    <script>
        layui.use(['form'], function () {
            form = layui.form;
            form.on('select(province)', function (data) {
                var province = data.value;
                load_region(province, 'city');
            });
            form.on('select(city)', function (data) {
                var city = data.value;
                load_region(city, 'district');
                form.render('select');
            });
        });

        function add_region(obj, name) {
            if (typeof (obj) == "undefined") {
                var p_obj = $("select[name=province]");
                var c_obj = $("select[name=city]");
                var d_obj = $("select[name=district]");
                var add_region = $('.add_region');
                var name = 'area_xg';
            } else {
                var p_obj = obj.find("select[name=province]");
                var c_obj = obj.find("select[name=city]");
                var d_obj = obj.find("select[name=district]");
                var add_region = obj.find(".add_region");
            }
            var p_val = parseInt(p_obj.val());
            var c_val = parseInt(c_obj.val());
            var d_val = parseInt(d_obj.val());
            var p_title = p_obj.find("option:selected").text();
            var c_title = c_obj.find("option:selected").text();
            var d_title = d_obj.find("option:selected").text();
            var val = 0;
            var title = '';
            if (d_val > 0) {
                val = d_val;
                title = d_title;
            } else if (c_val > 0) {
                val = c_val;
                title = c_title;
            } else if (p_val > 0) {
                val = p_val;
                title = p_title;
            }
            if (val > 0) {
                var type = 1;
                add_region.find('input[type=checkbox]').each(function () {
                    var region = parseInt($(this).val());
                    if (val == region) {
                        type = 0;
                        return true;
                    }
                });
                if (type == 0) {
                    layer.msg('已选中', {icon: 2});
                } else {
                    var html = '<input type="checkbox" name="' + name + '[]" value="' + val + '" title="' + title + '" checked>';
                    add_region.append(html);
                    form.render('checkbox');
                    add_region.show();
                }
            }
        }

        function add_region_c(obj, name) {
            if (typeof (obj) == "undefined") {
                var p_obj = $("select[name=province]");
                var c_obj = $("select[name=city]");
                var d_obj = $("select[name=district]");
                var add_region = $('.add_region');
                var name = 'ls_regions';
            } else {
                var p_obj = obj.find("select[name=province]");
                var c_obj = obj.find("select[name=city]");
                var d_obj = obj.find("select[name=district]");
                var add_region = obj.find(".add_region");
            }
            var p_val = parseInt(p_obj.val());
            var c_val = parseInt(c_obj.val());
            var select = p_obj;
            if (p_val > 0) {
                select = c_obj;
            }
            if (c_val > 0) {
                select = d_obj;
            }
            var num = 0;
            select.find('option').each(function () {
                var val = parseInt($(this).val());
                var title = $(this).text();
                if (val > 0) {
                    var type = 1;
                    add_region.find('input[type=checkbox]').each(function () {
                        var region = parseInt($(this).val());
                        if (val == region) {
                            type = 0;
                            return true;
                        }
                    });
                    if (type == 1) {
                        var html = '<input type="checkbox" name="' + name + '[]" value="' + val + '" title="' + title + '" checked>';
                        add_region.append(html);
                        num++;
                    }
                }
            });
            if (num > 0) {
                form.render('checkbox');
                add_region.show();
            }
        }

        function clear_check(obj, name) {
            if (typeof (obj) == "undefined") {
                var add_region = $('.add_region');
            } else {
                var add_region = obj.find("." + name);
            }
            add_region.hide();
            add_region.html('');
        }

        function search_users(obj) {
            if (typeof (obj) == "undefined") {
                var keywords = $("#user_name");
                var search_users = $(".search_users");
            } else {
                var keywords = obj.find("input[name=user_name]");
                var search_users = obj.find(".search_users");
            }
            var val = keywords.val();
            if (val == '') {
                layer.msg('不能为空', {icon: 2});
            } else {
                $.ajax({
                    url: '/search_users_new',
                    data: {keywords: val},
                    type: 'post',
                    dataType: 'html',
                    success: function (str) {
                        search_users.html(str);
                        form.render('select');
                    }
                });
            }
        }

        function add_users(obj, name) {
            if (typeof (obj) == "undefined") {
                var obj = $("select[name=user_name]");
                var add_users = $('.add_users');
                var name = 'ls_user_ids';
            } else {
                var add_users = obj.find(".add_users");
                var obj = obj.find("select[name=user_name]");
            }
            var val = parseInt(obj.val());
            var title = obj.find("option:selected").text();
            if (val > 0) {
                var type = 1;
                add_users.find('input[type=checkbox]').each(function () {
                    var user_id = parseInt($(this).val());
                    if (val == user_id) {
                        type = 0;
                        return true;
                    }
                });
                if (type == 0) {
                    layer.msg('已选中', {icon: 2});
                } else {
                    var html = '<input type="checkbox" name="' + name + '[]" value="' + val + '" title="' + title + '" checked>';
                    add_users.append(html);
                    form.render('checkbox');
                    add_users.show();
                }
            }
        }
    </script>
@endpush