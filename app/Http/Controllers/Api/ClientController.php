<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clients = Client::search($request->only(['search', 'status']))
            ->withCount('contacts')
            ->latest()
            ->paginate($request->integer('page', 10));

        return response()->json($clients, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        $client = DB::transaction(function () use ($request) {
            return Client::create([
                'name'      => $request->input('name'),
                'taxId'     => trim($request->input('taxId')),
                'status'    => $request->input('status'),
                'created_by'   => 1,
            ]);
        });

        return response()->json([
            'status'  => 'success',
            'message' => $client->name . ' creado exitosamente en el CRM.', // 👈 Solo concatenamos el string del nombre
            'data'    => $client
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return response()->json([
            'status' => 'success',
            'data'   => $client
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreClientRequest $request, Client $client)
    {
        $updatedClient = DB::transaction(function () use ($request, $client) {
            $client->update($request->validated());
            return $client;
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Cliente ' . $updatedClient->name . ' actualizado correctamente.',
            'data'    => $updatedClient
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        DB::transaction(function () use ($client) {
            $client->delete();
        });
        return response()->json([
            'status'  => 'success',
            'message' => 'Cliente eliminado correctamente.'
        ], Response::HTTP_OK);
    }
}
