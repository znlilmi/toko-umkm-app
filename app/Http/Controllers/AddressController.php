<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AddressController extends Controller
{
    /**
     * Display a list of the authenticated user's addresses.
     */
    public function index(): View
    {
        $addresses = auth()->user()->addresses()->latest()->get();

        return view('profile.addresses.index', compact('addresses'));
    }

    /**
     * Show the form for creating a new address.
     */
    public function create(): View
    {
        return view('profile.addresses.create');
    }

    /**
     * Store a newly created address in storage.
     */
    public function store(StoreAddressRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (! empty($data['is_default'])) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }

        auth()->user()->addresses()->create($data);

        return redirect()->route('addresses.index')
            ->with('success', 'Alamat berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified address.
     */
    public function edit(Address $address): View
    {
        $this->authorize('update', $address);

        return view('profile.addresses.edit', compact('address'));
    }

    /**
     * Update the specified address in storage.
     */
    public function update(UpdateAddressRequest $request, Address $address): RedirectResponse
    {
        $this->authorize('update', $address);

        $data = $request->validated();

        if (! empty($data['is_default'])) {
            auth()->user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($data);

        return redirect()->route('addresses.index')
            ->with('success', 'Alamat berhasil diperbarui.');
    }

    /**
     * Remove the specified address from storage.
     */
    public function destroy(Address $address): RedirectResponse
    {
        $this->authorize('delete', $address);

        $address->delete();

        return redirect()->route('addresses.index')
            ->with('success', 'Alamat berhasil dihapus.');
    }
}
