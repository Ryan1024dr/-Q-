<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    /*添加回答*/
    public function add(){
        
        /*判断用户是否登陆*/
        if(!new_any('User')->is_logined())
            return ['status' => 0,'msg' =>'you shoud login'];
        /*判断question_id和content是否存在*/
        if(!rq('question_id') || !rq('content'))
            return ['status' => 0,'msg' => 'qustion_id and content are not exist'];
        /*查找数据库*/
        $question = new_any('Answer')->find(rq('question_id'));

        if(!$question)
            return ['status' => 0,'msg' =>'question not exist'];
        /*获取数据*/
        $this->content = rq('content');
        $this->question_id = rq('question_id');
        $this->user_id = session('user_id');
        
        /*数据插入*/
        return $this->save()?
        ['status' => 1,'msg' =>$this->id]:
        ['status' => 0,'msg' => 'db insert failed'];
    }

    /*更新回答*/
    public function change(){

         /*判断用户是否登陆*/
         if(!new_any('User')->is_logined())
         return ['status' => 0,'msg' =>'you shoud login'];
          /*id和content是否存在*/
        if(!rq('id') || !rq('content'))
        return ['status' => 0,'msg' => 'id and content are not exist'];

        /*查找id*/
        $answer = $this->find(rq('id'));

        /*判断id和user_id是否一致*/
        if($answer->user_id != session('user_id'))
            return ['status' => 0,'msg' => 'no power to change'];

        $answer ->content = rq('content');

        /*写入数据库*/

        return $this->save()?
        ['status' => 1]:
        ['status' => 0,'msg' => 'db update failed'];
    }

    /*查看回答*/
    public function look(){

        /*判断id和question_id是否存在*/
        if(!rq('id') && !rq('question_id'))
            return ['status' => 0, 'msg' =>'id and qusetion_id are required'];
        /*用户想查看问题的回答*/
        if(rq('id'))
        {
            $answer = $this->find(rq('id'));
            if($answer)
                return ['status' => 0,'msg' => 'answer not exist'];
            return ['status' => 1,'data' => $answer];

        }
        /*用户想查看自己的回答*/
        if(!new_any('Question')->find(rq('question_id')))
            return ['status' => 0,'msg' =>'question not exist'];
        $answer = $this
        ->where('question_id',rq('question_id'))
        ->get()
        ->keyBy('id');

        return ['status' => 1,'data' => $answer]; 
    }
    /*用户给answer投票*/
    public function  vote(){
        /*判断用户是否登陆*/
        if(!new_any('User')->is_logined())
            return ['status' => 0,'msg' =>'you shoud login'];

        if(!rq('id') || !rq('vote'))
            return ['status' => 0 ,'msg' => 'id and vote are request '];
        
        $answer = $this->find(rq('id'));
        if(!$answer) 
            return ['status' => 0,'msg' => 'answer not exists'];
        
        /*1赞同票 2反对票*/
        $vote = rq('vote') <= 1 ? 1 : 2;

        /*检查此用户是否在相同问题下投票,如果投过票，删除投票*/
        $answer->users()
                ->newPivotStatement()
                ->where('user_id',session('user_id'))
                ->where('answer_id',rq('id'))
                ->delete();

        /*在连接表中增加数据*/
        $answer->users()->attach(session('user_id'),['vote' => $vote]);

        return ['status' => 1];
    }

    /*answer和user表多对多连接*/
    public function users(){
        return $this->belongsToMany('APP\User')->withPivot('vote')->withTimestamps();
    }

}
