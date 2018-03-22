@section("_layout_pagination")
    <script>
    var page = parseInt({{$page}}) , pageSize = parseInt({{$pageSize}});
    // 上
    function up(func,t){
        page<=2?page=1:page--;
        progress(5);
        window.location.href="/{{htname}}/"+func+""+t+"page="+page+"&pageSize="+pageSize;
    }
    // 下
    function next(func,t){
        page++;
        progress(5);
        window.location.href="/{{htname}}/"+func+""+t+"page="+page+"&pageSize="+pageSize;
    }
    // 跳转页
    function thisData(e,func,t){
        page=e;
        progress(5);
        window.location.href="/{{htname}}/"+func+""+t+"page="+page+"&pageSize="+pageSize;
    }
    // 点击跳转
    function jump(e,t){
        var t_ = $("#jump").val();
        progress(5);
        thisData(t_,e,t);
    }
    /**
     * 分页
     * last 多少页
     * funcname 跳转方法名
     */
    function setPaging(last,funcname,t){
        var temp_ul = "<li><a onclick=up('"+funcname+"','"+t+"') >«</a></li>";
        var temp_ = "<li class='disabled'><span>...</span></li>";
        var _is_active = "";
        var _no_active = "";
        for(var k=1;k<=last;k++){
            _is_active = "<li class='active'><a onclick=thisData("+k+",'"+funcname+"','"+t+"')>"+k+"</a></li>";
            _no_active = "<li><a onclick=thisData("+k+",'"+funcname+"','"+t+"')>"+k+"</a></li>";
            if(last<=10){
                if(k==page){
                    temp_ul += _is_active;
                }else{
                    temp_ul += _no_active;
                }
            }else{
//                  前六页 情况
                if(page<=6){
                    if(k==page){
                        temp_ul += _is_active ;
                    }else if (k<=8 || k>last-3){
                        temp_ul += _no_active;
                    }
                    if(k==9){
                        temp_ul += temp_;
                    }
//                  六页以上
                }else{
                    if(k<3){
                        temp_ul += _no_active;
                    }
                    if(k==2){
                        temp_ul += temp_;
                    }
//                      不是最后六页
                    if(page < (last-6)){
                        if(k==page){
                            temp_ul += _is_active ;
                        }else if((page-4)<k && k<( parseInt(page) + parseInt( 4)) || k>(last-2)){
                            temp_ul += _no_active;
                        }else if(k == (last-3)){
                            temp_ul += temp_;
                        }
//                      最后六页
                    }else{
                        if(k==page){
                            temp_ul += _is_active;
                        }else if (k > last-6){
                            temp_ul += _no_active;
                        }
                    }
                }
            }
        }
        temp_ul += "<li><a onclick=next('"+funcname+"','"+t+"') >»</a></li>";
        temp_ul += "<li style='float: left;width: 50px;margin: 0 10px;'><input id='jump' style='float: left;' type='text' class='form-control' /></li>";
        temp_ul += "<li><button style='float: left;' onclick=jump('"+funcname+"','"+t+"') class='btn btn-default'>跳转</button></li>";
        $("#foot_ul").html(temp_ul);
    }

    function progress(e){
        $("#progress_div").show();
        switch (e){
            case 1 : $("#progress").width("10%");break;
            case 2 : $("#progress").width("20%");break;
            case 3 : $("#progress").width("30%");break;
            case 4 : $("#progress").width("40%");break;
            case 5 : $("#progress").width("50%");break;
            case 6 : $("#progress").width("60%");break;
            case 7 : $("#progress").width("70%");break;
            case 8 : $("#progress").width("80%");break;
            case 9 : $("#progress").width("90%");break;
            case 10 : $("#progress").width("100%");setTimeout(function () {
                $("#progress_div").hide();
                $("#progress").width("0%");
            },500);break;
        }
    }

</script>
@endsection