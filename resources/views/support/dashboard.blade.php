<x-layouts.auth title="Painel de suporte" wide>
    <div class="actions">
        <a class="top-link" href="{{ url('/') }}">Inicio</a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="button-secondary" type="submit">Sair</button>
        </form>
    </div>

    <div class="header">
        <p class="eyebrow">Suporte</p>
        <h1>Painel de atendimento aos clientes</h1>
        <p class="description">
            Consulte contexto operacional para ajudar clinicas sem acessar a administracao principal da plataforma.
        </p>
    </div>

    <div class="posts">
        <article class="post">
            <h2>{{ $tenantsCount }}</h2>
            <p>Clinicas na plataforma</p>
        </article>

        <article class="post">
            <h2>{{ $tenantUsersCount }}</h2>
            <p>Usuarios de clinicas</p>
        </article>

        <article class="post">
            <h2>{{ $clientsCount }}</h2>
            <p>Clientes e tutores cadastrados</p>
        </article>

        <article class="post">
            <h2>{{ $petsCount }}</h2>
            <p>Pets e pacientes cadastrados</p>
        </article>

        <article class="post">
            <h2>{{ $appointmentsCount }}</h2>
            <p>Agendamentos no sistema</p>
        </article>
    </div>
</x-layouts.auth>
