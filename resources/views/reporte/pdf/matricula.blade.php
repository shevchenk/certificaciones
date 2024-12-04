<html>
<head>
  @include('reporte.pdf.css.matriculacss')
</head>
@php 
    $color = 'rojo';
    $ficha = 'FICHA DE MATRÍCULA';
    $tipo = 'matriculado';
    if( $estado_mat == 'Pre Aprobado' ){
        $color = 'azul';
        $ficha = 'FICHA DE INSCRIPCIÓN';
        $tipo = 'inscrito';
    }
@endphp
<header class="{{ $color }}">
    <h3>{{ $ficha }} NRO: {{ $id }}</h3>
    @if( $estado_mat == 'Pre Aprobado' )
    <!--h5>En un plazo no mayor a 4 días a partir de hoy se le remitirá la Ficha de matrícula.</h5-->
    @endif
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
        <p>Sr(ta): {{ $persona }}, con DNI: {{ $dni }}, se encuentra {{ $tipo }}(a) en:</p>
        <p class="negrita">{{ $formacion }}</p>
        @foreach( $detalle as $key => $value )
            @php 
            $dvalue = explode("|", $value); 
            $mod = "PRESENCIAL";
            if( $dvalue[5] == '1' ){
                $mod = "VIRTUAL";
            }
            @endphp
            <p><span class="negrita tab1"> {{ $dvalue[0] }} </span><span class="detalle">(Fec-inic: {{ $dvalue[4] }}, Hora inicio: {{ $dvalue[2] }}, Frecuencia: {{ $dvalue[1] }} Local: {{ $lugar_estudio }})</span></p>
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
            <p><span class="negrita">Promoción:</span> {{ $adicional[1] }}</p>
        @endif
        <!--
        <p><span class="negrita">Código promocional:</span>{{ $marketing }}</p>
        <p><span class="negrita">Se enteró de INTUR PERÚ, a través de:</span> {{ $medio_captacion2 }}</p> 
        -->
        <hr>
        <!--p>EL ALUMNO(A) DECLARA CONOCER LO SIGUIENTE:</p-->
        <p>EL INSCRITO DECLARA CONOCER Y ACEPTAR LAS POLÍTICAS DEL PROCESO DE ADMISIÓN</p>
        <ol class="nota">
        1. Se considera alumno matriculado y goza de todos los derechos como tal, aquel que ha realizado el pago de Inscripción,  Matrícula, y Primera Cuota,  asimismo  ha cumplido con presentar todos los documentos solicitados hasta la fecha programada de lo contrario la escuela tendrá la facultad de anular su ingreso y/o matricula. 
        2. Por ningún motivo se devuelven documentos de los estudiantes que ingresan a la escuela.  Dichos documentos forman parte del acervo documentario y estará bajo revisión ante las autoridades competentes de encontrarse falsificación o adulteración la escuela realiza el retiro del estudiante, con solo la mención del hecho. 
        3. Para apertura un aula debe haber 20 estudiantes matriculados como mínimo por programa de estudios, el inicio de clases y los horarios se programan de acuerdo a los estudiantes matriculados y disponibilidad de ambientes, es potestad de la institución hacer la reprogramación de inicio y cambio de horario, frecuencia o fecha, con conocimiento previo de los estudiantes matriculados. 
        4. El estudiante matriculado en fecha extemporánea o especial deberá esperar (7) días después de iniciado las clases para gozar de los derechos como alumno, debiendo estar supeditado a la disponibilidad de las unidades didácticas programadas, ambientes, frecuencia y horario. 
        5. La escuela podrá retener los certificados correspondientes a periodos no pagados. (Art. 2 de la Ley ni 29947). 
        6. El estudiante no podrá matricularse en el ciclo académico siguiente hasta la cancelación de la deuda de sus cuotas respectivas y otros gastos, reconociendo que la falta de pagos genera una mora, gastos administrativos que deberán ser cancelados sin reclamo alguno. 
        7. El estudiante acepta que el pago de las cuotas es de acuerdo al cronograma establecido por la coordinación académica y las cuotas vencidas generarán intereses moratorios (Art. 2 de la Ley n º 29947). Los estudiantes tienen conocimiento que la institución no cobra mensualidades, la institución cobra cuotas. También declara conocer el cronograma de pago de cuotas,  el  número de cuotas puede ser mínimo de  (5) o más.
        8.  Las Cuotas,  los  servicios Administrativos y Académicos no son congeladas, se incrementan anualmente y se adecuan a los incrementos gubernamentales de la UIT (Unidad Impositiva Tributaria). 
        9. El uso de uniforme, carnet o algún medio de identificación es obligatorio para los estudiantes presencial y semipresencial antes de ingresar a las instalaciones de la escuela.
        10. Los estudiantes tienen conocimiento que lo no contemplado en esta Ficha de Inscripción lo podrá ubicar en el Reglamento Institucional que se encuentra publicado en la página Web de la Institución y es de conocimiento Público y transparente. 
        11.  Las promociones que brinda la escuela a los estudiantes son solo con respecto a la primera cuota del 1° ciclo Académico. A partir del pago de la segunda cuota del primer ciclo se aplica la escala vigente de acuerdo a la promoción de cada inicio de clases y del periodo matriculado con respecto al segundo ciclo las Matrículas y Cuotas se adecuan a la escala Vigente. (Se consideran vigentes las promociones que se generen en la fecha de pago.) 
        12. Los estudiantes inscritos y matriculados en las siguientes modalidades,  declaran  tener conocimiento que la Modalidad Semipresencial o Modalidad a Distancia, sus clases se realizaran por medio de entornos virtuales de aprendizaje, las cuales tienen como máximo 50% de los créditos del plan de estudio y el otro 50% se podrá llevar de manera presencial  (Ley N° 30512 y su reglamento)  es potestad y dependencia de la institución definir el horario en donde los estudiantes podrán realizar sus clases presenciales. Por tanto, están obligados a presentarse en la escuela en la frecuencia y horario que la institución programe sus horarios. En la modalidad presencial la institución define el horario en donde los estudiantes podrán realizar sus clases de presenciales. Por tanto, están obligados a presentarse en la escuela en la frecuencia y horario que la institución programe sus horarios.
        13.- Los estudiantes de las carreras profesionales podrán tramites su bachiller y titulo profesional al comunicar su itinerario formativo estando aprobado todos sus créditos correspondientes, en el caso de las estudiantes de las carreras profesionales técnicas estos también podrán tramitar al aprobar todo su itinerario formativo donde podrán tramitar su bachiller técnico y su titulo profesional técnico, debiendo cumplir en ambos casos los requisitos que solicitar la institución.
        14. La escuela en aplicación de la Ley nº 30512 y su reglamento interno, podrá retirar al estudiante, por acto de indisciplina, destrucción o hurto de bienes e inmuebles de la institución, también si el estudiante se encuentra inmerso dentro de actos de terrorismo,  acoso sexual o apología a la violencia en general debidamente comprobados.  
        15. El alumno tiene conocimiento que, podrá solicitar la devolución de su dinero por los motivos que crea convenientes siguiendo el procedimiento indicado por la institución y llenar los formatos correspondientes,  respetando  plazos y encontrarse inmerso en una causal justa. 
        16. La escuela garantiza proteger los datos personales consignados en esta ficha única de trámite en aplicación a la Ley N.º 29733(Ley de protección de datos personales). 
        </ol>
    </div>
</body>
</html>
<!--
    <p class="page-break"></p>
-->