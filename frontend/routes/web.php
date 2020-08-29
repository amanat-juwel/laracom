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

Auth::routes();

###############################################
# PUBLIC ROUTE START
###############################################
Route::get('/', 'Web\WebController@index');
Route::prefix('web')->group(function(){
  Route::get('/pages/{slug}', 'PageController@showWebsite');
  Route::get('/users/logout', 'Auth\LoginController@userLogout')->name('user.logout');
  Route::get('/products/{id}/{item_name}', 'Web\ProductController@show');
  Route::get('/brands/', 'Web\BrandController@index');
  Route::get('/brands/{brand_name}', 'Web\BrandController@show');
  Route::get('/categories/{cata_name}', 'Web\CategoryController@show');
  Route::get('/contact-us/', 'Web\ContactUsController@index');
  //ADD-TO-WISHLIST
  Route::post('/add-to-wishlist', 'WishlistController@store');
  Route::delete('/remove-from-wishlist/{item_id}', 'WishlistController@destroy');
  Route::get('/wishlist', 'WishlistController@index');

  Route::post('/add-to-cart', 'Web\CartController@store');
  Route::put('/cart/{rowId}', 'Web\CartController@update');
  Route::delete('/cart/{rowId}', 'Web\CartController@destroy');
  Route::get('/cart', 'Web\CartController@index');
  Route::get('/checkout', 'Web\CartController@checkout');
  Route::post('/checkout', 'Web\CartController@storeCheckout');
  // Route::get('/special-offers/', 'Web\ContactUsController@index');

  //user account
  Route::get('/my-account', 'Web\UserAccountController@showAccount');
  Route::get('/order-history', 'Web\UserAccountController@orderHistory');
  Route::get('/order-information/{order_id}', 'Web\UserAccountController@orderInformation');

  Route::post('/register', 'Web\UserRegisterController@create');

}); 

###############################################
# ADMIN ROUTE START
###############################################

Route::prefix('admin')->group(function() {

  Route::get('/', 'AdminController@index')->name('admin.dashboard');

  //SLIDER
  Route::resource('sliders','SliderController');

  //PAGE
  Route::resource('pages','PageController');
  Route::post('/page/image-upload','PageController@summernoteImageUpload');

  //SETTINGS
  Route::get('/settings', 'SettingsController@index');
  Route::post('/settings/update', 'SettingsController@update');

  Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
  Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
  
  Route::get('/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');

  // Password reset routes
  Route::post('/password/email', 'Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
  Route::get('/password/reset', 'Auth\AdminForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
  Route::post('/password/reset', 'Auth\AdminResetPasswordController@reset');
  Route::get('/password/reset/{token}', 'Auth\AdminResetPasswordController@showResetForm')->name('admin.password.reset');

  ///POS ROUTES

  //Payment Notification onn/off
Route::get('/pn/{off}', 'PaymentNotificationController@paymentNotification');
Route::get('/subscription-fee-status', 'PaymentNotificationController@paymentStatus');

Route::get('/sales/email-invoice/{sales_master_id}', 'SalesController@emailInvoice');

Route::get('/account-type/{account_type_id}', 'BankTransactionController@subAccountType');

Route::get('/home', 'HomeController@index');

//Barcode
Route::get('/barcode', 'BarCodeController@index');
Route::post('/barcode', 'BarCodeController@create');

//LOGS
Route::get('/logs', 'HomeController@log');

//ROLES
Route::get('/roles', 'RoleController@index');
Route::get('/roles/create', 'RoleController@create');
Route::post('/roles/store', 'RoleController@store');
Route::get('/roles/{role_id}/edit', 'RoleController@edit');
Route::put('/roles/{role_id}','RoleController@update');
Route::delete('/roles/{role_id}', 'RoleController@destroy');

//SMS
Route::get('/sms/send-sms', 'SmsController@sendSms');
Route::get('/sms/sent', 'SmsController@sent');

//Admin Profile
Route::get('/profile', 'HomeController@view');

//Route::post('store', 'HomeController@store');


//Category CRUD
Route::get('/category', 'CategoryController@index');

Route::get('/users/manage-users', 'UserController@index');
Route::post('/users/store', 'UserController@store');
Route::get('/users/{id}/edit','UserController@edit');
Route::put('/users/{id}','UserController@update');
Route::delete('/users/{id}','UserController@destroy');

// Route::get('/', function (Request $request) {
//     $data = $request->json()->all();
// });

//Category CRUD
Route::get('/category', 'CategoryController@index');
Route::get('/category/create', 'CategoryController@create');
Route::post('/category', 'CategoryController@store');
Route::get('/category/{id}', 'CategoryController@show');
Route::get('/category/{id}/edit', 'CategoryController@edit');
Route::put('/category/update','CategoryController@update');
Route::delete('/category/{id}', 'CategoryController@destroy');

Route::get('/category_edit','CategoryController@edit');
Route::put('/category_update','CategoryController@update');
Route::delete('/category/{cata_id}','CategoryController@delete');

//Sub - Category CRUD
Route::get('/sub-category','SubCategoryController@index');
Route::get('/sub-category/get/{cata_id}','SubCategoryController@showSubCat');
Route::post('/sub-category-insert', 'SubCategoryController@store');
Route::get('/sub-category-edit/','SubCategoryController@edit');
Route::put('/sub-category-update','SubCategoryController@update');
Route::delete('/sub-category/{sub_cata_id}','SubCategoryController@delete');

//Sub - Sub - Category CRUD
Route::get('/sub-sub-category','SubSubCategoryController@index');
Route::get('/sub-sub-category/get/{sub_cata_id}','SubSubCategoryController@showSubCat');
Route::post('/sub-sub-category-insert', 'SubSubCategoryController@store');
Route::get('/sub-sub-category-edit/','SubSubCategoryController@edit');
Route::put('/sub-sub-category-update','SubSubCategoryController@update');
Route::delete('/sub-sub-category/{sub_cata_id}','SubSubCategoryController@delete');

//Unit CRUD
Route::get('/unit','UnitController@index');
Route::get('/unit/get/{cata_id}','UnitController@showSubCat');
Route::post('/unit-insert', 'UnitController@store');
Route::get('/unit-edit/','UnitController@edit');
Route::put('/unit-update','UnitController@update');
Route::delete('/unit/{id}','UnitController@delete');

//Brand CRUD
Route::get('/brand', 'BrandController@index');
Route::get('/brand/create', 'BrandController@create');
Route::post('/brand', 'BrandController@store');
Route::get('/brand/{id}', 'BrandController@show');
Route::get('/brand/{id}/edit', 'BrandController@edit');
Route::put('/brand/{id}','BrandController@update');
Route::delete('/brand/{id}', 'BrandController@destroy');

//Cust Category CRUD
Route::get('/cust/category', 'CustomerCategoryController@index');
Route::post('/cust/category/store', 'CustomerCategoryController@store');
Route::get('/cust/category/edit','CustomerCategoryController@edit');
Route::put('/cust/category/','CustomerCategoryController@update');
Route::delete('/cust/category/{id}','CustomerCategoryController@destroy');

//Customer CRUD
Route::get('/customer', 'CustomerController@index');
Route::get('/customer/create', 'CustomerController@create');
Route::post('/customer', 'CustomerController@store');
Route::get('/customer/{id}', 'CustomerController@show');
Route::get('/customer/{id}/edit', 'CustomerController@edit');
Route::put('/customer/{id}','CustomerController@update');
Route::delete('/customer/{id}', 'CustomerController@destroy');

Route::post('/customer/transaction', 'CustomerController@transaction');
Route::get('/customer/quick/{customer_name}/{customer_id}/{address}/{email}/{category}', 'CustomerController@storeQuickCustomer');

//Item CRUD
Route::get('/item', 'ItemController@index');
Route::get('/item/{item_id}/view','ItemController@show');
Route::get('/item/availablity/{item_id}/{stock_location_id}', 'ItemController@itemDetails');
Route::get('/item/create','ItemController@create');
Route::post('/item/store', 'ItemController@store');

Route::get('/item/{item_id}/inventory','ItemController@inventory');
Route::get('/item/{item_id}/edit','ItemController@edit');
Route::put('/item/{item_id}','ItemController@update');
Route::delete('/item/{item_id}','ItemController@destroy');
Route::resource('item','ItemController');
#summernote
Route::post('/item/image-upload','ItemController@summernoteImageUpload');

//Supplier CRUD

Route::get('/supplier', 'SupplierController@index');
Route::get('/supplier/create', 'SupplierController@create');
Route::post('/supplier', 'SupplierController@store');
Route::get('/supplier/{id}', 'SupplierController@show');
Route::get('/supplier/{id}/edit', 'SupplierController@edit');
Route::put('/supplier/{id}','SupplierController@update');
Route::delete('/supplier/{id}', 'SupplierController@destroy');

Route::post('/supplier/transaction', 'SupplierController@transaction');
Route::get('/supplier/payment/index', 'SupplierController@paymentIndex');
Route::get('/supplier/payment/{id}', 'SupplierController@editPayment');
Route::delete('/supplier/payment/{id}', 'SupplierController@destroyPayment');

//Stock Location
Route::get('/stock_location', 'StockLocationController@index');
Route::resource('stock_location','StockLocationController');
Route::post('/stock_location/create', 'StockLocationController@create');
Route::get('/stock_location/{stock_location_id}/edit','StockLocationController@edit');
Route::put('/stock_location/{stock_location_id}','StockLocationController@update');
Route::delete('/stock_location/{stock_location_id}','StockLocationController@destroy');
Route::get('/stock_location/item/movement', 'StockLocationController@movement');
Route::post('/stock_location/item/movement/store', 'StockLocationController@StoreMovement');
Route::get('/stock_location/item/quantity/{item_id}/{stock_location_id}', 'StockLocationController@getAvailableQty');
Route::delete('/stock_transfer/{id}', 'StockLocationController@destroyTransfer');
Route::get('/stock_location/item/lookup', 'StockLocationController@lookupIndex');
Route::get('/stock_location/item/lookup/pdf', 'StockLocationController@lookupIndexPDF');
//Purchase

Route::get('/purchase', 'PurchaseController@index');
Route::post('/purchase/store','PurchaseController@store');
Route::get('/purchase/purchase-details','PurchaseController@purchaseDetails');
Route::get('/purchase/memo_details/{purchase_master_id}','PurchaseController@show');
Route::put('/purchase/info/update','PurchaseController@purchaseInfoUpdate');
Route::get('/purchase/stock/{stock_id}/edit','PurchaseController@editStock');
Route::put('/purchase/stock/update','PurchaseController@stockUpdate');
Route::delete('/purchase/stock/single/{stock_id}/delete','PurchaseController@singleStockDestroy');
Route::delete('/purchase/{purchase_master_id}','PurchaseController@destroy');
Route::post('/purchase/add-to-existing-bill','PurchaseController@addItemToExistingBill');

//Orders 
Route::get('/orders', 'OrderController@index');
Route::get('/orders/{id}', 'OrderController@show');
Route::get('/orders/print/{id}', 'OrderController@print');
Route::post('/orders/payment-received/{id}', 'OrderController@paymentReceived');
Route::post('/orders/cancel/{id}', 'OrderController@orderCancled');
Route::delete('/orders/delete/{id}', 'OrderController@deleteOrder');
Route::post('/orders/delivered/{id}', 'OrderController@orderDelivered');

//Sales
Route::get('/sales', 'SalesController@index');
Route::resource('sales','SalesController');
Route::get('/sales/sales-details','SalesController@show');
Route::get('/sales/memo_details/{sales_master_id}','SalesController@inventorySalesDetails');
Route::get('/sales/invoice/{sales_master_id}','SalesController@invoiceDetails');
Route::get('/sales/chalan/{sales_master_id}','SalesController@chalanDetails');
Route::post('/sales/store',array('as'=>'store','uses'=>'SalesController@store'));
Route::put('/sales/update/{sales_master_id}','SalesController@updateSalesMaster');
Route::put('/sales/memo_details/{sales_master_id}','SalesController@updateSalesMaster');
Route::put('/sales/{sales_master_id}/edit/invoice/info', 'SalesController@updateInvoiceInfo');
Route::post('/sales/{sales_master_id}/add-item', 'SalesController@addNewItemToInvoice');
Route::get('/sales/resend-sms/{sales_master_id}', 'SalesController@resendSms');
Route::get('/sales/print/money-receipt/{sales_master_id}/{voucher_ref}', 'SalesController@printMoneyReceipt');

Route::get('/sales/invoice/item-unit/{sales_master_id}/{sales_details_id}/edit','SalesController@editItemUnit');
Route::put('/sales/invoice/unit/{tbl_item_unit_id}','SalesController@updateItemUnit');
Route::put('/sales/item/delivery','SalesController@deliverItem');
Route::get('/sales/add-less-edit/{add_less_id}','SalesController@editAddLess');
Route::put('/sales/add-less/update','SalesController@updateAddLess');
Route::delete('/sales/stock/single/{stock_id}/delete','SalesController@singleStockDestroy');

// ##new module for return and exchange
Route::get('/sales-return/exchange','SalesReturnExchangeController@index');
Route::get('/sales-return/exchange/create','SalesReturnExchangeController@create');
Route::post('/sales-return/exchange','SalesReturnExchangeController@store');
Route::get('/sales-return/exchange/{id}', 'SalesReturnExchangeController@show');
Route::get('/sales-return/exchange/{id}/print', 'SalesReturnExchangeController@print');
Route::delete('sales-return/return/exchange/{id}', 'SalesReturnExchangeController@destroy');

//Sales Return 
Route::get('/sales-return','SalesReturnController@index');
Route::post('/sales-return','SalesReturnController@store');
Route::get('/sales-return/list','SalesReturnController@showAll');
Route::get('/sales-return/details-view/{sales_return_master_id}','SalesReturnController@show');
Route::put('/sales-return/payment/{sales_return_master_id}/update','SalesReturnController@updatePayment');
Route::delete('/sales-return/{sales_return_master_id}','SalesReturnController@destroy');

//Purchase Return 
Route::get('/purchase-return','PurchaseReturnController@index');
Route::post('/purchase-return','PurchaseReturnController@store');
Route::get('/purchase-return/list','PurchaseReturnController@showAll');
Route::get('/purchase-return/details-view/{sales_return_master_id}','PurchaseReturnController@show');
Route::put('/purchase-return/payment/{sales_return_master_id}/update','PurchaseReturnController@updatePayment');
Route::delete('/purchase-return/{sales_return_master_id}','PurchaseReturnController@destroy');

//Money-receipt
Route::get('/money-receipt','MoneyReceiptController@index');
Route::get('/money-receipt/sales-info/{sales_master_id}','MoneyReceiptController@showInvoiceInfo');
Route::get('/money-receipt/customer-balance-info/{customer_id}','MoneyReceiptController@customerBalanceInfo');
Route::post('/money-receipt/store','MoneyReceiptController@store');
Route::get('/money-receipt/create','MoneyReceiptController@create');
Route::get('/money-receipt/print/{mr_id}','MoneyReceiptController@print');
Route::get('/money-receipt/edit/{mr_id}','MoneyReceiptController@edit');
Route::put('/money-receipt/{mr_id}','MoneyReceiptController@update');
Route::delete('/money-receipt/{mr_id}','MoneyReceiptController@destroy');


//Report
Route::get('/report/date-to-date-sales', 'ReportController@dateToDateSales');
Route::post('/report/date-to-date-sales-report', 'ReportController@dateToDateSalesReport');

Route::get('/report/memo-wise-date-to-date-sales', 'ReportController@dateToDateSalesMemoWise');
Route::post('/report/memo-wise-date-to-date-sales-report', 'ReportController@dateToDateSalesMemoWiseReport');
Route::get('/report/memo-wise-date-to-date-sales-report-pdf/{start_date}/{end_date}', 'ReportController@dateToDateSalesMemoWiseReportPDF');

Route::get('/report/executive-wise-date-to-date-sales', 'ReportController@dateToDateSalesExecutiveWise');
Route::post('/report/executive-wise-date-to-date-sales-report', 'ReportController@dateToDateSalesExecutiveWiseReport');

Route::get('/report/all-purchase', 'ReportController@allPurchase');

Route::get('/report/date-to-date-purchase', 'ReportController@dateToDatePurchase');
Route::post('/report/date-to-date-purchase-report', 'ReportController@dateToDatePurchaseReport');
Route::get('/report/date-to-date-purchase-report-pdf/{start_date}/{end_date}', 'ReportController@dateToDatePurchaseReportPDF');

Route::get('/report/date-to-date-purchase/from-supplier', 'ReportController@dateToDatePurchaseFromSupplier');
Route::post('/report/date-to-date-purchase/from-supplier', 'ReportController@dateToDatePurchaseFromSupplierReport');

Route::get('/report/date-to-date-sales/to-customer', 'ReportController@dateToDateSalesToCustomer');
Route::post('/report/date-to-date-sales/to-customer', 'ReportController@dateToDateSalesToCustomerReport');

Route::get('/report/date-to-date-sales-report-pdf/{start_date}/{end_date}', 'ReportController@dateToDateSalesReportPDF');
Route::get('/report/purchase-due-pdf', 'ReportController@purchaseDuePDF');
Route::get('/report/sales-due-pdf', 'ReportController@salesDuePDF');
Route::get('/report/current-stock', 'ReportController@currentStock');
Route::get('/report/current-stock-pdf', 'ReportController@currentStockPDF');
Route::get('/report/current-stock/category', 'ReportController@currentStockByCategory');
Route::get('/report/current-stock-category-pdf', 'ReportController@currentStockByCategoryPDF');
Route::get('/report/current-stock/sub-category', 'ReportController@currentStockBySubCategory');
Route::get('/report/current-stock-by-brand', 'ReportController@currentStockByBrand');

Route::get('/report/stock-transfer', 'ReportController@stockTransfer');
Route::post('/report/stock-transfer', 'ReportController@stockTransferReport');
Route::get('/report/stock-transfer/{start_date}/{end_date}', 'ReportController@stockTransferReportPrint');

Route::get('/report/inventory/descriptive', 'ReportController@inventoryDescriptive');
Route::post('/report/inventory/descriptive', 'ReportController@inventoryDescriptiveReport');
Route::get('/report/inventory/descriptive-pdf/{start_date}/{end_date}/{stock_location_id}', 'ReportController@inventoryDescriptivePrint');

Route::get('/report/inventory/locate', 'ReportController@inventoryLocate');
Route::post('/report/inventory/locate', 'ReportController@inventoryLocateReport');
Route::get('/report/inventory/locate-pdf/{start_date}/{end_date}/{stock_location_id}', 'ReportController@inventoryLocatePrint');

Route::get('/report/todays-expenses', 'ReportController@todaysExpenses');
Route::get('/report/todays-expenses-pdf', 'ReportController@todaysExpensesPDF');
Route::get('/report/date-to-date-expenses', 'ReportController@dateToDateExpenses');
Route::post('/report/date-to-date-expenses-report', 'ReportController@dateToDateExpensesReport');
Route::get('/report/date-to-date-expenses-report-pdf/{start_date}/{end_date}', 'ReportController@dateToDateExpensesReportPDF');

//ACCOUNTS REPORT
Route::group(['prefix' => 'reports/'], function () {
   Route::get('/income-statement','AccountsReport\IncomeStatementController@incomeStatement');
   Route::post('/income-statement','AccountsReport\IncomeStatementController@incomeStatementReport');

   Route::get('/daily-cash-sheet','AccountsReport\DailyCashSheetController@index');
   Route::post('/daily-cash-sheet','AccountsReport\DailyCashSheetController@report');
   Route::get('/daily-cash-sheet/{start_date}/{end_date}/{account_id}','AccountsReport\DailyCashSheetController@print');

   
});

//Report Finished Stock 
Route::group(['prefix' => 'report/finished-product/'], function () {
    Route::get('/stock-register','FinishedProductReportController@stockRegister');
    Route::post('/stock-register','FinishedProductReportController@stockRegisterReport');
    Route::get('/stock-register-print/{r_m_id}/{start_date}/{end_date}','FinishedProductReportController@stockRegisterPrint');

    Route::get('/current-stock','FinishedProductReportController@currentStock');
    Route::get('/current-stock-print','FinishedProductReportController@currentStockPrint');

    Route::get('/inventory', 'FinishedProductReportController@inventory');
  Route::post('/inventory', 'FinishedProductReportController@inventoryReport');
  Route::get('/inventory/{start_date}/{end_date}', 'FinishedProductReportController@inventoryReportPrint');

});

Route::get('/report/cash-in-hand', 'ReportController@cashInHand');
Route::post('/report/cash-in-hand', 'ReportController@cashInHandReport');
Route::get('/report/cash-in-hand-pdf/{reporting_date}', 'ReportController@cashInHandReportPDF');

Route::get('/report/statement/cash-book', 'ReportController@cashBook');
Route::post('/report/statement/cash-book', 'ReportController@cashBookReport');
Route::get('/report/statement/cash-book-pdf/{date}', 'ReportController@cashBookReportPDF');


Route::get('/report/date-to-date-profit-by-item', 'ReportController@dateToDateprofitByItem');
Route::post('/report/date-to-date-profit-by-item-report', 'ReportController@dateToDateprofitByItemReport');
Route::get('/report/date-to-date-profit-by-item-report-pdf/{start_date}/{end_date}', 'ReportController@dateToDateprofitByItemReportPDF');
Route::get('/report/income-statement', 'ReportController@indexIncomeStatement');
Route::post('/report/income-statement', 'ReportController@incomeStatement');
Route::get('/report/income-statement-pdf/{start_date}/{end_date}', 'ReportController@incomeStatementPDF');

Route::get('/report/account-statement', 'ReportController@indexAccountStatement');
Route::post('/report/account-statement', 'ReportController@AccountStatement');
Route::get('/report/account-statement-pdf/{start_date}/{end_date}/{bank_account_id}', 'ReportController@AccountStatementPDF');

Route::get('/report/trial-balance', 'ReportController@indexTrialBalance');
Route::post('/report/trial-balance', 'ReportController@TrialBalance');
Route::get('/report/trial-balance-pdf/{start_date}/{end_date}', 'ReportController@TrialBalancePDF');

Route::get('/report/warehouse-report', 'ReportController@warehouseReport');
Route::get('/report/receivable', 'ReportController@receivable');
Route::get('/report/grand-receivable', 'ReportController@grandReceivable');
Route::get('/report/payable', 'ReportController@payable');

Route::get('/report/balance-sheet', 'BalanceSheetController@index');
Route::post('/report/balance-sheet', 'BalanceSheetController@generateReport');


//Bank Account-Transaction CRUD
Route::group(['prefix' => 'bank'], function () {
#Transaction
  Route::get('/transaction', 'BankTransactionController@index');
  Route::resource('transaction','BankTransactionController');
  Route::post('/transaction/store', 'BankTransactionController@store');
  Route::get('/transfer', 'BankTransactionController@indexTransfer');
  Route::post('/transfer/store', 'BankTransactionController@storeTransfer');
  Route::get('/transaction/{bank_transaction_id}/edit','BankTransactionController@edit');
  Route::put('/transaction/{bank_transaction_id}','BankTransactionController@update');
  Route::delete('/transaction/{bank_transaction_id}','BankTransactionController@destroy');
#Account
    Route::get('/account', 'BankTransactionController@indexAccount');
  Route::post('/account/store', 'BankTransactionController@storeAccount');
  Route::get('/account/{bank_account_id}/edit','BankTransactionController@editAccount');
  Route::put('/account/{bank_account_id}','BankTransactionController@updateAccount');
  Route::delete('/account/{bank_account_id}','BankTransactionController@destroyAccount');
});

// Account-Group CRUD
Route::group(['prefix' => 'account'], function () {

  Route::get('/group', 'AccountGroupController@index');
  Route::post('/group/store', 'AccountGroupController@store');
  Route::get('/group/edit', 'AccountGroupController@edit');
  Route::put('/group/update', 'AccountGroupController@update');
  Route::delete('/group/{id}','AccountGroupController@destroy');

});



Route::get('/restore/stock', 'HomeController@restore');

Route::group(['prefix' => 'ledger'], function () {
  Route::get('/supplier', 'LedgerController@index');
  Route::post('/supplier', 'LedgerController@supplierLedger');
  Route::get('/supplier-ledger-pdf/{supplier_id}', 'LedgerController@supplierLedgerPDF');

  Route::get('/customer', 'LedgerController@indexCustomer');
  Route::post('/customer', 'LedgerController@customerLedger');
  Route::get('/customer-ledger-pdf/{customer_id}', 'LedgerController@customerLedgerPDF');

  Route::get('/account/group', 'LedgerController@indexAccountGroup');
  Route::post('/account/group', 'LedgerController@accountGroupLedger');
  Route::get('/account-group-ledger-pdf/{account_group_id}', 'LedgerController@accountGroupLedgerPDF');

  Route::resource('ledger','LedgerController');
  Route::post('/create', 'LedgerController@create');
  Route::post('/store', 'LedgerController@store');
  Route::get('/{id}/edit','LedgerController@edit');
  Route::put('/{id}','LedgerController@update');
  Route::delete('/{id}','LedgerController@destroy');
}); 
  
Route::get('/promotional-offer', 'PromotionalOfferController@index');

//HR CRUD
Route::get('/human-resource', 'EmployeeController@index');
Route::get('/human-resource/create', 'EmployeeController@create');
Route::post('/human-resource/store', 'EmployeeController@store');
Route::delete('/human-resource/{id}', 'EmployeeController@destroy');

//JOURNAL
Route::get('/journal', 'JournalController@index');
Route::get('/journal/create', 'JournalController@create');
Route::post('/journal/store', 'JournalController@store');
Route::get('/journal/{voucher_ref}', 'JournalController@show');
Route::get('/journal/{voucher_ref}/edit', 'JournalController@edit');
Route::put('/journal/{voucher_ref}', 'JournalController@update');
Route::delete('/journal/{voucher_ref}', 'JournalController@destroy');


//Database Backup
Route::group(['prefix' => 'database-backup/'], function () {
   Route::get('/','DatabaseBackupController@index');
   Route::get('/create','DatabaseBackupController@create');
   Route::delete('/delete','DatabaseBackupController@destroy');

});
//Database Backup

//Cash Book
Route::get('/report/statement/cash-book', 'ReportController@cashBook');
Route::post('/report/statement/cash-book', 'ReportController@cashBookReport');
Route::get('/report/statement/cash-book-pdf/{date}', 'ReportController@cashBookReportPDF');

Route::get('/report/inventory/descriptive/product', 'ReportController@inventoryDescriptiveProduct');
Route::post('/report/inventory/descriptive/product', 'ReportController@inventoryDescriptiveReportProduct');
Route::get('/report/inventory/descriptive/product-pdf/{start_date}/{end_date}', 'ReportController@inventoryDescriptiveProductPrint');

//Batch Crud
Route::group(['prefix' => 'batch'], function () {
  Route::get('/','BatchController@index');
  Route::get('/create','BatchController@create');
  Route::post('/store', 'BatchController@store');
  Route::get('/{id}/edit','BatchController@edit');
  Route::put('/update','BatchController@update');
  Route::delete('/{id}/delete','BatchController@destroy');
});


}); 