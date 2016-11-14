var app = angular.module('productApp',[] , function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');

});
app.controller('listdata',function($scope, $http){

    var vm = this;
    vm.users = []; //declare an empty array

        ////////////////Default values on page load/////////////////
    var keywords = 'undefined';
    var items_perpage = '50';
    var sort_type = 'sales'
    var order = 'DESC';

    /////////////////////////load first time when page load//////////////////////

        vm.getData = function(){
           // This would fetch the data on page change.
            vm.users = [];
            $http.get("/reports/show_sale_by_products/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){
                vm.users = response.data;
            }).complete;
        };

        vm.getData();


        $scope.limit = function() {
        var keywords = $scope.keywords;
        var items_perpage = $scope.limitText;
            if(keywords === '' ){
                keywords = 'undefined';
            }
            $http.get("/reports/show_sale_by_products/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

                vm.users = response.data;  //ajax request to fetch data into vm.data
            }).complete;
        };

        $scope.filter = function() {
            var keywords = $scope.keywords;
            var items_perpage = $scope.limitText;

            if(keywords === ''){
                keywords = 'undefined';
            }
            $http.get("/reports/show_sale_by_products/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

                vm.users = response.data;  //ajax request to fetch data into vm.data
            }).complete;
        };

        $scope.sort = function(sort_type) {

            var keywords = $scope.keywords;
            var items_perpage = $scope.limitText;
            
            
            if(keywords === ''){

                keywords = 'undefined';
            }

            if(angular.element(caret).hasClass('') ){
                var order = 'ASC';

                $http.get("/reports/show_sale_by_products/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

                    vm.users = response.data;  //ajax request to fetch data into vm.data

                }).complete;
                angular.element(caret).addClass('fa');
                angular.element(caret).addClass('fa-caret-down');

            }
            else if(angular.element(caret).hasClass('fa fa-caret-down')){
                var order = 'DESC';

                $http.get("/reports/show_sale_by_products/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

                    vm.users = response.data;  //ajax request to fetch data into vm.data

                }).complete;
                angular.element(caret).removeClass('fa-caret-down');
                angular.element(caret).addClass('fa');
                angular.element(caret).addClass('fa-caret-up');


            }
            else if(angular.element(caret).hasClass('fa fa-caret-up')){
                var order = 'ASC';

                $http.get("/reports/show_sale_by_products/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

                    vm.users = response.data;  //ajax request to fetch data into vm.data

                }).complete;
                angular.element(caret).removeClass('fa-caret-up');
                angular.element(caret).addClass('fa');
                angular.element(caret).addClass('fa-caret-down');
            }
        };


});

app.controller('listdata_lmonth',function($scope, $http){

    var vm = this;
    vm.users = []; //declare an empty array

    ////////////////Default values on page load/////////////////
    var keywords = 'undefined';
    var items_perpage = '50';
    var sort_type = 'sales'
    var order = 'DESC';

    /////////////////////////load first time when page load//////////////////////

    vm.getData = function(){
        // This would fetch the data on page change.
        vm.users = [];
        $http.get("/reports/show_sale_by_products_lmonth/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){
            vm.users = response.data;
        }).complete;
    };

    vm.getData();


    $scope.limit = function() {
        var keywords = $scope.keywords;
        var items_perpage = $scope.limitText;
        if(keywords === '' ){
            keywords = 'undefined';
        }
        $http.get("/reports/show_sale_by_products_lmonth/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

            vm.users = response.data;  //ajax request to fetch data into vm.data
        }).complete;
    };

    $scope.filter = function() {
        var keywords = $scope.keywords;
        var items_perpage = $scope.limitText;

        if(keywords === ''){
            keywords = 'undefined';
        }
        $http.get("/reports/show_sale_by_products_lmonth/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

            vm.users = response.data;  //ajax request to fetch data into vm.data
        }).complete;
    };

    $scope.sort = function(sort_type) {

        var keywords = $scope.keywords;
        var items_perpage = $scope.limitText;


        if(keywords === ''){

            keywords = 'undefined';
        }

        if(angular.element(caret_l).hasClass('') ){
            var order = 'ASC';

            $http.get("/reports/show_sale_by_products_lmonth/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

                vm.users = response.data;  //ajax request to fetch data into vm.data

            }).complete;
            angular.element(caret_l).addClass('fa');
            angular.element(caret_l).addClass('fa-caret-down');

        }
        else if(angular.element(caret_l).hasClass('fa fa-caret-down')){
            var order = 'DESC';

            $http.get("/reports/show_sale_by_products_lmonth/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

                vm.users = response.data;  //ajax request to fetch data into vm.data

            }).complete;
            angular.element(caret_l).removeClass('fa-caret-down');
            angular.element(caret_l).addClass('fa');
            angular.element(caret_l).addClass('fa-caret-up');


        }
        else if(angular.element(caret_l).hasClass('fa fa-caret-up')){
            var order = 'ASC';

            $http.get("/reports/show_sale_by_products_lmonth/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

                vm.users = response.data;  //ajax request to fetch data into vm.data

            }).complete;
            angular.element(caret_l).removeClass('fa-caret-up');
            angular.element(caret_l).addClass('fa');
            angular.element(caret_l).addClass('fa-caret-down');
        }
    };


});

app.controller('listdata_cmonth',function($scope, $http){

    var vm = this;
    vm.users = []; //declare an empty array

    ////////////////Default values on page load/////////////////
    var keywords = 'undefined';
    var items_perpage = '50';
    var sort_type = 'sales'
    var order = 'DESC';

    /////////////////////////load first time when page load//////////////////////

    vm.getData = function(){
        // This would fetch the data on page change.
        vm.users = [];
        $http.get("/reports/show_sale_by_products_cmonth/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){
            vm.users = response.data;
        }).complete;
    };

    vm.getData();


    $scope.limit = function() {
        var keywords = $scope.keywords;
        var items_perpage = $scope.limitText;
        if(keywords === '' ){
            keywords = 'undefined';
        }
        $http.get("/reports/show_sale_by_products_cmonth/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

            vm.users = response.data;  //ajax request to fetch data into vm.data
        }).complete;
    };

    $scope.filter = function() {
        var keywords = $scope.keywords;
        var items_perpage = $scope.limitText;

        if(keywords === ''){
            keywords = 'undefined';
        }
        $http.get("/reports/show_sale_by_products_cmonth/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

            vm.users = response.data;  //ajax request to fetch data into vm.data
        }).complete;
    };

    $scope.sort = function(sort_type) {

        var keywords = $scope.keywords;
        var items_perpage = $scope.limitText;


        if(keywords === ''){

            keywords = 'undefined';
        }

        if(angular.element(caret_c).hasClass('') ){
            var order = 'ASC';

            $http.get("/reports/show_sale_by_products_cmonth/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

                vm.users = response.data;  //ajax request to fetch data into vm.data

            }).complete;
            angular.element(caret_c).addClass('fa');
            angular.element(caret_c).addClass('fa-caret-down');

        }
        else if(angular.element(caret_c).hasClass('fa fa-caret-down')){
            var order = 'DESC';

            $http.get("/reports/show_sale_by_products_cmonth/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

                vm.users = response.data;  //ajax request to fetch data into vm.data

            }).complete;
            angular.element(caret_c).removeClass('fa-caret-down');
            angular.element(caret_c).addClass('fa');
            angular.element(caret_c).addClass('fa-caret-up');


        }
        else if(angular.element(caret_c).hasClass('fa fa-caret-up')){
            var order = 'ASC';

            $http.get("/reports/show_sale_by_products_cmonth/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

                vm.users = response.data;  //ajax request to fetch data into vm.data

            }).complete;
            angular.element(caret_c).removeClass('fa-caret-up');
            angular.element(caret_c).addClass('fa');
            angular.element(caret_c).addClass('fa-caret-down');
        }
    };


});

app.controller('listdata_14day',function($scope, $http){

    var vm = this;
    vm.users = []; //declare an empty array

    ////////////////Default values on page load/////////////////
    var keywords = 'undefined';
    var items_perpage = '50';
    var sort_type = 'sales'
    var order = 'DESC';

    /////////////////////////load first time when page load//////////////////////

    vm.getData = function(){
        // This would fetch the data on page change.
        vm.users = [];
        $http.get("/reports/show_sale_by_products_14day/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){
            vm.users = response.data;
        }).complete;
    };

    vm.getData();


    $scope.limit = function() {
        var keywords = $scope.keywords;
        var items_perpage = $scope.limitText;
        if(keywords === '' ){
            keywords = 'undefined';
        }
        $http.get("/reports/show_sale_by_products_14day/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

            vm.users = response.data;  //ajax request to fetch data into vm.data
        }).complete;
    };

    $scope.filter = function() {
        var keywords = $scope.keywords;
        var items_perpage = $scope.limitText;

        if(keywords === ''){
            keywords = 'undefined';
        }
        $http.get("/reports/show_sale_by_products_14day/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

            vm.users = response.data;  //ajax request to fetch data into vm.data
        }).complete;
    };

    $scope.sort = function(sort_type) {

        var keywords = $scope.keywords;
        var items_perpage = $scope.limitText;


        if(keywords === ''){

            keywords = 'undefined';
        }

        if(angular.element(caret_14).hasClass('') ){
            var order = 'ASC';

            $http.get("/reports/show_sale_by_products_14day/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

                vm.users = response.data;  //ajax request to fetch data into vm.data

            }).complete;
            angular.element(caret_14).addClass('fa');
            angular.element(caret_14).addClass('fa-caret-down');

        }
        else if(angular.element(caret_14).hasClass('fa fa-caret-down')){
            var order = 'DESC';

            $http.get("/reports/show_sale_by_products_14day/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

                vm.users = response.data;  //ajax request to fetch data into vm.data

            }).complete;
            angular.element(caret_14).removeClass('fa-caret-down');
            angular.element(caret_14).addClass('fa');
            angular.element(caret_14).addClass('fa-caret-up');


        }
        else if(angular.element(caret_14).hasClass('fa fa-caret-up')){
            var order = 'ASC';

            $http.get("/reports/show_sale_by_products_14day/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

                vm.users = response.data;  //ajax request to fetch data into vm.data

            }).complete;
            angular.element(caret_14).removeClass('fa-caret-up');
            angular.element(caret_14).addClass('fa');
            angular.element(caret_14).addClass('fa-caret-down');
        }
    };


});

app.controller('listdata_7day',function($scope, $http){

    var vm = this;
    vm.users = []; //declare an empty array

    ////////////////Default values on page load/////////////////
    var keywords = 'undefined';
    var items_perpage = '50';
    var sort_type = 'sales'
    var order = 'DESC';

    /////////////////////////load first time when page load//////////////////////

    $scope.getData = function(){
        // This would fetch the data on page change.
        vm.users = [];
        $http.get("/reports/show_sale_by_products_7day/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){
            vm.users = response.data;
        }).complete;
    };

    $scope.getData();


    $scope.limit = function() {
        var keywords = $scope.keywords;
        var items_perpage = $scope.limitText;
        if(keywords === '' ){
            keywords = 'undefined';
        }
        $http.get("/reports/show_sale_by_products_7day/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

            vm.users = response.data;  //ajax request to fetch data into vm.data
        }).complete;
    };

    $scope.filter = function() {
        var keywords = $scope.keywords;
        var items_perpage = $scope.limitText;

        if(keywords === ''){
            keywords = 'undefined';
        }
        $http.get("/reports/show_sale_by_products_7day/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

            vm.users = response.data;  //ajax request to fetch data into vm.data
        }).complete;
    };

    $scope.sort = function(sort_type) {

        var keywords = $scope.keywords;
        var items_perpage = $scope.limitText;


        if(keywords === ''){

            keywords = 'undefined';
        }

        if(angular.element(caret_7).hasClass('') ){
            var order = 'ASC';

            $http.get("/reports/show_sale_by_products_7day/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

                vm.users = response.data;  //ajax request to fetch data into vm.data

            }).complete;
            angular.element(caret_7).addClass('fa');
            angular.element(caret_7).addClass('fa-caret-down');

        }
        else if(angular.element(caret_7).hasClass('fa fa-caret-down')){
            var order = 'DESC';

            $http.get("/reports/show_sale_by_products_7day/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

                vm.users = response.data;  //ajax request to fetch data into vm.data

            }).complete;
            angular.element(caret_7).removeClass('fa-caret-down');
            angular.element(caret_7).addClass('fa');
            angular.element(caret_7).addClass('fa-caret-up');


        }
        else if(angular.element(caret_7).hasClass('fa fa-caret-up')){
            var order = 'ASC';

            $http.get("/reports/show_sale_by_products_7day/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

                vm.users = response.data;  //ajax request to fetch data into vm.data

            }).complete;
            angular.element(caret_7).removeClass('fa-caret-up');
            angular.element(caret_7).addClass('fa');
            angular.element(caret_7).addClass('fa-caret-down');
        }
    };


});

app.controller('listdata_yesterday',function($scope, $http){

    var vm = this;
    vm.users = []; //declare an empty array

    ////////////////Default values on page load/////////////////
    var keywords = 'undefined';
    var items_perpage = '50';
    var sort_type = 'sales'
    var order = 'DESC';

    /////////////////////////load first time when page load//////////////////////

    vm.getData = function(){
        // This would fetch the data on page change.
        vm.users = [];
        $http.get("/reports/show_sale_by_products_yes/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){
            vm.users = response.data;
        }).complete;
    };

    vm.getData();


    $scope.limit = function() {
        var keywords = $scope.keywords;
        var items_perpage = $scope.limitText;
        if(keywords === '' ){
            keywords = 'undefined';
        }
        $http.get("/reports/show_sale_by_products_yes/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

            vm.users = response.data;  //ajax request to fetch data into vm.data
        }).complete;
    };

    $scope.filter = function() {
        var keywords = $scope.keywords;
        var items_perpage = $scope.limitText;

        if(keywords === ''){
            keywords = 'undefined';
        }
        $http.get("/reports/show_sale_by_products_yes/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

            vm.users = response.data;  //ajax request to fetch data into vm.data
        }).complete;
    };

    $scope.sort = function(sort_type) {

        var keywords = $scope.keywords;
        var items_perpage = $scope.limitText;


        if(keywords === ''){

            keywords = 'undefined';
        }

        if(angular.element(caret_ys).hasClass('') ){
            var order = 'ASC';

            $http.get("/reports/show_sale_by_products_yes/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

                vm.users = response.data;  //ajax request to fetch data into vm.data

            }).complete;
            angular.element(caret_ys).addClass('fa');
            angular.element(caret_ys).addClass('fa-caret-down');

        }
        else if(angular.element(caret_ys).hasClass('fa fa-caret-down')){
            var order = 'DESC';

            $http.get("/reports/show_sale_by_products_yes/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

                vm.users = response.data;  //ajax request to fetch data into vm.data

            }).complete;
            angular.element(caret_ys).removeClass('fa-caret-down');
            angular.element(caret_ys).addClass('fa');
            angular.element(caret_ys).addClass('fa-caret-up');


        }
        else if(angular.element(caret).hasClass('fa fa-caret-up')){
            var order = 'ASC';

            $http.get("/reports/show_sale_by_products_yes/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

                vm.users = response.data;  //ajax request to fetch data into vm.data

            }).complete;
            angular.element(caret_ys).removeClass('fa-caret-up');
            angular.element(caret_ys).addClass('fa');
            angular.element(caret_ys).addClass('fa-caret-down');
        }
    };


});

app.controller('listdata_today',function($scope, $http){

    var vm = this;
    vm.users = []; //declare an empty array

    ////////////////Default values on page load/////////////////
    var keywords = 'undefined';
    var items_perpage = '50';
    var sort_type = 'sales'
    var order = 'DESC';

    /////////////////////////load first time when page load//////////////////////

    vm.getData = function(){
        // This would fetch the data on page change.
        vm.users = [];
        $http.get("/reports/show_sale_by_products_today/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){
            vm.users = response.data;
        }).complete;
    };

    vm.getData();


    $scope.limit = function() {
        var keywords = $scope.keywords;
        var items_perpage = $scope.limitText;
        if(keywords === '' ){
            keywords = 'undefined';
        }
        $http.get("/reports/show_sale_by_products_today/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

            vm.users = response.data;  //ajax request to fetch data into vm.data
        }).complete;
    };

    $scope.filter = function() {
        var keywords = $scope.keywords;
        var items_perpage = $scope.limitText;

        if(keywords === ''){
            keywords = 'undefined';
        }
        $http.get("/reports/show_sale_by_products_today/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

            vm.users = response.data;  //ajax request to fetch data into vm.data
        }).complete;
    };

    $scope.sort = function(sort_type) {

        var keywords = $scope.keywords;
        var items_perpage = $scope.limitText;


        if(keywords === ''){

            keywords = 'undefined';
        }

        if(angular.element(caret_td).hasClass('') ){
            var order = 'ASC';

            $http.get("/reports/show_sale_by_products_today/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

                vm.users = response.data;  //ajax request to fetch data into vm.data

            }).complete;
            angular.element(caret_td).addClass('fa');
            angular.element(caret_td).addClass('fa-caret-down');

        }
        else if(angular.element(caret_td).hasClass('fa fa-caret-down')){
            var order = 'DESC';

            $http.get("/reports/show_sale_by_products_today/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

                vm.users = response.data;  //ajax request to fetch data into vm.data

            }).complete;
            angular.element(caret_td).removeClass('fa-caret-down');
            angular.element(caret_td).addClass('fa');
            angular.element(caret_td).addClass('fa-caret-up');


        }
        else if(angular.element(caret_td).hasClass('fa fa-caret-up')){
            var order = 'ASC';

            $http.get("/reports/show_sale_by_products_today/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order).success(function(response){

                vm.users = response.data;  //ajax request to fetch data into vm.data

            }).complete;
            angular.element(caret_td).removeClass('fa-caret-up');
            angular.element(caret_td).addClass('fa');
            angular.element(caret_td).addClass('fa-caret-down');
        }
    };


});

app.controller('listdata_cus',function($scope, $http){

    var vm = this;
    vm.users = []; //declare an empty array

    ////////////////Default values on page load/////////////////
    var keywords = 'undefined';
    var items_perpage = '50';
    var sort_type = 'sales'
    var order = 'DESC';
    var from = 'undefined';
    var to = 'undefined';


    /////////////////////////load first time when page load//////////////////////

    $scope.getData = function(from, to){

        // This would fetch the data on page change.
        vm.users = [];
        $http.get("/reports/show_sale_by_products_cus/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order+"/"+from+"/"+to).success(function(response){
            vm.users = response.data;
        }).complete;
    };

    $scope.getData(from,to);


    $scope.limit = function() {
        var from = $scope.from_date;
        var to = $scope.to_date;
        from = from.replace(/\//g,"-");
        to = to.replace(/\//g, "-");

        var keywords = $scope.keywords;
        var items_perpage = $scope.limitText;
        if(keywords === '' ){
            keywords = 'undefined';
        }
        $http.get("/reports/show_sale_by_products_cus/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order+"/"+from+"/"+to).success(function(response){

            vm.users = response.data;  //ajax request to fetch data into vm.data
        }).complete;
    };

    $scope.filter = function() {
        var from = $scope.from_date;
        var to = $scope.to_date;
        from = from.replace(/\//g,"-");
        to = to.replace(/\//g, "-");
        var keywords = $scope.keywords;
        var items_perpage = $scope.limitText;

        if(keywords === ''){
            keywords = 'undefined';
        }
        $http.get("/reports/show_sale_by_products_cus/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order+"/"+from+"/"+to).success(function(response){

            vm.users = response.data;  //ajax request to fetch data into vm.data
        }).complete;
    };

    $scope.sort = function(sort_type) {
        var from = $scope.from_date;
        var to = $scope.to_date;
        from = from.replace(/\//g,"-");
        to = to.replace(/\//g, "-");

        var keywords = $scope.keywords;
        var items_perpage = $scope.limitText;


        if(keywords === ''){

            keywords = 'undefined';
        }

        if(angular.element(caret_cus).hasClass('') ){
            var order = 'ASC';

            $http.get("/reports/show_sale_by_products_cus/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order+"/"+from+"/"+to).success(function(response){

                vm.users = response.data;  //ajax request to fetch data into vm.data

            }).complete;
            angular.element(caret_cus).addClass('fa');
            angular.element(caret_cus).addClass('fa-caret-down');

        }
        else if(angular.element(caret_cus).hasClass('fa fa-caret-down')){
            var order = 'DESC';

            $http.get("/reports/show_sale_by_products_cus/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order+"/"+from+"/"+to).success(function(response){

                vm.users = response.data;  //ajax request to fetch data into vm.data

            }).complete;
            angular.element(caret_cus).removeClass('fa-caret-down');
            angular.element(caret_cus).addClass('fa');
            angular.element(caret_cus).addClass('fa-caret-up');


        }
        else if(angular.element(caret_cus).hasClass('fa fa-caret-up')){
            var order = 'ASC';

            $http.get("/reports/show_sale_by_products_cus/"+items_perpage+"/"+keywords+"/"+sort_type+"/"+order+"/"+from+"/"+to).success(function(response){

                vm.users = response.data;  //ajax request to fetch data into vm.data

            }).complete;
            angular.element(caret_cus).removeClass('fa-caret-up');
            angular.element(caret_cus).addClass('fa');
            angular.element(caret_cus).addClass('fa-caret-down');
        }
    };


});

