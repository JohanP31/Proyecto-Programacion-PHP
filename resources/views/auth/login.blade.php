<!DOCTYPE html>
<html lang="es">

@include('layouts.head')

<body>
    <main>
        <div class="container">

            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <a href="{{ route('home.index') }}" class="logo d-flex align-items-center w-auto">
                                    <img src="{{ asset('assets/img/logo.png') }}" alt="">
                                    <span class="d-none d-lg-block">Proyecto de programación</span>
                                </a>
                            </div><!-- End Logo -->

                            <div class="card mb-3">

                                <div class="card-body">

                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Iniciar sesión</h5>
                                        <p class="text-center small">Ingresa tu correo y contraseña para acceder al sistema.</p>
                                    </div>

                                    <form method="POST" action="{{ route('loginPost') }}" class="row g-3">
                                        @csrf
                                        <div class="col-12">
                                            <label for="email" class="form-label">Correo electrónico</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">@</span>
                                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    autocomplete="username" required autofocus>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="password" class="form-label">Contraseña</label>
                                            <input type="password" id="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                autocomplete="current-password" required>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit">Iniciar sesión</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main>

    @include('layouts.footer')

    @include('layouts.scripts')
</body>

</html>
