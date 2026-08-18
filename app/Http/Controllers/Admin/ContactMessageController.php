<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Services\ContactMessageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function __construct(protected ContactMessageService $messages) {}

    public function index(Request $request): View
    {
        $this->authorize('site.manage');

        $messages = $this->messages->paginate($request->only(['search']));

        return view('admin.contact-message.index', compact('messages'));
    }

    public function show(ContactMessage $contactMessage): View
    {
        $this->authorize('site.manage');

        return view('admin.contact-message.show', ['message' => $contactMessage]);
    }

    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        $this->authorize('site.manage');

        $this->messages->delete($contactMessage);

        return redirect()->route('contact-message.index')->with('success', 'Message removed successfully.');
    }
}
