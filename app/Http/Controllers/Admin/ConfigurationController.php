<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function getInput()
    {
        $configs = Configuration::where('is_enabled', 1)->get();
        $html = '';
        foreach ($configs as $config) {
            $html .= '<div class="col-lg-6 m-b-15"><div class="form-group field-pagemetadata">
            <label class="control-label col-md-12" for="page-metadata">' . $config->display_name . '</label>
            <input type="text" class="form-control" autocomplete="off" value="' . $config->value . '" name="Configuration[' . $config->key . ']"/>
            <div class="help-block"></div></div></div>';
        }

        // Return the Blade view with the generated HTML
        return view('admin.Configuration.index', ['html' => $html]);
    }


    // public function saveValue(Request $request)
    // {
    //     $configs = Configuration::all();
    //     foreach ($configs as $config) {
    //         $config->value = $request->input('Configuration.' . $config->key);
    //         $config->save();
    //     }
    //     return true;
    // }
    public function saveValue(Request $request)
    {
        $configs = Configuration::all();
        foreach ($configs as $config) {
            $value = $request->input('Configuration.' . $config->key);
            if ($value !== null) { // Ensure null values aren't saved
                $config->value = $value;
                $config->save();
            }
        }
        return redirect()->route('admin.configurations')->with('success', 'Configurations updated successfully!');
    }
}
