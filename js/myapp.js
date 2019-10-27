angular.module('myapp', ['ng', 'ngRoute', 'ngAnimate'])
  .config(function ($routeProvider) {
    $routeProvider.when('/m', {
      templateUrl: 'tpl/main.html',
      controller: 'mainCtrl'
    }).when('/s', {
      templateUrl: 'tpl/start.html',
      controller: 'startCtrl'
    }).when('/d/:dnum', {  //:dnum为一个变量(形参),可换
      templateUrl: 'tpl/detail.html',
      controller: 'detailCtrl'
    }).when('/o/:dtnum',{
      templateUrl:'tpl/order.html',
      controller: 'orderCtrl'
    }).when('/mo',{
      templateUrl:'tpl/myorder.html',
      controller:'myorderCtrl'
    }).otherwise({
      redirectTo: '/s'
    })
  })
  .controller('parentCtrl', function ($scope,$location) {
    $scope.jump = function (url) {
      $location.path(url);
      // console.log("aaa");
    }
  })
  .controller('startCtrl', function ($scope,$location) {

  })
  .controller('mainCtrl', function ($scope, $location, $http) {
    $scope.hasMore=true;
    $scope.dishList = [];
    $http.get('data/dish_listbypage.php?start=0').success(function (data) {
      $scope.dishList = $scope.dishList.concat(data);
    });
    //“加载更多”按钮的单击事件处理函数
    $scope.loadMore = function () {
      $http.get('data/dish_listbypage.php?start=' + $scope.dishList.length).success(function (data) {
        if(data.length<5){
          $scope.hasMore=false;
        }
        $scope.dishList = $scope.dishList.concat(data);
      });
    };
    //监视搜索框中的内容是否改变
    $scope.$watch('kw',function(){
      if($scope.kw){
        $http.get('data/dish_listbykw.php?kw='+$scope.kw).success(function(data){
          $scope.dishList=data;
        })
      }
    });
  })
  .controller('detailCtrl', function ($scope,$routeParams,$http) {
    $http.get('data/dish_listbydid.php?did='+$routeParams.dnum).success(function(data){
      $scope.dt=data[0];
    })
  })
  .controller('orderCtrl',function($scope,$routeParams,$http,$rootScope){
      $scope.order={did:$routeParams.dtnum} ;//order对象中保存着所有要发送给服务器的请求数据,通过双向绑定
      //提交订单之前把用户输入的电话号码保存在全局范围内

      $scope.submitOrder=function(){
        $rootScope.phone=$scope.order.phone;
        // console.log($rootScope.phone);
        var str=jQuery.param($scope.order);
        // $http.get('data/order_add.php?'+str).success(fn)
        $http.post('data/order_add.php',str).success(function(data){
          // console.log(data);
          $scope.result=data[0];
        })
      }
  })
  .controller('myorderCtrl',function($scope,$http,$rootScope){
    // console.log($rootScope.phone);
    $scope.orderList=[];
    $http.post('data/order_listbyphone.php','phone='+$rootScope.phone).success(function(data){
      // console.log(data);
      Date.prototype.toLocaleDateString=function(){
        return this.getFullYear() + "/" + (this.getMonth() + 1) + "/" + this.getDate() + "\t" + this.getHours() + ":" + this.getMinutes();
      };
      $.each(data,function(index,obj){
        obj.id=index;
        var date=new Date(obj.order_time-0);
        // console.log(date.toLocaleDateString());
        obj.order_time=date.toLocaleDateString();
        $scope.orderList.unshift(obj);
      });
      // console.log($scope.orderList);
    })
  })
  .run(function($http){
    //设置$http.post请求的默认请求消息头部
    $http.defaults.headers.post={'Content-Type':'application/x-www-form-urlencoded'}
  });
