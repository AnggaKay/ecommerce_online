<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $settings = [
            'site_name' => config('app.name'),
            'site_description' => config('app.description', ''),
            'site_logo' => config('app.logo', ''),
            'site_favicon' => config('app.favicon', ''),
            'contact_email' => config('app.contact_email', ''),
            'contact_phone' => config('app.contact_phone', ''),
            'address' => config('app.address', ''),
            'social_facebook' => config('app.social_facebook', ''),
            'social_twitter' => config('app.social_twitter', ''),
            'social_instagram' => config('app.social_instagram', ''),
            'social_youtube' => config('app.social_youtube', ''),
            'footer_text' => config('app.footer_text', ''),
            'meta_keywords' => config('app.meta_keywords', ''),
            'meta_description' => config('app.meta_description', ''),
        ];
        
        return view('admin.settings.index', compact('settings'));
    }
    
    /**
     * Update the application settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'site_favicon' => 'nullable|image|mimes:ico,png|max:1024',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'social_facebook' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_youtube' => 'nullable|url',
            'footer_text' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string',
        ]);
        
        // Update .env file
        $this->updateEnvFile('APP_NAME', $request->site_name);
        
        // Update settings in the database or config
        $settings = $request->except(['_token', 'site_logo', 'site_favicon']);
        
        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            $logoPath = $request->file('site_logo')->store('public/settings');
            $settings['site_logo'] = Storage::url($logoPath);
        }
        
        // Handle favicon upload
        if ($request->hasFile('site_favicon')) {
            $faviconPath = $request->file('site_favicon')->store('public/settings');
            $settings['site_favicon'] = Storage::url($faviconPath);
        }
        
        // Save settings to database or config
        foreach ($settings as $key => $value) {
            // You might want to use a Settings model/table or config files
            // For now, we'll just update the .env file for demonstration
            $this->updateEnvFile('APP_' . strtoupper($key), $value);
        }
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil diperbarui.');
    }
    
    /**
     * Update the .env file with new values.
     *
     * @param  string  $key
     * @param  string  $value
     * @return void
     */
    private function updateEnvFile($key, $value)
    {
        $path = base_path('.env');
        
        if (file_exists($path)) {
            $value = str_replace('"', '\"', $value);
            
            // Check if key exists
            if (strpos(file_get_contents($path), $key . '=') !== false) {
                // Replace value
                file_put_contents(
                    $path, 
                    preg_replace(
                        '/^' . $key . '=.*$/m',
                        $key . '="' . $value . '"',
                        file_get_contents($path)
                    )
                );
            } else {
                // Add key-value pair
                file_put_contents($path, file_get_contents($path) . "\n" . $key . '="' . $value . '"');
            }
        }
    }
} 