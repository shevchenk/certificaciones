<html>
<head>
  @include('reporte.pdf.css.pruebacss')
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
        <p>La Sr(ta). {{ $persona }}, con DNI: {{ $dni }}, se encuentra matriculada en:</p>
        <p class="negrita">{{ $formacion }}</p>
        @foreach( $detalle as $key => $value )
            @php 
            $dvalue = explode("|", $value); 
            $mod = "PRESENCIAL";
            if( $dvalue[5] == '1' ){
                $mod = "VIRTUAL";
            }
            @endphp
        <p><span class="negrita tab1"> {{ $dvalue[0] }} </span>(Mod: {{ $mod }}, Fec-inic: {{ $dvalue[4] }}, Fec fin: 2023-02-23, Hora inicio: {{ $dvalue[2] }}, Frecuencia: {{ $dvalue[1] }} Local: {{ $bandeja->lugar_estudio }})</p>
        @endfor
        <p class="negrita">Ha realizado los siguientes pagos</p>
        <p class="negrita">Pensión:</p>
        <p><span class="negrita tab1">Cuota 1: </span>(N° de Boleta/N° de Operación: BOL001-005687, Importe: S/229, Fecha: 24-10-2022,Tipo: Transferencia, Banco: Continental) CANCELADO</p>
        <p><span class="negrita tab1">Cuota 2: </span>(Importe: S/ 349, Fecha de vencimiento: 2022-12-23) PENDIENTE</p>
        <p><span class="negrita tab1">Cuota 3: </span>(Importe: S/ 349, Fecha de vencimiento: 2023-01-23) PENDIENTE</p>
        <p><span class="negrita tab1">Cuota 4: </span>(Importe: S/ 349, Fecha de vencimiento: 2023-02-23) PENDIENTE</p>
        <hr>
        <p><span class="negrita">Pagos para el 2do ciclo o módulo:</span> El programa comprende de 3 módulos. Debe de pagar S/ 10 soles por la matrícula al 2do y 3er módulo. El programa comprende de 12 cuotas, a partir de la 2da cuota pagará S/ 349.</p>
        <p><span class="negrita">Promoción de la matrícula:</span> No paga matrícula y la 1ra cuota es de S/ 229</p>
        <p><span class="negrita">Código promocional:</span> ESCOBEDO MACOCHOA ABIGAIL | Vender | INTUR - 583</p>
        <p><span class="negrita">Se enteró de INTUR PERÚ, a través de:</span> Internet - Facebook</p>
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