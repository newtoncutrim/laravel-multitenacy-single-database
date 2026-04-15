<x-layouts.auth title="Posts" wide>
    <div class="actions">
        <a class="top-link" href="{{ route('dashboard') }}">Painel</a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="button-secondary" type="submit">Sair</button>
        </form>
    </div>

    <div class="header">
        <p class="eyebrow">Posts</p>
        <h1>Meus posts</h1>
        <p class="description">Crie um post simples para a empresa {{ auth()->user()->tenat?->name }}.</p>
    </div>

    @if (session('status'))
        <p class="status">{{ session('status') }}</p>
    @endif

    <form method="POST" action="{{ route('posts.store') }}">
        @csrf

        <div class="field">
            <label for="title">Titulo</label>
            <input id="title" name="title" type="text" value="{{ old('title') }}" required>
            @error('title')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="content">Conteudo</label>
            <textarea id="content" name="content" required>{{ old('content') }}</textarea>
            @error('content')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button class="button" type="submit">Publicar</button>
    </form>

    @if ($posts->isEmpty())
        <p class="empty">Nenhum post criado ainda.</p>
    @else
        <div class="posts">
            @foreach ($posts as $post)
                <article class="post">
                    <h2>{{ $post->title }}</h2>
                    <p>{{ $post->content }}</p>
                    <div class="post-meta">
                        Criado em {{ $post->created_at->format('d/m/Y H:i') }}
                    </div>

                    <form method="POST" action="{{ route('posts.destroy', $post) }}" style="margin-top: 12px;">
                        @csrf
                        @method('DELETE')
                        <button class="button-secondary" type="submit">Apagar</button>
                    </form>
                </article>
            @endforeach
        </div>
    @endif
</x-layouts.auth>
