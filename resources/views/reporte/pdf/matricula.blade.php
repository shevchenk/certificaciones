<html>
<head>
  @include('reporte.pdf.css.matriculacss')
</head>
<header>
    <h3>FICHA DE PRE - MATRÍCULA NRO: {{ $id }}</h3>
    <h5>En un plazo no mayor a 4 días a partir de hoy se le remitirá la Ficha de matrícula.</h5>
    <img class="logo" src="{{ $url_logo }}"></img>
</header>
<footer>
    <table>
        <tr>
        <td>
            <p class="izq">
                {{ $empresa }}
            </p>
        </td>
        <td>
            <p class="page">
            Página
            </p>
        </td>
        </tr>
    </table>
</footer>
<body>
    <div class="content">
        <p class="negrita">{{ $fecha }}</p>
        <p>Sr(ta): {{ $persona }}, con DNI: {{ $dni }}, se encuentra matriculado(a) en:</p>
        <p class="negrita">{{ $formacion }}</p>
        @foreach( $detalle as $key => $value )
            @php 
            $dvalue = explode("|", $value); 
            $mod = "PRESENCIAL";
            if( $dvalue[5] == '1' ){
                $mod = "VIRTUAL";
            }
            @endphp
            <p><span class="negrita tab1"> {{ $dvalue[0] }} </span><span class="detalle">(Mod: {{ $mod }}, Fec-inic: {{ $dvalue[4] }}, Hora inicio: {{ $dvalue[2] }}, Frecuencia: {{ $dvalue[1] }} Local: {{ $lugar_estudio }})</span></p>
        @endforeach
        <p class="negrita">Ha realizado los siguientes pagos:</p>
        @if( $nro_pago_inscripcion != '' )
            <p><span class="negrita tab1">Inscripción</span>(N° de Boleta/N° de Operación: <span class="negrita">{{ $nro_pago_inscripcion }}</span>, Importe: <span class="negrita">S/{{ $monto_pago_inscripcion }}</span>, Tipo: <span class="negrita">{{ $tipo_pago_inscripcion }}</span>)</p>
        @endif
        @if( $nro_pago_matricula != '' )
            <p><span class="negrita tab1">Matrícula</span>(N° de Boleta/N° de Operación: <span class="negrita">{{ $nro_pago_matricula }}</span>, Importe: <span class="negrita">S/{{ $monto_pago_matricula }}</span>, Tipo: <span class="negrita">{{ $tipo_pago_matricula }}</span>)</p>
        @endif
        @if( $nro_pago_promocion != '' )
            <p><span class="negrita tab1">Promoción</span>(N° de Boleta/N° de Operación: <span class="negrita">{{ $nro_pago_promocion }}</span>, Importe: <span class="negrita">S/{{ $monto_pago_promocion }}</span>, Tipo: <span class="negrita">{{ $tipo_pago_promocion }}</span>)</p>
        @endif
        @if( isset($nombre_pago[0]) AND trim($nombre_pago[0]) != '' )
            @foreach( $nombre_pago as $key => $value )
                <p><span class="negrita tab1">{{ $value }}</span>(N° de Boleta/N° de Operación: <span class="negrita">{{ $nro_pago[$key] }}</span>, Importe: <span class="negrita">S/{{ $monto_pago[$key] }}</span>, Tipo: <span class="negrita">{{ $tipo_pago[$key] }}</span>)</p>
            @endforeach
        @endif
        <hr>
        <p class="negrita">Pagos pendientes:</p>
        @if( isset($pagos[0]->matricula_id) )
            @php $total = 0; @endphp
            @foreach( $pagos as $key => $value )
                @php
                    $ds = '';
                    $importe = 0;
                    $comprometido = 0;
                    $pagado = 0;
                    if( trim($value->saldo)!='' ){
                        $ds = $value->curso;
                        $importe = $value->saldo*1;
                        $comprometido = $value->precio*1;
                    }
                    else{
                        $comprometido = $value->monto_cronograma*1;
                    }

                    if( trim($value->salcd)!='' && $value->tipo_matricula == 1 ){
                        $ds = $value->cuotacd.' / FV:'.$value->fecha_cronograma;
                        $importe = $value->salcd*1;
                    }
                    else if( trim($value->cuota_cronograma)!='' && $value->tipo_matricula == 1){
                        $ds = $value->cuota_cronograma.' / FV:'.$value->fecha_cronograma;
                        $importe = $value->monto_cronograma*1;
                    }
                    else if( trim($value->salcd)!='' && $value->tipo_matricula == 2 ){
                        $ds = $value->cuotacd;
                        $importe = $value->salcd*1;
                    }
                    else if( trim($value->cuota_cronograma)!='' && $value->tipo_matricula == 2){
                        $ds = $value->cuota_cronograma;
                        $importe = $value->monto_cronograma*1;
                    }

                    

                    $pagado = $comprometido - $importe;
                    
                    if( $key == 0 ){
                        if( trim($value->salsi)!='' ){
                            echo "<p><span class='negrita tab1'>Inscripción</span>(Precio: <span class='negrita'>S/".trim($value->presi)."</span>, Monto pagado: <span class='negrita'>S/".round(($value->presi*1 - $value->salsi*1), 2)."</span>, Monto pendiente: <span class='negrita danger'>S/".trim($value->salsi)."</span>)</p>";
                            $total+= trim($value->salsi)*1;
                        }

                        if( trim($value->salsm)!='' ){
                            echo "<p><span class='negrita tab1'>Matricula</span>(Precio: <span class='negrita'>S/".trim($value->presm)."</span>, Monto pagado: <span class='negrita'>S/".round(($value->presm*1 - $value->salsm*1), 2)."</span>, Monto pendiente: <span class='negrita danger'>S/".trim($value->salsm)."</span>)</p>";
                            $total+= trim($value->salsm)*1;
                        }
                    }

                    if( $importe*1>0 ){
                        echo "<p><span class='negrita tab1'>".trim($ds)."</span>(Precio: <span class='negrita'>S/".round($comprometido, 2)."</span>, Monto pagado: <span class='negrita'>S/".round($pagado, 2)."</span>, Monto pendiente: <span class='negrita danger'>S/".round($importe, 2)."</span>)</p>";
                        $total += trim($importe)*1;
                    }
                @endphp
            @endforeach
            @php
                if( $total*1 > 0 ){
                    echo "<p class='tab1' style='background-color: #F8C269;'>MONTO TOTAL PENDIENTE S/".round($total,2)."</p>";
                }
                else{
                    echo "<p class='tab1'>.::No se registran pagos pendientes::.</p>";
                }
            @endphp
        @else
            <p class="tab1">.::No se registran pagos pendientes::.</p>
        @endif
        <hr>
        @if( $adicional[0] != '' )
            <p><span class="negrita">Pagos para el 2do ciclo o módulo:</span> {{ $adicional[0] }} </p>
        @endif
        @if( $adicional[1] != '' )
            <p><span class="negrita">Promoción de la matrícula:</span> {{ $adicional[1] }}</p>
        @endif
        <p><span class="negrita">Código promocional:</span>{{ $marketing }}</p>
        <p><span class="negrita">Se enteró de INTUR PERÚ, a través de:</span> {{ $medio_captacion2 }}</p>
        <hr>
        <p>EL ALUMNO(A) DECLARA CONOCER LO SIGUIENTE:</p>
        <ol class="nota">
            <li>Es de carácter obligatorio que el alumno en cada proceso de matrícula y/o ratificación deberá actualizar sus datos en el Q10 para la matrícula.</li>
            <li>Se considera alumno matriculado y goza de todos los derechos como tal, aquel que ha realizado el pago de la matrícula y pago de la primera cuota como mínimo.</li>
            <li>En tanto el alumno no cancele el íntegro de pago de sus pensiones educativas, el Instituto retendrá los Certificados y documentos académicos correspondientes al semestre del que se tiene deuda, conforme a la Ley Nro 29947.</li>
            <li>Las pensiones no pagadas generarán intereses moratorios, conforme a la Ley Nro 29947. Las moras generadas por incumplimiento de pago se establecen de acuerdo al contrato con el Banco. Las pensiones no son congeladas, se adecúan a los incrementos gubernamentales de la UIT (Unidad Impositiva Tributaria) anualmente.</li>
            <li>Todos los servicios administrativos y académicos, incluidos los de Grados, Títulos, Certificados u otros no son congeladas, se incrementan anualmente(incrementos gubernamentales de la UIT).</li>
            <li>Los pagos de pensiones educativas están programados de acuerdo al Calendario Académico por semestre y es entregado a los alumnos al finalizar el semestre académico anterior. UNA VEZ REALIZADO EL PAGO, NO HAY DEVOLUCIÓN DE DINERO.</li>
            <li>El alumno NO podrá matricularse en el ciclo académico siguiente hasta la cancelación de la deuda total por pensiones educativas.</li>
            <li>La NULIDAD DE Matrícula conlleva a la exclusión del alumno de registros y actas del semestre, y la realiza de oficio la Oficina de Registros Académicos, notificando al alumno para su descargo y posterior decisión del director.</li>
            <li>Las Promociones en las escalas que brinda el Instituto a los alumnos es solo en el Primer ciclo académico. A partir del II ciclo las matrículas y cuotas se adecúan a la escala vigente. Asimismo, el alumno solo se acoge a una sola promoción. En las escalas preferentes no hay descuentos.</li>
            <li>Lo no contemplado en la presente ficha de inscripción, lo podrá ubicar en el reglamento académico, publicado en la plataforma Q10 y que es de conocimiento de todo el alumnado.</li>
            <li>El alumno está de acuerdo con lo descrito y tiene conocimiento de todo lo ofertado bajo nuestra área de ventas.</li>
            <li>No hay devolución de dinero por ninguna razón o motivo</li>
        </ol>
    </div>
</body>
</html>
<!--
    <p class="page-break"></p>
-->