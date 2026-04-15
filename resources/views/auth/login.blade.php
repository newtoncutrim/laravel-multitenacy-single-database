<x-layouts.auth title="Entrar">
    <div class="header">
        <p class="eyebrow">Acesso</p>
        <h1>Entrar na conta</h1>
        <p class="description">Use seu e-mail e senha para continuar.</p>
    </div>

    <form method="POST" action="{{ route('login.store') }}">
        @csrf

        <div class="field">
            <label for="email">E-mail</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" autofocus required>
            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="password">Senha</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
            @error('password')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <label class="remember">
            <input name="remember" type="checkbox" value="1">
            Manter conectado
        </label>

        <button class="button" type="submit">Entrar</button>
    </form>

    <p class="switch">
        Ainda nao tem conta?
        <a href="{{ route('register') }}">Criar conta</a>
    </p>
</x-layouts.auth>
