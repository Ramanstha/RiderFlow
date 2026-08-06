<?php

namespace App\Http\Controllers\UI;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\StoreContactMessageRequest;
use App\Models\ContactMessage;
use App\Services\BannerService;
use App\Services\ContactService;
use App\Services\RiderService;
use App\Services\TestimonialService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        protected BannerService $banners,
        protected ContactService $contact,
        protected RiderService $riders,
        protected TestimonialService $testimonial,
    ) {}

    public function Home(): View
    {
        $banner = $this->banners->activeHeroBanner();
        $contact = $this->contact->primary();
        $riders = $this->riders->list();
        $testimonial = $this->testimonial->list();

        return view('ui.content.home', compact('banner', 'riders', 'contact', 'testimonial'));
    }

    public function about(): View
    {
        $riders = $this->riders->list();

        return view('ui.content.about', compact('riders'));
    }

    public function contact(): View
    {
        $contact = $this->contact->primary();

        return view('ui.content.contact', compact('contact'));
    }

    public function submitContact(StoreContactMessageRequest $request): RedirectResponse
    {
        ContactMessage::create($request->validated());

        return redirect()
            ->route('contact')
            ->with('success', 'Thanks for getting in touch. We will reply shortly.');
    }
}
