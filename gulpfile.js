process.env.DISABLE_NOTIFIER = true;
var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */


elixir(function(mix) {
    mix.styles([
        "font-awesome/css/font-awesome.min.css",
        "simple-line-icons/simple-line-icons.min.css",
        "bootstrap/css/bootstrap.min.css",
        "uniform/css/uniform.default.css",
        "bootstrap-switch/css/bootstrap-switch.min.css",
    ], 'public/assets/css/master_plugins.css', 'resources/assets/plugins');
});

//////////////////////////////// for angular/ uiform jquery conflict////////////////////////////////////

elixir(function(mix) {
    mix.styles([
        "font-awesome/css/font-awesome.min.css",
        "simple-line-icons/simple-line-icons.min.css",
        "bootstrap/css/bootstrap.min.css",
        "bootstrap-switch/css/bootstrap-switch.min.css",
    ], 'public/assets/css/master_plugins_angular.css', 'resources/assets/plugins');
});

elixir(function(mix) {
    mix.styles([
        "layout.css",
        "darkblue.css",
        "components-md.css",
        "plugins-md.min.css",
        "custom.css"
    ], 'public/assets/css/master_css.css');
});


elixir(function(mix) {
    mix.styles([
        "fullcalendar/fullcalendar.min.css",
        "jqvmap/jqvmap/jqvmap.css",
    ], 'public/assets/css/dashboard_plugins.css', 'resources/assets/plugins');
});


/*elixir(function(mix) {
    mix.styles([
    ], 'public/assets/css/dashboard_css.css' );
});*/



elixir(function(mix) {
    mix.scripts([
        "respond.min.js",
        "excanvas.min.js",
        "bootstrap/js/bootstrap.min.js",
        "js.cookie.min.js",
        "bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js",
        "jquery-slimscroll/jquery.slimscroll.min.js",
        "jquery.blockui.min.js",
        "uniform/jquery.uniform.min.js",
        "bootstrap-switch/js/bootstrap-switch.min.js",
        "moment.min.js",
        "jquery.jscroll.min.js",
        "bootstrap-daterangepicker/daterangepicker.min.js"
    ], 'public/assets/js/master_plugins.js', 'resources/assets/plugins');
});

elixir(function(mix) {
    mix.scripts([
        "respond.min.js",
        "excanvas.min.js",
        "bootstrap/js/bootstrap.min.js",
        "js.cookie.min.js",
        "bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js",
        "jquery-slimscroll/jquery.slimscroll.min.js",
        "jquery.blockui.min.js",
        "bootstrap-switch/js/bootstrap-switch.min.js",
        "moment.min.js",
        "jquery.jscroll.min.js",
        "bootstrap-daterangepicker/daterangepicker.min.js"
    ], 'public/assets/js/master_plugins_angular.js', 'resources/assets/plugins');
});



elixir(function(mix) {
    mix.scripts([
    "app.min.js",
    "layout.js",
    "RowSorter.js",
    "bootbox.min.js",
    "ui-bootbox.min.js",
    "common.js"
    ], 'public/assets/js/master_js.js');
});


elixir(function(mix) {
    mix.scripts([
        "fullcalendar/fullcalendar.min.js",
        "flot/jquery.flot.min.js",
        "flot/jquery.flot.resize.min.js",
        "jquery-easypiechart/jquery.easypiechart.min.js",
        "jquery.sparkline.min.js",
        "jqvmap/jqvmap/jquery.vmap.js",
        "jqvmap/jqvmap/data/jquery.vmap.sampledata.js",
    ], 'public/assets/js/dashboard_plugins.js', 'resources/assets/plugins');
});


elixir(function(mix) {
    mix.scripts([
        "ecommerce-dashboard.js",
        "demo.js",
        "quick-sidebar.js"
    ], 'public/assets/js/dashboard_js.js');
});



elixir(function(mix) {
    mix.styles([ 
        "login.css",
     ], 'public/assets/css/login.css');
});


elixir(function(mix) {
    mix.styles([
        "bootstrap/css/bootstrap.min.css"
    ], 'public/assets/css/login_plugins.css', 'resources/assets/plugins');
});


elixir(function(mix) {
    mix.styles([
        "select2/css/select2.min.css",
        "select2/css/select2-bootstrap.min.css",
        "bootstrap-datepicker/css/bootstrap-datepicker3.min.css",
        "jquery-file-upload/css/jquery.fileupload.css"
    ], 'public/assets/css/products_plugins.css', 'resources/assets/plugins');
});

elixir(function(mix) {
    mix.scripts([
        "select2/js/select2.min.js", 
        "typeahead/typeahead.bundle.min.js",
        "bootstrap-datepicker/js/bootstrap-datepicker.min.js"
    ], 'public/assets/js/products_plugins.js', 'resources/assets/plugins');
});



elixir(function(mix) {
    mix.scripts([
        "jquery.validate-1.14.0.min.js",
        "jquery-validate.bootstrap-tooltip.js"
    ], 'public/assets/js/products_js.js' );
});

elixir(function(mix) {
    mix.scripts([
        "select2/js/select2.min.js",
        "jscroll/jquery.jscroll.min.js"
    ], 'public/assets/js/post_plugins.js', 'resources/assets/plugins');
});


elixir(function(mix) {
    mix.styles([
        "select2/css/select2.min.css",
        "select2/css/select2-bootstrap.min.css",
        "bootstrap-datepicker/css/bootstrap-datepicker3.min.css",
        "typeahead/typeahead.css",
        "jquery-file-upload/css/jquery.fileupload.css"

    ], 'public/assets/css/post_plugins.css', 'resources/assets/plugins');
});

elixir(function(mix) {
    mix.scripts([
        "jquery.validate.js"
    ], 'public/assets/js/customer_js.js' ,'resources/assets/js');
});
elixir(function(mix) {
    mix.styles([
        "select2/css/select2.min.css",
        "select2/css/select2-bootstrap.min.css",
        "bootstrap-datepicker/css/bootstrap-datepicker3.min.css",
        "typeahead/typeahead.css",
        "jquery-file-upload/css/jquery.fileupload.css",
        "jquery-nestable/jquery.nestable.css"
    ], 'public/assets/css/menu_plugins.css', 'resources/assets/plugins');
});

elixir(function(mix) {
    mix.scripts([
        "select2/js/select2.min.js",
        "jquery-nestable/jquery.nestable.js"
    ], 'public/assets/js/menu_plugins.js', 'resources/assets/plugins');
});


//////////////// orders ////////////////////


elixir(function(mix) {
    mix.scripts([
        "select2/js/select2.min.js",
        "bootstrap-table-master/bootstrap-table.min.js",
        "bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js",
        "bootstrap-datepicker/js/bootstrap-datepicker.min.js"
    ], 'public/assets/js/orders_plugins.js', 'resources/assets/plugins');
});




elixir(function(mix) {
    mix.styles([
        "select2/css/select2.min.css",
        "select2/css/select2-bootstrap.min.css",
        "bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css",
        "bootstrap-datepicker/css/bootstrap-datepicker3.min.css",
        "bootstrap-table-master/bootstrap-table.min.css"

    ], 'public/assets/css/orders_plugins.css', 'resources/assets/plugins');
});

/////////////// end  //////////////////////



elixir(function(mix) {
    mix.scripts([
        "select2/js/select2.min.js",
        "typeahead/typeahead.bundle.min.js",
        "bootstrap-datepicker/js/bootstrap-datepicker.min.js",
        "bootstrap-touchspin/bootstrap.touchspin.js",
        "fuelux/js/spinner.min.js"
    ], 'public/assets/js/coupon_plugins.js', 'resources/assets/plugins');
});

elixir(function(mix) {
    mix.scripts([
        "jquery.validate-1.14.0.min.js",
        "jquery-validate.bootstrap-tooltip.js",
        "components-select2.min.js"
    ], 'public/assets/js/coupon_js.js' );
});

elixir(function(mix) {
    mix.styles([
        "select2/css/select2.min.css",
        "select2/css/select2-bootstrap.min.css",
        "bootstrap-datepicker/css/bootstrap-datepicker3.min.css",
        "jquery-file-upload/css/jquery.fileupload.css",
        "bootstrap-touchspin/bootstrap.touchspin.css"
    ], 'public/assets/css/coupon_plugins.css', 'resources/assets/plugins');
});

elixir(function(mix) {
    mix.scripts([

        "angular.min.js",
        "dirPagination.js"
    ], 'public/assets/js/angular_js.js' );
});

elixir(function(mix) {
    mix.scripts([
        "select2/js/select2.min.js",
        "typeahead/typeahead.bundle.min.js",
        "bootstrap-datepicker/js/bootstrap-datepicker.min.js",
        "bootstrap-touchspin/bootstrap.touchspin.js",
        "fuelux/js/spinner.min.js",
        "flot/jquery.flot.js",
        "flot/jquery.flot.time.js",
        "flot/jshashtable-2.1.js",
        "flot/jquery.numberformatter-1.2.3.min.js",
        "flot/jquery.flot.resize.js",
        "flot/jquery.flot.axislabels.js",
        "flot/jquery.flot.symbol.js",
        "flot/jquery.flot.stack.js",
        "flot/jquery.flot.tooltip.js"

    ], 'public/assets/js/reports_plugins.js', 'resources/assets/plugins');
});
elixir(function(mix) {
    mix.styles([
        "select2/css/select2.min.css",
        "select2/css/select2-bootstrap.min.css",
        "bootstrap-datepicker/css/bootstrap-datepicker3.min.css",
        "jquery-file-upload/css/jquery.fileupload.css",
        "bootstrap-touchspin/bootstrap.touchspin.css"
    ], 'public/assets/css/reports_plugins.css', 'resources/assets/plugins');
});



elixir(function(mix) {
    mix.scripts([
        "script.js",
        "angular-animate.js"
    ], 'public/assets/js/prodcutfeeds.js', 'resources/assets/js/productfeeds');
});

elixir(function(mix) {
    mix.scripts([
        "select2/js/select2.min.js"
    ], 'public/assets/js/slider_plugins.js', 'resources/assets/plugins');
});

elixir(function(mix) {
    mix.scripts([
        "components-select2.min.js"
    ], 'public/assets/js/slider_js.js' );
});

elixir(function(mix){
   mix.styles([
       "select2/css/select2.min.css",
       "select2/css/select2-bootstrap.min.css",
       "jquery-file-upload/css/jquery.fileupload.css"
   ], 'public/assets/css/sliders_plugins.css', 'resources/assets/plugins');
});


elixir(function(mix) {
    mix.scripts([
        "select2/js/select2.min.js"
    ], 'public/assets/js/actions_plugins.js', 'resources/assets/plugins');
});

elixir(function(mix) {
    mix.scripts([
        "components-select2.min.js"
    ], 'public/assets/js/actions_js.js' );
});

elixir(function(mix){
    mix.styles([
        "select2/css/select2.min.css",
        "select2/css/select2-bootstrap.min.css"
    ], 'public/assets/css/actions_plugins.css', 'resources/assets/plugins');
});
