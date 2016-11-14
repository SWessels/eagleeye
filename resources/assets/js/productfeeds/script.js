var feedsApp = angular.module('product.feeds', ['ngAnimate', 'ui.bootstrap']);
feedsApp.controller('TypeaheadCtrl',   function($scope, $http) {

    var _selected;

    $scope.selected = '';
    $scope.specialFieldsDB = '';


    // Any function returning a promise object can be used to load values asynchronously
    $scope.getProducts = function(val) {
        return $http.get('/getallproducts', {
            params: {
                address: val,
                sensor: true
            }
        }).then(function(response){
            return response.data.map(function(item){
                $scope.selected = item.id;
                return item.name;
            });
        });
    };

    $scope.ngModelOptionsSelected = function(value) {
        if (arguments.length) {
            _selected = value;
        } else {
            return _selected;
        }
    };

    $scope.modelOptions = {
        debounce: {
            default: 500,
            blur: 250
        },
        getterSetter: true
    };


        $scope.formData = {};

        // process the form
        $scope.processForm = function() {
            $http({
                method  : 'POST',
                url     : '/savespecialfeed',
                data    : $.param($scope.formData),  // pass in data as strings
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
            })
                .success(function(data) {
                    console.log(data);

                    if (!data.success) {
                        // if not successful, bind errors to error variables
                        $scope.errorproduct_name = data.errors.product_name;
                        $scope.errorspecialfield = data.errors.specialfield;
                        $scope.errorformError    = data.errors.form_error;
                    } else {
                        // if successful, bind success message to message
                        $scope.message = data.message;
                        $scope.errorproduct_name = '';
                        $scope.errorspecialfield = '';

                        var myEl = angular.element( document.querySelector( '#feed_special_fields' ) );
                        myEl.prepend('<li> <span class="feed_product_name">'+$scope.formData.product_name+'</span> <span class="feed_field">'+$scope.formData.specialfield+'</span><span class="feed_delete"><a href="">Delete</a> </span> </li>');
                        $scope.formData.specialfield = null;
                        $scope.formData.product_name = null;
                        $scope.errorformError = null;

                    }
                });
        };




});


    feedsApp.controller('productFeedsCtrl', function ($scope, $http) {
            $http.get("/getspecialfields").then(function (response) {
                $scope.specialFieldsDB = response.data;
            });
        $scope.deleteField = function(fid) {
            $http({
                method  : 'POST',
                url     : '/deletespecialfeed',
                data    : {fid:fid}
            })
                .success(function(data) {
                       if(data.action == true)
                       {
                           $('.fee_li_'+fid).remove();
                       }else{
                           alert('Error Deleting Record'); 
                       }

                });
        };
    });

