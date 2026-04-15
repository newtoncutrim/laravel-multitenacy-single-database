<?php

namespace App\Modules\Clinic\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Modules\Clinic\Actions\CreateClientAction;
use App\Modules\Clinic\Actions\UpdateClientAction;
use App\Modules\Clinic\Http\Requests\StoreClientRequest;
use App\Modules\Clinic\Http\Requests\UpdateClientRequest;
use App\Modules\Clinic\Http\Resources\ClientResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ClientController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Client::class);

        return ClientResource::collection(
            Client::query()
                ->latest()
                ->paginate()
        );
    }

    public function store(StoreClientRequest $request, CreateClientAction $action): ClientResource
    {
        $client = $action->execute($request->validated());

        return ClientResource::make($client);
    }

    public function show(Client $client): ClientResource
    {
        $this->authorize('view', $client);

        return ClientResource::make($client);
    }

    public function update(UpdateClientRequest $request, Client $client, UpdateClientAction $action): ClientResource
    {
        $client = $action->execute($client, $request->validated());

        return ClientResource::make($client);
    }

    public function destroy(Client $client): Response
    {
        $this->authorize('delete', $client);

        $client->delete();

        return response()->noContent();
    }
}
