<?php

use Carbon\Carbon;
use App\Models\Interactive;
use App\Models\Seo;
use App\Models\Demo;
use App\Models\Page;
use App\Models\Shop;
use App\Models\User;
use App\Models\Agent;
use App\Models\Group;
use App\Models\Mojsms;
use App\Models\Contact;
use App\Models\CronJob;
use App\Models\Feature;
use App\Models\Message;
use App\Models\Package;
use Twilio\Rest\Client;
use App\Models\Campaign;
use App\Models\Identity;
use App\Models\Language;
use App\Models\Provider;
use App\Models\QuotaLog;
use App\Models\QueueList;
use App\Models\Department;
use App\Models\Newsletter;
use App\Models\ThirdParty;
use App\Models\Application;
use App\Models\CallHistory;
use App\Models\DocumentKyc;
use App\Models\SaasContent;
use Illuminate\Support\Str;
use App\Models\CallDuration;
use App\Models\GroupContact;
use App\Models\Subscription;
use App\Mail\ExpiryAlertMail;
use App\Models\CampaignVoice;
use App\Models\CsvImportQueue;
use App\Models\ItemLimitCount;
use App\Models\PaymentHistory;
use App\Models\RecentActivity;
use App\Models\SystemCurrency;
use App\Models\TwilioCallCost;
use App\Models\CustomCssScript;
use App\Models\DepartmentAgent;
use App\Models\AgentIsAvailable;
use App\Models\LiveCallDuration;
use Harimayco\Menu\Models\Menus;
use App\Models\LeadsExportHistory;
use App\Console\Commands\CsvImport;
use App\Models\CampaignSmsStatusLog;
use Illuminate\Support\Facades\Cache;
use App\Models\CampaignVoiceStatusLog;
use GuzzleHttp\Client as GuzzleClient;
use App\Models\PackageSupportedCountry;
use Illuminate\Support\Facades\Storage;
use Telesign\sdk\messaging\MessagingClient;
use AmrShawky\LaravelCurrency\Facade\Currency;

/**
 * It returns an array of arrays.
 * 
 * @return An array of arrays.
 */
function whatsNewInTheUpdates() // version 3.0.0 
{
    return [
        'ADDED' => [
            '1' => 'Incoming Call UI',
            '2' => 'Call History For All User',
            '3' => 'Inbound Call Recording',
            '4' => 'Outbound Call Recording',
            '5' => 'Inbound Call QUEUE',
            '6' => 'QUEUE Redialing',
            '7' => 'QUEUE Waiting Room',
            '8' => 'Call Record to OPENAI Wishper Analyze',
            '9' => 'Download Call Record'
        ],

        'CHANGED' => [
            '1' => 'Create New Provider Form',
            '2' => 'Inbound Dialer UI'
        ]
    ];
}

/**
 * It opens a JSON file and returns the contents as an array
 * 
 * @param code The language code you want to open.
 * 
 * @return the contents of the file as a JSON string.
 */
function openJSONFile($code)
{
    $jsonString = [];
    if (File::exists(base_path('resources/lang/' . $code . '.json'))) {
        $jsonString = file_get_contents(base_path('resources/lang/' . $code . '.json'));
        $jsonString = json_decode($jsonString, true);
    }
    return $jsonString;
}

/**
 * It takes a language code and an array of translations, sorts the array, encodes it as JSON, and
 * saves it to a file
 * 
 * @param code The language code (e.g. en, fr, de, etc.)
 * @param data The data to be saved in the JSON file.
 */
function saveJSONFile($code, $data)
{
    ksort($data);
    $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents(base_path('resources/lang/' . $code . '.json'), stripslashes($jsonData));
}

/**
 * It checks if the key exists in the JSON file, if not, it adds it to the JSON file
 * 
 * @param key The key to translate.
 * 
 * @return the value of the key in the json file.
 */
function translate($key)
{
    $key = ucfirst(str_replace('_', ' ', $key));
    if (File::exists(base_path('resources/lang/en.json'))) {
        $jsonString = file_get_contents(base_path('resources/lang/en.json'));
        $jsonString = json_decode($jsonString, true);
        if (!isset($jsonString[$key])) {
            $jsonString[$key] = $key;
            saveJSONFile('en', $jsonString);
        }
    }

    return __($key);
}

/**
 * It takes two parameters, the first is the name of the environment variable you want to change, and
 * the second is the value you want to change it to
 * 
 * @param type The name of the environment variable you want to change.
 * @param val The value you want to set in the .env file
 */
function overWriteEnvFile($type, $val)
{
    $path = base_path('.env');
    if (file_exists($path)) {
        $val = '"'.trim($val).'"';
        if (is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0) {
            file_put_contents($path, str_replace($type.'="'.env($type).'"', $type.'='.$val, file_get_contents($path)));
        } else {
            file_put_contents($path, file_get_contents($path)."\r\n".$type.'='.$val);
        }
    }
}

/**
 * It returns the version of PHP.
 * 
 * @return The version of PHP.
 */
function versionOfPhp()
{
    return number_format((float) phpversion(), 2, '.', '');
}

/**
 * It reads the flags directory and returns the files in it
 * 
 * @return An array of the files in the directory.
 */
function readFlag()
{
    $dir = base_path('public/flags');
    $file = scandir($dir);
    return $file;
}

/**
 * It takes a string, removes the first 8 characters, replaces underscores with spaces, and capitalizes
 * the first letter of each word
 * 
 * @param name The name of the flag file.
 * 
 * @return the name of the flag with the first letter of each word capitalized.
 */
function flagRename($name)
{
    $nameSubStr = substr($name, 8);
    $nameReplace = ucfirst(str_replace('_', ' ', $nameSubStr));
    $nameReplace2 = ucfirst(str_replace('.png', '', $nameReplace));
    return $nameReplace2;
}

/**
 * It takes a country name and returns the path to the flag image
 * 
 * @param name The name of the flag.
 * 
 * @return the path to the flag image.
 */
function flagAsset($name)
{
    return asset('flags') . '/' . $name;
}

/**
 * It returns the language code of the active language
 * 
 * @return The value of the session variable 'locale' or the value of the environment variable
 * 'DEFAULT_LANGUAGE' or the string 'en'.
 */
function activeLanguage()
{
    $lang = Session::get('locale') ?? env('DEFAULT_LANGUAGE');
    return $lang ?? 'en';
}

function defaultLanguage()
{
    $lang = env('DEFAULT_LANGUAGE');
    return $lang ?? 'en';
}

/**
 * It returns the name of the language that is active in the country with the given code
 * 
 * @param code The language code (e.g. en, fr, de, etc.)
 * 
 * @return The name of the language.
 */
function activeLanguageCountryName($code)
{
    $language = Language::where('code', $code)->first();
    return $language->name;
}

/**
 * It takes a language code as a parameter and returns the image of the language flag
 * 
 * @param code The language code (e.g. en, fr, de, etc.)
 * 
 * @return The image of the language with the code that is passed in.
 */
function activeLanguageFlag($code)
{
    $language = Language::where('code', $code)->first();
    return $language->image;
}

//Get file path
//path is storage/app/
function filePath($file)
{
    return asset($file);
}

//delete file
function fileDelete($file)
{
    if ($file != null) {
        if (file_exists(public_path($file))) {
            unlink(public_path($file));
        }
    }
}

//uploads file
// uploads/folder
function fileUpload($file, $folder)
{
    return $file->store('uploads/'.$folder);
}

// make avatar
function avatar($name, $length = 1)
{
    return Avatar::create(substr($name, 0, $length))->toBase64();
}

/**
 * CURRENCY
 */

 // set currency
function setCurr($curr)
{
    if ($curr != null) {
        return Session::put('currency', $curr);
    } else {
        return Session::put('currency', SystemCurrency::where('default', 1)->first()->symbol);
    }
}

// get language
function curr()
{
    if (Session::get('currency') != null) {
        return Session::get('currency');
    } else {
        return SystemCurrency::where('default', 1)->first()->symbol;
    }
}

// convert price
function bill($amount)
{
    if ($amount != 0) {
        return $amount * SystemCurrency::where('symbol', curr())->first()->amount;
    } else {
        return 0 * SystemCurrency::where('symbol', curr())->first()->amount;
    }
}

// currency symbol
function symbol()
{
    return config('money.'.curr().'.symbol');
}

//  formatted converted price
function price($amount)
{
    return symbol().bill($amount);
}

//  formatted converted price
function onlyPrice($amount)
{
    return bill($amount);
}

function allCurrencies()
{
    return SystemCurrency::all();
}

// convertCurrency
/**
 * The function converts an amount from the default system currency to a specified currency using the
 * current exchange rate.
 * 
 * @param currency The currency parameter is the currency code or symbol that you want to convert the
 * amount to.
 * @param amount The amount parameter is the value that you want to convert from one currency to
 * another.
 * 
 * @return the converted amount of the given currency and amount.
 */
function convertCurrency($currency, $amount)
{
    // Get the default system currency symbol
    $defaultCurrency = SystemCurrency::where('default', 1)->first()->symbol;

    // Initialize cURL
    $curl = curl_init();

    // Set cURL options
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.currencyapi.com/v3/latest?base_currency=' . $defaultCurrency . '&currencies=' . $currency,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'apikey: fca_live_CsB91Cs6RZkt8F2YzoPBE7AzmAQl9EYcCy0cfanp' // Replace with your API key
        ),
    ));

    // Execute cURL request
    $response = curl_exec($curl);

    // Close cURL session
    curl_close($curl);

    // Convert the response to an associative array
    $data = json_decode($response, true);

    // Calculate the converted amount
    if (isset($data['data'][$currency])) {
        $rate = $data['data'][$currency]['value'];
        $convertedAmount = $rate * $amount;
    } else {
        // Handle the error in case the currency is not found or other issues
        $convertedAmount = "Error: Unable to convert currency.";
    }

    // Return the converted amount rounded to 2 decimal places
    return round($convertedAmount, 2);
}


/**
 * CURRENCY END
 */

/**
 * Notify User
 */
function expirationNotify($days)
{
    $subscription_users = Subscription::whereBetween('end_at', [Carbon::now(), Carbon::now()->addDays($days)])
                                      ->get();
    //get users from $subscription_users
    $users = User::whereIn('id', $subscription_users->pluck('user_id'))->get();
    foreach ($users as $user) {
        Mail::to($user->email)
            ->send(new ExpiryAlertMail($user));
    }
}

// Billing Plan

function billingPlan($domain = null)
{
    if (Auth::user()->role == 'admin') {
        $user_id = User::where('role', 'customer')
        ->where('domain', $domain)
        ->first()->id;
    } else {
        $user_id = User::where('role', 'customer')
        ->where('domain', Auth::user()->domain)
        ->first()->id;
    }

    return Subscription::where('user_id', $user_id)
                        ->with(['package', 'user', 'item_limit_count', 'payment_history'])
                        ->first();
}

/**
 * FEATURES
 */

// allFeatures
function allFeatures()
{
    return Feature::get();
}

function activeFeatures()
{
    return Feature::where('active', 1)->get();
}

// allFeaturesPaginate
function allFeaturesPaginate()
{
    return Feature::orderBy('name')->get();
}

function featureName($feature_id)
{
    return Feature::where('id', $feature_id)->first()->name ?? null;
}

/**
 * Packages
 */

// activePackages
function activePackages()
{
    return Package::where('active', 1)->with('supported_countries')->has('supported_countries')->get();
}

// allPackagesPaginate
function allPackagesPaginate()
{
    return Package::get();
}

// getPackageItems
function getPackageItems($package_id)
{
    return Package::where('id', $package_id)->first()->emails;
}

// getPackageBranch
function getPackageBranch($package_id)
{
    return Package::where('id', $package_id)->first()->sms;
}

// getPackagePrice
function getPackagePrice($package_id)
{
    return Package::where('id', $package_id)->first()->price;
}

// PackageDetails
function PackageDetails($package_id)
{
    return Package::where('id', $package_id)->first();
}

// isThisPackageIsFree
function isThisPackageIsFree($package_id)
{
    $trial = Package::where('id', $package_id)->first()->trial;

    if ($trial == 1) {
        return true;
    } else {
        return false;
    }
}

// packageStartEndDate
function packageStartEndDate($package_id)
{
    $package = Package::where('id', $package_id)->first();

    $start_date = Carbon::now();

    if ($package->range_type == 'day') {
        $end_date = Carbon::now()->addDays($package->range);
    }

    if ($package->range_type == 'month') {
        $end_date = Carbon::now()->addMonths($package->range);
    }

    if ($package->range_type == 'year') {
        $end_date = Carbon::now()->addYears($package->range);
    }

    if ($package->range_type == 'week') {
        $end_date = Carbon::now()->addWeeks($package->range);
    }

    return ['start_date' => $start_date, 'end_date' => $end_date];
}

// check feature exists in the package
function checkFeatureExists($package_id, $feature_id)
{
    $package = Package::where('id', $package_id)
                  ->where('feature_id', 'like', '%'.$feature_id.'%')
                  ->get();
    if ($package->count() > 0) {
        return 'true';
    }

    return 'false';
}

/**
 * User Subscription Data
 */

//  userSubscriptionData
function userSubscriptionData($domain)
{
    $subscription = Subscription::where('domain', $domain)
                                ->with(['package', 'user', 'item_limit_count', 'payment_history'])
                                ->first();

    return $subscription;
}

//  userActiveSubscription
function userActiveSubscription($subscription_id)
{
    return $subscription = Subscription::where('id', $subscription_id)
                                ->where('user_id', Auth::user()->id)
                                ->where('active', 1)
                                ->with('package')
                                ->first();

    if ($subscription != null) {
        return true;
    } else {
        return false;
    }
}

//  userSubscriptionData
function userActivePackage($subscription_id)
{
    $subscription = Subscription::where('id', $subscription_id)
                                ->where('user_id', Auth::user()->id)
                                ->where('active', 1)
                                ->with('package')
                                ->first();

    return $subscription->package_id;
}

// Active Package
function activePackage()
{
    if (Auth::user()->role == 'agent') {
        $subscription = Subscription::where('user_id', agent_owner_id())
                                ->where('active', 1)
                                ->first();
    }else {
        $subscription = Subscription::where('user_id', Auth::user()->id)
                                ->where('active', 1)
                                ->first();

    }
    return $subscription->package_id;
}

// userItemsLimit
function userEmailsLimit($domain)
{
    $item_limit_count = userSubscriptionData($domain)->item_limit_count->emails;

    return $item_limit_count;
}

// userItemsLimit
function userBranchLimit($domain)
{
    $branch_limit_count = userSubscriptionData($domain)->item_limit_count->sms;

    return $branch_limit_count;
}

// user item limit check
function userEmailLimitCheck($domain)
{
    $item_limit_count = userSubscriptionData($domain)->item_limit_count->emails;

    if ($item_limit_count == 0) {
        return 'LIMIT-CROSSED';
    } else {
        return 'HAS-LIMIT';
    }
}

// user item limit check
function userSmsLimitCheck($domain)
{
    $branch_limit_count = userSubscriptionData($domain)->item_limit_count->sms;

    if ($branch_limit_count == 0) {
        return 'LIMIT-CROSSED';
    } else {
        return 'HAS-LIMIT';
    }
}

// user item limit left
function userEmailLimitLeft($domain)
{
    $item_limit_count = userSubscriptionData($domain)->item_limit_count->emails;

    return $item_limit_count;
}

// user branch limit left
function userSmsLimitLeft($domain)
{
    $branch_limit_count = userSubscriptionData($domain)->item_limit_count->sms;

    return $branch_limit_count;
}

// user item limit decrement
function userEmailLimitDecrement($domain)
{
    $userEmailLimitDecrement = ItemLimitCount::where('domain', $domain)->first();
    if ($userEmailLimitDecrement->emails == 0) {
        return $userEmailLimitDecrement->emails;
    }
    $userEmailLimitDecrement->decrement('emails', 1);

    return $userEmailLimitDecrement->emails;
}

// user item limit decrement
function userSmsLimitDecrement($domain)
{
    $userSmsLimitDecrement = ItemLimitCount::where('domain', $domain)->first();
    if ($userSmsLimitDecrement->sms == 0) {
        return $userSmsLimitDecrement->sms;
    }
    $userSmsLimitDecrement->decrement('sms', 1);

    return $userSmsLimitDecrement->sms;
}

// user subscription date end in
function userSubscriptionDateEndIn($domain)
{
    $subscription = userSubscriptionData($domain);

    $end_date = $subscription->end_at;

    $end_date = Carbon::parse($end_date);

    $end_date = $end_date->diffInDays();

    return $end_date;
}

// checkExpiry
function checkExpiry($user_id)
{
    if (is_admin($user_id)) {
        return false;
    }

    $subscription = Subscription::HasAgent($user_id)->first();

    return $subscription 
        ? ($subscription->end_at < Carbon::now() ? 'EXPIRED' : 'NOT EXPIRED')
        : 'NO SUBSCRIPTION';
}

// INVOICE NUMBER
function invoiceNumber()
{
    return date('Y').rand(1000, 10000);
}

/**
 * Get the user with the given id from the database and return it.
 * 
 * @param id The id of the user you want to get the info of.
 * 
 * @return A user object.
 */
function getUserInfo($id)
{
    $user = User::where('id', $id)->first();

    return $user;
}

/**
 * It returns the value of the `site_name` key in the `application` array, if it exists, otherwise it
 * returns the string `Teleman`.
 * 
 * @return The value of the site_name key in the application array, or the string 'Teleman' if the key
 * doesn't exist.
 */
function appName()
{
    return application('site_name') ?? 'Teleman';
}

/**
 * It returns the value of the `site_author` key in the `seo` array, or if that key doesn't exist, it
 * returns `The Code Studio`
 * 
 * @return The value of the seo() function, which is the value of the site_author key in the 
 * array.
 */
function orgName()
{
    return seo('site_author') ?? 'The Code Studio';
}

/**
 * It returns the site logo if it exists, otherwise it returns the default logo
 * 
 * @return the value of the site_logo key in the application config file. If the key is not found, it
 * will return the default logo.png file.
 */
function logo()
{
    return asset(application('site_logo')) ?? asset('logo.png');
}

function darkLogo()
{
    return asset(application('site_dark_logo')) ?? asset('dark-logo.png');
}

function orgPhone()
{
    return application('site_phone') ?? '+8801533149024';
}

function orgTestPhone()
{
    return application('test_phone') ?? '+8801533149024';
}

function orgEmail()
{
    return application('site_email') ?? 'teleman@thecodestudio.xyz';
}

function orgAddress()
{
    return application('site_email');
}

function orgTel()
{
    return application('site_phone') ?? '+8801533149024';
}

function orgColor()
{
    return application('site_colors') ?? '#00b289 ';
}

function invoice_path($file)
{
    return public_path('invoice_pdf/'.$file.'.pdf');
}

function domain_invoice_path($file)
{
    return public_path('domain_invoice_pdf/'.$file.'.pdf');
}

/**
 * CLIENTS
 */
function allClients()
{
    return User::where('role', 'customer')
                ->with(['subscription', 'item_limit_count', 'payment_histories'])
                ->has('subscription')
                ->paginate(15);
}

function allClientsCount()
{
    return User::where('role', 'customer')
                ->count();
}

function allAgentsCount()
{
    return User::where('role', 'agent')
                ->count();
}

/**
 * CHECKS TRIAL USED OR NOT
 */
function checkUserTrialUsed($user_id)
{
    $checkUserTrialUsed = PaymentHistory::where('user_id', $user_id)
                           ->where('payment_status', 'trial')
                           ->first();

    if ($checkUserTrialUsed != null) {
        return 'true';
    } else {
        return 'false';
    }
}

// userRestriction
function userRestriction($user_id)
{
    $userRestriction = User::where('id', $user_id)
                            ->where('restriction', 1)
                            ->first();

    if ($userRestriction != null) {
        return 'true';
    } else {
        return 'false';
    }
}

// convertdaysToWeeksMonthsYears
function convertdaysToWeeksMonthsYears($days)
{
    $start_date = new DateTime('1970-01-01');
    $end_date = (new DateTime('1970-01-01'))->add(new DateInterval("P{$days}D"));
    $dd = date_diff($start_date, $end_date);

    return $dd->y.' '.Str::pluralStudly('year', $dd->y).', '.$dd->m.' '.Str::pluralStudly('month', $dd->m).', '.$dd->d.' '.Str::pluralStudly('day', $dd->d);
}

// user payment history
function userPaymentHistory()
{
    $userPaymentHistory = PaymentHistory::where('user_id', Auth::user()->id)
                                        ->with('subscription')
                                        ->latest()
                                        ->get();

    return $userPaymentHistory;
}

// user payment history
function customerPaymentHistory()
{
    $customerPaymentHistories = PaymentHistory::with('subscription')
                                        ->latest()
                                        ->get();

    return $customerPaymentHistories;
}

// user_subscription_data
function user_subscription_data($domain = null)
{
    if (Auth::user()->role == 'admin') {
        $user = User::where('role', 'customer')
        ->where('domain', $domain)
        ->with(['subscription', 'item_limit_count'])
        ->has('subscription')
        ->first();
    } else {
        $user = User::where('role', 'customer')
        ->where('domain', Auth::user()->domain)
        ->with(['subscription', 'item_limit_count'])
        ->has('subscription')
        ->first();
    }

    $info = [
        'domain' => $user->domain,
        'rest_name' => $user->rest_name,
        'rest_address' => $user->rest_address,
        'created_at' => $user->created_at->diffForHumans(),
        'package_id' => $user->subscription->package->id,
        'subscription_name' => $user->subscription->package->name,
        'total_items' => $user->subscription->emails,
        'start_at' => $user->subscription->start_at,
        'end_at' => $user->subscription->end_at,
        'payment_status' => $user->subscription->payment_status,
        'payment_gateway' => $user->subscription->payment_gateway,
        'amount' => $user->subscription->amount,
        'emails' => $user->subscription->credit,
    ];

    return $info;
}

    /**
     * DASHBOARD DATA
     */

    // dashboard_data
    function dashboard_data()
    {
        $dashboard_data = [
            // User
            'total_customers' => User::where('role', 'customer')->count(),
            'total_customers_inactive' => User::where('role', 'customer')->where('restriction', 1)->count(),
            'total_customers_active' => User::where('role', 'customer')->where('restriction', 0)->count(),

            // PaymentHistory
            'total_payments_pending' => PaymentHistory::where('payment_status', 'pending')->count(),
            'total_payments' => PaymentHistory::count(),

            // Subscription
            'total_subscriptions' => Subscription::count(),
            'total_subscriptions_active' => Subscription::where('active', 1)->count(),
            'total_subscriptions_inactive' => Subscription::where('active', 0)->count(),

            // Package
            'total_packages' => Package::count(),
            'total_packages_active' => Package::where('active', 1)->count(),
            'total_packages_inactive' => Package::where('active', 0)->count(),

            // Earnings
            'total_earning_today' => PaymentHistory::whereDate('created_at', Carbon::today())->sum('amount'),
            'total_earning_this_month' => PaymentHistory::whereMonth('created_at', Carbon::now()->month)->sum('amount'),
            'total_earning_this_year' => PaymentHistory::whereYear('created_at', Carbon::now()->year)->sum('amount'),
            'total_earning_all_time' => PaymentHistory::sum('amount'),

            // Average Subscription
            'average_subscription_today' => PaymentHistory::whereDate('created_at', Carbon::today())->count(),
            'average_subscription_this_month' => PaymentHistory::whereMonth('created_at', Carbon::now()->month)->count(),
            'average_subscription_this_year' => PaymentHistory::whereYear('created_at', Carbon::now()->year)->count(),
            'average_subscription_all_time' => PaymentHistory::count(),

            //current month name
            'current_month_name' => Carbon::now()->format('F'),

            // active subscription month based
            'active_subscription_month_based' => Subscription::selectRaw("count(id) AS total, 
				DATE_FORMAT(created_at, '%M') AS month, 
				YEAR(created_at) AS year
			    ")
                ->groupBy('month')
                ->orderBy('created_at')
                ->get(),

            // average subscription month based
            'average_subscription_month_based' => PaymentHistory::selectRaw("count(id) AS total,
                DATE_FORMAT(created_at, '%M') AS month,
                YEAR(created_at) AS year
                ")
                ->groupBy('month')
                ->orderBy('created_at')
                ->get(),

            // sales revenue month based
            'sales_revenue_month_based' => PaymentHistory::selectRaw("SUM(amount) AS total,
                DATE_FORMAT(created_at, '%M') AS month,
                YEAR(created_at) AS year
                ")
                ->groupBy('month')
                ->orderBy('created_at')
                ->get(),

            // sales revenue between current month from last month
            'sales_revenue_between_current_month' => PaymentHistory::selectRaw('SUM(amount) AS total')
                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->first()->total,

            // sales revenue only this week
            'sales_revenue_this_week' => PaymentHistory::selectRaw('SUM(amount) AS total')
                ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->first()->total,

            // total sales overview
            'total_sales_overview' => PaymentHistory::selectRaw('SUM(amount) AS total')
                ->first()->total,

            // sales total last 30 day wise
            'sales_total_per_day_wise_this_month' => thirty_days_dates_value_count(),

        ];

        return $dashboard_data;
    }

    /**
     * 30 days dates
     */
    function thirty_days_dates()
    {
        $dates = [];
        for ($i = 0; $i < 30; $i++) {
            $dates[] = Carbon::now()->subDays($i)->format('Y-m-d');
        }

        return $dates;
    }

    /**
     * 30 days dates value count
     */
    function thirty_days_dates_value_count()
    {
        $count = collect();

        foreach (thirty_days_dates() as $date) {
            $amount = PaymentHistory::whereDate('created_at', $date)->sum('amount');
            $count->push($amount);
        }

        return $count;
    }

    // recent users purchase activities
    function activity($name, $message)
    {
        $activity = new RecentActivity;
        $activity->message = $name.' '.$message;
        $activity->save();
    }

    // recent users purchase activities
    function activities()
    {
        $activities = RecentActivity::latest()->take(20)->get();

        return $activities;
    }

/**
 * DASHBOARD DATA::END
 */

/**
 * GET COUNTRY INFO VIA IP
 */
function get_country_info_via_ip($ip)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => 'http://ip-api.com/php/'.$ip,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ]);

    $response = curl_exec($curl);

    curl_close($curl);
    $data = unserialize($response);

    if ($data['status'] == 'fail') {
        $info = [
            'country' => null,
            'country_code' => null,
            'region_name' => null,
            'city' => null,
            'zip' => null,
            'lat' => null,
            'lon' => null,
            'timezone' => null,
        ];
    } else {
        $info = [
            'country' => $data['country'],
            'country_code' => Str::lower($data['countryCode']),
            'region_name' => $data['regionName'],
            'city' => $data['city'],
            'zip' => $data['zip'],
            'lat' => $data['lat'],
            'lon' => $data['lon'],
            'timezone' => $data['timezone'],
        ];
    }

    return $info;
}

// get country wise user
function get_users_by_country()
{
    $countries = User::where('role', 'customer')
                    ->selectRaw('count(id) AS total, 
                                    country_code,
                                    country')
                    ->groupBy('country_code')
                    ->orderBy('total', 'desc')
                    ->get();

    return $countries;
}

 /** TELESIGN */
 function teleSign($phone, $message)
 {
     $customer_id = env('TELESIGN_CUSTOMER_ID');
     $api_key = env('TELESIGN_API_KEY');
     $phone_number = "$phone";
     $message = "$message";
     $message_type = 'ARN';
     $messaging = new MessagingClient($customer_id, $api_key);
     $response = $messaging->message($phone_number, $message, $message_type);
 }

/**
 * SIDE MENU
 */
function menu()
{
    return [

        'dashboard' => [
            'icon' => 'ni-dashboard',
            'route_name' => 'backend',
            'permission' => 'everyone',
            'params' => [],
            'title' => translate('Dashboard'),
        ],

        'dialer' => [
            'icon' => 'ni-mobile',
            'route_name' => 'dialer.index',
            'permission' => 'everyone',
            'params' => [],
            'title' => translate('Web Dialer'),
        ],

        'departments' => [
            'icon' => 'ni-share-alt',
            'route_name' => 'dashboard.departments.index',
            'permission' => 'adminCustomer',
            'params' => [],
            'title' => translate('Departments'),
        ],

        'messages' => [
            'icon' => 'ni-chat',
            'route_name' => 'message.index',
            'permission' => 'everyone',
            'params' => [],
            'title' => translate('Messages'),
        ],

        'shops' => [
            'icon' => 'ni-cart',
            'route_name' => 'shop.index',
            'permission' => 'adminCustomer',
            'params' => [],
            'title' => translate('Shops'),
            'sub_menu' => [
                'purchased_numbers' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'shop.purchased.numbers',
                    'params' => [],
                    'permission' => 'adminCustomer',
                    'title' => translate('Purchased'),
                ],
                'new_order' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'shop.ordered.numbers',
                    'params' => [],
                    'permission' => 'admin',
                    'title' => translate('New Order') . ' (' . get_new_ordered_numbers()->count() . ')',
                ],
                'renew_order' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'shop.renew.numbers',
                    'params' => [],
                    'permission' => 'adminCustomer',
                    'title' => translate('Renew Order') . ' (' . get_renew_ordered_numbers()->count() . ')',
                ],
                'configurable' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'shop.configurable.numbers',
                    'params' => [],
                    'permission' => 'admin',
                    'title' => translate('Configurable') . '(' . get_configurable_numbers()->count() . ')',
                ],
            ],
        ],

        'contacts' => [
            'icon' => 'ni-book',
            'title' => translate('Contacts'),
            'route_name' => 'dashboard.contact.index',
            'params' => [],
            'permission' => 'everyone',
            'sub_menu' => [
                'create_contact' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.contact.index',
                    'params' => [],
                    'permission' => 'everyone',
                    'title' => translate('New Contact'),
                ],
                'groups' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.contact.group.index',
                    'params' => [],
                    'permission' => 'everyone',
                    'title' => translate('Groups'),
                ],
            ],
        ],

        'providers' => [
            'icon' => 'ni-call-alt',
            'title' => translate('Providers'),
            'route_name' => 'dashboard.provider.index',
            'params' => [],
            'permission' => 'adminCustomer',
            'sub_menu' => [
                'create_provider' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.provider.index',
                    'params' => [],
                    'permission' => 'admin',
                    'title' => translate('New Provider'),
                ],
                'provider_accounts' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.provider.accounts',
                    'params' => [],
                    'permission' => 'adminCustomer',
                    'title' => translate('Provider Accounts'),
                ],
                // 'analytics' => [
                //     'icon' => 'ni-minus-sm',
                //     'route_name' => 'dashboard.analytics.index',
                //     'params' => [],
                //     'permission' => 'adminCustomer',
                //     'title' => translate('Analytics'),
                // ],
            ],
        ],

        'call_history' => [
            'icon' => 'ni-view-list-wd',
            'title' => translate('Call History'),
            'route_name' => 'dashboard.call.history',
            'params' => [],
            'permission' => 'everyone',
        ],

        'geo_permissions' => [
            'icon' => 'ni-coin-alt',
            'title' => translate('Geo Permissions'),
            'route_name' => 'dashboard.twilio.call.cost.index',
            'params' => [],
            'permission' => 'everyone',
        ],

        'campaigns' => [
            'icon' => 'ni-grid-line',
            'title' => translate('Campaigns'),
            'route_name' => 'dashboard.campaign.index',
            'params' => [],
            'permission' => 'everyone',
            'sub_menu' => [
                'create_campaigns' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.campaign.index',
                    'params' => [],
                    'permission' => 'everyone',
                    'title' => translate('New Campaign'),
                ],
                'voice_campaign' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.campaign.voice',
                    'params' => [],
                    'permission' => 'everyone',
                    'title' => translate('Live Call Campaign'),
                ],
                'leads' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.campaign.leads',
                    'params' => [],
                    'permission' => 'everyone',
                    'title' => translate('Campaign Leads'),
                ],

            ],
        ],

        'clients' => [
            'icon' => 'ni-users',
            'title' => translate('Clients'),
            'route_name' => 'dashboard.clients.index',
            'params' => [],
            'permission' => 'admin',
            'sub_menu' => [
                'all-clients' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.clients.index',
                    'params' => [],
                    'title' => translate('All Clients'),
                ],
                'agents' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.agents.index',
                    'params' => [],
                    'title' => translate('Agents'),
                ],
                'kyc' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.kyc.index',
                    'params' => [],
                    'title' => translate('Verify Documents'),
                ]
            ],
        ],

        'agents' => [
            'icon' => 'ni-users',
            'title' => translate('Agents'),
            'route_name' => 'dashboard.agents.index',
            'params' => [],
            'permission' => 'customer',
            'sub_menu' => [
                'all-agents' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.agents.index',
                    'params' => [],
                    'title' => translate('New Agent'),
                ],
            ],
        ],

        'order_and_invoice' => [
            'icon' => 'ni-tranx',
            'title' => translate('Order & Invoice'),
            'route_name' => 'dashboard.profile.billing.history',
            'params' => [],
            'permission' => 'admin',
            'sub_menu' => [
                'payment_history' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.profile.billing.history',
                    'params' => [],
                    'title' => translate('Payment History'),
                ],
            ],
        ],

        'features' => [
            'icon' => 'ni-note-add',
            'route_name' => 'dashboard.features.create',
            'permission' => 'admin',
            'params' => [],
            'title' => translate('Features'),
        ],

        'packages' => [
            'icon' => 'ni-property-alt',
            'route_name' => 'dashboard.packages.index',
            'permission' => 'admin',
            'params' => [],
            'title' => translate('Packages'),
        ],

        'cronjobs' => [
            'icon' => 'ni-update',
            'route_name' => 'dashboard.cron.jobs',
            'permission' => 'admin',
            'params' => [],
            'title' => translate('Cron Jobs'),
        ],

        'blogs' => [
            'icon' => 'ni-book-read',
            'route_name' => 'dashboard.page.index',
            'permission' => 'admin',
            'params' => [],
            'title' => translate('Blogs/Pages'),
        ],

        'settings' => [
            'icon' => 'ni-setting-alt',
            'title' => translate('Settings'),
            'route_name' => 'dashboard.application.setup',
            'params' => [],
            'permission' => 'admin',
            'sub_menu' => [
                'application' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.application.setup',
                    'params' => [],
                    'permission' => 'admin',
                    'title' => translate('Application'),
                ],
                'languages' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'language.index',
                    'permission' => 'admin',
                    'params' => [],
                    'title' => translate('Language'),
                ],
                'seo' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.seo.setup',
                    'params' => [],
                    'title' => translate('SEO'),
                    'permission' => 'admin',
                ],
                'menus' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.menu-builder.index',
                    'params' => [],
                    'title' => translate('Menus'),
                    'permission' => 'admin',
                ],
                'currency' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.currency.index',
                    'params' => [],
                    'permission' => 'admin',
                    'title' => translate('Currency'),
                ],
                'languages' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'language.index',
                    'permission' => 'admin',
                    'params' => [],
                    'title' => translate('Language'),
                ],
                'smtp' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.smtp.index',
                    'params' => [],
                    'title' => translate('SMTP'),
                    'permission' => 'admin',
                ],
                'custom_styles_scripts' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.application.custom.styles.scripts',
                    'params' => [],
                    'permission' => 'admin',
                    'title' => translate('Custom CSS JS'),
                ],
            ],
        ],

        'payment_gateways' => [
            'icon' => 'ni-cards',
            'title' => translate('Payment Gateways'),
            'route_name' => 'dashboard.payment.gateways',
            'params' => [],
            'permission' => 'admin',
            'sub_menu' => [
                'ssl_commerz' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.sslcommerz.setup',
                    'params' => [],
                    'title' => translate('SSL COMMERZ'),
                    'permission' => 'admin',
                ],
                'braintree' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.braintree.setup',
                    'params' => [],
                    'title' => translate('PayPal Braintree'),
                    'permission' => 'admin',
                ],
                'stripe' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.stripe.setup',
                    'params' => [],
                    'title' => translate('Stripe'),
                    'permission' => 'admin',
                ],
                'flutterwave' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.flutterwave.index',
                    'params' => [],
                    'title' => translate('Flutterwave'),
                    'permission' => 'admin',
                ],
                'Paystack' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'paystack.index',
                    'params' => [],
                    'title' => translate('Paystack'),
                    'permission' => 'admin',
                ],
                'razorpay' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'razorpay.payment.index',
                    'params' => [],
                    'title' => translate('Razorpay'),
                    'permission' => 'admin',
                ],
            ],
        ],

        'My_Subscription' => [
            'icon' => 'ni-file-text',
            'route_name' => 'dashboard.profile.billing.subscription',
            'title' => translate('My Subscription'),
            'permission' => 'customer',
            'params' => [],
        ],

        'Payment_History' => [
            'icon' => 'ni-report-profit',
            'route_name' => 'dashboard.profile.billing.history',
            'title' => translate('Payment History'),
            'permission' => 'customer',
            'params' => [],
        ],

        'Billing_Cycle' => [
            'icon' => 'ni-coin-eur',
            'route_name' => 'dashboard.profile.billing',
            'title' => translate('Billing Cycle'),
            'permission' => 'customer',
            'params' => [],
        ],

        'Account_Settings' => [
            'icon' => 'ni-account-setting',
            'route_name' => 'dashboard.profile.information',
            'title' => translate('Account Settings'),
            'permission' => 'customer',
            'params' => [],
        ],

        'Account_Reports' => [
            'icon' => 'ni-reports-alt',
            'route_name' => 'dashboard.profile.account.report',
            'title' => translate('Account Reports'),
            'permission' => 'customer',
            'params' => [],
        ],

        'kyc' => [
            'icon' => 'ni-check-circle-cut',
            'route_name' => 'dashboard.kyc.index',
            'title' => translate('Verify Document'),
            'permission' => 'adminCustomer',
            'params' => [],
        ],

        'addons' => [
            'icon' => 'ni-puzzle',
            'title' => translate('Addons'),
            'route_name' => 'dashboard.addons.index',
            'params' => [],
            'permission' => 'adminCustomer',
            'sub_menu' => [
                'perfex' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'perfex.index',
                    'params' => [],
                    'title' => translate('Perfex CRM'),
                    'permission' => 'adminCustomer',
                ],
                'woocommerce' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'wp.index',
                    'params' => [],
                    'title' => translate('WooCommerce'),
                    'permission' => 'adminCustomer',
                ],
                'bulk_sms' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.addons.index',
                    'params' => [],
                    'title' => translate('Bulk SMS'),
                    'permission' => 'admin',
                ],
            ],
        ],

        'newsletters' => [
            'icon' => 'ni-report-profit',
            'route_name' => 'dashboard.newsletters.index',
            'title' => translate('Newsletters'),
            'permission' => 'admin',
            'params' => [],
        ],

        'upgrade' => [
            'icon' => 'ni-sort-v',
            'route_name' => 'dashboard.upgrade',
            'title' => translate('Upgrade'),
            'permission' => 'admin',
            'params' => [],
        ],

    ];
}

/**
 * EXTENDED MENU
 */

 function extended_menu()
{

    $cacheKey = 'extended_menu_cache'; // Define a unique key for the cache
    $ttl = 60 * 60; // Time-to-live for the cache, e.g., 1 hour

    // Check if the menu is cached and return it if available
    if (Cache::has($cacheKey)) {
        return Cache::get($cacheKey);
    }

    $menu = [

        'dashboard' => [
            'icon' => 'ni-dashboard',
            'route_name' => 'backend',
            'permission' => 'everyone',
            'params' => [],
            'title' => translate('Dashboard'),
            'sub_menu' => [
                'shops' => [
                    'icon' => 'ni-cart',
                    'route_name' => 'shop.index',
                    'permission' => 'adminCustomer',
                    'params' => [],
                    'title' => translate('Shops'),
                ],
                'dialer' => [
                    'icon' => 'ni-mobile',
                    'route_name' => 'dialer.index',
                    'params' => [],
                    'permission' => 'everyone',
                    'title' => translate('Web Dialer'),
                ],
                'departments' => [
                    'icon' => 'ni-share-alt',
                    'route_name' => 'dashboard.departments.index',
                    'permission' => 'adminCustomer',
                    'params' => [],
                    'title' => translate('Departments'),
                ],
                'messages' => [
                    'icon' => 'ni-chat',
                    'route_name' => 'message.index',
                    'permission' => 'everyone',
                    'params' => [],
                    'title' => translate('Messages'),
                ],
                'contacts' => [
                    'icon' => 'ni-book',
                    'route_name' => 'dashboard.contact.index',
                    'params' => [],
                    'permission' => 'everyone',
                    'title' => translate('Contacts'),
                ],
                'providers' => [
                    'icon' => 'ni-call-alt',
                    'route_name' => 'dashboard.provider.index',
                    'params' => [],
                    'permission' => 'adminCustomer',
                    'title' => translate('Providers'),
                ],
                'call_history' => [
                    'icon' => 'ni-view-list-wd',
                    'title' => translate('Call History'),
                    'route_name' => 'dashboard.call.history',
                    'params' => [],
                    'permission' => 'everyone',
                ],
                'geo_permissions' => [
                    'icon' => 'ni-coin-alt',
                    'route_name' => 'dashboard.twilio.call.cost.index',
                    'params' => [],
                    'permission' => 'everyone',
                    'title' => translate('Geo Permissions'),
                ],
                'campaigns' => [
                    'icon' => 'ni-grid-line',
                    'route_name' => 'dashboard.campaign.index',
                    'params' => [],
                    'permission' => 'everyone',
                    'title' => translate('Campaigns'),
                ],
                'clients' => [
                    'icon' => 'ni-users',
                    'route_name' => 'dashboard.clients.index',
                    'params' => [],
                    'permission' => 'admin',
                    'title' => translate('User Management'),
                ],
                'agents' => [
                    'icon' => 'ni-users',
                    'route_name' => 'dashboard.agents.index',
                    'params' => [],
                    'permission' => 'customer',
                    'title' => translate('Agents'),
                ],
                'order_and_invoice' => [
                    'icon' => 'ni-tranx',
                    'route_name' => 'dashboard.profile.billing.history',
                    'params' => [],
                    'permission' => 'admin',
                    'title' => translate('Order & Invoice'),
                ],
                'features' => [
                    'icon' => 'ni-note-add',
                    'route_name' => 'dashboard.features.create',
                    'permission' => 'admin',
                    'params' => [],
                    'title' => translate('Features'),
                ],
                'packages' => [
                    'icon' => 'ni-property-alt',
                    'route_name' => 'dashboard.packages.index',
                    'permission' => 'admin',
                    'params' => [],
                    'title' => translate('Packages'),
                ],
                'cronjobs' => [
                    'icon' => 'ni-update',
                    'route_name' => 'dashboard.cron.jobs',
                    'permission' => 'admin',
                    'params' => [],
                    'title' => translate('Cron Jobs'),
                ],
                'blogs' => [
                    'icon' => 'ni-book-read',
                    'route_name' => 'dashboard.page.index',
                    'permission' => 'admin',
                    'params' => [],
                    'title' => translate('Blogs/Pages'),
                ],
                'languages' => [
                    'icon' => 'ni-sign-usdt',
                    'route_name' => 'language.index',
                    'permission' => 'admin',
                    'params' => [],
                    'title' => translate('Language'),
                ],
                'settings' => [
                    'icon' => 'ni-setting-alt',
                    'route_name' => 'dashboard.application.setup',
                    'permission' => 'admin',
                    'params' => [],
                    'title' => translate('Application Setup'),
                ],
                'payment_gateways' => [
                    'icon' => 'ni-cards',
                    'route_name' => 'dashboard.payment.gateways',
                    'permission' => 'admin',
                    'params' => [],
                    'title' => translate('Payment Gateways'),
                ],
                'addons' => [
                    'icon' => 'ni-puzzle',
                    'route_name' => 'dashboard.addons.index',
                    'permission' => 'adminCustomer',
                    'params' => [],
                    'title' => translate('Addons'),
                ],
                'My_Subscription' => [
                    'icon' => 'ni-file-text',
                    'route_name' => 'dashboard.profile.billing.subscription',
                    'title' => translate('My Subscription'),
                    'permission' => 'customer',
                    'params' => [],
                ],
                'Payment_History' => [
                    'icon' => 'ni-report-profit',
                    'route_name' => 'dashboard.profile.billing.history',
                    'title' => translate('Payment History'),
                    'permission' => 'customer',
                    'params' => [],
                ],
                'Billing_Cycle' => [
                    'icon' => 'ni-coin-eur',
                    'route_name' => 'dashboard.profile.billing',
                    'title' => translate('Billing Cycle'),
                    'permission' => 'customer',
                    'params' => [],
                ],
                'Account_Settings' => [
                    'icon' => 'ni-account-setting',
                    'route_name' => 'dashboard.profile.information',
                    'title' => translate('Account Settings'),
                    'permission' => 'customer',
                    'params' => [],
                ],

                'Account_Reports' => [
                    'icon' => 'ni-reports-alt',
                    'route_name' => 'dashboard.profile.account.report',
                    'title' => translate('Account Reports'),
                    'permission' => 'customer',
                    'params' => [],
                ],
                'kyc' => [
                    'icon' => 'ni-check-circle-cut',
                    'route_name' => 'dashboard.kyc.index',
                    'title' => translate('Verify Documents'),
                    'permission' => 'adminCustomer',
                    'params' => [],
                ],
                'newsletters' => [
                    'icon' => 'ni-report-profit',
                    'route_name' => 'dashboard.newsletters.index',
                    'title' => translate('Newsletters'),
                    'permission' => 'admin',
                    'params' => [],
                ],
                'upgrade' => [
                    'icon' => 'ni-sort-v',
                    'route_name' => 'dashboard.upgrade',
                    'title' => translate('Upgrade'),
                    'permission' => 'admin',
                    'params' => [],
                ],
            ],
        ],

        'shops' => [
            'icon' => 'ni-cart',
            'route_name' => 'shop.index',
            'permission' => 'adminCustomer',
            'params' => [],
            'title' => translate('Shops'),
            'sub_menu' => [
                'purchased_numbers' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'shop.purchased.numbers',
                    'params' => [],
                    'permission' => 'adminCustomer',
                    'title' => translate('Purchased'),
                ],
                'new_order' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'shop.ordered.numbers',
                    'params' => [],
                    'permission' => 'admin',
                    'title' => translate('New Order') . ' (' . get_new_ordered_numbers()->count() . ')',
                ],
                'renew_order' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'shop.renew.numbers',
                    'params' => [],
                    'permission' => 'adminCustomer',
                    'title' => translate('Renew Order') . '  (' . get_renew_ordered_numbers()->count() . ')',
                ],
                'configurable' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'shop.configurable.numbers',
                    'params' => [],
                    'permission' => 'admin',
                    'title' => translate('Configurable') . '  (' . get_configurable_numbers()->count() . ')',
                ],
            ],
        ],

        'dialer' => [
            'icon' => 'ni-mobile',
            'route_name' => 'dialer.index',
            'permission' => 'everyone',
            'params' => [],
            'title' => translate('Web Dialer'),
            'sub_menu' => [],
        ],

        'departments' => [
            'icon' => 'ni-share-alt',
            'route_name' => 'dashboard.departments.index',
            'permission' => 'adminCustomer',
            'params' => [],
            'title' => translate('Departments'),
        ],

        'messages' => [
            'icon' => 'ni-chat',
            'route_name' => 'message.index',
            'permission' => 'everyone',
            'params' => [],
            'title' => translate('Messages'),
        ],

        'contacts' => [
            'icon' => 'ni-book',
            'title' => translate('Contacts'),
            'route_name' => 'dashboard.contact.index',
            'params' => [],
            'permission' => 'everyone',
            'sub_menu' => [
                'create_contact' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.contact.index',
                    'params' => [],
                    'title' => translate('New Contact'),
                    'permission' => 'everyone',
                ],
                'groups' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.contact.group.index',
                    'params' => [],
                    'title' => translate('Groups'),
                    'permission' => 'everyone',
                ],
            ],
        ],

        'providers' => [
            'icon' => 'ni-call-alt',
            'title' => translate('Providers'),
            'route_name' => 'dashboard.provider.index',
            'params' => [],
            'permission' => 'adminCustomer',
            'sub_menu' => [
                'create_provider' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.provider.index',
                    'params' => [],
                    'title' => translate('New Provider'),
                    'permission' => 'admin',
                ],
                'provider_accounts' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.provider.accounts',
                    'params' => [],
                    'title' => translate('Provider Accounts'),
                    'permission' => 'adminCustomer',
                ],
                // 'analytics' => [
                //     'icon' => 'ni-minus-sm',
                //     'route_name' => 'dashboard.analytics.index',
                //     'params' => [],
                //     'title' => translate('Analytics'),
                //     'permission' => 'adminCustomer',
                // ],
            ],
        ],

        'call_history' => [
            'icon' => 'ni-view-list-wd',
            'title' => translate('Call History'),
            'route_name' => 'dashboard.call.history',
            'params' => [],
            'permission' => 'everyone',
        ],

        'geo_permissions' => [
            'icon' => 'ni-coin-alt',
            'title' => translate('Geo Permissions'),
            'route_name' => 'dashboard.twilio.call.cost.index',
            'params' => [],
            'permission' => 'everyone',
        ],

        'call_history' => [
            'icon' => 'ni-view-list-wd',
            'title' => translate('Call History'),
            'route_name' => 'dashboard.call.history',
            'params' => [],
            'permission' => 'everyone',
        ],

        'campaigns' => [
            'icon' => 'ni-grid-line',
            'title' => translate('Campaigns'),
            'route_name' => 'dashboard.campaign.index',
            'params' => [],
            'permission' => 'everyone',
            'sub_menu' => [
                'create_campaigns' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.campaign.index',
                    'params' => [],
                    'title' => translate('New Campaign'),
                    'permission' => 'everyone',
                ],
                'voice_campaign' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.campaign.voice',
                    'params' => [],
                    'title' => translate('Live Call Campaign'),
                    'permission' => 'everyone',
                ],
                'leads' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.campaign.leads',
                    'params' => [],
                    'title' => translate('Campaign Leads'),
                    'permission' => 'everyone',
                ],

            ],
        ],

        'clients' => [
            'icon' => 'ni-users',
            'title' => translate('User Management'),
            'route_name' => 'dashboard.clients.index',
            'params' => [],
            'permission' => 'admin',
            'sub_menu' => [
                'all-clients' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.clients.index',
                    'params' => [],
                    'title' => translate('All Clients'),
                    'permission' => 'admin',
                ],
                'agents' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.agents.index',
                    'params' => [],
                    'title' => translate('Agents'),
                    'permission' => 'admin',
                ],
                'kyc' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.kyc.index',
                    'params' => [],
                    'title' => translate('Verify Documents') . ' (' . unseen_kyc_documents() . ')',
                    'permission' => 'admin',
                ]
            ],
        ],

        'agents' => [
            'icon' => 'ni-users',
            'title' => translate('Agents'),
            'route_name' => 'dashboard.agents.index',
            'params' => [],
            'permission' => 'customer',
            'sub_menu' => [
                'agents' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.agents.index',
                    'params' => [],
                    'title' => translate('New Agent'),
                    'permission' => 'customer',
                ],
            ],
        ],

        'order_and_invoice' => [
            'icon' => 'ni-tranx',
            'title' => translate('Order & Invoice'),
            'route_name' => 'dashboard.profile.billing.history',
            'params' => [],
            'permission' => 'admin',
            'sub_menu' => [
                'payment_history' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.profile.billing.history',
                    'params' => [],
                    'title' => translate('Payment History'),
                    'permission' => 'admin',
                ],
            ],
        ],

        'features' => [
            'icon' => 'ni-note-add',
            'route_name' => 'dashboard.features.create',
            'permission' => 'admin',
            'params' => [],
            'title' => translate('Features'),
        ],

        'packages' => [
            'icon' => 'ni-property-alt',
            'route_name' => 'dashboard.packages.index',
            'permission' => 'admin',
            'params' => [],
            'title' => translate('Packages'),
        ],

        'cronjobs' => [
            'icon' => 'ni-update',
            'route_name' => 'dashboard.cron.jobs',
            'permission' => 'admin',
            'params' => [],
            'title' => translate('Cron Jobs'),
        ],

        'blogs' => [
            'icon' => 'ni-book-read',
            'route_name' => 'dashboard.page.index',
            'permission' => 'admin',
            'params' => [],
            'title' => translate('Blogs/Pages'),
        ],

        'settings' => [
            'icon' => 'ni-setting-alt',
            'title' => translate('Settings'),
            'route_name' => 'dashboard.application.setup',
            'params' => [],
            'permission' => 'admin',
            'sub_menu' => [
                'application' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.application.setup',
                    'params' => [],
                    'title' => translate('Application'),
                    'permission' => 'admin',
                ],
                'seo' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.seo.setup',
                    'params' => [],
                    'title' => translate('SEO'),
                    'permission' => 'admin',
                ],
                'menus' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.menu-builder.index',
                    'params' => [],
                    'title' => translate('Menus'),
                    'permission' => 'admin',
                ],
                'currency' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.currency.index',
                    'params' => [],
                    'title' => translate('Currency'),
                    'permission' => 'admin',
                ],
                'languages' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'language.index',
                    'permission' => 'admin',
                    'params' => [],
                    'title' => translate('Language'),
                ],
                'smtp' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.smtp.index',
                    'params' => [],
                    'title' => translate('SMTP'),
                    'permission' => 'admin',
                ],
                'custom_styles_scripts' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.application.custom.styles.scripts',
                    'params' => [],
                    'title' => translate('Custom CSS JS'),
                    'permission' => 'admin',
                ],
            ],
        ],

        'payment_gateways' => [
            'icon' => 'ni-cards',
            'title' => translate('Payment Gateways'),
            'route_name' => 'dashboard.payment.gateways',
            'params' => [],
            'permission' => 'admin',
            'sub_menu' => [
                'ssl_commerz' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.sslcommerz.setup',
                    'params' => [],
                    'title' => translate('SSL COMMERZ'),
                    'permission' => 'admin',
                ],
                'braintree' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.braintree.setup',
                    'params' => [],
                    'title' => translate('PayPal Braintree'),
                    'permission' => 'admin',
                ],
                'stripe' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.stripe.setup',
                    'params' => [],
                    'title' => translate('Stripe'),
                    'permission' => 'admin',
                ],
                'flutterwave' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.flutterwave.index',
                    'params' => [],
                    'title' => translate('Flutterwave'),
                    'permission' => 'admin',
                ],
                'paystack' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'paystack.index',
                    'params' => [],
                    'title' => translate('Paystack'),
                    'permission' => 'admin',
                ],
                'razorpay' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'razorpay.payment.index',
                    'params' => [],
                    'title' => translate('Razorpay'),
                    'permission' => 'admin',
                ],
                'squad' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'squad.index',
                    'params' => [],
                    'title' => translate('Squad'),
                    'permission' => 'admin',
                ],
            ],
        ],

        'addons' => [
            'icon' => 'ni-puzzle',
            'title' => translate('Addons'),
            'route_name' => 'dashboard.addons.index',
            'params' => [],
            'permission' => 'adminCustomer',
            'sub_menu' => [
                'perfex' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'perfex.index',
                    'params' => [],
                    'title' => translate('Perfex CRM'),
                    'permission' => 'adminCustomer',
                ],
                'woocommerce' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'wp.index',
                    'params' => [],
                    'title' => translate('WooCommerce'),
                    'permission' => 'adminCustomer',
                ],
                'bulk_sms' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.addons.index',
                    'params' => [],
                    'title' => translate('Bulk SMS'),
                    'permission' => 'admin',
                ],
                'mojsms' => [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dashboard.addons.mojsms',
                    'params' => [],
                    'title' => translate('mojsms.eu'),
                    'permission' => 'adminCustomer',
                ],
            ],
        ],

        'My_Subscription' => [
            'icon' => 'ni-file-text',
            'route_name' => 'dashboard.profile.billing.subscription',
            'title' => translate('My Subscription'),
            'permission' => 'customer',
            'params' => [],
        ],

        'Payment_History' => [
            'icon' => 'ni-report-profit',
            'route_name' => 'dashboard.profile.billing.history',
            'title' => translate('Payment History'),
            'permission' => 'customer',
            'params' => [],
        ],

        'Billing_Cycle' => [
            'icon' => 'ni-coin-eur',
            'route_name' => 'dashboard.profile.billing',
            'title' => translate('Billing Cycle'),
            'permission' => 'customer',
            'params' => [],
        ],

        'Account_Settings' => [
            'icon' => 'ni-account-setting',
            'route_name' => 'dashboard.profile.information',
            'title' => translate('Account Settings'),
            'permission' => 'customer',
            'params' => [],
        ],

        'Account_Reports' => [
            'icon' => 'ni-reports-alt',
            'route_name' => 'dashboard.profile.account.report',
            'title' => translate('Account Reports'),
            'permission' => 'customer',
            'params' => [],
        ],

        'newsletters' => [
            'icon' => 'ni-report-profit',
            'route_name' => 'dashboard.newsletters.index',
            'title' => translate('Newsletters'),
            'permission' => 'admin',
            'params' => [],
        ],

        'upgrade' => [
            'icon' => 'ni-sort-v',
            'route_name' => 'dashboard.upgrade',
            'title' => translate('Upgrade'),
            'permission' => 'admin',
            'params' => [],
        ],

    ];

    // departments submenu
    foreach (departments() as $department) {

        $isAgentInDepartment = is_agent(auth()->id()) && agent_is_in_department(get_agent_id(auth()->id()), $department->id);

        // only showing outbound departments
        if (department_options($department->id)['outbound'] == true) {
            $menu['dialer']['sub_menu'][] = [
                'icon' => 'ni-minus-sm',
                'route_name' => 'dialer.index',
                'permission' => $isAgentInDepartment ? 'agent' : 'adminCustomer',
                'class' => 'ml-4',
                'params' => [
                    'department' => $department->id,
                    'department_slug' => Str::slug($department->name)
                ],
                'title' => $department->name,
            ];
        }
    }

    $menu['dialer']['sub_menu'][] = [
        'icon' => 'ni-arrow-to-down',
        'route_name' => null,
        'permission' => 'everyone',
        'params' => [],
        'title' => translate('Inbound Department'),
    ];

    // departments submenu
    foreach (departments() as $department) {
        // Only showing outbound departments
        if (department_options($department->id)['inbound'] == true) {
            foreach ($department->providers as $provider) {
                // Check if the user is an agent and is in this department
                $isAgentInDepartment = is_agent(auth()->id()) && agent_is_in_department(get_agent_id(auth()->id()), $department->id);

                $menu['dialer']['sub_menu'][] = [
                    'icon' => 'ni-minus-sm',
                    'route_name' => 'dialerpad',
                    'permission' => $isAgentInDepartment ? 'agent' : 'adminCustomer',
                    'class' => 'ml-4',
                    'new_window' => 'target="_blank"',
                    'params' => [
                        'my_number' => getProviderById($provider->provider_id)->phone ?? null,
                    ],
                    'title' => $department->name . ' (' . getProviderById($provider->provider_id)->provider_name . ')',
                ];
            }
        }
    }

    Cache::put($cacheKey, $menu, $ttl);
    return $menu;
}

/**
 * Get Submenu
 */
function all_side_menu()
{
    return [
        'backend' => 'dashboard',

        'dialer.index' => 'dialer',

        'dashboard.contact.index' => 'contacts',
        'dashboard.contact.group.index' => 'contacts',
        'dashboard.contact.search' => 'contacts',
        
        'dashboard.provider.index' => 'providers',
        'dashboard.provider.edit' => 'providers',
        'dashboard.provider.accounts' => 'providers',
        'dashboard.analytics.index' => 'providers',

        'dashboard.campaign.index' => 'campaigns',
        'dashboard.campaign.voice' => 'campaigns',
        'dashboard.campaign.leads' => 'campaigns',

        'dashboard.clients.index' => 'clients',

        'dashboard.application.setup' => 'settings',
        'language.index' => 'settings',
        'dashboard.seo.setup' => 'settings',
        'dashboard.menu-builder.index' => 'settings',
        'dashboard.currency.index' => 'settings',
        'dashboard.smtp.index' => 'settings',
        'dashboard.application.custom.styles.scripts' => 'settings',
        'language.index' => 'settings',
        'language.translate' => 'settings',


        'dashboard.sslcommerz.setup' => 'payment_gateways',
        'dashboard.braintree.setup' => 'payment_gateways',
        'dashboard.stripe.setup' => 'payment_gateways',
        'dashboard.payment.gateways' => 'payment_gateways',
        'dashboard.flutterwave.index' => 'payment_gateways',
        'paystack.index' => 'payment_gateways',
        'paystack.store' => 'payment_gateways',
        'razorpay.payment.index' => 'payment_gateways',
        'razorpay.payment.setup' => 'payment_gateways',
        'dashboard.instamojo.index' => 'payment_gateways',
        'payment.setup.instamojo.store' => 'payment_gateways',
        'squad.index' => 'payment_gateways',

        'dashboard.interactive.index' => 'departments',
        'dashboard.interactive.create' => 'departments',
        'dashboard.interactive.show' => 'departments',

        'dashboard.addons.index' => 'addons',
        'perfex.index' => 'addons',
        'wp.index' => 'addons',

        'shop.index' => 'shops',
        'shop.purchased.numbers' => 'shops',
        'shop.ordered.numbers' => 'shops',
        'shop.configurable.numbers' => 'shops',
        'shop.renew.numbers' => 'shops',
    ];
}

/**
 * Search by key from all_side_menu()
 */
function search_side_menu($key)
{
    $all_side_menu = all_side_menu();
    if (array_key_exists($key, $all_side_menu)) {
        return $all_side_menu[$key];
    }
    return false;
}


/**
 * PAYMENT GATEWAYS
 */
function availableGateways()
{
    return [
        'SSL COMMERZ' => [
            'logo' => asset('ssl.png'),
            'title' => 'SSL COMMERZ',
            'slug' => 'sslcommerz',
        ],
        'PayPal' => [
            'logo' => asset('frontend/titania/assets/img/graphics/icons/checkout/paypal.svg'),
            'title' => 'PayPal',
            'slug' => 'paypal',
        ],
        'Stripe' => [
            'logo' => asset('frontend/titania/assets/img/graphics/icons/checkout/stripe.svg'),
            'title' => 'Stripe',
            'slug' => 'stripe',
        ],
        'Flutterwave' => [
            'logo' => asset('payment_gatways/flw_icon.png'),
            'title' => 'Flutterwave',
            'slug' => 'flutterwave',
        ],
        'Paystack' => [
            'logo' => asset('payment_gatways/paystack.png'),
            'title' => 'Paystack',
            'slug' => 'paystack',
        ],
        // 'Instamojo' => [
        //     'logo' => asset('payment_gatways/instamojo.png'),
        //     'title' => 'Instamojo',
        //     'slug' => 'instamojo',
        // ],
        'Razorpay' => [
            'logo' => asset('payment_gatways/razorpay.png'),
            'title' => 'Razorpay',
            'slug' => 'razorpay',
        ],
    ];
}

// generateRandomString
function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}

// trim domain
function trimDomain($domain)
{
    $checkProtocol = Str::contains($domain, ['https://', 'http://']);

    if ($checkProtocol == true) {
        if (Str::contains($domain, 'https://')) {
            $removeHttps = Str::after($domain, 'https://');
        } elseif (Str::contains($domain, 'http://')) {
            $removeHttps = Str::after($domain, 'http://');
        }

        $base_domain = Str::before($removeHttps, '/public');

        return $base_domain;
    } else {
        return $domain;
    }
}

// getPayableAmountFromInvoice
function getPayableAmountFromInvoice($invoice, $currency)
{
    $amount = PaymentHistory::where('invoice', $invoice)->first()->amount;

    return Currency::convert()
            ->from(SystemCurrency::where('default', 1)->first()->symbol)
            ->to($currency)
            ->amount($amount)
            ->date(Carbon::now())
            ->round(2)
            ->get();
}

// getPayableAmountInBDT 
function getPayableAmountInBDT($invoice)
{
    $amount = PaymentHistory::where('invoice', $invoice)->first()->amount;

    return Currency::convert()
            ->from(SystemCurrency::where('default', 1)->first()->symbol)
            ->to('BDT')
            ->amount($amount)
            ->date(Carbon::now())
            ->round(2)
            ->get();
}

// get user info from invoice
function getUserInfoFromInvoice($invoice)
{
    $user = User::where('id', PaymentHistory::where('invoice', $invoice)->first()->user_id)->first();

    return $user;
}

// OTP
function generateOTP()
{
    $otp = Str::random(6);

    return $otp;
}

// all newsletters
function allNewsletters()
{
    return Newsletter::latest()
                      ->get();
}

// SEO
function seo($name)
{
    return Seo::where('name', $name)->first()->value ?? null;
}

// Application
function application($name)
{
    return Application::where('name', $name)->first()->value ?? null;
}

/**
 * The function `saasContent` retrieves the text content associated with a given `cid` from the
 * `SaasContent` table and then translates it using the `translate` function.
 * 
 * @param cid The parameter "cid" is used to identify the specific SaasContent record that you want to
 * retrieve. It is likely a unique identifier for each SaasContent record in the database.
 * 
 * @return the translated text content for the given `` value.
 */
function saasContent($cid)
{
    $cacheKey = 'saas_content_' . $cid;

    $cacheDuration = now()->addMinutes(5);

    $text = Cache::remember($cacheKey, $cacheDuration, function () use ($cid) {
        return SaasContent::where('cid', $cid)->first()->text ?? null;
    });

    if ($text != null) {
        return translate($text);
    }else {
        return $text;
    }
}

function saasImagePath($path)
{
    if ($path != null) {
        return asset('frontend/saas_content/uploads/'.$path);
    }
}

/**
 * MENU
 */
function menus($name)
{
    return $public_menu = Menu::getByName($name); //return array
}

/**
 * Pages
 */
function pages()
{
    return Page::where('status', 1)->get();
}

function pageName($id)
{
    return Page::where('id', $id)->first()->page_name ?? null;
}

/**
 * DUMMY DATA
 */
function dataApplication()
{
    $new = new Application;
    $new->name = 'site_name';
    $new->value = 'Teleman - Telemarketing & Voice Service Application';
    $new->save();

    $new = new Application;
    $new->name = 'site_email';
    $new->value = 'prince@thecodestudio.xyz';
    $new->save();

    $new = new Application;
    $new->name = 'site_phone';
    $new->value = '+880123456789';
    $new->save();

    $new = new Application;
    $new->name = 'site_facebook';
    $new->value = 'https://www.facebook.com/thecodestudio';
    $new->save();

    $new = new Application;
    $new->name = 'site_instagram';
    $new->value = 'https://www.instagram.com/thecodestudio/';
    $new->save();

    $new = new Application;
    $new->name = 'site_twitter';
    $new->value = 'https://twitter.com/thecodestudio';
    $new->save();

    $new = new Application;
    $new->name = 'site_youtube';
    $new->value = 'https://www.youtube.com/channel/thecodestudio';
    $new->save();

    $new = new Application;
    $new->name = 'site_linkedin';
    $new->value = 'https://www.linkedin.com/company/thecodestudio';
    $new->save();

    $new = new Application;
    $new->name = 'site_gateway_supports';
    $new->value = 'gateways.png';
    $new->save();

    $new = new Application;
    $new->name = 'site_logo';
    $new->value = 'logo.png';
    $new->save();

    $new = new Application;
    $new->name = 'site_dark_logo';
    $new->value = 'dark-logo.png';
    $new->save();

    $new = new Application;
    $new->name = 'site_favicon';
    $new->value = 'favicon.png';
    $new->save();

    $curr = new SystemCurrency;
    $curr->name = 'US Dollar';
    $curr->code = '840';
    $curr->symbol = 'USD';
    $curr->icon = '$';
    $curr->amount = '1';
    $curr->default = 1;
    $curr->save();

    $lan = new Language;
    $lan->code = 'en';
    $lan->name = 'English';
    $lan->image = 'Flag_of_the_United_States.png';
    $lan->save();

    File::put(base_path('/resources/lang/' . $lan->code . '.json'),'{}');

    $primary_menu = new Menus;
    $primary_menu->name = 'primary menu';
    $primary_menu->save();

    $footer_1 = new Menus;
    $footer_1->name = 'footer 1';
    $footer_1->save();

    $footer_4 = new Menus;
    $footer_4->name = 'dashboard menu 1';
    $footer_4->save();
}

/**
 * DEMO
 */
function demo()
{
    if (env('DEMO') == 'YES') {
        return true;
    } else {
        return false;
    }
}

 /**
  * TIMEZONE
  */
 function timeZone()
 {
     $json = file_get_contents(base_path('public/timezone.json'));

     return json_decode($json, true);
 }

    /**
     * PROVIDERS
     */
    function providers()
    {
        return [
            'Twilio',
        ];
    }

function createUserXMLfile($say, $audio, $file_name)
{
    $xml =
'<?xml version="1.0" encoding="UTF-8"?>
<Response>
  <Say voice="alice">'.$say.'</Say>
  <Play>'.$audio.'</Play>
</Response>';

    $path = '/public/voices/'.$file_name.'.xml';

    File::put(base_path($path), $xml);
}

   // voice_server_list
   function voice_server_list()
   {
       return [
           'Twilio',
       ];
   }

   /**
    * If the user is an admin, return all providers, otherwise return only providers that have an
    * agent.
    * </code>
    * 
    * @return A collection of Provider models.
    */
   function getVoiceServerWiseList()
    {
        $userId = auth()->id();
        $isAdmin = is_admin($userId);
        $cacheKey = $isAdmin ? 'voice_server_list_admin' : 'voice_server_list_agent_' . $userId;
        $cacheDuration = now()->addMinutes(30);
        $providers = Cache::remember($cacheKey, $cacheDuration, function () use ($isAdmin, $userId) {
            if ($isAdmin) {
                return Provider::get();
            } else {
                return Provider::HasAgent($userId)->get();
            }
        });
        return $providers;
    }


   /**
    * It returns a list of all the agents that have a voice server.
    * 
    * @return A collection of objects.
    */
   function getVoiceServerUserBasedList()
   {
        return Provider::HasAgent(auth()->id())->get();
   }

    // audioUpload
   function audioUpload($file, $folder)
   {
       return $file->store('/voices'.$folder);
   }

    // audioPath
   function audioPath($file)
   {
       return asset($file);
   }

    /**
     * Get Country from json
     */
    function getCountry()
    {
        $json = file_get_contents(base_path('public/country.json'));

        $countries = json_decode($json, true);

        // order by key
        sort($countries);
        return $countries;

    }

    /**
     * Profession list
     */
    function professionList()
    {
        return [
            'Accountant',
            'Actor',
            'Actress',
            'Advisor',
            'Architect',
            'Artist',
            'Auditor',
            'Author',
            'Baker',
            'Banker',
            'Barber',
            'Beautician',
            'Builder',
            'Businessman',
            'Businesswoman',
            'Carpenter',
            'Carpet Cleaner',
            'Carpet Installer',
            'Carpet Trader',
            'Carpet Worker',
        ];
    }

    /**
     * All Contacts OrderBy Alphabet
     */
    function allContacts()
    {
        $cacheKey = 'contacts_with_agents';

        $cacheDuration = now()->addMinutes(60); // Cache for 1 hour

        $contacts = Cache::remember($cacheKey, $cacheDuration, function () {
            return Contact::HasAgent()
                        ->orderBy('name', 'asc')
                        ->simplePaginate(50);
        });

        return $contacts;
    }

    /**
     * All Groups OrderBy Alphabet
     */
    function allGroups()
    {
        return Group::HasAgent()->orderBy('name', 'asc')->get();
    }

    /**
     * Check contact is in group
     */
    function checkContactInGroup($contact_id, $group_id)
    {
        $group_contact = GroupContact::where('group_id', $group_id)->where('contact_id', $contact_id)->first();
        if ($group_contact != null) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * It takes an id, finds the group with that id, gets the name of that group, makes it uppercase,
     * and returns it
     * 
     * @param id The id of the group you want to get the name of.
     * 
     * @return The name of the group.
     */
    function group_name($id)
    {
        $name = Group::where('id', $id)->first()->name ?? 'no-group';

        return Str::upper($name);
    }
    
    /**
     * Get all providers that have an agent.
     * 
     * @return A collection of Provider objects.
     */
    function all_providers()
    {
        return Provider::HasAgent(auth()->id())->get();
    }

    /**
     * Get all providers that have an agent, where the user_id is equal to the user_id passed in, and
     * where the phone is equal to the phone passed in.
     * 
     * @param phone The phone number of the provider
     * @param user_id The user id of the logged in user
     * 
     * @return A collection of providers that have an agent and match the user_id and phone number.
     */
    function getProvider($user_id, $phone)
    {
        return Provider::HasAgent($user_id)
                       ->where('phone', $phone)
                       ->first();
    }

    /**
     * The function "getProviderById" retrieves a provider by their ID and checks if they have an agent
     * associated with them.
     * 
     * @param user_id The user_id parameter is the ID of the user for whom we want to retrieve the
     * provider information.
     * @param provider_id The provider_id parameter is the unique identifier of the provider you want
     * to retrieve.
     * 
     * @return a Provider object that matches the given user_id and provider_id.
     */
    function getProviderById($provider_id)
    {
        return Provider::where('id', $provider_id)
                       ->first();
    }

    /**
     * It takes an id, finds the provider name in the database, and returns the name in uppercase.
     * 
     * @param id The id of the provider you want to get the name of.
     * 
     * @return The provider_name function is returning the provider_name from the Provider model.
     */
    function provider_name($id)
    {
        $name = Provider::where('id', $id)->first()->provider_name ?? 'no-provider';

        return Str::upper($name);
    }

    /**
     * Provider Phone
     */
    function provider_phone($id)
    {
        $phone = Provider::where('id', $id)->first()->phone ?? 'no-phone';

        return Str::upper($phone);
    }

    /**
     * Account SID
     */
    function account_sid($id)
    {
        $account_sid = Provider::where('id', $id)->first()->account_sid;

        return Str::upper($account_sid);
    }

    /**
     * auth_token
     */
    function auth_token($id)
    {
        $auth_token = Provider::where('id', $id)->first()->auth_token;

        return Str::upper($auth_token);
    }

    /**
     * Campaigns
     */
    function campaigns()
    {
        return Campaign::HasAgent()->with('provider')->orderBy('name', 'asc')->get();
    }

    /**
     * Campaign Name
     */
    function campaign_name($id) // campaign_id
    {
        $name = Campaign::where('id', $id)->first()->name;

        return Str::upper($name);
    }

    /**
     * Twilio Setup
     */
    function twilioSetup($sid, $token)
    {
        $twilio = new Client($sid, $token);

        return $twilio;
    }

    /**
     * TWILIO RECORDING
     */
    function twilioRecording($account_sid, $token, $sid)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.twilio.com/2010-04-01/Accounts/'.$account_sid.'/Calls/'.$sid.'/Recordings.json',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Basic '.base64_encode($account_sid.':'.$token),
            ],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);

        $recordings = json_decode($response);

        foreach ($recordings->recordings as $recording) {
            return $recording->media_url;
        }
    }

     /**
      * twilio_calling
      */
     function twilio_calling($provider_id, $to_number, $recording, $audio_file, $user_id = null)
     {
         $voice_server = Provider::find($provider_id);

         // Initialize the Programmable Voice API
         $client = new Client($voice_server->account_sid, $voice_server->auth_token);

         //Lookup phone number to make sure it is valid before initiating call
         $phone_number = $client->lookups->v1->phoneNumbers($voice_server->phone)->fetch();

         // If phone number is valid and exists
         if ($phone_number) {
             /**
              * AUDIO URL
              */

             // Initiate call and record call
            $call = $client->account->calls->create(
            $to_number, // Destination phone number
            $voice_server->phone, // Valid Twilio phone number
            [
                'record' => $recording, // Record the call: true or false
                "url" => $audio_file // URL of the audio file to play
            ]
            );
             // AUDIO URL ENDS

             if ($call) {
                 if (! is_admin($user_id)) {
                     store_call_duration('campaign_name', $to_number, $call->accountSid, $call->sid, $call->duration, $call->price, $call->status, $user_id);
                 }
             } else {
                 return false;
             }
         }
     }

    /**
     * GET PHONE NUMBER
     */
    function phone_number($id)
    {
        return Contact::where('id', $id)->first()->phone;
    }

    /**
     * Quota Log Store
     */
    function quota_log_store($provider_id, $user_id, $contact_id, $to_number)
    {
        $quota_log = new QuotaLog;
        $quota_log->user_id = $provider_id;
        $quota_log->user_id = $user_id;
        $quota_log->contact_id = $contact_id;
        $quota_log->to_number = $to_number;
        $quota_log->save();
    }

/**
 * Check Quota Hourly
 */
function check_quota_hourly($user_id, $provider_id)
{
    if (is_admin($user_id)) {
        return 'not_crossed';
    } else {
        $quota = Provider::where('id', $provider_id)->first();
        $quota_log = QuotaLog::HasAgent($user_id)->where('provider_id', $provider_id)
                                ->where('created_at', '>=', Carbon::now()
                                ->subHour())
                                ->get();
        if ($quota_log->count() >= $quota->hourly_quota) {
            return 'crossed';
        } else {
            return 'not_crossed';
        }
    }
}

/**
 * Count Quota Hourly
 */
function count_quota_hourly($user_id, $provider_id)
{
    $quota_log = QuotaLog::where('user_id', $user_id)->where('provider_id', $provider_id)
                            ->where('created_at', '>=', Carbon::now()
                            ->subHour())
                            ->get();

    return $quota_log->count();
}

/**
 * Hourly Quota left in percentage
 */
function hourly_quota_left_in_percentage($user_id, $provider_id)
{
    $quota = Provider::HasAgent($user_id)->first();
    $quota_log = QuotaLog::HasAgent($user_id)->where('provider_id', $provider_id)
                            ->where('created_at', '>=', Carbon::now()
                            ->subHour())
                            ->get();
    $quota_left = $quota->hourly_quota - $quota_log->count();
    $quota_left_percentage = ($quota_left / $quota->hourly_quota) * 100;

    return $quota_left_percentage;
}

/**
 * Hourly Quota left
 */
function hourly_quota_left($user_id, $account_sid)
{
    $quota = Provider::HasAgent($user_id)->where('account_sid', $account_sid)->first();
    $quota_log = QuotaLog::where('user_id', $user_id)->where('provider_id', $quota->id)
                            ->where('created_at', '>=', Carbon::now()
                            ->subHour())
                            ->get();

    return $quota_left = $quota->hourly_quota - $quota_log->count();
}

/**
 * Fetch Twilio Account
 */
function fetch_twilio_account($user_id, $account_sid)
{
    $provider = Provider::HasAgent($user_id)
                        ->where('account_sid', $account_sid)
                        ->first();

    return twilioSetup($provider->account_sid, $provider->auth_token)
                ->api
                ->v2010
                ->accounts($provider->account_sid)
                ->fetch();
}

/**
 * ACCOUNT DATA
 */
function account_data($user_id, $account_sid)
{
    return [
        'friendly_name' => fetch_twilio_account($user_id, $account_sid)->friendlyName,
        'status' => fetch_twilio_account($user_id, $account_sid)->status,
        'balance' => fetch_twilio_account($user_id, $account_sid)->subresourceUris['balance'],
        'usage' => fetch_twilio_account($user_id, $account_sid)->subresourceUris['usage'],
        'available_phone_numbers' => fetch_twilio_account($user_id, $account_sid)->subresourceUris['available_phone_numbers'],
        'subresource_uris' => fetch_twilio_account($user_id, $account_sid)->subresourceUris,
    ];
}

/**
 * All-Time Usage, All Usage Categories
 */
function all_time_usage($user_id, $account_sid)
{
    $provider = Provider::HasAgent($user_id)->where('account_sid', $account_sid)->first();
    $records = twilioSetup($provider->account_sid, $provider->auth_token)
                    ->usage
                    ->records
                    ->read();

    // Loop over the list of records and echo a property for each one
    foreach ($records as $record) {
        return [
            'total_count' => $record->count,
            'category' => $record->category,
        ];
    }
}

/**
 * Export Calls CSV
 */
function export_calls_csv($user_id, $account_sid)
{
    $provider = Provider::where('user_id', $user_id)->where('account_sid', $account_sid)->first();

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.twilio.com/2010-04-01/Accounts/'.$provider->account_sid.'/Calls.csv?PageSize=1000',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Basic '.base64_encode($provider->account_sid.':'.$provider->auth_token),
        ],
    ]);
    $response = curl_exec($curl);
    curl_close($curl);

    //give your file a name.
    $fileName = $provider->account_sid.'.csv';

    //add the file path where you want to store your csv file
    $filePath = base_path('public/calls_csv/').$fileName;

    $fp = fopen($filePath, 'w+');
    fwrite($fp, print_r($response, true));

    //Once the data is written, it will be saved in the path given.
    fclose($fp);

    //download the csv file
    return response()->download($filePath)->deleteFileAfterSend(true);
}

/**
 * Twilio Balance
 */
function twilio_balance($user_id, $account_sid)
{
    $provider = Provider::HasAgent($user_id)->where('account_sid', $account_sid)->first();

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.twilio.com/2010-04-01/Accounts/'.$provider->account_sid.'/Balance.json',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => [
            'Authorization: Basic '.base64_encode($provider->account_sid.':'.$provider->auth_token),
        ],
    ]);

    $response = curl_exec($curl);

    curl_close($curl);

    return json_decode($response)->balance.' '.json_decode($response)->currency;
}

/**
 * Twilio Account completed calls
 */
function twilio_analytics($account_sid, $from)
{
    $provider = Provider::where('user_id', Auth::id())->where('account_sid', $account_sid)->first();

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.twilio.com/2010-04-01/Accounts/'.$provider->account_sid.'/Calls.json',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => [
            'Authorization: Basic '.base64_encode($provider->account_sid.':'.$provider->auth_token),
        ],
    ]);

    $response = curl_exec($curl);

    curl_close($curl);

    // count completed calls
    $completed_calls = 0;
    foreach (json_decode($response)->calls as $call) {
        if ($call->status == 'completed' && $call->from_formatted == $from) {
            $completed_calls++;
        }
    }

    // queue calls
    $queue_calls = 0;
    foreach (json_decode($response)->calls as $call) {
        if ($call->status == 'queued' && $call->from_formatted == $from) {
            $queue_calls++;
        }
    }

    // no answer calls
    $no_answer_calls = 0;
    foreach (json_decode($response)->calls as $call) {
        if ($call->status == 'no-answer' && $call->from_formatted == $from) {
            $no_answer_calls++;
        }
    }

    // initiated calls
    $initiated_calls = 0;
    foreach (json_decode($response)->calls as $call) {
        if ($call->status == 'in-progress' && $call->from_formatted == $from) {
            $initiated_calls++;
        }
    }

    // ringing calls
    $ringing_calls = 0;
    foreach (json_decode($response)->calls as $call) {
        if ($call->status == 'ringing' && $call->from_formatted == $from) {
            $ringing_calls++;
        }
    }

    // busy calls
    $busy_calls = 0;
    foreach (json_decode($response)->calls as $call) {
        if ($call->status == 'busy' && $call->from_formatted == $from) {
            $busy_calls++;
        }
    }

    // canceled calls
    $canceled_calls = 0;
    foreach (json_decode($response)->calls as $call) {
        if ($call->status == 'canceled' && $call->from_formatted == $from) {
            $canceled_calls++;
        }
    }

    // failed calls
    $failed_calls = 0;
    foreach (json_decode($response)->calls as $call) {
        if ($call->status == 'failed' && $call->from_formatted == $from) {
            $failed_calls++;
        }
    }

    // total calls
    $total_calls = 0;
    foreach (json_decode($response)->calls as $call) {
        if ($call->from_formatted == $from) {
            $total_calls++;
        }
    }

    // total completed calls percentage
    $total_completed_calls_percentage = 0;
    if ($total_calls > 0) {
        $total_completed_calls_percentage = ($completed_calls / $total_calls) * 100;
    }

    return [
        'completed_calls' => $completed_calls,
        'queue_calls' => $queue_calls,
        'no_answer_calls' => $no_answer_calls,
        'initiated_calls' => $initiated_calls,
        'ringing_calls' => $ringing_calls,
        'busy_calls' => $busy_calls,
        'canceled_calls' => $canceled_calls,
        'failed_calls' => $failed_calls,
        'total_calls' => $total_calls,
        'total_completed_calls_percentage' => round($total_completed_calls_percentage, 2).'%',
    ];
}

/**
 * Check Twilio Connection
 */
function check_twilio_connection($account_sid)
{
    $provider = Provider::where('account_sid', $account_sid)
                        ->first();

    try {
        return twilioSetup($provider->account_sid, $provider->auth_token)
                ->api
                ->v2010
                ->accounts($provider->account_sid)
                ->fetch();
    } catch (\Throwable $th) {
        return false;
    }
}

/**
 * VOICE CAMPAIGN
 */
function campaign_emails($campaign_id)
{
    $campaign_group_id = Campaign::HasAgent()->where('id', $campaign_id)->first()->group_id;

    // get group contacts
    return $group_contacts = GroupContact::where('group_id', $campaign_group_id)->get();
}

/**
 * VOICE CAMPAIGN
 */
function voice_campaign($campaign_id, $status)
{
    return $voice_campaign_data = CampaignVoice::HasAgent()
                        ->where('campaign_id', $campaign_id)
                        ->where('status', $status)
                        ->get();
}

/**
 * VOICE CAMPAIGN STATUS
 */
function voice_campaign_data($campaign_id, $contact_id)
{
    return $voice_campaign_data = CampaignVoice::HasAgent()
                        ->where('campaign_id', $campaign_id)
                        ->where('campaign_id', $contact_id)
                        ->get();
}

/**
 * CHECK VOICE CALLED
 */
function check_voice_called($campaign_id, $contact_id, $phone)
{
    $voice_campaign_data = CampaignVoice::HasAgent()
                        ->where('campaign_id', $campaign_id)
                        ->where('contact_id', $contact_id)
                        ->first();

    if ($voice_campaign_data) {
        return true;
    } else {
        return false;
    }
}

/**
 * CHECK VOICE CALLED
 */
function check_sms_sent($campaign_id, $contact_id, $phone)
{
    $sms_campaign_data = CampaignSmsStatusLog::where('campaign_id', $campaign_id)
                                            ->where('contact_id', $contact_id)
                                            ->first();

    if ($sms_campaign_data) {
        return true;
    } else {
        return false;
    }
}

/**
 * VOICE CAMPAIGN STATUS
 */
function voice_campaign_status($campaign_id, $contact_id, $phone)
{
    $voice_campaign_data = CampaignVoice::HasAgent()
                        ->where('campaign_id', $campaign_id)
                        ->where('contact_id', $contact_id)
                        ->first();

    if ($voice_campaign_data) {
        return $voice_campaign_data->status;
    } else {
        return false;
    }
}

/**
 * VOICE CAMPAIGN STATUS LOG
 */
function voice_campaign_status_log($campaign_id, $contact_id, $status)
{
    switch ($status) {
        case 'd':
            $status_message = 'Dialed';
            break;

        case 'p':
            $status_message = 'Picked up';
            break;

        case 'b':
            $status_message = 'Busy';
            break;

        case 's':
            $status_message = 'Switched Off';
            break;

        case 'l':
            $status_message = 'Lead';
            break;

        default:
            $status_message = 'No Log Found';
            break;
    }

    $log = new CampaignVoiceStatusLog;
    $log->campaign_id = $campaign_id;
    $log->contact_id = $contact_id;
    $log->user_id = Auth::id();
    $log->agent_name = Auth::user()->name;
    $log->status = $status_message;
    $log->save();
}

/**
 * VOICE CAMPAIGN STATUS LOG
 */
function voice_campaign_status_log_update($campaign_id, $contact_id)
{
    return CampaignVoiceStatusLog::where('campaign_id', $campaign_id)
                                    ->HasAgent()
                                    ->where('contact_id', $contact_id)
                                    ->get();
}

/**
 * LEADS
 */

 // get campaign expectioned leads
function campaign_expectation_leads($campaign_id)
{
    return Campaign::where('id', $campaign_id)->HasAgent()->first()->expectation;
}

 function leads_data($campaign_id)
 {
     $leads_data = CampaignVoice::HasAgent()
                        ->where('campaign_id', $campaign_id)
                        ->get(); // get all leads for this campaign

     return [
         'campaign_name' => Campaign::where('id', $campaign_id)->HasAgent()->first()->name,
         'total_contacts' => $leads_data->count(), // total contacts
         'picked' => $leads_data->where('status', 'p')->count(), // picked up
         'busy' => $leads_data->where('status', 'b')->count(), // busy
         'swiched_off' => $leads_data->where('status', 's')->count(), // switched off
         'lead' => $leads_data->where('status', 'l')->count(), // lead
         'total' => $leads_data->count(), // total
         'picked_percentage' => round(($leads_data->where('status', 'p')->count() == 0 ? 0 : $leads_data->where('status', 'p')->count() / $leads_data->count()) * 100, 2).'%', // picked up percentage
         'busy_percentage' => round(($leads_data->where('status', 'b')->count() == 0 ? 0 : $leads_data->where('status', 'b')->count() / $leads_data->count()) * 100, 2).'%', // busy percentage
         'swiched_off_percentage' => round(($leads_data->where('status', 's')->count() == 0 ? 0 : $leads_data->where('status', 's')->count() / $leads_data->count()) * 100, 2).'%', // switched off percentage
         'lead_percentage' => round(($leads_data->where('status', 'l')->count() == 0 ? 0 : $leads_data->where('status', 'l')->count() / $leads_data->count()) * 100, 2).'%', // lead percentage
         'lead_percentage_expectation' => round(($leads_data->where('status', 'l')->count() == 0 ? 0 : $leads_data->where('status', 'l')->count() / $leads_data->count()) * 100, 2) >= campaign_expectation_leads($campaign_id) ? true : false, // lead percentage expectation good
     ];
 }

    /**
     * Store data to Leads Export history
     */
    function store_leads_export_history($campaign_id)
    {
        $history = new LeadsExportHistory;
        $history->user_id = Auth::id();
        $history->campaign_id = $campaign_id;
        $history->campaign_name = leads_data($campaign_id)['campaign_name'];
        $history->total_contacts = leads_data($campaign_id)['total_contacts'];
        $history->picked = leads_data($campaign_id)['picked'];
        $history->busy = leads_data($campaign_id)['busy'];
        $history->swiched_off = leads_data($campaign_id)['swiched_off'];
        $history->lead = leads_data($campaign_id)['lead'];
        $history->total = leads_data($campaign_id)['total'];
        $history->picked_percentage = leads_data($campaign_id)['picked_percentage'];
        $history->busy_percentage = leads_data($campaign_id)['busy_percentage'];
        $history->swiched_off_percentage = leads_data($campaign_id)['swiched_off_percentage'];
        $history->lead_percentage = leads_data($campaign_id)['lead_percentage'];
        $history->lead_percentage_expectation = leads_data($campaign_id)['lead_percentage_expectation'];
        $history->export_date = date('Y-m-d H:i:s');
        $history->save();
    }

/**
 * Active IVR
 */
function active_ivr()
{
    return Provider::where('ivr', 1)->first()->capability_token ?? null;
}

/**
 * active provider
 */
function active_provider()
{
    return Provider::where('ivr', 1)->first() ?? null;
}

function teleman_config($key)
{
    return config('teleman.'.$key);
}

/**
 * Check User Credit
 */
function credit($user_id)
{
    $user_credit = Subscription::where('user_id', $user_id)->first()->credit;

    return $user_credit ?? 0;
}

/**
 * Get package Credit
 */
function package_credit($package_id)
{
    $package_credit = Package::where('id', $package_id)->first()->credit;

    return $package_credit ?? 0;
}

/**
 * User current credit
 */
function user_current_credit($user_id)
{
    $user_credit = ItemLimitCount::HasAgent($user_id)->first();

    if ($user_credit) {
        return $user_credit->credit; 
    } else {
        return 0;
    }
}

/**
 * Store Call duration and deduction
 */
function store_call_duration($campaign_name, $phone, $accountSid, $sid, $duration, $deduction, $status, $user_id = null)
{

    $country_code = get_country_code_from_number($phone);

    $call_duration = new CallDuration;
    $call_duration->user_id = $user_id;
    $call_duration->campaign_name = $campaign_name;
    $call_duration->phone = $phone;
    $call_duration->accountSid = $accountSid;
    $call_duration->sid = $sid;
    $call_duration->duration = $duration;
    $call_duration->deduction = $deduction;
    $call_duration->app_deduction = call_cost_per_second($country_code);
    $call_duration->status = $status;
    $call_duration->active = 1;
    $call_duration->save();
}

function fetch_call_duration_deduction()
{
    echo 'fetching call duration & deduction..................'.PHP_EOL;

    $all_calls = CallDuration::where('duration', null)
                                ->get();

    echo 'total calls to fetch: '.$all_calls->count().PHP_EOL;

    foreach ($all_calls as $all_call) {
        $provider = Provider::where('account_sid', $all_call->accountSid)
                                ->first();

        $twilio = new Client($provider->account_sid, $provider->auth_token);
        $call = $twilio->calls($all_call->sid)
                           ->fetch();

        if ($call) {
            if (! is_admin($all_call->user_id)) {
                $call_duration = CallDuration::where('sid', $all_call->sid)->first();
                $call_duration->duration = $call->duration;
                $call_duration->deduction = $call->price;
                $call_duration->status = $call->status;
                $call_duration->app_deduction = call_cost_per_second(get_country_code_from_number($call_duration->phone));
                $call_duration->save();

                echo 'call duration & deduction updated for call: '.$all_call->sid.PHP_EOL;
            }
        }
    }

    echo 'DONE';
}

/**
 * Total Call duration and deduction
 */
function total_call_duration_deduction($user_id)
{
    $total_call_duration = CallDuration::where('user_id', $user_id)
                                        ->where('duration', '!=', null)
                                        ->where('deduction', '!=', null)
                                        ->sum('duration');
    $total_call_deduction = CallDuration::where('user_id', $user_id)
                                        ->where('duration', '!=', null)
                                        ->where('deduction', '!=', null)
                                        ->sum('deduction');

    $total_deduction = CallDuration::where('user_id', $user_id)
                                        ->where('status', 'completed')
                                        ->sum('total_deduction');

    $call_cost_per_second = Subscription::where('user_id', $user_id)
                                ->where('active', 1)
                                ->with('package')
                                ->first()->package->call_cost_per_second;

    return [
        'total_call_duration_seconds' => $total_call_duration,
        'total_call_duration' => gmdate('H:i:s', $total_call_duration),
        'total_call_deduction' => $total_call_deduction,
        'total_deduction' => $total_deduction,
    ];
}

/**
 * Check user_id is not admin
 */
function all_users()
{
    $all_users = User::NotRestricted()
                    ->WhereNot('agent')
                    ->orderBy('name')
                    ->get();

    return $all_users;
}
/**
 * Check user_id is not admin
 */
function is_admin($user_id)
{
    $admin = User::where('id', $user_id)
                ->first();

    if ($admin->role == 'admin') {
        return true;
    } else {
        return false;
    }
}

/**
 * The function checks if a user with a given ID is a customer or not.
 * 
 * @param user_id The user_id parameter is the ID of the user that we want to check if they are a
 * customer or not.
 * 
 * @return a boolean value. It returns true if the user with the given user_id has a role of
 * 'customer', and false otherwise.
 */
function is_customer($user_id)
{
    $customer = User::where('id', $user_id)
                ->first();

    if ($customer->role == 'customer') {
        return true;
    }else {
        return false;
    }
}

/**
 * If the user id is the same as the authenticated user id, return true, else return false
 * 
 * @param user_id The user id of the user you want to check.
 * 
 * @return A boolean value.
 */
function you($user_id)
{
    if ($user_id == auth()->id()) {
        return true;
    }else {
        return false;
    }
}

/**
 * Check user_id is not admin
 */
function is_agent($user_id)
{
    $agent = User::where('id', $user_id)
                ->first();
    if ($agent->role == 'agent') {
        return true;
    } else {
        return false;
    }
}

/**
 * User Role
 */
function userRoleByAuthId()
{
    if (Auth::user()->role != 'agent') {
       $role = User::where('id', Auth::id())
                ->first();
    } else {
        $role = User::where('id', agent_owner_id())
                ->first();
    }

    return $role->role;
}

/**
 * It deducts the total call duration from the user's credit.
 * 
 * @param user_id The user id of the user who's credit is being deducted.
 */
function deduct_credit($user_id)
{
    if (!is_admin($user_id)) {
        $user_credit = ItemLimitCount::where('user_id', $user_id)->first();
        $user_credit->credit = $user_credit->all_time_credit - total_call_duration_deduction($user_id)['total_deduction'];
        $user_credit->save();
    }
    
}

/**
 * Deduce credit from user
 */
function deduct_credit_by_using_sms($sms_charge, $user_id = null)
{
    if (!is_admin($user_id)) {
        $user_credit = ItemLimitCount::HasAgent($user_id)->first();
        $user_credit->credit = $user_credit->credit - $sms_charge;
        $user_credit->save();
    }
}

/**
 * Live call deduct credit
 */
function live_call_deduct_credit($user_id, $dialer_session_uuid)
{
    $live_call_duration_deduction = LiveCallDuration::HasAgent()
                                                    ->where('dialer_session_uuid', $dialer_session_uuid)
                                                    ->first()->total_deduction;
    $user_credit = ItemLimitCount::HasAgent($user_id)->first();
    $user_credit->credit = $user_credit->credit - $live_call_duration_deduction;
    $user_credit->save();
}

/**
 * Call Cost per seconds Package
 */
function call_cost_per_second($country_code)
{
    $call_cost = TwilioCallCost::where('code', $country_code)
                                ->first()
                                ->teleman_cost_per_second;

    // remove $ sign from $call_cost
    $call_cost = str_replace('$', '', $call_cost);

    return $call_cost;
}

/**
 * The function retrieves the cost of sending an SMS for a given country code using the Twilio API.
 * 
 * @param country_code The country_code parameter is a string that represents the country code for a
 * specific country.
 * 
 * @return the cost of sending an SMS for a specific country code.
 */
function sms_cost_for_per_sms($country_code)
{
    $sms_cost = TwilioCallCost::where('code', $country_code)
                                ->with('twilio_sms_cost')
                                ->first()->twilio_sms_cost->teleman_sms_cost ?? 1;

    // remove $ sign from $sms_cost
    $sms_cost = str_replace('$', '', $sms_cost);

    return $sms_cost;
}

/**
 * Check User balance is enough or not to make call
 */
function check_balance($user_id)
{
    if (is_admin($user_id)) {
        return true;
    } else {
        if (user_current_credit($user_id) > 0) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * Create voice response XML file
 */
function createVCPHPfile($cid)
{
    $vc_path = public_path('vc');

    $caller_id = str_replace('+', '', $cid);

    if (! File::isDirectory($vc_path)) {
        File::makeDirectory($vc_path, 0777, true, true);
    }

    $post = '$_POST["To"]';

    $php = "<?php
header('Content-type: text/xml');
?>
<Response>
    <Dial callerId='$cid'>
        <?php echo $post; ?>
    </Dial>
</Response>";

    $path = $vc_path.'/'.'cid'.$caller_id.'.php';

    File::put($path, $php);
}

/**
 * ACTIVE THEME
 */
function active_theme()
{
    return 'titania';
}

/**
 * TELEMAN LOG
 */
function tlog($message)
{
    Log::channel('teleman')->info($message);
}

/**
  * Flutterwave Supported Countries and currencies
  */
function flutterwaveSupportedCountries()
{
    return [
        'Argentine Peso' => 'ARS',
        'Brazilian Real' => 'BRL',
        'British Pound Sterling' => 'GBP',
        'Canadian Dollar' => 'CAD',
        'Cape Verdean Escudo' => 'CVE',
        'Chilean Peso' => 'CLP',
        'Colombian Peso' => 'COP',
        'Congolese France' => 'CDF',
        'Egyptian Pound' => 'EGP',
        'SEPA' => 'EUR',
        'Gambian Dalasi' => 'GMD',
        'Ghanaian Cedi' => 'GHS',
        'Guinean Franc' => 'GNF',
        'Kenyan Shilling' => 'KES',
        'Liberian Dollar' => 'LRD',
        'Malawian Kwacha' => 'MWK',
        'Mexican Peso' => 'MXN',
        'Moroccan Dirham' => 'MAD',
        'Mozambican Metical' => 'MZN',
        'Nigerian Naira' => 'NGN',
        'Peruvian Sol' => 'SOL',
        'Rwandan Franc' => 'RWF',
        'Sierra Leonean Leone' => 'SLL',
        'São Tomé and Príncipe dobra' => 'STD',
        'South African Rand' => 'ZAR',
        'Tanzanian Shilling' => 'TZS',
        'Ugandan Shilling' => 'UGX',
        'United States Dollar' => 'USD',
        'Central African CFA Franc BEAC' => 'XAF',
        'West African CFA Franc BCEAO' => 'XOF',
        'Zambian Kwacha' => 'ZMW'

    ];
}

/**
 * Dashboard UI
 */

 function site_dashboard()
 {
    return Application::where('name', 'site_dashboard')->first()->value ?? 'EXTENDED';
 }

 /**
  * Twilio Call Costs
  */
function all_teleman_call_costs()
{
    //OrderBy alphabate
    return TwilioCallCost::orderBy('country')->get();
}
 /**
  * Twilio Call Costs
  */
function twilio_call_costs()
{
    //OrderBy alphabate
    return TwilioCallCost::with('twilio_sms_cost')
                         ->orderBy('country')
                         ->paginate(20);
}

/**
 * Check all the package has supported countries
 */
function check_all_the_package_has_supported_countries()
{
    $packages = Package::with('supported_countries')->doesntHave('supported_countries')->count();

    if ($packages <= 0) {
        return false; // not all the package has supported countries
    } else {
        return true; // all the package has supported countries
    }
}

/**
 * Get Country code from the number
 */
function get_country_code_from_number($phone)
{
    $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
    $NumberProto = $phoneUtil->parse($phone, null);
    return '+' . $NumberProto->getCountryCode();
}

/**
 * Call Cost minute to secons
 */
function call_cost_minute_to_seconds($call_cost)
{
    // remove $ from teleman_cost
    $teleman_cost_without_dollar_sign = str_replace('$', '', $call_cost);
    $one_min_in_seconds = 60;
    $call_will_be_cost_per_seconds = number_format($teleman_cost_without_dollar_sign/$one_min_in_seconds, 10);
    return $call_will_be_cost_per_seconds;
}

/**
 * Demo audio mp3
 */
function demo_audio_mp3()
{
    return 'https://thecodestudio.xyz/audio.mp3';
}

/**
 * provider audio
 */
function provider_audio($provider_id)
{
    return Provider::where('id', $provider_id)->first()->audio;
}

/**
 * provider audio
 */
function campaign_audio($campaign_id)
{
    return Campaign::where('id', $campaign_id)->first()->audio;
}

/**
 * Provider Info
 */
function provider_info($account_sid)
{
    return Provider::where('account_sid', $account_sid)->first();
}

/**
 * Check Paystack keys
 */
function check_paystack_keys()
{
    $publicKey = config('paystack.publicKey');
    $secretKey = config('paystack.secretKey');
    $paymentUrl = config('paystack.paymentUrl');
    $merchantEmail = config('paystack.merchantEmail');

    if ($publicKey == '' || $secretKey == '' || $paymentUrl == '' || $merchantEmail == '') {
        return false;
    } else {
        return true;
    }
}

/**
 * IVR data
 */
function ivr_data()
{
    return Ivr::get();
}

/**
 * Get campaign provider info
 */
function campaign_provider_info($campaign_id)
{
    $provider_id =  Campaign::where('id', $campaign_id)->first()->provider;

    return Provider::where('id', $provider_id)->first();
}

/**
 * hostwithHttp 
 */
function hostwithHttp()
{
    $hostwithHttp = request()->getSchemeAndHttpHost();
    return $hostwithHttp; // https://teleman.com 
}

/**
* Agent owner ID
*/
function agent_owner_id($auth_id = null)
{

    if ($auth_id == null) {
        $auth_id = Auth::id();
    }

    return Agent::where('user_id', $auth_id)->first()->assined_for_customer_id;
}

/**
 * The function returns the owner ID of a user based on their role.
 * 
 * @return The function `owner_id()` returns the `id` of the user who is the owner of the current
 * session. The owner can be either the authenticated user or the owner of the agent account if the
 * authenticated user is an agent.
 */
function owner_id()
{
    if (Auth::user()->role != 'agent') {
       $owner_id = User::where('id', Auth::id())
                ->first();
    } else {
        $owner_id = User::where('id', agent_owner_id())
                ->first();
    }

    return $owner_id->id;
}

/**
 * All Agents
 */
function all_agents()
{
    return Agent::where('assined_for_customer_id', auth()->id())
                ->with('user')
                ->get();
}

/**
 * CronJob
 */
function CronJob($name, $status, $issue)
{
    $delete_last_record = CronJob::where('cron_name', $name)
                                 ->delete();

    $cron = new CronJob;
    $cron->cron_name = $name;
    $cron->status = $status;
    $cron->issue = $issue;
    $cron->save();
}

/**
 * CronJob Last Served
 */
function CronJob_Last_Served()
{
    return CronJob::latest()->get();
}

/**
 * CustomCssScript
 */
function custom_css_script($type)
{
    return CustomCssScript::where('type', $type)->first()->code ?? null;
}

/**
 * Verify Documents
 */
function kyc_documents()
{
    return DocumentKyc::get();
}

/**
 * Unseen documents count
 */
function unseen_kyc_documents()
{
    return DocumentKyc::where('seen', 0)->count() ?? 0;
}

/**
 * User KYC document
 */
function user_kyc_document($user_id)
{
    $kyc = DocumentKyc::where('user_id', $user_id)->first();

    return $kyc;
}

/**
 * Check Verify or not
 */
function kyc_verified($user_id)
{
    $approval = user_kyc_document($user_id);

    if (isset($approval->approval)) {
       if ($approval->approval == 1) {
            return true;
        }else {
            return false;
        }
    }else {
        return false;
    }

}

/**
 * Check KYC Approved or Pending
 */
function kyc_document_approval($user_id)
{
    $kyc = DocumentKyc::where('user_id', $user_id)->first();

    if ($kyc) {
        switch ($kyc->approval) {
            case '1':
                return 'approved';
                break;
            case '2':
                return 'rejected';
                break;
            
            default:
                return 'pending';
                break;
        }
    }else {
        return 'rejected';
    }
}

/**
 * Twilio Send SMS
 */
function twilioSendSMS($campaign_id, $to_number, $message)
{

    $campaign = Campaign::where('id', $campaign_id)->first();

    $provider = Provider::find($campaign->provider);
    // Initialize the Programmable Voice API
    $client = new Client($provider->account_sid, $provider->auth_token);
    $client->messages->create($to_number, ['from' => $provider->phone, 'body' => $message]);
}

/**
 * CampaignSmsStatusLog
 */
function CampaignSmsStatusLog($campaign_id, $contact_id, $user_id, $agent_name, $message)
{
    $sms_log = new CampaignSmsStatusLog;
    $sms_log->campaign_id = $campaign_id;
    $sms_log->contact_id = $contact_id;
    $sms_log->user_id = $user_id;
    $sms_log->agent_name = $agent_name;
    $sms_log->message = $message;
    $sms_log->save();
}

/**
 * It generates a random token.
 * 
 * @param lenght The lenght of the token. Default is 25.
 * 
 * @return A string of hexadecimal characters.
 */
function generate_token($lenght = 25)
{
    $random = random_bytes($lenght);
    return bin2hex($random);
}

/**
 * Get User Info By User Token
 */

 function token_user($token)
 {
    return ThirdParty::where('user_token', $token)->first();
 }

 /**
  * Return the number of shops that are available for purchase.
  * 
  * @return A collection of shops that have been released.
  */
 function shops_available_number()
 {
    return Shop::Released()
                ->whereNull('purchased_user_id')
                ->get();
 }

 /**
  * "If the user is an admin, return all released numbers that have been purchased. If the user is not
  * an admin, return all released numbers that have been purchased by the user."
  * </code>
  * 
  * @return A collection of Shop models.
  */
 function get_purchased_numbers()
 {
    /* Checking if the user is an admin, if so, it will return all the released items. If not, it will
    return all the released items that are confirmed and purchased by the user. */
    if (is_admin(auth()->id())) {
        return Shop::Released()
                    ->Confirmed()
                    ->whereNotNull('purchased_user_id')
                    ->get();
    }else {
        return Shop::where('purchased_user_id', auth()->id())
                    ->Released()
                    ->whereNotNull('purchased_user_id')
                    ->get();
    }
 }

 /**
  * It returns all the rows from the Shop table where the purchased_user_id column is not null.
  * 
  * @return A collection of Shop objects.
  */
 function get_new_ordered_numbers()
 {
    /* Returning all the shops that are not confirmed and have a purchased user id. */
    return Shop::whereNotNull('purchased_user_id')
                ->NotConfirmed()
                ->get();
 }

 /**
  * It returns all the orders that are renewed and are not null.
  * 
  * @return A collection of Shop models.
  */
 function get_renew_ordered_numbers()
 {
    /* Checking if the user is an admin or not. If the user is an admin, it will return all the shops
    that have been purchased. If the user is not an admin, it will return all the shops that have
    been purchased by the user. */
    if (is_admin(auth()->id())) {
        /* Returning all the shops that have been purchased and renewed. */
        return Shop::whereNotNull('purchased_user_id')
                ->Renew()
                ->get();
    }else {
        /* Returning all the shops that are renewed and purchased by the authenticated user. */
        return Shop::where('purchased_user_id', auth()->id())
                    ->Renew()
                    ->get();
    }
 }
 
 /**
  * Get all the confirmed and released numbers that have been purchased but don't have a provider.
  * 
  * @return A collection of Shop objects.
  */
 function get_configurable_numbers()
 {
    return Shop::Released()
               ->Confirmed()
               ->with('provider')
               ->doesntHave('provider')
               ->whereNotNull('purchased_user_id')
               ->get();
 }

 /**
  * If the user has a purchased_user_id, return false, otherwise return true
  * 
  * @param number The phone number of the user
  * 
  * @return A boolean value.
  */
 function check_purchased_user($number)
 {
    /* Checking if the phone number exists in the database. */
    $check = Shop::where('phone', $number)->first();

    /* Checking if the user has purchased the item. */
    if ($check->purchased_user_id) {
        return true; // purchased
    }else {
        return false; // not purchased
    }
 }

 /**
  * It takes two dates, and returns the difference between them in days, and a message that says how
  * many days it is.
  * 
  * @param start_at 2019-12-01
  * @param end_at 2019-12-31
  * 
  * @return array:2 [▼
  *   "days" => 0
  *   "message" => "0 days"
  * ]
  */
 function diffDates($end_at)
 {
    $from = Carbon::parse(Carbon::now());
    $to = Carbon::parse($end_at);

    /* Calculating the difference between the two dates in days. */
    $days = $to->diffInDays($from);

    return [
        'days' => $days,
        'message' => $days . ' ' . Str::plural('day', $days)
    ];
 }

 /**
  * If the end date is less than the current date, return the number of days between the two dates.
  * 
  * @param end_at The date the subscription ends
  * 
  * @return The number of days between the current date and the date passed in.
  */
 function check_phone_number_subscription_end_days($id) // shop id
 {
    $shop = Shop::where('id', $id)->first();

    $from = Carbon::parse(Carbon::now());
    $to = Carbon::parse($shop->end_at);

    /* Calculating the difference between the two dates in days. */
    $days = $to->diffInDays($from);

    if ($days == 0) {
        /* Checking if the user has already purchased the number. If yes, it will revoke the number. */
        if (check_purchased_user($shop->phone) == true) {
            $shop->purchased_user_id = null;
            $shop->start_at = null;
            $shop->end_at = null;
            $shop->confirmed = 0; // false
            if ($shop->save()) {
                $provider = Provider::where('phone', $shop->phone)->delete();
            }
        }
        return true;
    }else {
        return false;
    }

 }

/**
 * If the user's rest_name is null, replace the spaces in the user's name with nothing and save it to
 * the user's rest_name.
 */
function generate_user_slug($user_id)
{
    $user = Identity::updateOrCreate(
        ['user_id' =>  $user_id],
        ['identity' => str_replace(" ", "", getUserInfo($user_id)->name)]
    );
}

/**
 * Get the identity of a user, if it exists.
 * 
 * @param user_id The user's ID
 * 
 * @return The identity of the user with the given user_id.
 */
function get_user_identity($user_id)
{
    return Identity::where('user_id', $user_id)->first()->identity ?? null;
}

/**
 * This PHP function retrieves the identity ID of a user based on their user ID.
 * 
 * @param user_id The user ID is a unique identifier assigned to each user in a system or application.
 * It is used to distinguish one user from another and is often used as a reference when retrieving or
 * updating user information.
 * 
 * @return the ID of the first identity record in the database that belongs to the user with the given
 * user ID. If no identity record is found, it returns null.
 */
function get_user_identity_id($user_id)
{
    return Identity::where('user_id', $user_id)->first()->id ?? null;
}

/**
 * It returns the first provider record where the user_id matches the user_id passed in
 * 
 * @param user_id The user ID of the user you want to get the provider info for.
 * 
 * @return The first provider with the user_id of 
 */
function user_provider_info($user_id)
{
    return Provider::HasAgent($user_id)
                    ->first();
}

/**
 * The function returns information about an active provider with a specific user ID and IVR status.
 * 
 * @param user_id The user ID is a unique identifier assigned to a user in a system or application. In
 * this case, it is used as a parameter to retrieve information about a provider who is associated with
 * the user ID and has an IVR (Interactive Voice Response) enabled.
 * 
 * @return the first provider with the given user ID and an IVR value of 1.
 */
function user_active_provider_info()
{
    return Provider::HasAgent(auth()->id())->where('ivr', 1)->first();
}

/**
 * > Group all messages by user number, sort each group by sent_at, and return the result as an array
 * 
 * @return An array of arrays of messages grouped by user number.
 */
function messages($my_number)
{
    $userId = auth()->id();
    $cacheKey = 'messages_for_user_' . $userId . '_number_' . $my_number;
    $cacheDuration = now()->addMinutes(30);
    $messages = Cache::remember($cacheKey, $cacheDuration, function () use ($userId, $my_number) {
        return Message::where('user_id', $userId)
                      ->where('my_number', $my_number)
                      ->orderBy('seen', 'asc')
                      ->orderBy('sent_at', 'desc')
                      ->get()
                      ->groupBy('user_number')
                      ->map(function ($grouped) {
                          return $grouped->sortByDesc('sent_at')->toArray();
                      })
                      ->toArray();
    });
    return $messages;
}

/**
 * It returns the time, content and seen status of the last message sent by a user
 * 
 * @param user_number The phone number of the user you want to get the last message from.
 * 
 * @return An array of the time, message, and seen status of the last message sent to the user.
 */
function conversationOfLastMessage($user_number, $my_number)
{
    /* Getting the latest message sent to the user and returning the time, message, and if it has been
    seen. */
    $message = Message::where('user_id', auth()->id())
                      ->where('user_number', $user_number)
                      ->where('my_number', $my_number)
                      ->latest('sent_at')
                      ->first();
    return [
        'time' => Carbon::parse($message->sent_at)->format('h:i A'),
        'activeSince' => Carbon::parse($message->sent_at)->diffForhumans(),
        'message' => $message->content,
        'seen' => $message->seen,
        'user_number' => $user_number,
    ];
}

/**
 * It returns all the messages that belong to the authenticated user and have the same user_number as
 * the one passed to the function
 * 
 * @param user_number The number of the user you want to get the conversation with.
 * 
 * @return A collection of messages
 */
function conversations($user_number, $my_number)
{
    return Message::where('user_id', auth()->id())
                    ->where('user_number', $user_number)
                    ->where('my_number', $my_number)
                    ->oldest()
                    ->get();
}

/**
 * It takes a date and time string and returns a Carbon object
 * 
 * @param date_time The date and time you want to format.
 * 
 * @return The date and time in the format of the Carbon class.
 */
function time_formatter($date_time)
{
    return Carbon::parse($date_time);
}

/**
 * It counts the total number of messages in the database where the user_id is the current user's id
 * and the sender is the current user's number or the recipient is the current user's number
 * 
 * @param my_number The number of the user who is logged in.
 * 
 * @return The number of messages that have been sent or received by the user.
 */
function count_total_messages($my_number)
{
    return Message::where('user_id', auth()->id())
                    ->where('my_number', $my_number)
                    ->count();
}

/**
 * The function counts the total number of unseen messages for a specific user and phone number.
 * 
 * @param my_number The parameter `` is likely a phone number or some other identifier that
 * is associated with the messages being counted. It is used in the `where` clause to filter the
 * messages by a specific number.
 * 
 * @return the count of unseen messages for a specific user and phone number.
 */
function count_total_unseen_messages($my_number)
{
    return Message::where('user_id', auth()->id())
                    ->where('seen', 0)
                    ->where('my_number', $my_number)
                    ->count();
}

/**
 * It updates the `seen` column of the latest message sent by the user to 1
 * 
 * @param user_number The number of the user you're messaging
 * @param my_number The number of the user who is logged in.
 */
function message_as_seen($user_number, $my_number)
{
    /* Getting the latest message sent by the user. */
    $message = Message::where('user_id', auth()->id())
                      ->where('user_number', $user_number)
                      ->where('my_number', $my_number)
                      ->where('seen', 0)
                      ->first();

    /* Updating the message to seen. */
    if ($message) {
        $message->seen = 1;
        $message->save();
    }

}

/**
 * `lordicon(, , ,  = null,  = null,  = null)`
 * 
 * @param cdn The name of the CDN you want to use.
 * @param icon The name of the icon you want to use.
 * @param trigger The class or id of the element you want to trigger the icon.
 * @param primary The primary color of the icon.
 * @param secondary The secondary color of the icon.
 * @param size The size of the icon in pixels.
 */
function lordicon($cdn, $icon, $trigger, $primary = null, $secondary = null, $size = null)
{
    echo '<script src="https://cdn.lordicon.com/' . $cdn . '.js"></script>
            <lord-icon
                src="https://cdn.lordicon.com/' . $icon . '.json"
                trigger="'. $trigger .'"
                colors="primary:#'. $primary .',secondary:#'. $secondary .'"
                style="width:'. $size .'px;height:'. $size .'px">
            </lord-icon>';
}

/* The above code is searching for a contact with a phone number that matches the phone number passed
in. */
function find_contact($phoneNumber)
{
    // Remove all non-digit characters from the phone number
    $phoneNumberDigitsOnly = preg_replace('/\D/', '', $phoneNumber);
    // Search for the contact with the matching phone number
    return Contact::where('user_id', auth()->id())
                    ->where('phone', 'LIKE', '%' . $phoneNumberDigitsOnly . '%')
                    ->first();
}

function find_contact_by_phone($phoneNumber)
{
    // Search for the contact with the matching phone number
    return Contact::where('phone', $phoneNumber)
                    ->first();
}

/**
 * It takes a name, splits it into parts, and then takes the first letter of each part and concatenates
 * them together
 * 
 * @param name The name to be shortened.
 */
function shortname($name)
{
    $parts = strtok($name, ' ');
    $shortname = '';

    /* Taking the first two letters of the first and last name and making them uppercase. */
    while ($parts !== false) {
    $shortname .= strtoupper(substr($parts, 0, 1));
    $parts = strtok(' ');
    
        /* Checking if the shortname is greater than 2 characters and if it is, it is shortening it to
        2 characters. */
        if ($parts === false && strlen($shortname) > 2) {
            $shortname = substr($shortname, 0, 2);
        }
    }

    echo $shortname;
}

/**
 * The function returns the count of unseen messages for a specific user and phone number.
 * 
 * @param my_number The phone number associated with the messages being searched for.
 * 
 * @return the count of new messages that belong to the authenticated user and have not been seen yet,
 * and match the given `my_number`.
 */
function new_message_found($my_number)
{
    return Message::where('my_number', $my_number)
                    ->where('user_id', auth()->id())
                    ->where('seen', 0)
                    ->count();
}

/**
 * The function finds the user ID associated with a given phone number in a Provider database.
 * 
 * @param phone The phone parameter is a string representing the phone number of a user.
 * 
 * @return the `user_id` of the `Provider` whose `phone` number matches the input ``.
 */
function find_user_id_by_number($phone)
{
    /* The above code is returning the user_id of a Provider model where the phone number matches the
    given  parameter. */
    return Provider::where('phone', $phone)->first()->user_id;
}

/**
 * The function stores a message with various details into a database table called "messages".
 * 
 * @param sender The sender of the message.
 * @param recipient The recipient parameter refers to the person or entity who will receive the
 * message. It could be a phone number, email address, username, or any other identifier that can be
 * used to direct the message to the intended recipient.
 * @param content The content of the message that is being stored in the database.
 * @param my_number The parameter "my_number" is likely referring to the phone number or identifier of
 * the user who is sending the message.
 * @param user_id The user_id parameter is likely an identifier for the user who is sending the
 * message. It could be a unique identifier such as a user's ID in a database or a session ID.
 */
function store_to_messages($recipient, $content, $user_id, $campaign_id = null)
{

    if ($campaign_id) {
        $campaign = Campaign::where('id', $campaign_id)->first();
        $provider = Provider::find($campaign->provider);
    }

    DB::table('messages')->insert([
        'sender' => $provider->phone,
        'recipient' => $recipient,
        'content' => $content,
        'user_number' => $recipient,
        'my_number' => $provider->phone,
        'sent_at' => now(),
        'seen' => 1,
        'user_id' => $user_id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

/**
 * This function retrieves call histories for a given identity ID that were created today.
 * 
 * @param identity_id The identity_id parameter is used to filter the call histories based on the
 * identity of the user who made the call. It is likely a unique identifier assigned to each user in
 * the system.
 * 
 * @return This function returns a collection of call histories for a specific identity ID that were
 * created today.
 */
function getCallHistories ($identity_id)
{
    return CallHistory::where('identity_id', $identity_id)
        ->whereDate('created_at', today())
        ->get();
}

function getAllCallHistories ()
{
    if (is_admin(auth()->id())) {
        return CallHistory::get();
    }else{
        return CallHistory::where('user_id', auth()->id())
                        ->get();
    }
}

function createCallHistory($my_number, $caller_number, $get_caller_uuid_session, $hang_up_time, $status)
{

    /* The above code is querying the database for a CallHistory record that matches the specified
    conditions. The conditions include the authenticated user's ID, the user's identity ID, the
    user's phone number, the caller's phone number, and the caller's UUID session. If a matching
    record is found, it is returned as a CallHistory object. */
    $history = CallHistory::where([
        'user_id' => auth()->id(),
        'identity_id' => get_user_identity_id(auth()->id()),
        'my_number' => $my_number,
        'caller_uuid' => $get_caller_uuid_session,
    ])->first();

    /* The above code is a conditional statement in PHP. It checks if the variable `` is
    truthy (i.e. not null, 0, false, or an empty string). If `` is truthy, then the code
    inside the curly braces (`{}`) will be executed. However, since the code inside the curly
    braces is commented out with ` */
    if ($history) {

        /* The above code is checking if the `pick_up_time` property of the `` object is
        null. If it is null, it sets the `pick_up_time` property to the current time using the
        `now()` function. */
        if ($history->pick_up_time == null) {
            $history->pick_up_time = now();
        }

        /* The above code is checking if the `hang_up_time` property of the `` object is
        null. If it is null, it sets the `hang_up_time` property to the current time using the
        `now()` function. */
        if ($history->hang_up_time == null) {
            $history->hang_up_time = now();
        }

        $history->status = $status;
        $history->save();
    } else {
        $history = CallHistory::create([
            'user_id' => auth()->id(),
            'identity_id' => get_user_identity_id(auth()->id()),
            'my_number' => $my_number,
            'caller_number' => $caller_number,
            'caller_uuid' => $get_caller_uuid_session,
            'pick_up_time' => now(),
            'hang_up_time' => $hang_up_time ?? null,
            'status' => $status,
        ]);
    }

}

/**
 * It generates a UUID.
 * 
 * @return A UUID string.
 * The uuid() method generates a version 4 UUID, which is a random UUID
 */
function generate_uuid()
{
    return Str::uuid();
}

/**
 * The function calculates the duration of a call in hours, minutes, and seconds given the incoming and
 * hangup times.
 * 
 * @param incomingTime The time when the call was received or started.
 * @param hangupTime The time when the call was ended or hung up. It should be in a format that can be
 * parsed by the Carbon library, such as "Y-m-d H:i:s".
 * 
 * @return a formatted string representing the duration of a call in hours, minutes, and seconds.
 */
function calculateCallDuration($incomingTime, $hangupTime)
{
    $incoming = Carbon::parse($incomingTime);
    $hangup = Carbon::parse($hangupTime);
    $durationInSeconds = $hangup->diffInSeconds($incoming);
    
    $hours = floor($durationInSeconds / 3600);
    $minutes = floor(($durationInSeconds - ($hours * 3600)) / 60);
    $seconds = $durationInSeconds - ($hours * 3600) - ($minutes * 60);
    
    return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
}

/**
 * The function `storeFileFromURL` downloads a file from a given URL and saves it to a specified
 * folder.
 * 
 * @param file_name The file name you want to give to the saved file. This can be any string value.
 * @param url The URL of the file you want to download and store.
 * @param folder The "folder" parameter is the name of the folder where you want to store the file. You
 * need to provide the name of the folder as a string.
 * 
 * @return a JSON response. If the file is saved successfully, it will return a JSON response with a
 * message indicating that the file was saved successfully. If there is an error while saving the file,
 * it will return a JSON response with an error message indicating that the file failed to save.
 */
function storeFileFromURL($file_name, $url, $folder)
{
    $destinationFolder = public_path($folder); // Change 'your_folder_name' to the folder name where you want to store the file.

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Fetch the audio content
    $audioContent = curl_exec($ch);

    // Check if the fetch was successful
    if ($audioContent === false) {
        // Handle error here
        $error = curl_error($ch);
        curl_close($ch);
        Log::info('Failed to fetch the audio: ' . $error);
    }

    // Check the HTTP status code
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Check for 404 Not Found error
    if ($httpCode === 404) {
        Log::info('File not found.');
    }

    // Generate a unique file name or use the original file name if available.
    $fileName = 'recording_' . $file_name . '.mp3'; // Change '.mp3' to the appropriate file extension if required.

    // Save the file to the destination folder.
    $file_path = $destinationFolder . DIRECTORY_SEPARATOR . $fileName;
    if (file_put_contents($file_path, $audioContent)) {
        Log::info('File saved successfully.');
    } else {
        Log::info('Failed to save the file.');
    }
}

/**
 * The function returns the file path for a recording file with the given name.
 * 
 * @param file_name The file_name parameter is a string that represents the name of the recording file.
 * 
 * @return the file path of a recording file with the given file name. The file path is constructed by
 * concatenating the string "recording_" with the file name and the file extension ".mp3".
 */
function getTheRecordingFile($file_name)
{
    return public_path('vc/recording_' . $file_name . '.mp3');
}

/**
 * The `analyze_call_record` function uses the OpenAI API to transcribe and analyze a call recording
 * file.
 * 
 * @return the response from the OpenAI API after analyzing the call recording.
 */
function analyze_call_record($file_name)
{
    // Your OpenAI API key
    $openAiApiKey = env('OPENAI_API_KEY');

    // Local path to the Twilio recording file
    $twilioRecordingPath = base_path('public/vc/recording_' . $file_name . '.mp3');

    // Modify the prompt to be more specific and informative
    $prompt = "Based on the provided call texts, please perform a brief analysis of the conversation:";

    // Check if the file exists
    if (!file_exists($twilioRecordingPath)) {
        smilify('error', 'Recording file not found.');
        return back();
    }

    // Read the file content as binary
    $fileHandle = fopen($twilioRecordingPath, 'rb');
    if ($fileHandle === false) {
        smilify('error', 'Error opening the recording file.');
        return back();
    }
    /* The above code is reading the content of a file using the `fread` function in PHP. It is reading
    the file specified by the `` variable, and the size of the file is determined by the
    `filesize` function using the `` variable. The content of the file is then
    stored in the `` variable. */
    $fileContent = fread($fileHandle, filesize($twilioRecordingPath));
    fclose($fileHandle);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.openai.com/v1/audio/transcriptions',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'file' => new CURLFILE($twilioRecordingPath), // Use the file path directly
            'model' => 'whisper-1',
            'prompt' => $prompt,
            'response_format' => 'text',
            'temperature' => '0',
            'language' => 'en',
        ),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: multipart/form-data',
            'Accept: application/json',
            'Authorization: Bearer ' . $openAiApiKey,
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    /* The above code is a PHP function that is returning the result of analyzing a call record
    response. */
    return analyze_result_of_the_call_record($response);
}

/**
 * The function `analyze_result_of_the_call_record` takes a call recording text as input, sends a
 * request to the OpenAI API to analyze the text, and returns the analysis result as a string.
 * 
 * @param text The `text` parameter is the call recording text that you want to analyze. It should be
 * provided as a string.
 * 
 * @return The function `analyze_result_of_the_call_record` returns the analysis result of the call
 * recording text as an ordered list.
 */
function analyze_result_of_the_call_record($text)
{
    // Your OpenAI API key
    $openAiApiKey = env('OPENAI_API_KEY');

    /* The above code is creating an array called `` with two key-value pairs. The first key is
    `'Content-Type'` and its value is `'application/json'`. The second key is `'Authorization'` and
    its value is `'Bearer ' . `. This code is commonly used to set headers for an HTTP
    request, such as when making an API call. */
    $headers = [
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $openAiApiKey,
    ];

    /* The above code is defining a variable called `` and assigning it a string value. The
    string is a prompt asking to analyze a call recording text and provide the information as an
    ordered list. The variable `` is concatenated to the end of the prompt. */
    $prompt = 'Analyze this call recording texts and provider all the information as a order list. Just write the analyze texts only.' . $text;

    /* The above code is creating an associative array called `` in PHP. It has three key-value
    pairs: 
    1. `'prompt' => `: This assigns the value of the variable `` to the key
    `'prompt'`.
    2. `'temperature' => 0`: This assigns the value `0` to the key `'temperature'`.
    3. `'max_tokens' => 500`: This assigns the value `500` to the key `'max_tokens'`. */
    $data = [
        'prompt' => $prompt,
        'temperature' => 0,
        'max_tokens' => 500
    ];

    /* The above code is creating a new instance of the GuzzleClient class and setting the base URI to
    'https://api.openai.com'. */
    $client = new GuzzleClient([
        'base_uri' => 'https://api.openai.com',
    ]);

    /* The above code is making a POST request to the OpenAI API. It is sending a request to the
    'v1/engines/text-davinci-002/completions' endpoint with the specified headers and JSON data. The
    purpose of this code is to interact with the OpenAI GPT-3 model and generate completions for the
    given input data. */
    $response = $client->post('v1/engines/text-davinci-002/completions', [ // Use 'gpt-3.5-turbo' instead of 'davinci-codex'
        'headers' => $headers,
        'json' => $data,
    ]);

    /* The above code is decoding the JSON response body and storing the result in the variable
    ``. The `json_decode()` function is used to convert a JSON string into a PHP array or
    object. In this case, the `getBody()->getContents()` method is used to retrieve the contents of
    the response body, and the `true` parameter is passed to `json_decode()` to specify that the
    result should be returned as an associative array. */
    $result = json_decode($response->getBody()->getContents(), true);

    // Extract the choices[0]['text'] from the response
    $analysisResult = $result['choices'][0]['text'];

    return [
        'transcribed_text' => $text,
        'analysis_result' => $analysisResult
    ];
}

/**
 * The function "checkAgentAvailability" checks if an agent is available or busy based on their status.
 * 
 * @param user_id The user_id parameter is the identifier of the agent whose availability status needs
 * to be checked.
 * 
 * @return a boolean value. If the status of the agent is 0, it will return true indicating that the
 * agent is available. Otherwise, it will return false indicating that the agent is busy.
 */
function checkAgentAvailability($user_id)
{
    $available = AgentIsAvailable::where('user_id', $user_id)->first();

    if ($available) {
        if ($available->status == 1) {
            return true;
        }else {
            return false;
        }
    }

    return true;
}

/**
 * The function updates the availability status of an agent identified by their user ID.
 * 
 * @param user_id The user_id parameter is the unique identifier for the agent. It is used to identify
 * which agent's availability status needs to be updated.
 * @param status The "status" parameter is used to specify the availability status of the agent. It can
 * be a boolean value (true or false) or any other value that represents the availability status of the
 * agent.
 */
function updateAgentAvailability($user_id, $status)
{
    AgentIsAvailable::updateOrCreate(
        ['user_id' => $user_id], // Use the "user_id" field as the unique identifier
        ['status' => $status] // The status you want to set
    );
}

/**
 * The function countQueueWaiting returns the number of items in the QueueList.
 * 
 * @return the count of the waiting queue.
 */
function countQueueWaiting()
{
    return QueueList::count();
}

/**
 * The function `moveToQueue` adds a new entry to the queue list with the provided caller number, my
 * number, serial, and optional user ID.
 * 
 * @param caller_number The phone number of the caller who wants to be added to the queue.
 * @param my_number The "my_number" parameter represents the phone number of the person or entity that
 * is being added to the queue.
 * @param serial The "serial" parameter is used to identify the position of the caller in the queue. It
 * could be a unique identifier or a sequential number assigned to each caller as they join the queue.
 * @param user_id The user_id parameter represents the agent_id, which is the identifier of the agent
 * who is handling the call.
 */
function moveToQueue($caller_number, $my_number, $serial, $user_id = null)
{
    // Search for a record based on caller_number and my_number
    $queue = QueueList::firstOrCreate([
        'caller_number' => $caller_number,
        'my_number' => $my_number,
    ], [
        'serial' => $serial,
        'user_id' => $user_id,
    ]);
    
    // If the record was newly created (i.e., it didn't exist before), save it.
    if ($queue->wasRecentlyCreated) {
        $queue->save();
    }
}

/**
 * The function removeFromQueue searches for a record in the database based on caller_number and
 * my_number, and deletes it if it exists.
 * 
 * @param caller_number The caller's phone number.
 * @param my_number The parameter "my_number" is a value that represents your number or the number
 * associated with the queue.
 */
function removeFromQueue($caller_number, $my_number)
{
    // Search for a record based on caller_number and my_number
    $queue = QueueList::where('caller_number', $caller_number)
                      ->where('my_number', $my_number)
                      ->first();
    
    // If the record exists, delete it from the database.
    if ($queue) {
        $queue->delete();
    }
}

/**
 * The function "getQueue" retrieves a list of queue items based on a given number.
 * 
 * @param my_number The parameter "my_number" is a value that is used to filter the queue list. The
 * function retrieves all the items in the queue list where the "my_number" column matches the provided
 * value.
 * 
 * @return a collection of QueueList objects where the 'my_number' attribute matches the provided
 *  parameter.
 */
function getQueue($my_number)
{
    return QueueList::where('my_number', $my_number)->get();
}

/**
 * The function "getCallerQueueSerialNumber" returns the position of a caller number in a queue.
 * 
 * @param my_number The parameter `` represents the phone number associated with the queue
 * you want to search in.
 * @param caller_number The caller_number parameter is the phone number of the caller that you want to
 * find in the queues array.
 * 
 * @return the serial number of the caller in the queue.
 */
function getCallerQueueSerialNumber($my_number, $caller_number)
{
    $queues = getQueue($my_number);

    $searchNumber = $caller_number;

    // Loop through the array to find the index of the object with the desired caller_number value
    $index = -1; // Initialize index as -1, indicating the number was not found
    foreach ($queues as $key => $item) {
        if ($item['caller_number'] === $searchNumber) {
            $index = $key;
            break; // Found the number, no need to continue searching
        }
    }

    // Output the index
    return $index + 1;
}

/**
 * The function checks if a recording file with a specific caller UUID exists in a specific directory.
 * 
 * @param caller_uuid The caller_uuid parameter is a unique identifier for the caller. It is used to
 * generate the file name for the recording file.
 * 
 * @return a boolean value indicating whether the file with the given file name exists in the specified
 * directory.
 */
function checkRecordFileExists($caller_uuid)
{
    $file_name = 'recording_' . $caller_uuid . '.mp3';
    return file_exists(public_path('vc/' . $file_name));
}

/**
 * The function retrieves the first Mojsms record where the user_id matches the authenticated user's
 * id.
 * 
 * @return the first record from the Mojsms table where the user_id column matches the authenticated
 * user's id.
 */
function get_mojsms($user_id)
{
    return Mojsms::where('user_id', $user_id)->first();
}

/**
 * The mojSMSender function sends a plain text message to a recipient using the MojSMS API.
 */
function mojSMSender($recipient, $message, $user_id)
{
    $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://mojsms.io/api/v3/sms/send',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
"recipient":"'.$recipient.'",
"sender_id":"'.get_mojsms($user_id)->sender_id.'",
"type":"plain",
"message":"'.$message.'"
}',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer ' . get_mojsms($user_id)->bearer_token,
    'Content-Type: application/json',
    'Accept: application/json',
    'User-Agent: mojsms_api'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;
}

/**
 * The function `splitCsv` takes an uploaded CSV file, splits it into multiple parts, and saves each
 * part as a separate CSV file with a unique filename.
 * 
 * @param uploadedFile The parameter "uploadedFile" is the path to the CSV file that you want to split
 * into multiple parts.
 */
function splitCsv($uploadedFile)
{
    // Determine the number of parts (you can adjust this)
    $numParts = 10;  // Change this to your desired number of parts

    // Calculate the approximate number of lines per part
    $totalLines = count(file($uploadedFile));
    $linesPerPart = ceil($totalLines / $numParts);

    // Open the input file for reading
    $file = fopen($uploadedFile, 'r');

    if ($file) {
        // Loop through the number of parts
        for ($part = 1; $part <= $numParts; $part++) {
            // Generate a unique filename for the part
            $filename = 'part' . $part . '.csv';

            // Open the output file for writing
            $outputFile = base_path('public/uploads/csv/' . auth()->id() . '/' . $filename);
            $outputHandle = fopen($outputFile, 'w');

            // Initialize line count for this part
            $lineCount = 0;

            // Loop through the CSV rows and write to the current part
            while ($lineCount < $linesPerPart && ($row = fgetcsv($file)) !== false) {
                if ($lineCount > 0 || $part === 1) {
                    fputcsv($outputHandle, $row);
                }
                $lineCount++;
            }

            // Close the current output file
            fclose($outputHandle);

            // Store to database
            CsvImportQueue::create([
                'user_id' => auth()->id(),
                'name' => $filename,
                'status' => false
            ]);

        }

        // Close the input file
        fclose($file);
    }
}

/**
 * The function calculates the progress of a CSV import task and returns a string indicating the
 * percentage completed.
 * 
 * @return a string that represents the progress of importing CSV files. The string includes the
 * percentage of completed tasks out of the total tasks.
 */
function csv_import_progress()
{
    $completedTasks = CsvImportQueue::where('user_id', auth()->id())->where('status', 1)->count();
    $totalTasks = CsvImportQueue::where('user_id', auth()->id())->count();;

    $percentage = ($completedTasks / $totalTasks) * 100;

    return round($percentage);
}

/**
 * The function checks if the authenticated user has any pending CSV import tasks in the queue.
 * 
 * @return The function `csv_import_checker()` returns a boolean value. If there is at least one record
 * in the `CsvImportQueue` table where the `user_id` is equal to the authenticated user's ID and the
 * `status` is 0, then the function returns `true`. Otherwise, it returns `false`.
 */
function csv_import_checker()
{
    $has_queue = CsvImportQueue::where('user_id', auth()->id())->where('status', 0)->count();

    if ($has_queue > 0) {
        return true;
    } else {
        return false;
    }
}

/**
 * The function returns the departments that have an agent with the authenticated user's ID.
 * 
 * @return a collection of departments that have an agent with the authenticated user's ID.
 */
function departments()
{
    return Department::HasAgent(auth()->id())->with([
        'provider',
        'providers'
    ])->get();
}

/**
 * The function "get_department" retrieves a department with a specific ID, along with its associated
 * provider, for the authenticated user.
 * 
 * @param id The parameter "id" is the identifier of the department that you want to retrieve.
 * 
 * @return a Department model instance with the specified ID, along with its associated provider.
 */
function get_department($id)
{
    return Department::HasAgent(auth()->id())
                        ->where('id', $id)
                        ->with('providers')
                        ->first();
}

/**
 * The function "department_options" returns an array of options for a given department ID.
 * 
 * @param department_id The department_id parameter is the unique identifier of the department for
 * which you want to retrieve the options.
 * 
 * @return An array containing the values of the 'inbound', 'outbound', 'call_forward', and 'ivr'
 * properties of the Department object with the given .
 */
function department_options($department_id)
{
    $department = Department::find($department_id);

    return [
        'inbound' => $department->inbound,
        'outbound' => $department->outbound,
        'call_forward' => $department->call_forward,
        'ivr' => $department->ivr
    ];
}

/**
 * The function finds the first department that has a provider with the given provider ID.
 * 
 * @param provider_id The provider_id parameter is the ID of the provider for which we want to find a
 * department number that is available.
 * 
 * @return the first department that has a provider with the specified provider_id.
 */
function find_department_number_is_available($provider_id)
{
    return Department::whereHas('provider', function ($query) use ($provider_id) {
        $query->where('provider_id', $provider_id);
    })->first();
}

/**
 * The function checks if an agent is assigned to a specific department.
 * 
 * @param agent_id The agent_id parameter is the unique identifier of the agent. It is used to identify
 * a specific agent in the system.
 * @param department_id The department_id parameter is the unique identifier of a department. It is
 * used to specify which department we want to check if the agent is in.
 * 
 * @return the first instance of the DepartmentAgent model where the agent_id and department_id match
 * the provided parameters.
 */
function agent_is_in_department($agent_id, $department_id)
{
    $department_agent = DepartmentAgent::where('agent_id', $agent_id)
                            ->where('department_id', $department_id)
                            ->first();

    if ($department_agent) {
        return true;
    } else {
        return false;
    }
}

// get agent id
function get_agent_id($user_id)
{
    return Agent::where('user_id', $user_id)->first()->id;
}

/**
 * The function returns the outbound department with the specified ID, belonging to the authenticated
 * agent, along with its provider.
 * 
 * @param id The parameter "id" is the ID of the department you want to retrieve from the database.
 * 
 * @return the first outbound department with the specified ID, along with its associated provider.
 */
function outbound_department($id)
{
    return Department::HasAgent(auth()->id())->where('id', $id)->Outbound()->with('providers')->first();
}

/**
 * The booster function in PHP returns true.
 * 
 * @return true
 */
function booster()
{
    return true;
}

/**
 * The function `makeTitleTranslateable` reads a PHP file, uses regex to find and replace static
 * strings with a translation function, and then writes the modified contents back to the file.
 */
function makeTitleTranslateable()
{
    // Read the PHP file
    $fileContents = file_get_contents(base_path('app/Helper.php'));

    // Use regex to find and replace only static strings
    // This regex matches 'title' => 'Some Value' but not complex expressions
    $pattern = "/'title'\s*=>\s*'([^']+)'(?!\s*\.)/";
    $replacement = "'title' => translate('$1')";
    $modifiedContents = preg_replace($pattern, $replacement, $fileContents);

    // Write the modified contents back to the file
    file_put_contents(base_path('app/xHelper.php'), $modifiedContents);
}

function setSettings($name, $value)
{
    Application::updateOrCreate(
        ['name' => $name],
        ['value' => $value]
    );
}
