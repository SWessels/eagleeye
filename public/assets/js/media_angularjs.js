

var app = angular.module('angularTable', ['angularUtils.directives.dirPagination'] , function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');

});

app.controller('listdata',function($scope, $http,$timeout){

    var vm = this;
    vm.users = []; //declare an empty array
    vm.pageno = 1; // initialize page no to 1
    vm.total_count = 0;
    var page_num = 1;
    vm.itemsPerPage = 60; //this could be a dynamic value from a drop down
    var keyword = "";
    $scope.searchText = '';
    /////////////////////////load first time when search bar is empty//////////////////////
   if($scope.searchText == ''){

        vm.getData = function(pageno){ // This would fetch the data on page change.
        vm.users = [];
            $http.get("/media/show_image_library/"+vm.itemsPerPage+"/"+pageno).success(function(response){
               
                vm.users = response.data;  //ajax request to fetch data into vm.data
                vm.total_count = response.total_count;
            }).complete;
        };
        // Call the function to fetch initial data on page load.
        vm.getData(vm.pageno);

    }
   if($scope.change = function(text) { return $scope.searchText; }){

       $scope.change = function(text) {

            keyword = $scope.searchText;
            var pageno = 1;
            vm.getData = function(pageno){ 
                page_num = pageno;
                vm.users = [];
                $http.get("/media/show_image_library/"+vm.itemsPerPage+"/"+pageno+"/"+keyword).success(function(response){

                    vm.users = response.data;
                    vm.total_count = response.total_count;
                }).complete;
            };

            vm.getData(pageno);

        };
    }

});
