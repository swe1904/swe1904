<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ajay
 * Date: 10/2/15
 * Time: 3:37 PM
 * To change this template use File | Settings | File Templates.
 */
namespace app\components;

class GlobalConstant {

    const BOOKING_ACTIVE = 1;
    const BOOKING_PAYMENT_PENDING = 2;
    const BOOKING_DELETED = 3;
    const BOOKING_REFUND = 4;
    const BOOKING_PAYMENT_RECEIVED = 5;
    const DEFAULT_SETTING_ID = 1;
    const COUNTRY_UNITED_KINGDOM = 182; //as it reflected in array maintained booking.php country function

    CONST MONTHS_DROPDOWN = ['January'=>'January','February'=>'February','March'=>'March','April'=>'April','May'=>'May','June'=>'June','July'=>'July','August'=>'August','September'=>'September','October'=>'October','November'=>'November','December'=>'December'
    ];

    CONST MONTHS_NUMBER_DROPDOWN = ['01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December'];

    CONST YEARS_DROPDOWN = ['2015'=>'2015', '2016'=>'2016', '2017'=>'2017', '2018'=>'2018', '2019'=>'2019', '2020'=>'2020', '2021'=>'2021', '2022'=>'2022', '2023'=>'2023', '2024'=>'2024', '2025'=>'2025', '2026'=>'2026', '2027'=>'2027', '2028'=>'2028', '2029'=>'2029', '2030'=>'2030'];


    CONST PAYMENT_MODE_CASH = 1;
    CONST PAYMENT_MODE_ONLINE_CHEQUE= 2;

    CONST RECEIPT_ITEM_TAX_NA = 1;
    CONST RECEIPT_ITEM_TAX_ZERO_RATED = 2;
    CONST RECEIPT_ITEM_TAX_EXEMPTED= 3;
    CONST RECEIPT_ITEM_TAX_20 = 4;
    // ON DEV ITS ID IS 18
    CONST CASE_STATUS_SENT_FOR_BILLING = 40; 


    CONST ORGANISATION_ADMIN_ARRAY = [
        'organisation-admin' => 'organisation-admin'
    ];
    CONST CLIENT_GROUP_MANAGER_ARRAY = [
        'Client Group Manager' => 'Client Group Manager'
    ];
    


    CONST RECEIPT_ITEM_TAX_ARRAY = [
        1 => 'Not Applicable',
        2 => 'Zero Rated',
        3 => 'Exempted',
        4 => '20 %',
    ];
    CONST INTERVAL_TYPE_ARRAY = [
        1 => '7 days, 15 days, 30 days',
        2 => '30 days, 60 days, 90 days',
        3 => '30 days, 90 days, 120 days',  
    ];
    CONST GET_QUOTES = 'quotes';
    CONST GET_INVOICES = 'invoices';
    CONST REPLY_FROM_NAME = "Pangea Portal";
    CONST REPLY_FROM_EMAIL = "info.pangeaportal@gmail.com";

    CONST CASE_STEP_STATUS_PROCESSING=0;
    CONST CASE_STEP_STATUS_ON_TIME=1;
    CONST CASE_STEP_STATUS_DELAYED=2;

    CONST CASE_STEP_STATUS_ON_TIME_LABEL='On time';
    CONST CASE_STEP_STATUS_DELAYED_LABEL='Delayed';
    CONST CASE_STEP_STATUS_PROCESSING_LABEL='Processing';

    CONST CASE_STEP_STATUS_ARRAY = [
        0 =>'Processing',
        1 =>'On Time',
        2 =>'Delay',
    ];
    CONST CASE_STEP_STATUS_COLOR_ARRAY = [
        0 =>'#f39c12',//orange
        1 =>'#00a65a',//green
        2 =>'#dd4b39',//red
    ];

    CONST ACTION_STYLE = "min-width:200px;";


    public static function generate_serial()
    {
        static $max = 60466175; // ZZZZZZ in decimal
        return strtoupper(sprintf(
            "%05s-%05s",
            base_convert(random_int(0, $max), 10, 36),
            base_convert(random_int(0, $max), 10, 36)
        ));
    }

    CONST ROLE_HR_MANAGER = 'HR Manager';
    CONST ROLE_COUNTRY_MANAGER = 'Country Manager';
    CONST ROLE_PAYROLL_MANAGER = 'Payroll Manager';
    CONST ROLE_EMPLOYEE = 'Employee';
    CONST ROLE_SUPERVISOR = 'Supervisor';
    CONST ROLE_DEPARTMENT_MANAGER = 'Department Manager';
    CONST ROLE_TEAM_MANAGER = 'Team Manager';
    
    CONST ROLE_CLIENT = 'Client';
    CONST ROLE_CLIENT_HR = 'Client-POC';
    CONST ROLE_SUPERADMIN = 'administrator';
    CONST ROLE_ORGANISATION_ADMIN = 'organisation-admin';
    CONST ROLE_CASE_WORKER = 'Case Worker';
    CONST ROLE_ORGANISATION_MANAGER = 'organisation-manager';
    CONST ROLE_FINANCE = 'Finance';
    CONST ROLE_CASE_MANAGER = 'Case Manager';
    CONST ROLE_CLIENT_CASE_MANAGER = 'Client Case Manager';
    CONST ROLE_CLIENT_CASE_WORKER = 'Client Case Worker';
    CONST ROLE_CLIENT_GROUP_MANAGER = 'Client Group Manager';
    CONST ROLE_CLIENT_ENTITY_MANAGER = 'Client Entity Manager';
    CONST ROLE_GLOBAL_FINANCE = 'Global Finance';


    CONST VAT_TYPE_ARRAY = [
        1 =>'STANDARD RATE',
        2 =>'ZERO-RATED',
        3 =>'EXEMPTED',
    ];

    CONST NORTHMAN_EMAIL_DOMAIN = '@northmansterling.com';

    CONST RECEIPT_SERVICE_FEE_SECTION_ID = 1;
    CONST RECEIPT_GOVT_FEE_SECTION_ID = 2;

}