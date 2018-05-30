<!DOCTYPE html>
<html  lang="zh" ng-app="question">
<head>
    <meta charset="utf-8" />

    <title>大学生问答网站</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="\node_modules\normalize-css\normalize.css" />
    <link rel="stylesheet" href="\css\base.css">
    <script src="\node_modules\jquery\dist\jquery.js"></script>
    <script src="\node_modules\angular\angular.js"></script>
    <script src="\node_modules\angular-ui-router\release\angular-ui-router.js"></script>
    <script src="\js\base.js"></script>
    
</head>
<body>
    <div class="navbar clearfix">
        <div class="fl">
            <div class="navbar_item">
                <li class="navbar_item brand">问吧</li>
            </div>
            
            <div class="navbar_item">
                <div class="brand1">
                    <input class="navbar_item search" type="text" name="search" placeholder="请输入关键字">
                    <button class="navbar_item button">
                </div>
                
            </div>
            
        </div>
        <div class="fr">
            <li class="navbar_item">item1</li>
            <li class="navbar_item">item2</li>
            <li class="navbar_item">item3</li>
        </div>
    </div>
    <div class="page">
        <div ui-view></div>
    </div>
</body>
<script type="text/ng-template" id="home.tpl">
    <div class="home"></div>
      <h1>首页</h1> 
    </div>
</script>
<script type="text/ng-template" id="login.tpl">
    <div>
    <h1>登录</h1>   
    </div>
</script>
</html>