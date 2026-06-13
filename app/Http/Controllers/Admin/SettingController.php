<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'setting' => SiteSetting::current(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $setting = SiteSetting::current();

        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'favicon' => ['nullable', 'file', 'mimes:ico,jpg,jpeg,png,webp,svg', 'max:1024'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ], [
            'company_name.required' => 'কোম্পানির নাম দিন।',
            'logo.mimes' => 'Logo JPG, PNG, WEBP বা SVG হতে হবে।',
            'favicon.mimes' => 'Favicon ICO, JPG, PNG, WEBP বা SVG হতে হবে।',
        ]);

        if ($request->hasFile('logo')) {
            $this->deleteUploadedFile($setting->logo);
            $validated['logo'] = $this->storeSettingImage($request->file('logo'), 'logo');
        } else {
            unset($validated['logo']);
        }

        if ($request->hasFile('favicon')) {
            $this->deleteUploadedFile($setting->favicon);
            $validated['favicon'] = $this->storeSettingImage($request->file('favicon'), 'favicon');
        } else {
            unset($validated['favicon']);
        }

        $setting->update($validated);

        return redirect()
            ->route('admin.settings.edit')
            ->with('status', 'Website settings সফলভাবে আপডেট হয়েছে।');
    }

    private function storeSettingImage(mixed $file, string $prefix): string
    {
        $directory = public_path('uploads/settings');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = uniqid($prefix.'_', true).'.'.$file->extension();
        $file->move($directory, $filename);

        return 'uploads/settings/'.$filename;
    }

    private function deleteUploadedFile(?string $path): void
    {
        if (! $path || str_starts_with($path, 'images/')) {
            return;
        }

        $fullPath = public_path($path);

        if (is_file($fullPath)) {
            unlink($fullPath);
        }
    }
}
