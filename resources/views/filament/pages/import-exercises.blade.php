<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_24rem]">
            <section class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="max-w-3xl">
                    <p class="text-sm font-medium uppercase tracking-wide text-primary-600 dark:text-primary-400">
                        Dataset Import
                    </p>

                    <h2 class="mt-2 text-xl font-semibold text-gray-950 dark:text-white">
                        Import exercises from a JSON file
                    </h2>

                    <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-400">
                        Upload a small project-owned JSON dataset to create or update the exercise catalog.
                        The importer validates every row, skips invalid entries, and returns a summary after
                        preview or import.
                    </p>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    <div class="rounded-md border border-gray-200 p-4 dark:border-gray-800">
                        <div class="text-sm font-semibold text-gray-950 dark:text-white">1. Validate</div>
                        <p class="mt-2 text-sm leading-5 text-gray-600 dark:text-gray-400">
                            The file must be a JSON array using the v1 import contract.
                        </p>
                    </div>

                    <div class="rounded-md border border-gray-200 p-4 dark:border-gray-800">
                        <div class="text-sm font-semibold text-gray-950 dark:text-white">2. Upsert</div>
                        <p class="mt-2 text-sm leading-5 text-gray-600 dark:text-gray-400">
                            Categories, muscles, equipment, and exercises are matched by slug.
                        </p>
                    </div>

                    <div class="rounded-md border border-gray-200 p-4 dark:border-gray-800">
                        <div class="text-sm font-semibold text-gray-950 dark:text-white">3. Sync</div>
                        <p class="mt-2 text-sm leading-5 text-gray-600 dark:text-gray-400">
                            Muscle roles and equipment links are synced for every imported exercise.
                        </p>
                    </div>
                </div>
            </section>

            <aside class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-sm font-semibold text-gray-950 dark:text-white">
                    Before importing
                </h3>

                <ul class="mt-4 space-y-3 text-sm leading-5 text-gray-600 dark:text-gray-400">
                    <li>Use Dry Run first to preview counts and validation errors.</li>
                    <li>Large production datasets should stay outside the repository.</li>
                    <li>The v1 format is single-locale; translations are out of scope.</li>
                    <li>Media import is not supported by this workflow yet.</li>
                </ul>
            </aside>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="text-base font-semibold text-gray-950 dark:text-white">
                        Fitness Exercise Import JSON v1
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-gray-600 dark:text-gray-400">
                        Each exercise carries its taxonomy values. New categories, muscles, and equipment are
                        created automatically from those values, then linked to the exercise.
                    </p>
                </div>
            </div>

            <div class="mt-8 overflow-hidden rounded-lg border border-gray-200 bg-gray-50 shadow-sm dark:border-gray-800 dark:bg-gray-950">
                <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3 dark:border-gray-800">
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Example payload
                    </div>

                    <div class="rounded-md bg-white px-2 py-1 text-xs text-gray-500 ring-1 ring-gray-200 dark:bg-gray-900 dark:text-gray-400 dark:ring-gray-800">
                        JSON
                    </div>
                </div>

                <pre class="overflow-x-auto p-5 text-xs leading-6"><code><span class="text-gray-500">[</span>
  <span class="text-gray-500">{</span>
    <span class="text-primary-600 dark:text-primary-400">"slug"</span><span class="text-gray-500">:</span> <span class="text-emerald-700 dark:text-emerald-400">"push-up"</span><span class="text-gray-500">,</span>
    <span class="text-primary-600 dark:text-primary-400">"name"</span><span class="text-gray-500">:</span> <span class="text-emerald-700 dark:text-emerald-400">"Push Up"</span><span class="text-gray-500">,</span>
    <span class="text-primary-600 dark:text-primary-400">"display_name"</span><span class="text-gray-500">:</span> <span class="text-emerald-700 dark:text-emerald-400">"Push-Up"</span><span class="text-gray-500">,</span>
    <span class="text-primary-600 dark:text-primary-400">"aliases"</span><span class="text-gray-500">:</span> <span class="text-gray-500">[</span><span class="text-emerald-700 dark:text-emerald-400">"press up"</span><span class="text-gray-500">],</span>
    <span class="text-primary-600 dark:text-primary-400">"description"</span><span class="text-gray-500">:</span> <span class="text-emerald-700 dark:text-emerald-400">"A bodyweight upper-body pushing exercise."</span><span class="text-gray-500">,</span>
    <span class="text-primary-600 dark:text-primary-400">"instructions"</span><span class="text-gray-500">:</span> <span class="text-gray-500">[</span>
      <span class="text-emerald-700 dark:text-emerald-400">"Start in a high plank position."</span><span class="text-gray-500">,</span>
      <span class="text-emerald-700 dark:text-emerald-400">"Lower your chest toward the floor."</span><span class="text-gray-500">,</span>
      <span class="text-emerald-700 dark:text-emerald-400">"Press back to the starting position."</span>
    <span class="text-gray-500">],</span>
    <span class="text-primary-600 dark:text-primary-400">"tips"</span><span class="text-gray-500">:</span> <span class="text-gray-500">[</span>
      <span class="text-emerald-700 dark:text-emerald-400">"Keep your body in a straight line."</span>
    <span class="text-gray-500">],</span>
    <span class="text-primary-600 dark:text-primary-400">"difficulty"</span><span class="text-gray-500">:</span> <span class="text-emerald-700 dark:text-emerald-400">"beginner"</span><span class="text-gray-500">,</span>
    <span class="text-primary-600 dark:text-primary-400">"force"</span><span class="text-gray-500">:</span> <span class="text-emerald-700 dark:text-emerald-400">"push"</span><span class="text-gray-500">,</span>
    <span class="text-primary-600 dark:text-primary-400">"mechanic"</span><span class="text-gray-500">:</span> <span class="text-emerald-700 dark:text-emerald-400">"compound"</span><span class="text-gray-500">,</span>
    <span class="text-primary-600 dark:text-primary-400">"category"</span><span class="text-gray-500">:</span> <span class="text-emerald-700 dark:text-emerald-400">"strength"</span><span class="text-gray-500">,</span>
    <span class="text-primary-600 dark:text-primary-400">"primary_muscles"</span><span class="text-gray-500">:</span> <span class="text-gray-500">[</span><span class="text-emerald-700 dark:text-emerald-400">"chest"</span><span class="text-gray-500">,</span> <span class="text-emerald-700 dark:text-emerald-400">"triceps"</span><span class="text-gray-500">],</span>
    <span class="text-primary-600 dark:text-primary-400">"secondary_muscles"</span><span class="text-gray-500">:</span> <span class="text-gray-500">[</span><span class="text-emerald-700 dark:text-emerald-400">"shoulders"</span><span class="text-gray-500">],</span>
    <span class="text-primary-600 dark:text-primary-400">"equipment"</span><span class="text-gray-500">:</span> <span class="text-gray-500">[</span><span class="text-emerald-700 dark:text-emerald-400">"body only"</span><span class="text-gray-500">],</span>
    <span class="text-primary-600 dark:text-primary-400">"status"</span><span class="text-gray-500">:</span> <span class="text-emerald-700 dark:text-emerald-400">"published"</span>
  <span class="text-gray-500">}</span>
<span class="text-gray-500">]</span></code></pre>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <div class="rounded-md bg-gray-50 p-4 dark:bg-gray-800/50">
                    <h3 class="text-sm font-semibold text-gray-950 dark:text-white">Taxonomy mapping</h3>
                    <p class="mt-2 text-sm leading-5 text-gray-600 dark:text-gray-400">
                        Values like <code class="rounded bg-white px-1 py-0.5 dark:bg-gray-900">body only</code>
                        are normalized to slugs like <code class="rounded bg-white px-1 py-0.5 dark:bg-gray-900">body-only</code>.
                        New names are generated as title case.
                    </p>
                </div>

                <div class="rounded-md bg-gray-50 p-4 dark:bg-gray-800/50">
                    <h3 class="text-sm font-semibold text-gray-950 dark:text-white">Idempotency</h3>
                    <p class="mt-2 text-sm leading-5 text-gray-600 dark:text-gray-400">
                        Re-uploading the same file updates existing exercises by
                        <code class="rounded bg-white px-1 py-0.5 dark:bg-gray-900">slug</code>
                        and does not create duplicate taxonomy records.
                    </p>
                </div>
            </div>
        </div>

        @if ($summary)
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h2 class="text-base font-semibold text-gray-950 dark:text-white">
                    Import Summary
                </h2>

                <dl class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Created exercises</dt>
                        <dd class="text-2xl font-semibold text-gray-950 dark:text-white">{{ $summary['created_exercises'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Updated exercises</dt>
                        <dd class="text-2xl font-semibold text-gray-950 dark:text-white">{{ $summary['updated_exercises'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Created categories</dt>
                        <dd class="text-2xl font-semibold text-gray-950 dark:text-white">{{ $summary['created_categories'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Created muscles</dt>
                        <dd class="text-2xl font-semibold text-gray-950 dark:text-white">{{ $summary['created_muscles'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Created equipment</dt>
                        <dd class="text-2xl font-semibold text-gray-950 dark:text-white">{{ $summary['created_equipment'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Skipped rows</dt>
                        <dd class="text-2xl font-semibold text-gray-950 dark:text-white">{{ $summary['skipped_rows'] }}</dd>
                    </div>
                </dl>

                @if ($summary['dry_run'])
                    <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                        Dry run only. No database records were changed.
                    </p>
                @endif

                @if ($summary['errors'])
                    <div class="mt-6">
                        <h3 class="text-sm font-semibold text-gray-950 dark:text-white">Skipped rows and errors</h3>

                        <ul class="mt-2 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            @foreach ($summary['errors'] as $error)
                                <li>
                                    @if ($error['row'])
                                        Row {{ $error['row'] }}:
                                    @endif
                                    {{ $error['message'] }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-filament-panels::page>
