<?php

namespace App\Http\Controllers\Client;

use App\Http\Requests\Client\StoreParcelAssignmentRequest;
use App\Http\Requests\Client\UpdateParcelAssignmentRequest;
use App\Models\DailyParcelCount;
use App\Services\ClientParcelAssignmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Clients assign a day's parcel load to their own riders. The rider then
 * reports delivered against that figure.
 */
class ParcelAssignmentController extends PortalController
{
    public function __construct(protected ClientParcelAssignmentService $assignments) {}

    public function index(Request $request): View
    {
        $company = $this->company();

        return view('client.assignment-parcel.index', [
            'assignments' => $this->assignments->paginate(
                $company,
                $request->only(['rider_id', 'date_from', 'date_to']),
            ),
            'riders' => $this->activeRiders(),
        ]);
    }

    public function create(): View
    {
        return view('client.assignment-parcel.create', ['riders' => $this->activeRiders()]);
    }

    public function store(StoreParcelAssignmentRequest $request): RedirectResponse
    {
        $this->assignments->assign($this->company(), $request->validated());

        return redirect()->route('client.parcel-assignment.index')
            ->with('success', 'Parcels assigned to the rider.');
    }

    public function edit(DailyParcelCount $assignment): View
    {
        $this->authorizeAssignment($assignment);

        return view('client.assignment-parcel.edit', ['assignment' => $assignment->load('rider:id,name')]);
    }

    public function update(UpdateParcelAssignmentRequest $request, DailyParcelCount $assignment): RedirectResponse
    {
        $this->authorizeAssignment($assignment);

        $this->assignments->update($this->company(), $assignment, $request->validated());

        return redirect()->route('client.parcel-assignment.index')
            ->with('success', 'Parcel assignment updated.');
    }

    public function destroy(DailyParcelCount $assignment): RedirectResponse
    {
        $this->authorizeAssignment($assignment);

        $reported = $assignment->hasRiderReport();

        $this->assignments->withdraw($this->company(), $assignment);

        return redirect()->route('client.parcel-assignment.index')->with(
            'success',
            $reported
                ? 'Assignment withdrawn. The rider keeps the numbers they already reported.'
                : 'Assignment withdrawn.',
        );
    }

    /**
     * 404 rather than 403 so the portal does not confirm that another
     * company's assignment id exists.
     */
    protected function authorizeAssignment(DailyParcelCount $assignment): void
    {
        if ($assignment->assigned_by_company_id !== $this->company()->id) {
            throw new NotFoundHttpException('Assignment not found.');
        }
    }

    protected function activeRiders()
    {
        return $this->company()->riders()->orderBy('name')->get(['riders.id', 'riders.name', 'riders.status']);
    }
}
