<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Testimonial\StoreTestimonialRequest;
use App\Http\Requests\Testimonial\UpdateTestimonialRequest;
use App\Models\Testimonial;
use App\Services\TestimonialService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function __construct(protected TestimonialService $testimonials) {}

    public function index(Request $request): View
    {
        $this->authorize('site.manage');

        $testimonials = $this->testimonials->paginate($request->only(['search']));

        return view('admin.testimonial.index', compact('testimonials'));
    }

    public function create(): View
    {
        $this->authorize('site.manage');

        return view('admin.testimonial.create');
    }

    public function store(StoreTestimonialRequest $request): RedirectResponse
    {
        $this->authorize('site.manage');

        $this->testimonials->create($request->validated());

        return redirect()->route('testimonial.index')->with('success', 'Testimonial added successfully.');
    }

    public function edit(Testimonial $testimonial): View
    {
        $this->authorize('site.manage');

        return view('admin.testimonial.edit', compact('testimonial'));
    }

    public function update(UpdateTestimonialRequest $request, Testimonial $testimonial): RedirectResponse
    {
        $this->authorize('site.manage');

        $this->testimonials->update($testimonial, $request->validated());

        return redirect()->route('testimonial.index')->with('success', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $this->authorize('site.manage');

        $this->testimonials->delete($testimonial);

        return redirect()->route('testimonial.index')->with('success', 'Testimonial removed successfully.');
    }
}
