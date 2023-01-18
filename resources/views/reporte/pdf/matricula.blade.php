<html>
<head>
  @include('reporte.pdf.css.matriculacss')
</head>
@php 
    $color = 'rojo';
    $ficha = 'FICHA DE MATRÍCULA';
    if( $estado_mat == 'Pre Aprobado' ){
        $color = 'azul';
        $ficha = 'FICHA DE INSCRIPCIÓN';
    }
@endphp
<header class="{{ $color }}">
    <h3>{{ $ficha }} NRO: {{ $id }}</h3>
    @if( $estado_mat == 'Pre Aprobado' )
    <h5>En un plazo no mayor a 4 días a partir de hoy se le remitirá la Ficha de matrícula.</h5>
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
        <!--
        <p><span class="negrita">Código promocional:</span>{{ $marketing }}</p>
        <p><span class="negrita">Se enteró de INTUR PERÚ, a través de:</span> {{ $medio_captacion2 }}</p> 
        -->
        <hr>
        <!--p>EL ALUMNO(A) DECLARA CONOCER LO SIGUIENTE:</p-->
        <p>EL INSCRITO DECLARA CONOCER Y ACEPTAR LAS POLÍTICAS DEL PROCESO DE ADMISIÓN</p>
        <ol class="nota">
        1. Se considera alumno matriculado y goza de todos los derechos como tal, aquel que ha realizado el pago de Inscripción, Matrícula,
        Servicios complementarios y Primera Cuota, asimismo ha cumplido con presentar todos los documentos solicitados hasta la fecha
        programada de lo contrario el Instituto tendrá la facultad de anular su ingreso y/o matricula. 2. Por ningún motivo se devuelven
        documentos de los alumnos que ingresan al instituto. Dichos documentos forman parte del acervo documentario y estará bajo revisión
        ante las autoridades competentes de encontrarse falsificación o adulteración el instituto retira al estudiante, con solo la mención del
        hecho. 3. Para apertura un aula debe haber 20 alumnos matriculados como mínimo por programa. El inicio de clases y los horarios se
        programan de acuerdo a los alumnos matriculados y disponibilidad de ambientes, es potestad de la institución hacer la reprogramación
        de inicio y cambio de horario, frecuencia o fecha, con conocimiento previo de los alumnos matriculados. 4. El alumno matriculado en
        fecha extemporánea o especial deberá esperar (7) días después de iniciado las clases para gozar de los derechos como alumno,
        debiendo estar supeditado a la disponibilidad de Asignaturas programadas, ambientes, frecuencia y Horario de Estudio. 5. En tanto el
        alumno no cancele el integro de pago de cuota de pensiones respectivas, el Instituto podrá retendrá los certificados y/o otros
        documentos correspondientes a los periodos no pagados. (Art. 2 de la Ley nº 29947) 6. El alumno no podrá matricularse en el ciclo
        académico siguiente hasta la cancelación de la deuda por pensiones respectivas y otros gastos, reconociendo que la falta de pagos
        genera una mora, gastos administrativos que deberán ser cancelados sin reclamo alguno. 7. El alumno acepta que el pago de las Cuotas
        es de acuerdo al cronograma establecido por Coordinación Académica y las cuotas vencidas generarán intereses moratorios (Art. 2 de la
        Ley n º 29947). Los alumnos tienen conocimiento que la institución no cobra mensualidades, la institución cobra cuotas. También
        declara conocer el cronograma de pago de cuotas, el número de cuotas puede ser mínimo de (5) o más. 8. Las Cuotas, los servicios
        Administrativos y Académicos no son congeladas, se incrementan anualmente y se adecuan a los incrementos gubernamentales de la
        UIT (Unidad Impositiva Tributaria). 9. El uso de uniforme, carnet o algún medio de identificación es obligatorio para los estudiantes
        presencial y semipresencial antes de ingresar a las instalaciones del instituto. 10. Los alumnos tienen conocimiento que lo no
        contemplado en esta Ficha de Inscripción lo podrá ubicar en el Reglamento Académico que se encuentra publicado en la página Web de
        la Institución y es de conocimiento Público y transparente. 11. El Alumno acepta realizar el pago de S/. 35.00 soles por servicios
        complementarios por cada semestre académico, los servicios complementarios se brindarán en horas no académicas. Principalmente
        para la coordinación de eventos y otras actividades. 12. Las promociones que brinda el Instituto a los alumnos son solo con respecto a la
        primera cuota del 1° ciclo Académico. A partir del pago de la segunda cuota del primer ciclo se aplica la escala vigente en el periodo
        matriculado con respecto al segundo ciclo las Matrículas y Cuotas se adecuan a la escala Vigente. (Se consideran vigentes las
        promociones que se generen en la fecha de pago.) 13. Los alumnos inscritos y matriculados en las siguientes modalidades, declaran
        tener conocimiento que: -Modalidad Semipresencial o Distancia: Sus clases se realizaran por medio de entornos virtuales de
        aprendizaje, las cuales tienen como máximo 50% de los créditos del plan de estudio y el otro 50% se podrá llevar de manera presencial
        (Ley nº 30512 y su reglamento) es potestad y dependencia de la institución definir el lugar, ubicación, horario en donde los alumnos
        podrán realizar sus clases presenciales. Por tanto, están obligados a presentarse en el instituto en la frecuencia y hora que la institución
        programe sus horarios. -Modalidad presencial: Sus clases se realizarán por medio de entornos virtuales de aprendizaje las cuales tienen
        como máximo 30% de los créditos del plan de estudio y el otro 70% se podrán llevar de manera presencial acorde (Ley 30512 y su
        reglamento) es potestad y dependencia de la institución definir el lugar, ubicación, horario en donde los alumnos podrán realizar sus
        clases de presenciales. Por tanto, están obligados a presentarse en el instituto en la frecuencia y hora que la institución programe sus
        horarios. 14. Los alumnos inscritos y matriculados en las siguientes modalidades, se comprometen a: -Modalidad Semipresencial o
        Distancia: Reconocer que el 50% de los créditos del plan de estudio se llevan en entornos virtuales de aprendizaje, no obstante, el
        instituto se compromete en brindar sala de estudio, sala de computo, sala de reunión (Solo para alumnos que no cuentan con
        computadora, internet o mecanismos que le ayuden a una absorción rápida de conocimientos de las nuevas tecnológicas académicas o
        no cuenten con los recursos económicos para alquilar equipos y ambientes de aprendizaje). Adicionalmente Asesoramiento práctico y
        teórico por docentes, seminarios, capacitaciones y reforzamiento de cursos (las cuales no son consideradas como aulas o clases de
        estudio, si no como reforzamiento académico para los estudiantes como valor agregado de la institución hacia al alumno) estos
        QUITAR
        adicionales tendrán un costo si lo determina la institución comunicando al estudiante con anticipación. -Modalidad presencial:
        Reconocer que el 30% de los créditos del plan de estudio se llevan en entornos virtuales de aprendizaje, no obstante, el instituto se
        compromete en brindar sala de estudio, sala de computo, sala de reunión (Solo para alumnos que no cuentan con computadora,
        internet o mecanismos que le ayuden a una absorción rápida de conocimientos de las nuevas tecnológicas académicas o no cuenten con
        los recursos económicos para alquilar equipos y ambientes de aprendizaje). Adicionalmente Asesoramiento práctico y teórico por
        docentes, seminarios, capacitaciones y reforzamiento de cursos (las cuales no son consideradas como aulas o clases de estudio, si no
        como reforzamiento académico para los estudiantes como valor agregado de la institución hacia al alumno) estos adicionales tendrán un
        costo si lo determina la institución comunicando al estudiante con anticipación. 15. Los alumnos inscritos y matriculados en la
        modalidad Semipresencial: se comprometen en: brindar sus pruebas, trabajos y exámenes mediante entornos virtuales de aprendizaje,
        las cuales será la única manera académica de verificar los conocimiento y habilidades adquiridas en este proceso, estar al día con el pago
        de sus cuotas, de caso contrario el estudiante transfiere el poder a la institución para suspender la emisión de cualquier documento
        académico solicitado por el alumno. 16. Los alumnos inscritos y matriculados en la modalidad Semipresencial: tienen conocimiento que
        sus clases se realizaran por medio de entornos virtuales de aprendizaje las cuales tiene como máximo 50% de los créditos del plan de
        estudio y el otro 50% se podrán llevar de manera presencial acorde a la Ley nº 30512 y su reglamento (es potestad del instituto definir
        cuales cursos se llevaran bajo entornos virtuales de aprendizaje y otros de forma presencial). 17. Los alumnos inscritos y matriculados en
        la modalidad Presencial: tienen conocimiento que sus clases se realizaran por medio de entornos virtuales de aprendizaje las cuales
        tiene como máximo 30% de los créditos del plan de estudio y el otro 70% se podrán llevar de manera presencial acorde a la Ley nº 30512
        y su reglamento (es potestad del instituto definir cuales cursos se llevaran bajo entornos virtuales de aprendizaje y otros de forma
        presencial). 18. Los alumnos inscritos y matriculados en la modalidad Semipresencial y presencial tienen conocimiento que sus clases
        presenciales se llevaran en la ubicación e instalaciones que el instituto disponga bajo criterio de ambientes, tiempos, sala de cómputo,
        aulas u otros disponibles. 19. Los alumnos inscritos y matriculados en la modalidad Semipresencial tienen conocimiento que pueden
        solicitar a la institución la implementación de un centro de alta capacitación (CAC) cercano a su localidad si es que se reúne la cantidad
        de estudiantes para la implementación del cual, dejando claro que esto es potestad de la institución implementarlo o no. El (CAC) no es
        un centro de estudio o instituto autorizado. 20. Los alumnos inscritos y matriculados en la modalidad Semipresencial tienen
        conocimiento que un centro de alta capacitación (CAC) son ambientes físicos que implementa la institución como apoyo adicional a
        estudiantes que no cuentan con computadora, ambientes y tiempo para coordinar con grupos de trabajo (otros estudiantes) y un
        profesional que brinda asesorías especificas en cursos específicos. Teniendo conocimiento que un (CAC) no es un instituto o centro de
        estudio autorizado. 21. Los alumnos inscritos y matriculados en la modalidad Semipresencial y presencial tienen conocimiento que cada
        periodo académico se realizaran la semana de actualización internacional y nacional, en donde se convoca a todos los estudiantes
        participar de forma obligatoria se considera (Asistencia, practica, pruebas y exámenes). Es exclusividad y potestad de la institución la
        ubicación, fecha y hora de la actividad. 22. Los alumnos inscritos y matriculados en la modalidad Semipresencial y presencial tienen
        conocimiento que debe realizar sus prácticas pre profesionales mediante convenios con empresas o en caso contrario en la institución
        cumpliendo con la Ley nº 30512 un total de (384) Horas. 23. Los alumnos inscritos y matriculados en la modalidad Semipresencial y
        presencial tienen conocimiento que tienen que culminar 120 créditos como mínimo para poder realizar el curso de titulación y proceder
        a la solicitud de grado de bachiller técnico acorde a la Ley nº 30512 y su reglamento. 24. Los alumnos inscritos y matriculados de los
        programas a distancia y otros postulantes; tienen conocimiento que cuentan con el sistema de créditos y que es potestad de la
        institución regular los aspectos académicos. 25. Los alumnos inscritos y matriculados de los programas a distancia y otros postulantes;
        tienen conocimiento que la convalidación o traslado de sus estudios con la institución es potestad de la institución regular los aspectos
        académicos, por lo tanto, la institución no garantiza la convalidación total o parcial de los estudios presentados en el proceso de
        convalidación o traslado. 26. Los alumnos inscritos y matriculados de los programas a distancia y otros postulantes; tienen conocimiento
        que para el proceso de convalidación tienen que estar inscritos en los programas de estudio autorizados, de caso contrario no se
        procede de ninguna forma la convalidación. Tienen que estar inscritos en el periodo académico anterior a la solicitud de proceso de
        convalidación, el proceso de convalidación tiene la duración de (30) días. 27. Los alumnos inscritos y matriculados de los programas a
        distancia y otros postulantes; tienen conocimiento que las acumulaciones de créditos de los programas a distancia no conducen al grado
        o título, al no tener ellos la condición de estudiantes de programas técnico profesionales. El alumno autoriza al instituto en matricularlo
        en los programas de estudios autorizados por el MINEDU, y mantener su inscripción por 12 meses académicos. 28. El alumno declara
        conocer que el instituto cuenta con CAC y centros de idioma a nivel nacional, estos pueden brindar asesorías, seminarios y
        acompañamiento a los estudiantes y oficinas de atención documentaria, pero no es obligatorio su asistencia. 29. El alumno declara
        conocer que el instituto debe matricularlo en los programas de estudio autorizados por el MINEDU en el marco de la ley nº 30512 y su
        reglamento, si este solicita la convalidación por créditos. 30. El instituto en aplicación de la Ley nº 30512 y su reglamento interno, podrá
        retirar al estudiante, por acto de indisciplina, destrucción o hurto de bienes e inmuebles de la institución, también si el estudiante se
        encuentra inmerso dentro de actos de terrorismo, acoso sexual o apología a la violencia en general debidamente comprobados. 31. El
        alumno tiene conocimiento que, podrá solicitar la devolución de su dinero por los motivos que crea convenientes siguiendo el
        procedimiento indicado por la institución y llenar los formatos correspondientes, respetando plazos y encontrarse inmerso en una
        causal justa. 32. El instituto garantiza proteger los datos personales consignados en esta ficha única de trámite en aplicación a la Ley nº
        29733(Ley de protección de datos personales). 33. Todos los alumnos están inscritos - matriculados- reciben educación en el centro de
        capacitación ISAM, los cuales recibirán actualizaciones, seminarios, capacitación durante su formación profesional los cuales podrán
        convalidar cursos con el Institutos diversos.34. Los anuncios donde se indica “2 años académicos”, quiere decir que no considera
        vacaciones ni feriados.
                    <!--<li>Es de carácter obligatorio que el alumno en cada proceso de matrícula y/o ratificación deberá actualizar sus datos en el Q10 para la matrícula.</li>
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
            <li>No hay devolución de dinero por ninguna razón o motivo</li>-->
        </ol>
    </div>
</body>
</html>
<!--
    <p class="page-break"></p>
-->