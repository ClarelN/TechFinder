@extends('template')

@section('main')
<main class="flex-grow-1 d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="card shadow-sm" style="width: 100%; max-width: 440px;">
        <div class="card-header bg-dark text-white text-center py-3">
            <h4 class="mb-0 fw-bold">
                <span style="color:#0dcaf0;">TechFinder</span> — Connexion
            </h4>
        </div>
        <div class="card-body p-4">

            {{-- Erreur globale --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                {{-- Rôle --}}
                <div class="mb-3">
                    <label for="role_user" class="form-label fw-semibold">
                        Rôle <span class="text-danger">*</span>
                    </label>
                    <select id="role_user"
                            name="role_user"
                            class="form-select @error('role_user') is-invalid @enderror"
                            required>
                        <option value="">— Sélectionner un rôle —</option>
                        <option value="admin"      {{ old('role_user') === 'admin'      ? 'selected' : '' }}>Admin</option>
                        <option value="technicien" {{ old('role_user') === 'technicien' ? 'selected' : '' }}>Technicien</option>
                        <option value="client"     {{ old('role_user') === 'client'     ? 'selected' : '' }}>Client</option>
                    </select>
                    @error('role_user')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Login --}}
                <div class="mb-3">
                    <label for="login_user" class="form-label fw-semibold">
                        Login <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           id="login_user"
                           name="login_user"
                           value="{{ old('login_user') }}"
                           class="form-control @error('login_user') is-invalid @enderror"
                           placeholder="Votre identifiant"
                           autofocus
                           required>
                    @error('login_user')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Mot de passe --}}
                <div class="mb-4">
                    <label for="password_user" class="form-label fw-semibold">
                        Mot de passe <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="password"
                               id="password_user"
                               name="password_user"
                               class="form-control @error('password_user') is-invalid @enderror"
                               placeholder="••••••••"
                               required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword" tabindex="-1">
                            <i id="eyeIcon">👁</i>
                        </button>
                        @error('password_user')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-dark w-100 fw-semibold">
                    Se connecter
                </button>
            </form>
        </div>
    </div>
</main>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.getElementById('password_user');
        input.type = input.type === 'password' ? 'text' : 'password';
    });
</script>
@endsection
