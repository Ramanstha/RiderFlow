<?php

namespace App\Http\Controllers\Client;

use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RiderController extends PortalController
{
    public function index(Request $request): View
    {
        $riders = $this->company()->riders()
            ->when($request->string('search')->trim()->value(), fn ($q, $search) => $q->where(
                fn ($w) => $w->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
            ))
            ->when($request->string('status')->trim()->value(), fn ($q, $status) => $q->where('riders.status', $status))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('client.rider.index', compact('riders'));
    }

    public function show(Rider $rider): View
    {
        $this->authorizeRider($rider);

        $rider->load(['assignments' => fn ($q) => $q->with('company')->latest('start_date')->limit(10)]);

        return view('client.rider.show', [
            'rider' => $rider,
            'attendance' => $rider->attendances()->latest('date')->limit(15)->get(),
            'parcels' => $rider->parcelCounts()->latest('date')->limit(15)->get(),
            'performance' => $rider->performances()->latest('period')->limit(12)->get(),
        ]);
    }

    /**
     * A client may only open riders on its own roster. 404 rather than 403 so
     * the portal does not confirm that another company's rider id exists.
     */
    protected function authorizeRider(Rider $rider): void
    {
        if (! $this->company()->riders()->whereKey($rider->getKey())->exists()) {
            throw new NotFoundHttpException('Rider not found.');
        }
    }
}
