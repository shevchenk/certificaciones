<p><strong>Se realizó su inscripci&oacute;n correctamente</strong></p>
<p><strong>&nbsp;</strong></p>
<p><strong>Estimado(a) {{ $data['persona']->paterno.' '.$data['persona']->materno.', '.$data['persona']->nombre }} </strong></p>
<p>&nbsp;</p>
<p>Reciba usted nuestro cordial saludo a la vez expresarle mis felicitaciones por dar los pasos previos a la obtenci&oacute;n de su grado acad&eacute;micos.</p>
<?php 
    $materia='Seminario';
    $inscripcion=' a Seminario(s):';
    if( trim($data['cursos'][0]->especialidad)!='' ){
        $materia='Curso';
        $inscripcion=' a Especialidad de: '.$data['cursos'][0]->especialidad;
    }
    elseif( trim($data['cursos'][0]->tipo_curso)==1 ){
        $materia='Curso';
        $inscripcion=' a Curso(s) Libre(s):';
    }
?>
<p><strong>Ud se ha inscrito {{$inscripcion}} </strong></p>
<table class="table table-bordered table-striped">
<thead>
    <tr>
        <th>Nro</th>
        <th>{{ $materia }}</th>
    </tr>
</thead>
<tbody>
    <?php
    foreach ($data['cursos'] as $key => $value) {
        echo "<tr>";
        echo    "<td>".($key+1)."</td>";
        echo    "<td>".$value->curso."</td>";
        echo "</tr>";
    }

    $local='localhost/pae/public';
        if( $_SERVER['SERVER_NAME']=='formacioncontinua.pe' ){
            $local='formacioncontinua.pe';
        }
    ?>
</tbody>
</table>
<p><strong>Para finalizar la inscripción se requiere que valide sus datos personales <a href="http://{{ $local }}/validar/inscripcion?key={{$data['key']}}&persona={{$data['persona']->id}}&matricula={{$data['matricula_id']}}">Aquí</a></strong></p>
<p>&nbsp;</p>
<p><strong>Pasos que debe realizar para que hagamos entrega del certificado de asistencia al seminario:</strong></p>
<ol>
<li>Si el seminario es presencial, deber&aacute; asistir a la Av. Arequipa 3565, auditorio 1, primer piso. Deber&aacute; mostrar esta ficha para su ingreso, debe llegar 15 minutos antes de la hora se&ntilde;alada.</li>
<li>Si el seminario es virtual, participe haciendo clic sobre el nombre del seminario.</li>
<li>En ambos casos, realizar un comentario sobre el contenido del seminario, haciendo clic en COMENTE AQU&Iacute;.</li>
<li>Recoja su certificado dos d&iacute;as despu&eacute;s de realizado su comentario, seg&uacute;n el paso 3.</li>
</ol>
<p>Los certificados de los seminarios se recogen en las oficinas de la Direcci&oacute;n de Extensi&oacute;n Universitaria y Responsabilidad Social, sito en la Av. Bolivia 300, de lunes a s&aacute;bados de 8:00 am a 8:00 pm, domingos de 8:00 a 2:00 pm.</p>
<p>Cabe mencionar que el art&iacute;culo 15 del Reglamento de Grados y T&iacute;tulos, especifica que todo alumno para la obtenci&oacute;n de Grado Acad&eacute;mico de Bachiller necesita obligatoriamente asistir como m&iacute;nimo a 10 seminarios programados por la Universidad sean estos presenciales o virtuales, en caso de no hacerlo no podr&aacute; tramitar su grado acad&eacute;mico.</p>
<p><strong>&nbsp;</strong></p>
<p><strong>Mg. Juan Luna G&aacute;lvez</strong></p>
<p><strong>Director</strong></p>
<p><strong>Direcci&oacute;n de Extensi&oacute;n Universitaria y Responsabilidad Social</strong></p>

