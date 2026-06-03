<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Obtiene una lista paginada de los clientes.
     *
     * Permite filtrar por nombre, nit o estado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $clients = Client::search($request->only(['search', 'status']))
            ->withCount('contacts')
            ->latest()
            ->paginate(10);

        return response()->json($clients, Response::HTTP_OK);
    }

    /**
     * Crea un nuevo cliente.
     *
     * @param  \App\Http\Requests\Api\StoreClientRequest $request Datos validados del formulario
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreClientRequest $request)
    {
        $client = DB::transaction(function () use ($request) {
            return Client::create($request->validated());
        });

        return response()->json([
            'status'  => 'success',
            'message' => $client->name . ' creado exitosamente en el CRM.', // 👈 Solo concatenamos el string del nombre
            'data'    => $client
        ], Response::HTTP_CREATED);
    }

    /**
     * Muestra la información de un cliente.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Client $client)
    {
        $client->load('creator');
        return response()->json([
            'status' => 'success',
            'data'   => $client
        ], Response::HTTP_OK);
    }

    /**
     * Actualiza la información de un cliente.
     *
     * @param  \App\Http\Requests\Api\StoreClientRequest $request Datos validados del formulario
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\JsonResponse
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
     * Elimina un cliente existente.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\JsonResponse
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
