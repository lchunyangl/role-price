@extends('common.layui2_index')
@push('header')
    <style>
        .layui-form-label {
            width: 150px !important;
        }

        .layui-input-block {
            margin-left: 150px !important;
        }
    </style>
@endpush
@section('content')
    <div style="padding: 30px;">
        {!! Form::open(['route'=>['goods_role_price.update',$info->id],'method'=>'put','class'=>'layui-form layui-form-pane','autocomplete'=>'off']) !!}
        <div class="layui-form-item" id="goods">
            <label class="layui-form-label">商品名称</label>
            <div class="layui-input-block">
                <div class="layui-input-inline">
                    <input class="layui-input" type="text" placeholder=""
                           autocomplete="off" disabled value="{{$info->goods->goods_name}}-{{$info->goods->goods_sn}}">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">活动角色</label>
            <div class="layui-input-block">
                <div class="layui-input-inline">
                    <input class="layui-input" type="text" placeholder=""
                           autocomplete="off" disabled value="{{$info->role->name or '角色不存在'}}">
                </div>
            </div>
        </div>
        @foreach($errors->all() as $key=>$message)
            {{$message}}
            <br/>
        @endforeach
        <div class="layui-form-item">
            <label class="layui-form-label">活动有效期</label>
            <div class="layui-input-block">
                <div class="layui-input-inline">
                    <input type="text" name="role[{{$info->role_id}}][promote_start_date]" placeholder=""
                           autocomplete="off"
                           class="layui-input datetime-picker" value="{{$info->promote_start_date}}">
                </div>
                <div class="layui-form-mid">-</div>
                <div class="layui-input-inline">
                    <input type="text" name="role[{{$info->role_id}}][promote_end_date]" placeholder=""
                           autocomplete="off"
                           class="layui-input datetime-picker" value="{{$info->promote_end_date}}">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">活动价格</label>
            <div class="layui-input-inline">
                <input class="layui-input" type="text" name="role[{{$info->role_id}}][goods_price]" lay-skin="switch"
                       value="{{$info->goods_price}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">限购数量</label>
            <div class="layui-input-inline">
                <input class="layui-input" type="text" name="role[{{$info->role_id}}][limit_num]" lay-skin="switch"
                       value="{{$info->limit_num}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">优先级</label>
            <div class="layui-input-inline">
                <input class="layui-input" type="text" name="role[{{$info->role_id}}][level]" lay-skin="switch"
                       value="{{$info->level}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否启用</label>
            <div class="layui-input-inline">
                <input type="radio" name="role[{{$info->role_id}}][is_promote]" value="1" title="启用"
                       @if($info->is_promote==1) checked @endif>
                <input type="radio" name="role[{{$info->role_id}}][is_promote]" value="0" title="关闭"
                       @if($info->is_promote==0) checked @endif>
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
        });
    </script>
@endpush