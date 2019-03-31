@extends('common.layui2_index')
@section('content')
    <blockquote class="layui-elem-quote"
                style="border-left: none;line-height: normal;padding-left: 30px;padding-right: 30px;">
        @include('common.top_tip')
        <span style="text-align: right;float: right;">
            <a href="{{route('goods_role_price.index')}}" class="layui-btn layui-btn-mini pjax_full">
                查看活动商品价格
            </a>
        </span>
    </blockquote>
    <div style="padding-left: 30px;padding-right: 30px;" class="">
        @include('role-price::hd_role.search')
        <div class="layui-row" id="pjax-container">
            <table class="layui-table">
                <thead>
                <tr>
                    <th>编号</th>
                    <th>名称</th>
                    <th>有效期</th>
                    <th>限制区域</th>
                    <th>限制等级</th>
                    <th>限制会员</th>
                    <th>是否启用</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @forelse($result as $v)
                    <tr>
                        <td>{{$v->id}}</td>
                        <td>{{$v->name}}</td>
                        <td>
                            开始时间：{{$v->start_time}}
                            <br/>
                            结束时间：{{$v->end_time}}
                        </td>
                        <td onclick="show_content($(this))"
                            data-msg="{{$v->region->pluck('region_name')->implode(',')}}"
                            style="cursor: pointer"
                            title="点击查看完整内容">{{str_limit($v->region->pluck('region_name')->implode(','))}}
                        </td>
                        <td>{{$v->rank->pluck('rank_name')->implode(',')}}</td>
                        <td onclick="show_content($(this))"
                            data-msg="{{$v->user->pluck('msn')->implode(',')}}"
                            style="cursor: pointer" title="点击查看完整内容">{{str_limit($v->user->pluck('msn')->implode(','))}}
                        </td>
                        <td><img src="{{yes_no_img($v->is_enabled)}}"></td>
                        <td>
                            {!! edit_handle(route('hd_role.edit',['id'=>$v->id]),'编辑',1) !!}
                            {!! delete_handle(route('hd_role.destroy',['id'=>$v->id])) !!}
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
    <script type="text/javascript" src="{{path('js/area.js')}}"></script>
    <script>
        var form;
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
            form.on('select(cat1)', function (data) {
                var cat = data.value;
                load_category(cat, 'cat2');
                form.render('select');
            });
        });
    </script>
@endpush