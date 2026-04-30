<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ArchiveController extends Controller
{
    public function index(): View
    {
        $this->authorizeArchiveAccess();

        $archivedProducts = Product::onlyTrashed()
            ->with(['category', 'firstAvailableBatch'])
            ->latest('deleted_at')
            ->get();

        $archivedCategories = Category::onlyTrashed()
            ->withCount('products')
            ->latest('deleted_at')
            ->get();

        $archivedSuppliers = Supplier::onlyTrashed()
            ->latest('deleted_at')
            ->get();

        $archivedBatches = ProductBatch::onlyTrashed()
            ->with(['product', 'supplier'])
            ->latest('deleted_at')
            ->get();

        $archivedUsers = collect();
        if (auth()->user()->isOwner() || auth()->user()->isAdmin()) {
            $archivedUsers = User::onlyTrashed()
                ->latest('deleted_at')
                ->get();
        }

        return view('archives.index', compact(
            'archivedProducts',
            'archivedCategories',
            'archivedSuppliers',
            'archivedBatches',
            'archivedUsers'
        ));
    }

    public function restore(string $type, int $id): RedirectResponse
    {
        $this->authorizeArchiveAccess();

        [$modelClass, $label] = $this->archiveModel($type);
        $this->authorizeRestoreType($type);

        $record = $modelClass::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($record, $type) {
            $record->restore();

            if ($type === 'batches') {
                $inventory = Inventory::firstOrCreate(
                    ['product_id' => $record->product_id],
                    ['quantity' => 0, 'reorder_level' => 5, 'last_updated' => today()]
                );

                $inventory->increment('quantity', $record->quantity);
                $inventory->update(['last_updated' => today()]);
            }
        });

        return back()->with('success', "{$label} unarchived successfully.");
    }

    private function authorizeArchiveAccess(): void
    {
        if (! auth()->user()->isOwner() && ! auth()->user()->isAdmin() && ! auth()->user()->isStaff()) {
            abort(403, 'You do not have permission to view archived records.');
        }
    }

    private function authorizeRestoreType(string $type): void
    {
        if ($type === 'users' && ! auth()->user()->isOwner() && ! auth()->user()->isAdmin()) {
            abort(403, 'You do not have permission to restore archived users.');
        }

        if ($type !== 'users' && ! auth()->user()->isOwner() && ! auth()->user()->isAdmin() && ! auth()->user()->isStaff()) {
            abort(403, 'You do not have permission to restore archived inventory records.');
        }
    }

    private function archiveModel(string $type): array
    {
        return match ($type) {
            'products' => [Product::class, 'Product'],
            'categories' => [Category::class, 'Category'],
            'suppliers' => [Supplier::class, 'Supplier'],
            'batches' => [ProductBatch::class, 'Batch'],
            'users' => [User::class, 'User'],
            default => abort(404),
        };
    }
}
