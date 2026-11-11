<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        @auth
            @if (in_array(mb_strtolower((string) auth()->user()->role), ['admin', 'administrador'], true))
                <!-- VISTA EXCLUSIVA PARA EL ADMINISTRADOR: Solo aparece Gestión de usuarios -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people"></i>
                        <span>Gestión de usuarios</span>
                    </a>
                </li>
            @else
                <!-- VISTA PARA USUARIOS NORMALES / CLIENTES (Intacta) -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.catalog') }}">
                        <i class="bi bi-grid"></i>
                        <span>Catálogo</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('cart.index') }}">
                        <i class="bi bi-cart"></i>
                        <span>Carrito</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('products.index') }}">
                        <i class="bi bi-box-seam"></i>
                        <span>Productos</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('sales.index') }}">
                        <i class="bi bi-receipt"></i>
                        <span>Mis compras</span>
                    </a>
                </li>
            @endif
        @endauth
    </ul>

</aside>