<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\StoreContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __construct(protected ContactService $contacts) {}

    public function index(Request $request): View
    {
        $this->authorize('site.manage');

        $contacts = $this->contacts->paginate($request->only(['search']));

        return view('admin.contact.index', compact('contacts'));
    }

    public function create(): View
    {
        $this->authorize('site.manage');

        return view('admin.contact.create');
    }

    public function store(StoreContactRequest $request): RedirectResponse
    {
        $this->authorize('site.manage');

        $this->contacts->create($request->validated());

        return redirect()->route('contact-info.index')->with('success', 'Contact details added successfully.');
    }

    public function edit(Contact $contactInfo): View
    {
        $this->authorize('site.manage');

        return view('admin.contact.edit', ['contact' => $contactInfo]);
    }

    public function update(UpdateContactRequest $request, Contact $contactInfo): RedirectResponse
    {
        $this->authorize('site.manage');

        $this->contacts->update($contactInfo, $request->validated());

        return redirect()->route('contact-info.index')->with('success', 'Contact details updated successfully.');
    }

    public function destroy(Contact $contactInfo): RedirectResponse
    {
        $this->authorize('site.manage');

        $this->contacts->delete($contactInfo);

        return redirect()->route('contact-info.index')->with('success', 'Contact details removed successfully.');
    }
}
