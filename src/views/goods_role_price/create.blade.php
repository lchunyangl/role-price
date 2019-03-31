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
        {!! Form::open(['route'=>['goods_role_price.store',$goods->goods_id],'method'=>'post','class'=>'layui-form layui-form-pane','autocomplete'=>'off']) !!}
        @foreach($errors->get('goods_id') as $message)
            {{$message}}
            <br/>
        @endforeach
        <div class="layui-form-item" id="goods">
            <label class="layui-form-label">商品名称</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" placeholder=""
                       autocomplete="off" disabled value="{{$goods->goods_name}}-{{$goods->goods_sn}}">
            </div>
        </div>
        <div>
            @foreach($prices as $price)
                @foreach($errors->get('role.*') as $key=>$messages)
                    @if(str_contains($key,"role.{$price->role_id}."))
                        @foreach($messages as $message)
                            {{$message}}
                            <br/>
                        @endforeach
                    @endif
                @endforeach
                <div class="layui-form-item role-price">
                    <label class="layui-form-label" style="color: red;">{{$price->role->name}}</label>
                    <div class="layui-input-block">
                        <div class="layui-input-inline" style="width: auto">
                            <input type="checkbox" name="role[{{$price->role_id}}][is_promote]" lay-skin="switch"
                                   lay-text="开启|关闭" value="1" @if($price->is_promote==1) checked @endif>
                        </div>
                        <div class="layui-input-inline">
                            <input type="text" name="role[{{$price->role_id}}][promote_start_date]" placeholder=""
                                   autocomplete="off"
                                   class="layui-input datetime-picker" value="{{$price->promote_start_date}}">
                        </div>
                        <div class="layui-form-mid">-</div>
                        <div class="layui-input-inline">
                            <input type="text" name="role[{{$price->role_id}}][promote_end_date]" placeholder=""
                                   autocomplete="off"
                                   class="layui-input datetime-picker" value="{{$price->promote_end_date}}">
                        </div>
                        <div class="layui-form-mid">活动价：</div>
                        <div class="layui-input-inline">
                            <input type="text" name="role[{{$price->role_id}}][goods_price]" placeholder=""
                                   autocomplete="off"
                                   class="layui-input" value="{{$price->goods_price}}">
                        </div>
                        <div class="layui-form-mid">限购数量：</div>
                        <div class="layui-input-inline">
                            <input type="text" name="role[{{$price->role_id}}][limit_num]" placeholder=""
                                   autocomplete="off"
                                   class="layui-input" value="{{$price->limit_num}}">
                        </div>
                        <div class="layui-btn-group">
                            <button class="layui-btn" type="button" onclick="up_down($(this),1)">
                                <i class="layui-icon">&#xe619;</i>
                            </button>
                            <button class="layui-btn" type="button" onclick="up_down($(this),-1)">
                                <i class="layui-icon">&#xe61a;</i>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="role[{{$price->role_id}}][id]" value="{{intval($price->id)}}">
                    <input class="level" type="hidden" name="role[{{$price->role_id}}][level]" value="{{$loop->index}}">
                </div>
            @endforeach
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

        function up_down(_obj, type) {
            var parent = _obj.parents('.role-price');
            if (type == 1) {
                var prev = parent.prev();
                if (typeof (prev.html()) == "undefined") {
                    layer.msg('已经处于最上层，不能移动', {icon: 2});
                } else {
                    prev.fadeOut("fast", function () {
                        $(this).before(parent);
                        parent.find('.level').val(parent.index());
                        $(this).find('.level').val($(this).index());
                    }).fadeIn();
                }
            } else {
                var next = parent.next();
                if (typeof (next.html()) == "undefined") {
                    layer.msg('已经处于最下层，不能移动', {icon: 2});
                } else {
                    next.fadeOut("fast", function () {
                        $(this).after(parent);
                        parent.find('.level').val(parent.index());
                        $(this).find('.level').val($(this).index());
                    }).fadeIn();
                }
            }
        }
    </script>
@endpush