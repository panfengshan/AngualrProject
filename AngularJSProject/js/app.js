/**
 * Created by Administrator on 2016/3/14.
 */
angular.module('kaifanla',['ng','ngRoute','ngAnimate'])
  .controller('parentCtrl',function($scope){
    $scope.headerFile='tpl/header.html';
    $scope.footerFile='tpl/footer.html';
  })
  .controller('startCtrl',function($scope,$timeout,$location){
    $timeout(function(){
      $location.path('/main');
    },3000)
  })
  .controller('mainCtrl',function($scope,$http){
    //当View一呈现就要像服务器请求菜品数据
    $http.get('data/dish_getbypage.php?start=')
      .success(function(data){
        $scope.dishList=data;  //把服务器端返回的数据上升为model变量，就可以进行双向数据绑定
        //$scope.digest();   //如果用jQuery的get方法就要写这个轮训
      });
    $scope.hasMore=true;
    $scope.loadMore=function(){
      $http.get('data/dish_getbypage.php?start='+$scope.dishList.length)
        .success(function(data){
          $scope.dishList=$scope.dishList.concat(data);
          if(data.length<5){
            $scope.hasMore=false;
          }
        });
    }
    //当搜索关键字发生改变，立即向服务器发请求
    $scope.$watch('kw',function(){
      if($scope.kw){  //若输入框不为空才发送请求
        $http.get('data/dish_getbykw.php?kw='+$scope.kw)
          .success(function(data){
            $scope.dishList=data;
          })
        }
      });

  })
  .controller('detailCtrl',function($scope,$routeParams,$http){
    $http.get('data/dish_getbyid.php?did='+$routeParams.dnum)
      .success(function(data){
        $scope.dishList=data[0];
      })
  })
  .controller('orderCtrl',function($scope,$rootScope,$routeParams,$http){
    $scope.order={did:$routeParams.dorder};//用于封装用户的所有输入
    $scope.isshow=true;
    $scope.show=function(){

      var result=jQuery.param($scope.order);
      $http.get('data/order_add.php?'+result)
        .success(function(data){
          if(data.status==200){
            $scope.isshow=false;
          }
          $scope.num=data.reason;
        })
      $rootScope.phone=$scope.order.phone;
    }
  })
  .controller('myorderCtrl',function($scope,$http,$rootScope){
      $http.get('data/order_getbyphone.php?phone='+$rootScope.phone)
        .success(function(data){
          $scope.orderList=data;
        })
  })
  .config(function($routeProvider){
    $routeProvider
      .when('/start',{
        templateUrl:'tpl/start.html',
        controller:'startCtrl'
      })
      .when('/main',{
      templateUrl:'tpl/main.html',
        controller:'mainCtrl'
    })
      .when('/detail/:dnum',{  //num是参数，可随意取名，不加冒号（:）的话，/num还是固定的，而/:num可以是变化的
      templateUrl:'tpl/detail.html',
        controller:'detailCtrl'
    })
      .when('/order/:dorder',{
      templateUrl:'tpl/order.html',
        controller:'orderCtrl'
    })
      .when('/myorder',{
        templateUrl:'tpl/myorder.html',
        controller:'myorderCtrl'
      })
		 .otherwise({
		redirectTo:'/start'
	  });
  })

