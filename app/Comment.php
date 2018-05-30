<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /*添加评论api*/
    public function add()
    {

        /*判断用户是否登陆*/
        if(!new_any('User')->is_logined())
            return['status' => 0,'msg' =>'you should login'];
            
        /*判断content是否存在*/
        if(!rq('content'))
            return ['status' => 0,'msg' =>'content is not exist'];

        /*既没有问题id也没有答案id*/
        if(!rq('question_id') && !rq('answer_id'))
            return ['status' => 0,'msg' => 'question_id and answer_id not exist'];

        /*既有问题id又有答案id会报错*/
        if(rq('question_id')  && rq('answer_id'))
            return ['status' => 0,'msg' => 'question_id and answer_id cant together exixt'];
        
        /*评论问题*/
        if(rq('question_id'))
        {
            $question = new_any('Question')->find(rq('question_id'));
            
            if(!$question)
                return ['status' => 0,'msg' => 'question not exists'];

            $this->question_id = rq('question_id'); 
        }else{
            /*评论答案*/
            $answer = new_any('Answer')->find(rq('answer_id'));
            if(!$answer)
                return ['status' => 0,'msg' => 'answer not exists'];

            $this->answer_id = rq('answer_id'); 
        }
        /*检查是否存在回复评论的评论*/
        if(rq('reply_to'))
        {
            //$target是你要评论的那条评论
            $target = $this->find(rq('reply_to'));
            /*检查目标评论是否存在*/
            if(!$target)
                return ['status' => 0,'msg' => 'target not exists'];
            /*检查是否在评论自己的评论*/
            if($target->user_id == session('user_id'))
                return ['status' => 0,'msg' => 'canot reply to yourself'];

            $this->reply_to = rq('reply_to'); 
        }
        /*保存到数据库*/
        $this->content = rq('content');
        $this->user_id = session('user_id');
        
        return $this->save()? ['status' => 1,'id' => $this->id]:['status' => 0, 'msg' => 'db insert failed'];
    }

    /*查看评论api*/
    public function look()
    {
        /*没有问题id又没有答案id会报错*/
        if(!rq('question_id')  && !rq('answer_id'))
            return ['status' => 0,'msg' => 'question_id or answer_id cannot exist'];
        /*都有也报错*/
        if(rq('question_id')  && rq('answer_id'))
            return ['status' => 0,'msg' => 'question_id and answer_id cant together exixt'];

            /*查看问题评论*/
        if(rq('question_id'))
        {
            /*查看评论的问题是否存在*/
            $question = new_any('Question')->find(rq('question_id'));
            if(!$question)
                return ['status' => 0,'msg' => 'question not exist'];
            $data = $this->where('question_id',rq('question_id'))->get();

        }else{
            /*查看回答评论*/
            $answer = new_any('Answer')->find(rq('answer_id'));
            if(!$answer)
                return ['status' => 0,'msg' => 'answer not exist'];
            $data = $this->where('answer_id',rq('answer_id'))->get();
        }

        return ['status' => 1,'data' => $data->keyBy('id')];

    }
    /*删除评论api*/
    public function del()
    {
         /*判断用户是否登陆*/
         if(!new_any('User')->is_logined())
         return['status' => 0,'msg' =>'you should login'];

         /*判断id存不存在*/
         if(!rq('id'))
            return ['status' => 0,'msg' =>'id is not exists'];
        $comment = $this->find(rq('id'));
        if(!$comment) return ['status' => 0,'msg' => 'comment is not exist'];

        if($comment->user_id != session('user_id'))
            return ['status' => 0,'msg' => 'not your comment'];
        
        $this->where('reply_to' , rq('id'))->delete();

        return $comment->delete()?
            ['status' => 1]:['status' => 0,'msg' => 'db delete failed'];
    }
}
