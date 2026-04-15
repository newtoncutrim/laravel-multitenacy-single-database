<x-layouts.auth title="Painel da plataforma" wide>
    <div class="actions">
        <a class="top-link" href="{{ url('/') }}">Inicio</a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="button-secondary" type="submit">Sair</button>
        </form>
    </div>

    <div class="header">
        <p class="eyebrow">Plataforma</p>
        <h1>Painel administrativo SaaS</h1>
        <p class="description">Gerencie clinicas, planos, assinaturas e suporte da plataforma.</p>
    </div>

    <div class="posts">
        <article class="post">
            <h2>{{ $tenantsCount }}</h2>
            <p>Clinicas cadastradas</p>
        </article>

        <article class="post">
            <h2>{{ $tenantUsersCount }}</h2>
            <p>Usuarios de clinicas</p>
        </article>

        <article class="post">
            <h2>{{ $plansCount }}</h2>
            <p>Planos comerciais</p>
        </article>

        <article class="post">
            <h2>{{ $subscriptionsCount }}</h2>
            <p>Assinaturas ativas ou historicas</p>
        </article>
    </div>
</x-layouts.auth>
