<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommonController extends Controller
{
    /*时间线api */
    public function timeline(){
        list($limit,$skip) = paginate(rq('page'),rq('limit'));
        /*获取问题数据 */
        $questions = new_any('Question')
            ->limit($limit)
            ->skip($skip)
            ->orderBy('created_at','desc')
            ->get();

        /*获取回答数据 */
        $answers = new_any('Answer')
            ->limit($limit)
            ->skip($skip)
            ->orderBy('created_at','desc')
            ->get();

        // dd($questions->toArray());
        // dd($answers->toArray());

        /*合并数据 */
        /*merge是update和inster的合并，通过原表或子查询的连接条件对另外一张表进行查询*/
        $data = $questions->merge($answers);
        /*将合并的数据按时间倒序排序*/
        $data = $data->sortByDesc(function($item){
            return $item->create_at;
        });

        $data = $data->values()->all();

        return [ 'status' => 1, 'data' => $data];
    }
}
