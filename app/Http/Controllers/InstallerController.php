<?php

namespace App\Http\Controllers;

use App\Models\User;
use Artisan;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Str;

class InstallerController extends Controller
{
    public function welcome()
    {
        overWriteEnvFile('APP_URL', URL::to('/'));
        Artisan::call('optimize:clear');
        Artisan::call('key:generate');
        return view('install.welcome');
    }

    // permission
    protected function permission()
    {
        $permission['curl_enabled'] = function_exists('curl_version');
        $permission['db_file_write_perm'] = is_writable(base_path('.env'));
        $permission['storage'] = is_writable(base_path('storage'));
        $permission['bootstrap'] = is_readable(base_path('bootstrap/cache'));
        $permission['public'] = is_writable(base_path('public'));
        $permission['htaccess'] = is_readable(base_path('.htaccess'));

        return view('install.permission', compact('permission'));
    }

    // create
    protected function create()
    {
        return view('install.setup');
    }

    //save database information in env file
    //here the get database key or data for env file
    // clear cache
    protected function dbStore(Request $request)
    {
        foreach ($request->types as $type) {
            //here the get database key or data for env file
            overWriteEnvFile($type, $request[$type]);
        }
        Artisan::call('optimize:clear');

        return redirect()->route('check.db');
    }

    // checkDbConnection
    protected function checkDbConnection()
    {
        try {
            //check the database connection for import the sql file
            DB::connection()->getPdo();

            return redirect()->route('sql.setup')->with('success', 'Your database connection done successfully');
        } catch (\Exception $e) {
            return redirect()->route('sql.setup')->with('wrong', 'Could not connect to the database. Please check your configuration');
        }
    }

    //import sql page
    protected function importSql()
    {
        return view('install.importSql');
    }

    /*import here demo data with instructor register form*/
    public function importFreshSql()
    {
        Artisan::call('migrate');
        dataApplication();

        session(['locale' => 'en']);

        return $this->adminCreate();
    }

    //admin create page
    protected function adminCreate()
    {
        return view('install.user');
    }

    //create a admin with full access
    //save and add the super access permission
    // replace the RouteService provider when installation is done
    //return the dashboard when all is done
    protected function adminStore(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string'],
        ],
        [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'invalid email',
            'email.unique' => 'Email already exist',
            'password.min' => 'Password must be minimum 8 characters',
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'admin';
        $user->restriction = 0;

        if ($user->save()) {
            overWriteEnvFile('APP_INSTALL', 'YES');
        } else {
            overWriteEnvFile('APP_INSTALL', 'NO');
        }

        return view('install.done');
    }

    //END
}
