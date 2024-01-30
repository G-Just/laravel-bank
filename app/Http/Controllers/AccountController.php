<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Client;

class AccountController extends Controller
{
    /**
     * Check if access authorized.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = [];
        foreach (Account::all('IBAN') as $account) {
            $accounts[] = $account->IBAN;
        }
        $number = $accounts[0] ?? '';
        while (in_array($number, $accounts) || $number === '') { // Ensures that generated IBAN is unique
            $number = '';
            foreach (range(1, 11) as $digit) {
                $number = $number . (string)rand(0, 9);
            }
            $IBAN = 'LT0099999' . $number;
        }

        $clients = Client::all()->sortBy('firstName');

        return view('accounts.create', ['clients' => $clients, 'IBAN' => $IBAN]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request)
    {
        $request->validate([
            'client_id' => 'numeric'
        ]);

        Account::create([...$request->all(), 'balance' => 0]);

        return redirect()->route('clients')->with('message', 'Account created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        return view('accounts.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        //
    }
}
