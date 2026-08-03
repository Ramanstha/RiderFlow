<?php

namespace App\Services;

use App\Models\Company;
use App\Models\DailyParcelCount;
use App\Models\Rider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

/**
 * A client company assigns a day's parcel load to one of its riders. There is
 * only ever one parcel row per rider per day, so assigning either creates that
 * row or updates the assigned figure on the row the rider has already started.
 */
class ClientParcelAssignmentService
{
    /**
     * Assign (or re-assign) a parcel load for one rider on one date.
     *
     * @param  array{rider_id: int, date: string, assigned: int}  $data
     */
    public function assign(Company $company, array $data): DailyParcelCount
    {
        $rider = $this->ownRider($company, (int) $data['rider_id']);

        $existing = DailyParcelCount::query()
            ->where('rider_id', $rider->id)
            ->whereDate('date', $data['date'])
            ->first();

        if ($existing) {
            // The rider may have logged this day themselves already; taking it
            // over is fine, but never below what they have reported.
            $this->guardBelowReported($existing, (int) $data['assigned']);

            $existing->forceFill([
                'assigned' => (int) $data['assigned'],
                'assigned_by_company_id' => $company->id,
                'assigned_at' => now(),
            ])->save();

            return $existing;
        }

        return DailyParcelCount::query()->create([
            'rider_id' => $rider->id,
            'assigned_by_company_id' => $company->id,
            'date' => $data['date'],
            'assigned' => (int) $data['assigned'],
            'assigned_at' => now(),
            'delivered' => 0,
            'failed' => 0,
            'returned' => 0,
        ]);
    }

    /**
     * Change the assigned figure on an existing assignment.
     */
    public function update(Company $company, DailyParcelCount $assignment, array $data): DailyParcelCount
    {
        $this->authorize($company, $assignment);
        $this->guardBelowReported($assignment, (int) $data['assigned']);

        $assignment->forceFill([
            'assigned' => (int) $data['assigned'],
            'assigned_at' => now(),
        ])->save();

        return $assignment;
    }

    /**
     * Withdraw an assignment. If the rider has already reported against it the
     * row is kept and simply handed back to them, so no reported work is lost.
     */
    public function withdraw(Company $company, DailyParcelCount $assignment): void
    {
        $this->authorize($company, $assignment);

        if ($assignment->hasRiderReport()) {
            $assignment->forceFill([
                'assigned_by_company_id' => null,
                'assigned_at' => null,
            ])->save();

            return;
        }

        $assignment->delete();
    }

    /**
     * Assignments made by this company, newest first.
     */
    public function paginate(Company $company, array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return DailyParcelCount::query()
            ->with('rider:id,name')
            ->where('assigned_by_company_id', $company->id)
            ->when($filters['rider_id'] ?? null, fn (Builder $q, $id) => $q->where('rider_id', $id))
            ->when($filters['date_from'] ?? null, fn (Builder $q, $date) => $q->whereDate('date', '>=', $date))
            ->when($filters['date_to'] ?? null, fn (Builder $q, $date) => $q->whereDate('date', '<=', $date))
            ->latest('date')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * A client may only assign to riders on its own roster.
     */
    protected function ownRider(Company $company, int $riderId): Rider
    {
        $rider = $company->riders()->whereKey($riderId)->first();

        if (! $rider) {
            throw ValidationException::withMessages([
                'rider_id' => 'That rider is not on your fleet.',
            ]);
        }

        return $rider;
    }

    /**
     * A client may only touch assignments it made itself.
     */
    protected function authorize(Company $company, DailyParcelCount $assignment): void
    {
        if ($assignment->assigned_by_company_id !== $company->id) {
            throw ValidationException::withMessages([
                'assigned' => 'This parcel assignment does not belong to your company.',
            ]);
        }
    }

    /**
     * Cutting the load below what the rider already reported would leave the
     * row inconsistent, so it is refused.
     */
    protected function guardBelowReported(DailyParcelCount $assignment, int $assigned): void
    {
        $reported = max($assignment->delivered, $assignment->failed, $assignment->returned);

        if ($assigned < $reported) {
            throw ValidationException::withMessages([
                'assigned' => "This rider has already reported {$reported} parcels for that day, so the assignment cannot be lower.",
            ]);
        }
    }
}
