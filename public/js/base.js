;(function()
{
    'user strict';

    angular.module('question',[
        'ui.router',
    ])
    /*配置文件*/
    .config(function($interpolateProvider,
        $stateProvider,
        $urlRouterProvider)
    {
        $interpolateProvider.startSymbol('[:');
        $interpolateProvider.endSymbol(':]');

        //$urlRouterProvider.otherwise('/login');
        
        $stateProvider
            .state('home',{
                url:'/home',
                templateUrl:'home.tpl'
        })
            .state('login',{
                url:'/login',
                templateUrl:'login.tpl'
        })
    })
})();