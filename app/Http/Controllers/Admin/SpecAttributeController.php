<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SpecAttributeRequest;
use App\Models\SpecAttribute;
use App\Models\SpecAttributeValue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SpecAttributeController extends Controller
{
    /**
     * Display a listing of all specification attributes and their options.
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');

        $query = SpecAttribute::with(['values'])->withCount('products');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%");
        }

        $specifications = $query->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.specifications.index', [
            'specifications' => $specifications,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new specification attribute.
     */
    public function create(): View
    {
        return view('admin.specifications.create', [
            'specification' => new SpecAttribute,
        ]);
    }

    /**
     * Store a newly created specification attribute with its options.
     */
    public function store(SpecAttributeRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $attribute = DB::transaction(function () use ($validated, $request): SpecAttribute {
            $slug = ! empty($validated['slug'])
                ? Str::slug($validated['slug'])
                : Str::slug($validated['name']);

            $baseSlug = $slug;
            $counter = 1;
            while (SpecAttribute::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }

            $attribute = SpecAttribute::create([
                'name' => trim((string) $validated['name']),
                'slug' => $slug,
                'sort_order' => (int) ($validated['sort_order'] ?? 0),
                'is_active' => $request->boolean('is_active', true),
            ]);

            if (! empty($validated['values']) && is_array($validated['values'])) {
                $sort = 1;
                foreach ($validated['values'] as $val) {
                    $cleaned = trim((string) $val);
                    if ($cleaned !== '') {
                        $attribute->values()->create([
                            'value' => $cleaned,
                            'sort_order' => $sort++,
                        ]);
                    }
                }
            }

            return $attribute;
        });

        return redirect()
            ->route('admin.specifications.index')
            ->with('success', "Specification attribute \"{$attribute->name}\" created successfully.");
    }

    /**
     * Show the form for editing the specified specification attribute.
     */
    public function edit(SpecAttribute $specification): View
    {
        $specification->load(['values'])->loadCount('products');

        return view('admin.specifications.edit', [
            'specification' => $specification,
        ]);
    }

    /**
     * Update the specified specification attribute and its options.
     */
    public function update(SpecAttributeRequest $request, SpecAttribute $specification): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request, $specification): void {
            $slug = ! empty($validated['slug'])
                ? Str::slug($validated['slug'])
                : Str::slug($validated['name']);

            $baseSlug = $slug;
            $counter = 1;
            while (SpecAttribute::where('slug', $slug)->whereKeyNot($specification->id)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }

            $specification->update([
                'name' => trim((string) $validated['name']),
                'slug' => $slug,
                'sort_order' => (int) ($validated['sort_order'] ?? 0),
                'is_active' => $request->boolean('is_active'),
            ]);

            // 1. Delete selected options
            if (! empty($validated['delete_value_ids'])) {
                SpecAttributeValue::where('spec_attribute_id', $specification->id)
                    ->whereIn('id', $validated['delete_value_ids'])
                    ->delete();
            }

            // 2. Update existing options
            if (! empty($validated['values']) && is_array($validated['values'])) {
                foreach ($validated['values'] as $valId => $valText) {
                    $cleaned = trim((string) $valText);
                    if ($cleaned !== '') {
                        SpecAttributeValue::where('id', $valId)
                            ->where('spec_attribute_id', $specification->id)
                            ->update(['value' => $cleaned]);
                    }
                }
            }

            // 3. Add new options
            if (! empty($validated['new_values']) && is_array($validated['new_values'])) {
                $currentMaxSort = (int) $specification->values()->max('sort_order');
                foreach ($validated['new_values'] as $newVal) {
                    $cleaned = trim((string) $newVal);
                    if ($cleaned !== '') {
                        $specification->values()->create([
                            'value' => $cleaned,
                            'sort_order' => ++$currentMaxSort,
                        ]);
                    }
                }
            }
        });

        return redirect()
            ->route('admin.specifications.index')
            ->with('success', "Specification attribute \"{$specification->name}\" updated successfully.");
    }

    /**
     * Remove the specified specification attribute from storage.
     */
    public function destroy(SpecAttribute $specification): RedirectResponse
    {
        $name = $specification->name;
        $specification->delete();

        return redirect()
            ->route('admin.specifications.index')
            ->with('success', "Specification attribute \"{$name}\" deleted successfully.");
    }
}
