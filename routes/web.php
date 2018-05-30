<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('index');
});

Route::get('api',function() {
    return ['version'=>0.1];
});

/**************************************************************/
/*user.api*/
/**************************************************************/
/*注册api*/
Route::any('api/user/signup',function() {
    return new_any('User')->signup();
});
/*登录api*/
Route::any('api/user/login',function(){
    return new_any('User')->login();
});
/*判断用户是否登录api*/
Route::any('api/user/is_logined',function(){
    return new_any('User')->is_logined();
});
/*注销api*/
Route::any('api/user/logout',function(){
    return new_any('User')->logout();
});
/*修改密码api*/
Route::any('api/user/change_password',function(){
    return new_any('User')->change_password();
});
/*重置密码api*/
Route::any('api/user/reset_password',function(){
    return new_any('User')->reset_password();
});
/*验证找回密码api*/
Route::any('api/user/validate_reset_password',function(){
    return new_any('User')->validate_reset_password();
});
/*获取用户信息api*/
Route::any('api/user/read',function(){
    return new_any('User')->read();
});
/*检查用户是否存在api*/
Route::any('api/user/exist',function(){
    return new_any('User')->exist();
});

/**************************************************************/
/*question.api*/
/**************************************************************/

/*提出问题api*/
Route::any('api/question/add',function(){
    return new_any('Question')->add();
});
/*更新问题api*/
Route::any('api/question/change',function(){
    return new_any('Question')->change();
});
/*查看问题api*/
Route::any('api/question/look',function(){
    return new_any('Question')->look();
});
/*删除问题api*/
Route::any('api/question/del',function(){
    return new_any('Question')->del();
});

/**************************************************************/
/*answer.api*/
/**************************************************************/
/*新增回答api*/
Route::any('api/answer/add',function()
{
    return new_any('Answer')->add();
});
/*修改回答api*/
Route::any('api/answer/change',function()
{
    return new_any('Answer')->change();
});
/*查看回答api*/
Route::any('api/answer/look',function()
{
    return new_any('Answer')->look();
});
/*投票api*/
Route::any('api/answer/vote',function()
{
    return new_any('Answer')->vote();
});


/**************************************************************/
/*comment.api*/
/**************************************************************/
/*增加评论api*/
Route::any('api/comment/add',function(){
    return new_any('Comment')->add();
});
/*查看评论api*/
Route::any('api/comment/look',function(){
    return new_any('Comment')->look();
});
/*删除评论api*/
Route::any('api/comment/del',function(){
    return new_any('Comment')->del();
});
Route::any('api/user/change_password',function() {
    return user_ins()->change_password();
});
 
/*时间线(在首页问题和评论的综合)*/
Route::any('api/timeline','CommonController@timeline');
 
