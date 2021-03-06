<?php use App\Models\Mantenimiento\Privilegio; ?>
<ul class="sidebar-menu">
        @if (isset($menu))
            {{ $cargo='' }}
            @foreach ( $menu as $key => $val)
                <li class="treeview">
                    <a href="#">
                        <i class="{{ $val->icono }}"></i>
                        <span>{{ $val->menu }}</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        @php $opciones=explode('||',$val->opciones); @endphp
                        @foreach ( $opciones as $op )
                        @php $dop=explode('|',$op); @endphp
                        <li><a href="{{ $dop[1] }}"><i class="{{ $dop[2] }}"></i> {{ $dop[0] }}</a></li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        @endif
    <?php 
        $privilegio=Privilegio::find(Auth::user()->privilegio_id);
    ?>
    @if ( $activaraula AND ($privilegio->cargo=='2' OR $privilegio->cargo=='3') )
    <li class="treeview">
        <a href="{{ $aula->aulaurl }}/ReportDinamic/Api.ApiCurso@Validaracceso?id={{ $aula->idaula }}&dni={{ $dni }}&cargo={{ $menu[0]->cargo }}" target="__blank">
            <i class="fa fa-university"></i> <span>Mis Cursos</span>
        </a>
    </li>
    @endif
    @if ( $privilegio->cargo=='1' )
    <li class="treeview">
        <a href="{{ $aula->aulaurl }}/ReportDinamic/Api.ApiCurso@Validaracceso?id={{ $aula->idaula }}&dni={{ $dni }}&cargo={{ $menu[0]->cargo }}&empresa_id={{ Auth::user()->empresa_id }}" target="__blank">
            <i class="fa fa-university"></i> <span>Adm. Aula</span>
        </a>
    </li>
    @endif
    @if ( Auth::user()->privilegio_id==1 )
        @php $texto='Activar Envio Email'; 
            if( session('validar_email')==1 ){
                $texto='Desactivar Envio Email';
            }
        @endphp
    <li class="treeview">
        <a href="validar/email">
            <i class="fa fa-envelope"></i> <span>{{ $texto }}</span>
        </a>
    </li>
    @endif
    <li class="treeview">
        <a href="#">
            <i class="fa fa-user-secret"></i> <span>Mis datos</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="secureaccess.myself"><i class="fa fa-lock"></i> Datos Personales </a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="salir">
            <i class="fa fa-undo"></i> <span>Salir</span>
        </a>
    </li>
</ul>
