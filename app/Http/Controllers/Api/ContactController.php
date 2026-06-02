<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreContactRequest;
use App\Models\Client;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    /**
     * Obtiene una lista paginada de los contactos de un cliente.
     *
     * Permite filtrar por nombre, correo o cargo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Client $client)
    {
        $Contacts = $client->contacts()->search($request->only(['search', 'position']))
            ->latest()
            ->paginate($request->integer('page', 10));

        return response()->json($Contacts, Response::HTTP_OK);
    }

    /**
     * Crea un nuevo contacto asociado a un cliente.
     *
     * Valida la información mediante StoreContactRequest y lo almacena
     *
     * @param  \App\Http\Requests\Api\StoreContactRequest $request Datos validados del formulario
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreContactRequest $request, Client $client)
    {
        $contact = DB::transaction(function () use ($request, $client) {
            return $client->contacts()->create(
                $request->validated()
            );
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Contacto creado exitosamente.',
            'data'    => $contact
        ], Response::HTTP_CREATED);
    }

    /**
     * Actualiza la información de un contacto.
     *
     * Valida la información mediante StoreContactRequest y lo almacena
     *
     * @param  \App\Http\Requests\Api\StoreContactRequest $request Datos validados del formulario
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreContactRequest $request, Contact $contact)
    {
        $updatedConContact = DB::transaction(function () use ($request, $contact) {
            $contact->update($request->validated());
            return $contact;
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Contacto actualizado correctamente.',
            'data'    => $updatedConContact
        ], Response::HTTP_OK);
    }

    /**
     * Elimina un contacto existente.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Contact $contact)
    {
        DB::transaction(function () use ($contact) {
            $contact->delete();
        });
        return response()->json([
            'status'  => 'success',
            'message' => 'Contacto eliminado correctamente.'
        ], Response::HTTP_OK);
    }
}
