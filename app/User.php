<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;
use Hash;

class User extends Model
{
    /*注册api*/
    public function signup()
    {
        $username = Request::get('username');
        $password = Request::get('password');
        
        // var_dump($username);
        // var_dump($password);

        /*判断用户名和密码是否为空*/ 
        
        if(!($username && $password))
            return err('username and password is null');
        
        /*判断用户名是否存在*/
        
        $user_exists = $this->where('username',$username)->exists();
        if ($user_exists)
            return err('username is exists');
        
        /*用户密码加密*/

        $password_hashed = Hash::make($password);

        /*存入数据库*/
        
        $user = $this;
        $user->username = $username;
        $user->password = $password_hashed;
        if($user->save())
            return suc(['id' => $user->id]);
        else 
            return err('insert failed');
    }

    /*获取用户信息api*/
    public function read(){
        if(!rq('id'))
            return err('required id');

        $get = ['id','username','avatar_url','intro'];
        //$this->get($get);
        $user = $this->find(rq('id'),$get);
        $data = $user->toArray();
        $answer_count = new_any('Answer')->where('user_id',rq('id'))->count();
        $question_count = new_any('Question')->where('user_id',rq('id'))->count();
        // $answer_count = $user->answers()->count();

        $data['answer_count'] = $answer_count;
        $data['question_count'] = $question_count;

        return suc($data);
        
    }

    /*登录api*/
    public function login()
    {
        /*检查用户名和密码是否存在*/
        $username = rq('username');
        $password = rq('password');
        if(!($username && $password))
            return err('userame and password are required');

        /*判断用户名是否存在*/
        $user = $this->where('username',$username)->first();
        if(!$user)
            return err('user not exists' );
        
        /*检查密码是否正确*/
        $hashed_password = $user->password;
        //第一个参数是明文密码，第二个是数据库里的加密之后的密码
        if(!Hash::check($password,$hashed_password))
            return err('invalid password');
        
        /*登录成功之后将数据写入session*/
        session()->put('logineduser',$user->username);
        session()->put('user_id',$user->id);
        /*返回正确信息和user_id*/
        return ['status' => 1,'id' => $user->id];
    }

    /*注销API*/
    public function logout()
    {
        /*删除username和user_id*/
        session()->pull('logineduser');
        session()->forget('user_id');
        return ['status' => 1];    
    }

    /*判断是否登陆*/
    public function is_logined()
    {
        /*如果已经登录就返回user_id否则返回false*/
        return session('user_id') ?: false;
    }
    
    /*修改密码api*/
    public function change_password(){
        if(!$this->is_logined())
            return err('login required');
        
        if(!rq('old_password') || !rq('new_password'))
            return err('old_password and new_password are required');
        
        /*检查输入的密码和旧密码是否一样*/
        // find当前登录的用户id
        $user = $this->find(session('user_id'));
        if(!Hash::check(rq('old_password'),$user->password))
            return err('ivalid old_password');
        /*插入新密码*/
        $user->password = bcrypt(rq('new_password'));
        //$user->password = Hash::make(rq('new_password'));
        /*存入数据库*/
        if($user->save()){
            return ['status' => 1];
        }else{
            return err('db update failed');
        }
    }

    /*找回密码api*/
    public function reset_password(){
        
        if($this->is_robot())
            return err('max frequency reached');
        
        /*检查电话号码存不存在*/
        if(!rq('phone'))
            return err('phone is required');
        // 这里user是一个model
        $user = $this->where('phone',rq('phone'))->first();

        if(!$user)
            return err('invaild phone number');

         /*生成验证码*/
        $captcha = $this->generate_captcha();

        $user->phone_captcha = $captcha;
        if($user->save()){
             /*如果验证码保存成功发送验证码短信*/
            $this->send_sms();

            /*为下一次机器人调用做准备*/
            $this->update_robot_time();
            return suc();
        }
        return err('db update falied'); 
    }

    /*验证找回密码*/
    public function validate_reset_password(){
        if($this->is_robot(2))
            return err('max frequency reached');

        if(!rq('phone') || !rq('phone_captcha') || !rq('new_password'))
            return err('phone,phone_captcha,new_password are required');
    
        /*检查用户是否存在*/
        $user = $this->where([
            'phone' => rq('phone'),
            'phone_captcha' => rq('phone_captcha')
        ])->first();
        //phone错误或是phone_captcha错误
        if(!$user)
            return err('invalid phone or invalid phone_captcha');
        
        /*加密新密码*/
        $user->password = bcrypt(rq('new_password'));
        $this->update_robot_time();
        return $user->save()?suc():err('db update failed');
    }

    /*检查机器人*/
    public function is_robot($time = 10){
        /*如果session中没有last_action_time说明接口从未被调用过*/
        if(!session('last_action_time'))
            return false;
        //当前的时间
        $current_time = time();
        $last_active_time = session('last_action_time');

        $elapsed = $current_time - $last_active_time;
        return !($elapsed > $time);
    }

    /*更新机器人行为时间*/
    public function update_robot_time(){
        session()->put('last_action_time',time());
    }

    /*发送短信的方法*/
    public function send_sms(){
        return true;
    }

    /*生成验证法（是一个方法）*/
    public function generate_captcha(){
        return rand(1000,9999);
    }

    /*answer和user表多对多连接*/
    public function answers(){
        return $this->belongsToMany('APP\User')->withPivot('vote')->withTimestamps();
    }
    
//     public function questions(){
//         return $this
//          ->belongsToMany('App\Question')
//          ->withPivot('vote')
//          ->withTimestamps();
//     }

    public function exist(){
        return suc(['count' => $this->where(rq())->count()]);
    }
}
