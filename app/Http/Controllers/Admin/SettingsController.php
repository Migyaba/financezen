<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = AppSetting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $fields = [
            ['key' => 'app_name', 'group' => 'general', 'type' => 'string'],
            ['key' => 'support_email', 'group' => 'general', 'type' => 'string'],
            ['key' => 'contact_email', 'group' => 'general', 'type' => 'string'],
            ['key' => 'subscription_price', 'group' => 'pricing', 'type' => 'int'],
            ['key' => 'trial_days', 'group' => 'pricing', 'type' => 'int'],
            ['key' => 'currency', 'group' => 'pricing', 'type' => 'string'],
            ['key' => 'fedapay_public_key', 'group' => 'fedapay', 'type' => 'string'],
            ['key' => 'fedapay_secret_key', 'group' => 'fedapay', 'type' => 'string'],
            ['key' => 'fedapay_mode', 'group' => 'fedapay', 'type' => 'string'],
            ['key' => 'mail_host', 'group' => 'email', 'type' => 'string'],
            ['key' => 'mail_port', 'group' => 'email', 'type' => 'int'],
            ['key' => 'mail_username', 'group' => 'email', 'type' => 'string'],
            ['key' => 'mail_from_name', 'group' => 'email', 'type' => 'string'],
            ['key' => 'maintenance_message', 'group' => 'notification', 'type' => 'string'],
            ['key' => 'registration_enabled', 'group' => 'notification', 'type' => 'bool'],
        ];

        foreach ($fields as $field) {
            if ($request->has($field['key'])) {
                AppSetting::updateOrCreate(
                    ['key' => $field['key']],
                    [
                        'value' => $request->input($field['key']),
                        'type' => $field['type'],
                        'group' => $field['group'],
                    ]
                );
            }
        }

        return back()->with('success', 'Paramètres sauvegardés.');
    }
}
