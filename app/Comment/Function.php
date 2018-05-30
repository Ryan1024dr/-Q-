<?php

    /*实现动态实例化对象*/
function new_any($any){
    $any = "App\\".$any;
    return new $any;
}
/*重命名request类中get方法*/
function rq($key = null ,$default = null){
    if(!$key) return Request::all();
    return Request::get($key);
}

/*封装一个分页函数*/
/*page代表第几页;limit代表想要查多少条数据*/
function paginate($page=1,$limit=16){
    $limit = $limit ?: 16;
    $skip = ( $page ? $page -1 :0 ) * $limit;
    return [$limit,$skip];
}

/*返回错误信息*/
function err($msg = null){
    return ['status' => 0 , 'msg' => $msg];
}
/*返回正确信息*/
function suc($data_to_merge = []){
    $data = ['status' => 1 , 'data' => []];
    if($data_to_merge)
        $data['data'] = array_merge($data['data'],$data_to_merge);
    return $data;
}