<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

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

        <!-- Opción de Productos para crear (ubicada justo debajo del Carrito) -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('products.index') }}">
                <i class="bi bi-box-seam"></i>
                <span>Productos</span>
            </a>
        </li>

        {{-- // TEMPORALMENTE COMENTADO PARA ENTREGA ACADÉMICA
        @auth
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('sales.index') }}">
                    <i class="bi bi-receipt"></i>
                    <span>Mis compras</span>
                </a>
            </li>
            @if (in_array(mb_strtolower((string) auth()->user()->role), ['admin', 'administrador'], true))
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i><span>Administración</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('admin.categories.index') }}"><i class="bi bi-tags"></i><span>Categorías</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('admin.sales.index') }}"><i class="bi bi-receipt"></i><span>Ventas</span></a>
                </li>
            @endif
        @endauth
        // REACTIVAR EN FUTURAS VERSIONES --}}
    </ul>

</aside>