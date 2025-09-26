<?php

namespace App\Http\Controllers;

use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Models\SystemCurrency;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SystemCurrencyController extends Controller
{
    public function index()
    {
        $currencies = SystemCurrency::get();

        return view('backend.currency.index', compact('currencies'));
    }

    public function store(Request $request)
    {
        $currencyData = config('money.'.$request->code);

        $this->validate($request, [
            'code' => 'required|unique:system_currencies',
        ]);

        $currency = new SystemCurrency;
        $currency->name = $currencyData['name'];
        $currency->code = $currencyData['code'];
        $currency->symbol = $request->code;
        $currency->icon = $currencyData['symbol'];
        $currency->amount = convertCurrency($request->code, 1);
        $currency->default = 0;
        $currency->save();

        activity($currency->name, 'new currency is added');
        smilify('success', 'Currencies has been added successfully');

        return back();
    }

    public function update($id)
    {
        $currency = SystemCurrency::where('id', $id)->first();

        $amount = convertCurrency($currency->symbol, 1);

        $currency->amount = $amount;
        $currency->save();

        smilify('success', 'Currencies has been added successfully');

        return back();
    }

    //defaultLanguage
    public function defaultCurrency($code)
    {
        $currencies = SystemCurrency::get();

        foreach ($currencies as $currency) {
            if ($currency->code == $code) {
                $currency->default = 1;
                $currency->save();
            } else {
                $currency->default = 0;
                $currency->save();
            }
        }

        return $this->updateCurrency($code);
    }

    public function updateCurrency($code)
    {
        $currencies = SystemCurrency::get();

        foreach ($currencies as $currency) {
            $currency->amount = convertCurrency($currency->symbol, 1);
            $currency->save();
        }

        smilify('success', 'Currencies has been added successfully');

        return back();
    }

    //delete the language
    public function destroy($id)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        SystemCurrency::where('id', $id)->delete();

        smilify('success', 'Currencies has been deleted successfully');

        return back();
    }

    //ENDS HERE
}
