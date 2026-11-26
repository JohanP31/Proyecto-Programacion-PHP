<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        @auth
            @php
                $userRole = mb_strtolower(trim((string) auth()->user()->role));
                $isAdministrator = in_array($userRole, ['admin', 'administrador'], true);
                $isCashier = $userRole === 'cajero';
            @endphp

            @if ($isAdministrator)
                <li class="nav-heading">Administración</li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Panel de administración</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people"></i>
                        <span>Gestión de usuarios</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.products.index') }}">
                        <i class="bi bi-box-seam"></i>
                        <span>Administrar productos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.categories.index') }}">
                        <i class="bi bi-tags"></i>
                        <span>Administrar categorías</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.sales.index') }}">
                        <i class="bi bi-receipt"></i>
                        <span>Historial de ventas</span>
                    </a>
                </li>
                <li class="nav-heading">Tienda</li>
            @endif

            <li class="nav-item">
                <a class="nav-link" href="{{ route('products.catalog') }}">
                    <i class="bi bi-grid"></i>
                    <span>Catálogo</span>
                </a>
            </li>

            @if ($isCashier)
                <li class="nav-heading">Consultas</li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.categories.index') }}">
                        <i class="bi bi-tags"></i>
                        <span>Categorías disponibles</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('sales.index') }}">
                        <i class="bi bi-receipt"></i>
                        <span>Historial de ventas</span>
                    </a>
                </li>
            @else
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