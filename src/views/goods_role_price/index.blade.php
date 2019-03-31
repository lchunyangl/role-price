@extends('common.layui2_index')
@section('content')
    <blockquote class="layui-elem-quote"
                style="border-left: none;line-height: normal;padding-left: 30px;padding-right: 30px;">
        @include('common.top_tip')
        <span style="text-align: right;float: right;">
            <a href="{{route('hd_role.index')}}" class="layui-btn layui-btn-mini pjax_full">
                查看活动角色
            </a>
        </span>
    </blockquote>
    <div style="padding-left: 30px;padding-right: 30px;" class="">
        @include('role-price::goods_role_price.search')
        <div class="layui-row" id="pjax-container">
            <table class="layui-table">
                <thead>
                <tr>
                    <th>编号</th>
                    <th>商品货号</th>
                    <th>商品名称</th>
                    <th>活动角色</th>
                    <th>活动有效期</th>
                    <th>活动价格</th>
                    <th>限购数量</th>
                    <th>总量</th>
                    <th>是否开启促销</th>
                    <th>优先级</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @forelse($result as $v)
                    <tr>
                        <td>{{$v->id}}</td>
                        <td>{{$v->goods->goods_sn}}</td>
                        <td>{{$v->goods->goods_name}}</td>
                        <td onclick="show_content($(this))"
                            data-msg="限制区域：{{$v->role->region->pluck('region_name')->implode(',')}}
                                    <br/>限制等级：{{$v->role->rank->pluck('rank_name')->implode(',')}}
                                    <br/>限制会员：{{$v->role->user->pluck('msn')->implode(',')}}"
                            style="cursor: pointer" title="点击查看完整内容">{{$v->role->name or '角色不存在'}}
                        </td>
                        <td>
                            开始时间：{{$v->promote_start_date}}
                            <br/>
                            结束时间：{{$v->promote_end_date}}
                        </td>
                        <td>{{price_formats($v->goods_price)}}</td>
                        <td>{{$v->limit_num}}</td>
                        <td>{{$v->total_num}}</td>
                        <td>
                            <img src="{{yes_no_img($v->is_promote)}}"/>
                        </td>
                        <td>{{$v->level}}</td>
                        <td>
                            {!! edit_handle(route('goods_role_price.edit',['id'=>$v->id])) !!}
                            {!! delete_handle(route('goods_role_price.destroy',['id'=>$v->id])) !!}
                        </td>
                    </tr>
                @empty
                    <tr style="text-align: center">
                        <td colspan="20">没有结果</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {!! $result->links('common.layui2_pages') !!}
        </div>
    </div>
@endsection
@push('footer')
    @include('common.pjax2')
    <script>
        layui.use(['form'], function () {
            form = layui.form;
        });

        function show_content(obj) {
            var msg = obj.data('msg');
            layer.alert(msg, {
                title: '区域',
                skin: 'layui-layer-lan' //样式类名
                , closeBtn: 0
            });
        }

        function up_sm(obj) {
            var config = obj.data('config');
            if (config.confirm == 1) {
                layer.confirm(config.msg, function (index) {
                    //此处请求后台程序，下方是成功后的前台处理……
                    $('.layui-layer-btn1').click();
                    $.ajax({
                        url: config.url,
                        type: config.method,
                        dataType: 'json',
                        success: function (data) {
                            layer.msg(data.msg, {icon: parseInt(data.error) + 1, time: 1000});
                        }
                    });
                });
            } else {
                $.ajax({
                    url: config.url,
                    type: config.method,
                    dataType: 'json',
                    success: function (data) {
                        layer.msg(data.msg, {icon: parseInt(data.error) + 1, time: 1000});
                    }
                });
            }
        }
    </script>
@endpush