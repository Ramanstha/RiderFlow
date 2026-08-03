<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\StoreBannerRequest;
use App\Http\Requests\Banner\UpdateBannerRequest;
use App\Models\Banner;
use App\Services\BannerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function __construct(protected BannerService $banners) {}

    public function index(Request $request): View
    {
        $this->authorize('site.manage');

        $banners = $this->banners->paginate($request->only(['search']));

        return view('admin.banner.index', compact('banners'));
    }

    public function create(): View
    {
        $this->authorize('site.manage');

        return view('admin.banner.create');
    }

    public function store(StoreBannerRequest $request): RedirectResponse
    {
        $this->authorize('site.manage');

        $this->banners->create($request->validated());

        return redirect()->route('banner.index')->with('success', 'Banner added successfully.');
    }

    public function edit(Banner $banner): View
    {
        $this->authorize('site.manage');

        return view('admin.banner.edit', compact('banner'));
    }

    public function update(UpdateBannerRequest $request, Banner $banner): RedirectResponse
    {
        $this->authorize('site.manage');

        $this->banners->update($banner, $request->validated());

        return redirect()->route('banner.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $this->authorize('site.manage');

        $this->banners->delete($banner);

        return redirect()->route('banner.index')->with('success', 'Banner removed successfully.');
    }
}
