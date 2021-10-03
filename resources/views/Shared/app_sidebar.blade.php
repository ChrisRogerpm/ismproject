<ul class="kt-menu__nav ">
    <li class="kt-menu__item"><a href="{{route('CentroOperativo.Listar')}}" class="kt-menu__link ">
            <i class="kt-menu__link-icon la fas fa-building"></i>
            <span class="kt-menu__link-text">CENTRO OPERATIVOS</span></a>
    </li>
    <li class="kt-menu__item"><a href="{{route('Gestor.Listar')}}" class="kt-menu__link ">
            <i class="kt-menu__link-icon la fas fa-user-tag"></i>
            <span class="kt-menu__link-text">GESTORES</span></a>
    </li>
    <li class="kt-menu__item"><a href="{{route('Bonificacion.Listar')}}" class="kt-menu__link ">
            <i class="kt-menu__link-icon la fas fa-comment-dollar"></i>
            <span class="kt-menu__link-text">BONIFICACIONES</span></a>
    </li>
    <li class="kt-menu__item"><a href="{{route('Comision.Listar')}}" class="kt-menu__link ">
            <i class="kt-menu__link-icon la fas fa-comment-dollar"></i>
            <span class="kt-menu__link-text">COMISIONES</span></a>
    </li>
    <li class="kt-menu__item"><a href="{{route('Cliente.Listar')}}" class="kt-menu__link ">
            <i class="kt-menu__link-icon la fas fa-users"></i>
            <span class="kt-menu__link-text">CLIENTES</span></a>
    </li>
    <li class="kt-menu__item"><a href="{{route('Supervisor.Listar')}}" class="kt-menu__link ">
            <i class="kt-menu__link-icon la fas fa-users"></i>
            <span class="kt-menu__link-text">SUPERVISORES</span></a>
    </li>
    <li class="kt-menu__item"><a href="{{route('Mesa.Listar')}}" class="kt-menu__link">
            <i class="kt-menu__link-icon la fas fa-table"></i>
            <span class="kt-menu__link-text">MESAS</span></a>
    </li>
    <li class="kt-menu__item"><a href="{{route('Linea.Listar')}}" class="kt-menu__link">
            <i class="kt-menu__link-icon la fas fa-table"></i>
            <span class="kt-menu__link-text">LINEAS</span></a>
    </li>
    <li class="kt-menu__item"><a href="{{route('Ruta.Listar')}}" class="kt-menu__link ">
            <i class="kt-menu__link-icon la fas fa-table"></i>
            <span class="kt-menu__link-text">RUTAS</span></a>
    </li>
    <li class="kt-menu__item"><a href="{{route('Producto.Listar')}}" class="kt-menu__link ">
            <i class="kt-menu__link-icon la fas fa-table"></i>
            <span class="kt-menu__link-text">PRODUCTOS</span></a>
    </li>
    <li class="kt-menu__item"><a href="{{route('Pedido.Listar')}}" class="kt-menu__link ">
            <i class="kt-menu__link-icon la fas fa-table"></i>
            <span class="kt-menu__link-text">PEDIDO</span></a>
    </li>
    <li class="kt-menu__item  kt-menu__item--submenu">
        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
            <i class="kt-menu__link-icon la fas fa-table"></i>
            <span class="kt-menu__link-text">REPORTES</span>
            <i class="kt-menu__ver-arrow la la-angle-right"></i>
        </a>
        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
            <ul class="kt-menu__subnav">
                <li class="kt-menu__item " aria-haspopup="true">
                    <a href="{{route('Reporte.ProductoMasVendido')}}" class="kt-menu__link ">
                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                            <span></span>
                        </i>
                        <span class="kt-menu__link-text">PRODUCTO MÁS VENDIDO</span>
                    </a>
                </li>
                <li class="kt-menu__item " aria-haspopup="true">
                    <a href="{{route('Reporte.NroPedidoMasVendido')}}" class="kt-menu__link ">
                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                            <span></span>
                        </i>
                        <span class="kt-menu__link-text">NRO PEDIDO MÁS VENDIDO</span>
                    </a>
                </li>
                <li class="kt-menu__item " aria-haspopup="true">
                    <a href="{{route('Reporte.ComisionGestores')}}" class="kt-menu__link ">
                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                            <span></span>
                        </i>
                        <span class="kt-menu__link-text">COMISIONES GESTORES</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
    <li class="kt-menu__item">
        <a href="{{route('Conversion.Pedido')}}" class="kt-menu__link ">
            <i class="kt-menu__link-icon la fas fa-cog"></i>
            <span class="kt-menu__link-text">CONVERSIÓN DE ARCHIVO PEDIDO</span>
        </a>
    </li>
</ul>
