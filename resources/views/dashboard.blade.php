<x-layouts.auth title="Painel">
    <a class="top-link" href="{{ url('/') }}">Voltar</a>

    <div class="header">
        <p class="eyebrow">Painel</p>
        <h1>Ola, {{ auth()->user()->name }}</h1>
        <p class="description">
            Empresa: {{ auth()->user()->tenat?->name ?? 'Sem empresa vinculada' }}
        </p>
    </div>

    <p class="switch">
        <a href="{{ route('posts.index') }}">Ver posts</a>
    </p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="button" type="submit">Sair</button>
    </form>
</x-layouts.auth>
