<x-layouts.auth title="Criar conta">
    <div class="header">
        <p class="eyebrow">Cadastro</p>
        <h1>Criar conta</h1>
        <p class="description">Informe os dados da empresa e do primeiro usuario.</p>
    </div>

    <form method="POST" action="{{ route('register.store') }}">
        @csrf

        <div class="field">
            <label for="tenant_name">Empresa</label>
            <input id="tenant_name" name="tenant_name" type="text" value="{{ old('tenant_name') }}" autocomplete="organization" autofocus required>
            @error('tenant_name')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="name">Nome</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required>
            @error('name')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="email">E-mail</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="password">Senha</label>
            <input id="password" name="password" type="password" autocomplete="new-password" required>
            @error('password')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Confirmar senha</label>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
        </div>

        <button class="button" type="submit">Criar conta</button>
    </form>

    <p class="switch">
        Ja tem conta?
        <a href="{{ route('login') }}">Entrar</a>
    </p>
</x-layouts.auth>
