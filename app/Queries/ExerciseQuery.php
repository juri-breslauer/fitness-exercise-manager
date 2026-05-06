<?php

namespace App\Queries;

use App\Models\Exercise;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ExerciseQuery
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, Exercise>
     */
    public function paginate(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->baseQuery()
            ->when(
                $filters['search'] ?? null,
                fn (Builder $query, string $search) => $query->where(function (Builder $query) use ($search): void {
                    $search = '%'.Str::lower($search).'%';

                    $query
                        ->whereRaw('LOWER(name) LIKE ?', [$search])
                        ->orWhereRaw('LOWER(display_name) LIKE ?', [$search]);
                })
            )
            ->when(
                $filters['category'] ?? null,
                fn (Builder $query, string $category) => $query->whereRelation('category', 'slug', $category)
            )
            ->when(
                $filters['muscle'] ?? null,
                fn (Builder $query, string $muscle) => $query->whereRelation('muscles', 'slug', $muscle)
            )
            ->when(
                $filters['equipment'] ?? null,
                fn (Builder $query, string $equipment) => $query->whereRelation('equipment', 'slug', $equipment)
            )
            ->when(
                $filters['difficulty'] ?? null,
                fn (Builder $query, string $difficulty) => $query->where('difficulty', $difficulty)
            )
            ->when(
                $filters['force'] ?? null,
                fn (Builder $query, string $force) => $query->where('force', $force)
            )
            ->when(
                $filters['mechanic'] ?? null,
                fn (Builder $query, string $mechanic) => $query->where('mechanic', $mechanic)
            )
            ->where('status', 'published')
            ->tap(fn (Builder $query) => $this->applySort($query, $filters['sort'] ?? 'name'))
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @return Builder<Exercise>
     */
    private function baseQuery(): Builder
    {
        return Exercise::query()
            ->with(['category', 'primaryMuscles', 'secondaryMuscles', 'equipment']);
    }

    /**
     * @param  Builder<Exercise>  $query
     */
    private function applySort(Builder $query, string $sort): void
    {
        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $column = ltrim($sort, '-');

        $query->orderBy($column, $direction);
    }
}
