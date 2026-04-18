<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Segment;
use App\Models\Tenant;
use App\Services\TenantProvisioningService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'As credenciais informadas nao conferem.',
            ]);
        }

        $request->session()->regenerate();

        return response()->json([
            'data' => $this->userPayload($request->user()),
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'tenant_name' => ['required', 'string', 'max:255', 'unique:tenants,name'],
            'segment_slug' => ['nullable', 'string', 'exists:segments,slug'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = DB::transaction(function () use ($data) {
            $segment = Segment::query()
                ->where('slug', $data['segment_slug'] ?? 'veterinary')
                ->where('active', true)
                ->first();

            $tenant = Tenant::create([
                'segment_id' => $segment?->id,
                'name' => $data['tenant_name'],
            ]);

            app(TenantProvisioningService::class)->provision($tenant, $segment);

            $user = $tenant->users()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'data' => $this->userPayload($user),
        ], Response::HTTP_CREATED);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->userPayload($request->user()),
        ]);
    }

    public function logout(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }

    private function userPayload($user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'tenant_id' => $user->tenant_id,
            'tenant' => $user->tenant ? [
                'id' => $user->tenant->id,
                'name' => $user->tenant->name,
                'segment' => $user->tenant->segment ? [
                    'slug' => $user->tenant->segment->slug,
                    'name' => $user->tenant->segment->name,
                ] : null,
            ] : null,
            'roles' => $user->roles()->pluck('slug')->values(),
            'area' => $this->area($user),
            'home_path' => route($user->homeRoute(), absolute: false),
        ];
    }

    private function area($user): string
    {
        if ($user->isPlatformAdmin()) {
            return 'platform';
        }

        if ($user->isSupportUser()) {
            return 'support';
        }

        return 'clinic';
    }
}
