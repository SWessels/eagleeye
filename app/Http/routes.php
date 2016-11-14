<?php
/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
 

Route::group(array('prefix' => 'wc-api/v2'), function()
{
    //Route::resource('product', 'API');
});

Route::group(['middleware' => ['web','status:active']], function () {
    //Route::auth();



    // users add/edit/update routes
    Route::get('/', 'PagesController@dashBoard');
    Route::get('/dashboard', 'PagesController@dashBoard')->name('home');
    Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);
    //Route::post('login', ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);
    Route::get('login', 'Auth\AuthController@getLogin');

    Route::get('sendtryoutemail', 'CategoriesController@sendtryoutemail');
    Route::post('login', 'Auth\AuthController@postLogin');


    Route::post('delete-tag', 'TagsController@delete');
    Route::post('delete-term', 'TermsController@delete');
    Route::post('delete-category', 'PostCategoryController@delete');
    Route::post('delete-posttag', 'PostTagsController@delete');
    Route::post('delete-post', 'PostsController@delete');
    Route::post('delete-page', 'PagesController@delete');
    Route::post('delete-customer', 'CustomerController@delete');

   // Route::get('AttTerms', 'AttributesController@AttTerms');

    ///////////////////////////////Media Routes//////////////////////
    Route::post('/media/show_image_library', 'MediaController@showLibrary');
    Route::get('/media/show_image_library/{perpage}/{pageno}', 'MediaController@showLibrary1');
    Route::get('/media/show_image_library/{perpage}/{pageno}/{search}', 'MediaController@showLibrarySearch');
    Route::post('/media/show_image_detail', 'MediaController@showDetail');
    Route::post('/media/save_images', 'MediaController@saveImagesById');
    Route::post('/media/remove_image_by_PostId', 'MediaController@removeImageByIdForPosts');
    Route::post('/media/remove_image_by_productId', 'MediaController@removeImageByIdForProducts');
    Route::post('/media/save_images_to_db', 'MediaController@saveImagesToDb');
    Route::post('/media/save_images_product_gallery', 'MediaController@saveImagesForProductGallery');
    Route::post('/media/remove_galleryimage_by_id', 'MediaController@removeImagesForProductGallery');
    Route::post('/media/update_image_title', 'MediaController@updateImageTitle');
    Route::post('/media/delete_image', 'MediaController@deleteImage');

    //////////////////////////Menu Routes//////////////////////////////////////
    
    Route::post('/menu/save_menu', 'MenuController@saveMenu');
    Route::post('/menu/search_page', 'MenuController@searchPage');
    Route::post('/menu/search_postcats', 'MenuController@searchPostCats');
    Route::post('/menu/search_category', 'MenuController@searchCategory');
    Route::post('/menu/show_menu_detail', 'MenuController@showDetail');
    Route::post('/menu/del_menu', 'MenuController@menuDelete');
    Route::post('/menu/del_menu', 'MenuController@menuDelete');

    ////////////////////////Coupon///////////////////////////////////////////
    Route::get('/coupons/get_all_coupons/{perpage}/{pageno}', 'CouponsController@getAllCoupons');
    Route::get('/coupons/{id}/del', 'CouponsController@deleteCouponById');
    Route::get('/coupons/{id}/copy', 'CouponsController@copyCouponById');
    Route::post('delete-page', 'CouponsController@delete');
    Route::post('/coupons/get_products', 'CouponsController@getProducts');
    Route::post('/coupons/get_categories', 'CouponsController@getCategories');
    
    ////////////////////////////Date Reports///////////////////////////////////////////
    Route::get('/reports/get_year_by_date', 'RevenueReportController@getYearByDate');
    Route::get('/reports/get_year_by_date_perDay', 'RevenueReportController@getYearByDatePerDay');
    Route::get('/reports/get_year_by_date_perWeek', 'RevenueReportController@getYearByDatePerWeek');
    Route::get('/reports/get_lmonth_by_date', 'RevenueReportController@getLmonthByDate');
    Route::get('/reports/get_lmonth_by_date_perWeek', 'RevenueReportController@getLmonthByDatePerWeek');
    Route::get('/reports/get_cmonth_by_date', 'RevenueReportController@getCmonthByDate');
    Route::get('/reports/get_cmonth_by_date_perWeek', 'RevenueReportController@getCmonthByDatePerWeek');
    Route::get('/reports/get_14days_by_date', 'RevenueReportController@get14DaysByDate');
    Route::get('/reports/get_14days_by_date_perWeek', 'RevenueReportController@get14DaysByDatePerWeek');
    Route::get('/reports/get_7days_by_date', 'RevenueReportController@get7DaysByDate');
    Route::get('/reports/get_yesterday_by_date', 'RevenueReportController@getYesterdayByDate');
    Route::get('/reports/get_today_by_date', 'RevenueReportController@getTodayByDate');
    Route::post('/reports/get_custom_by_date', 'RevenueReportController@getCustomByDate');
    Route::post('/reports/get_custom_by_date_perWeek', 'RevenueReportController@getCustomByDatePerWeek');
    Route::post('/reports/get_custom_by_date_perMonth', 'RevenueReportController@getCustomByDatePerMonth');
    Route::post('/reports/get_product_variations', 'RevenueReportController@showProductVar');
    ////////////////////////Product Reports///////////////////////////////////////////////////////////

    Route::get('revenue_report/sale_by_product', 'RevenueReportController@sale_by_product');
    Route::get('/reports/show_sale_by_products/{perpage}/{keywords}/{sort_type}/{order_by}','RevenueReportController@showSaleByProduct');
    Route::get('/reports/show_sale_by_products_lmonth/{perpage}/{keywords}/{sort_type}/{order_by}','RevenueReportController@showSaleByProduct_lmonth');
    Route::get('/reports/show_sale_by_products_cmonth/{perpage}/{keywords}/{sort_type}/{order_by}','RevenueReportController@showSaleByProduct_cmonth');
    Route::get('/reports/show_sale_by_products_14day/{perpage}/{keywords}/{sort_type}/{order_by}','RevenueReportController@showSaleByProduct_14day');
    Route::get('/reports/show_sale_by_products_7day/{perpage}/{keywords}/{sort_type}/{order_by}','RevenueReportController@showSaleByProduct_7day');
    Route::get('/reports/show_sale_by_products_yes/{perpage}/{keywords}/{sort_type}/{order_by}','RevenueReportController@showSaleByProduct_yes');
    Route::get('/reports/show_sale_by_products_today/{perpage}/{keywords}/{sort_type}/{order_by}','RevenueReportController@showSaleByProduct_today');
    Route::get('/reports/show_sale_by_products_cus/{perpage}/{keywords}/{sort_type}/{order_by}/{from}/{to}','RevenueReportController@showSaleByProduct_custom');
    Route::post('/reports/get_year_by_product', 'RevenueReportController@getYearByProduct');
    Route::post('/reports/get_lmonth_by_product', 'RevenueReportController@getLmonthByProduct');
    Route::post('/reports/get_cmonth_by_product', 'RevenueReportController@getCmonthByProduct');
    Route::post('/reports/get_14days_by_product', 'RevenueReportController@get14daysByProduct');
    Route::post('/reports/get_7days_by_product', 'RevenueReportController@get7daysByProduct');
    Route::post('/reports/get_yesterday_by_product', 'RevenueReportController@getYesterdaysByProduct');
    Route::post('/reports/get_today_by_product', 'RevenueReportController@getTodayByProduct');
    Route::post('/reports/get_custom_by_product', 'RevenueReportController@getCustomByProduct');

    ////////////////////////////////////////////////////////////////////////////
    
    Route::get('/attterms/{id}', 'TermsController@listterms');
    Route::post('/add_mobile_sliders', 'SlidersController@addMobileSliders');
    Route::post('/add_desktop_sliders', 'SlidersController@addDesktopSliders');
    Route::post('/add_mobile_homepage_sliders', 'SlidersController@addMobileHomeSliders');
    Route::post('/add_desktop_homepage_sliders', 'SlidersController@addDesktopHomeSliders');
    Route::post('/add_product_sliders', 'SlidersController@addProductSliders');
    Route::post('/addCategorySidebarSliders', 'SlidersController@addCategorySidebarSliders');
    Route::post('/sliders/get_products', 'SlidersController@getProducts');
    Route::post('/sliders/get_featured_image', 'SlidersController@getFeaturedImageById');
    Route::get('/show_mobile_banners/', 'BannersController@showMobileBanners');
    Route::post('/add_mobilecategory_banners', 'BannersController@addMobileCategoryBanners');
    Route::post('/add_mobilepage_banners', 'BannersController@addMobilePageBanners');
    Route::get('/show_desktop_banners/', 'BannersController@showDesktopBanners');
    Route::post('/add_desktopcategory_banners', 'BannersController@addDesktopCategoryBanners');
    Route::post('/add_desktoppage_banners', 'BannersController@addDesktopPageBanners');


    Route::resource('users', 'UsersController');
    Route::resource('products', 'ProductsController');
//	Route::resource('products/edit', 'ProductController@edit');
    Route::resource('orders', 'OrdersController');
    Route::resource('categories', 'CategoriesController');
    Route::resource('terms', 'TermsController');
    Route::resource('tags', 'TagsController');
    Route::resource('attributes', 'ProductAttributesController');
    Route::resource('posts', 'PostsController');
    Route::resource('PostCategories', 'PostCategoryController');

    Route::resource('customers', 'CustomerController');

    Route::resource('pages', 'PagesController');
    Route::resource('posttags', 'PostTagsController');
    Route::resource('tabs', 'TabsController');
    Route::resource('media', 'MediaController');
    Route::resource('menu', 'MenuController');

    Route::resource('ordernotes', 'OrderNotesController');

    Route::resource('coupons', 'CouponsController');
    Route::resource('revenue_report', 'RevenueReportController');

    Route::resource('sliders', 'SlidersController@index');
    Route::resource('actions', 'PricingController');
 

    Route::get('/updatestockstatus', 'ProductsController@updateStockStatus');
    Route::get('/populateinventory', 'BaseController@populateMasterInventory');
    Route::post('/productquickupdate', 'ProductsController@productQuickUpdate');
    Route::post('/check_sku', 'ProductsController@checkSKU');



	Route::post('/ajax/save_product', 'Ajax@saveProduct');
	Route::post('/ajax/save_slug', 'Ajax@updateSlug');
	Route::post('/ajax/update_product', 'Ajax@updateProductSlugTitle');
	
	Route::post('/ajax/save_category', 'Ajax@saveCategory');
    Route::post('/ajax/save_post_category', 'Ajax@savePostCategory');
    Route::post('/ajax/save_post_tag', 'PostsController@savePostTag');
    Route::post('/posts/save_post_slug', 'PostsController@savePostSlug');
    Route::post('/posts/update_post_slug', 'PostsController@updatePostSlug');
    Route::post('/pages/get_page_slug', 'PagesController@getPageSlug');
    Route::post('/pages/update_page_slug', 'PagesController@updatePageSlug');
    
    
    //import routes

    Route::get('/import/', 'Import@index');
    Route::get('/importcat/{file}', 'Import@importCategories');         // url: domain.com/importcat/tmh_cats
    Route::get('/productcat/{file}', 'Import@getProductCategories');    // url: domain.com/productcat/products
    Route::get('/importtags/{file}', 'Import@importTags');              // url: domain.com/importtags/tags
    Route::get('/producttags/{file}', 'Import@getProductTags');         // url: domain.com/producttags/products
    Route::get('/importproducts/{file}', 'Import@importVariableProducts');          // url: domain.com/importproducts/products
    Route::get('/importimages/{file}', 'Import@importImages');          // url: domain.com/importimages/products
    Route::get('/importposts/{file}', 'Import@importPosts');
    Route::get('/setsku/{connection}', 'Import@setSKU');
    Route::get('/encryptpasswords/', 'Import@encryptPasswords');
    Route::get('/importorders/', 'Import@importOrders');
    Route::get('/importcoupons/', 'Import@importCoupons');


    // change prefix for domain
    Route::get('changedomain/{domainid}', [
        'as' => 'changedomain', 'uses' => 'DomainsController@ChangeConnection'
    ]);

    Route::get('/load_quick_edit/{id}','ProductsController@getQuickEditHtml');
    Route::post('/ajax/get_all_products','ProductsController@getAllProductsForJs'); 
	Route::post('/ajax/save_product_tag','Ajax@saveProductTag'); 
	Route::post('/ajax/delete_tag_with_product','Ajax@delete_tag_with_product'); 
	Route::get('/ajax/get_product_quick_edit/{id}','Ajax@product_quick_edit'); 
	Route::post('/ajax/get_attribute_terms_by_id', 'Ajax@get_attribute_terms_by_id'); 
	Route::post('/ajax/save_attribute_features', 'Ajax@save_attribute_features'); 
    Route::post('/ajax/save_attribute_terms','Ajax@saveAttTerms');
    Route::post('/ajax/get_days_of_month','Ajax@getDaysOfMonth');
    Route::post('/ajax/get_months_of_year','Ajax@getMonthsOfYear');

    // bulk delete products
    Route::post('/products/bulk_delete','ProductsController@bulkDelete');

    // orders: bulk action
    Route::post('/orders/bulk_action','OrdersController@bulkAction');
    Route::get('/orders/{id}/{status}', 'OrdersController@changeStatus');
    Route::post('/orders/save_order_note', 'OrdersController@SaveOrderNote');

    Route::post('/orders/delete_order_note', 'OrderNotesController@deleteOrderNote');
    Route::get('/download_order_invoice/{id}/', 'OrdersController@downloadInvoice')->name('downloadinvoice');


    
    //save variation
    Route::post('/ajax/save_variation','ProductsController@saveVariation');

    // delete variation
    Route::post('/ajax/delete_variation','ProductsController@deleteVariation');

    // delete all variations
    //Route::post('/ajax/delete_all_variations','ProductsController@deleteAllVariation');
    
    // save product attributes in product add/edit page
    Route::post('/ajax/save_attributes','ProductsController@saveProductAttributes');

    // fetch variations on variation tab active
    Route::post('/ajax/get_variations','ProductsController@getProductVariations');
     
    // update variations 
    Route::post('/ajax/update_variations','ProductsController@updateVariation');
    
    // add custom attribute 
    Route::post('/ajax/add_custom_attribute','ProductsController@addCustomAttribute');

    // create product component
    Route::post('/ajax/create_component', 'ProductsController@createProductComponent');

    // update components
    Route::post('/ajax/update_components', 'ProductsController@updateProductComponents');

    // delete component
    Route::post('/ajax/delete_component', 'ProductsController@deleteProductComponent');

    // save email to wait list for product
    Route::post('/ajax/save_to_waitlist', 'ProductsController@addToWaitList');

    // remove form wait list
    Route::post('/ajax/remove_form_waitlist', 'ProductsController@removeFromWaitList');

    //add_color_swatch
    Route::post('/ajax/add_color_swatch', 'ProductsController@addColorSwatch');

    //remove_color_swatch
    Route::post('/ajax/remove_color_swatch', 'ProductsController@removeColorSwatch');

    // create_custom_tab
    Route::post('/ajax/create_custom_tab', 'TabsController@addCustomTab');

    // update_tabs
    Route::post('/ajax/update_tabs', 'TabsController@updateCustomTabs');

    //delete_custom_tab
    Route::post('/ajax/delete_custom_tab', 'TabsController@deleteCustomTab');

    // update_details_tab
    Route::post('/ajax/update_details_tab', 'TabsController@updateDetailsTab');

    //build_all_products_dropdown
    Route::post('/ajax/build_all_products_dropdown', 'ProductsController@buildAllProductsDropdown');

    // build_product_json
    Route::get('/ajax/build_product_json', 'ProductsController@buildProductsJson');

    // products_dropdown_from_json
    Route::get('/ajax/products_dropdown_from_json', 'ProductsController@productsDropDownFromJson');

    Route::post('/ajax/save_term_index','Ajax@saveindexterms');
	
	//woocommerce API link routes
	Route::get('woolink', 'WooLink@index');
	Route::get('woolink/users', 'WooLink@users');
    Route::get('woolink/categories', 'WooLink@productCategories');
    Route::get('woolink/coupons', 'WooLink@coupons');
    Route::get('woolink/orders', 'WooLink@orders');
    //product average routine
    Route::get('productavg', 'ProductAvg@index');
    
    //added for intelligent stocks section
    //Route::get('stocks','Stocks@index');
    Route::resource('stocks','Stocks');
    Route::post('stocks/updateDelivery','Stocks@updateDelivery');
    Route::post('stocks/updateHorizon','Stocks@updateHorizon');
    Route::post('stocks/pagination','Stocks@pagination');
    //WpApi link
    //Route::get('postlink', 'Postlink@index');

    //multisafe test
    Route::get('multisafe/connect','Multisafe@connect');
    Route::get('multisafe/notify','Multisafe@notify');
    Route::get('multisafe/cancel','Multisafe@cancel');
    Route::get('multisafe/complete','Multisafe@complete');
    Route::get('multisafe/info','Multisafe@getDetails');
    Route::get('multisafe/refund','Multisafe@doRefund');

    //klarna
    Route::get('klarna/connect','KlarnaAPI@connect');
    Route::get('klarna/status','KlarnaAPI@checkStatus');
    Route::get('klarna/activate', 'KlarnaAPI@activateInvoice');
    Route::get('klarna/refund','KlarnaAPI@refund');

    // customers
    Route::get('/ajax/get_customers', 'CustomerController@getCustomers');

    Route::get('/productfeeds', 'ProductFeedsController@index');

    Route::get('/product_feeds/{feed?}', 'ProductFeedsController@feedAll');
    
    Route::get('/getallproducts', 'ProductsController@getProductByNameForJs');
    Route::post('/savespecialfeed', 'ProductFeedsController@saveSpecialField');
    Route::get('/getspecialfields', 'ProductFeedsController@getSpecialFields');
    Route::post('/deletespecialfeed', 'ProductFeedsController@deleteSpecialField');

    Route::get('create_summary_tables', 'SummaryTableController@index');
    Route::get('resize_folder_images/',  'ResizeMediaController@resizeFolderImages');
    Route::get('resizeoneimage/{id}',  'ResizeMediaController@resizeOneImage');
    Route::get('import_product_seo_details_db',  'Import@impoertProductSeoDetailDb');
    Route::get('create_all_xml',  'Import@create_all_xml');


    ////////////////////// category products drag and drop module ///////////////////////
    Route::get('/category_drag_drop/{cat_id?}', 'PluginsController@categoryProductsDragAndDrop');
    Route::post('/savedraftcategoryproducts', 'PluginsController@saveDraftSorted');
    Route::get('/seo_general/', 'PluginsController@seoGeneralSettings');
    Route::post('/save_seo_general/', 'PluginsController@saveSeoGeneralSettings');
    Route::get('/sitemap_index/',  'PluginsController@show_sitemap_index');
    Route::get('/sitemap_index_details/{sitemap?}',  'PluginsController@sitemap_index_details');

    ///////////////////// actions or pricing module //////////////////////////////////////
    Route::get('/actions',  'PricingController@index');
    Route::get('/actions/create', ['as' => 'actions.create', 'uses' => 'PricingController@create']);
    Route::post('/ajax/get_category_products',  'Ajax@getCategoryProducts');
    Route::post('/ajax/save_order_value',  'Ajax@saveOrderValues');


    ///////////////////// process waitlist //////////////////////////////////////
    Route::get('processwaitlist/{sku}/{newqty}{returntype?}',  'WaitList@processWaitList');
    Route::post('waitlist/process_waitlist_composite',  'WaitList@processWaitListComposite');


    /////////////////// WSSN routes ////////////////////////////////////////////
    Route::get('wssn/',  'Import@wssn');



});