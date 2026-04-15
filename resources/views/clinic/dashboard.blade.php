<x-layouts.auth title="Painel da clinica" wide>
    <div class="actions">
        <a class="top-link" href="{{ url('/') }}">Inicio</a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="button-secondary" type="submit">Sair</button>
        </form>
    </div>

    <div class="header">
        <p class="eyebrow">Clinica</p>
        <h1>{{ auth()->user()->tenant?->name }}</h1>
        <p class="description">Gerencie tutores, pacientes, agenda, prontuarios e operacao da clinica.</p>
    </div>

    <div class="posts">
        <article class="post">
            <h2>{{ $clientsCount }}</h2>
            <p>Clientes e tutores</p>
        </article>

        <article class="post">
            <h2>{{ $petsCount }}</h2>
            <p>Pets e pacientes</p>
        </article>

        <article class="post">
            <h2>{{ $appointmentsCount }}</h2>
            <p>Agendamentos</p>
        </article>
    </div>

    <p class="switch">
        <a href="{{ route('clinic.posts.index') }}">Abrir posts da clinica</a>
    </p>
</x-layouts.auth>
