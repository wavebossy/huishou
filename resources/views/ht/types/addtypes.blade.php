@extends('ht.layouts._ht_layout')

@section('content')
    @include('ht.layouts._myalert')
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6">
            <form role="form" action="/{{htname}}/typesSave" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{csrf_token()}}" />
                <div class="form-group">
                    <label for="name">分类名称</label>
                    <select class="form-control" name="types_id" >
                        @foreach($types as $type)
                            <option value="{{$type->id}}">{{$type->type_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">子分类名称</label>
                    <input type="text" class="form-control" name="type_name" placeholder="子分类名称" required='required' oninvalid="setCustomValidity('请输入子分类名称')" oninput="setCustomValidity('')">
                </div>
                <div class="form-group">
                    <label for="name">价格</label>
                    <input type="text" class="form-control" name="prices" placeholder="价格" required='required' oninvalid="setCustomValidity('请输入回收显示价格')" oninput="setCustomValidity('')">
                </div>
                <div class="form-group">
                    <label for="name">单位</label>
                    <input type="text" class="form-control" name="units" placeholder="单位,如【元/公斤】" required='required' oninvalid="setCustomValidity('请输入回收显示价格')" oninput="setCustomValidity('')">
                </div>
                <div class="form-group">
                    <label for="name">回收备注</label>
                    <input type="text" class="form-control" name="remark" placeholder="备注信息" >
                </div>
                <div class="form-group">
                    <label for="name">显示顺序</label>
                    <input type="text" class="form-control" name="sub_sort" placeholder="显示顺序,可不填" >
                </div>
                <div class="form-group">
                    <label for="inputfile">添加图片</label>
                    <input type="file" name="imgfile" >
                </div>
                <button type="submit" class="btn btn-default">添加</button>
            </form>
        </div>
    </div>

@endsection
