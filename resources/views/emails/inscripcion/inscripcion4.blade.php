<p>&nbsp;</p>
<table style="width: 453.35pt; border-collapse: collapse; border: none; margin-left: 4.8pt; margin-right: 4.8pt;" width="0">
<tbody>
<tr>
<td style="width: 471.55pt; padding: 0cm 5.4pt 0cm 5.4pt;" colspan="3" width="616">
<center><strong><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #002060;"> 
<?php
    $imagen="http://formacioncontinua.pe/img/inscripcion/logo4.png";
?>
<img src={{ $imagen }}></span></strong></center></td>
</tr>
<tr>
<td style="width: 453.35pt; padding: 0cm 5.4pt 0cm 5.4pt;" colspan="3" width="616">
<p style="background: white;"><strong><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #002060;">Hola <?php echo $persona->nombre." ".$persona->paterno; ?>,</span></strong></p>
<p><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">&nbsp;</span></p>
</td>
</tr>
<tr>
<td style="width: 453.35pt; padding: 0cm 5.4pt 0cm 5.4pt;" colspan="3" width="616">
<p style="background: white;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">&iexcl;Bienvenido a Formaci&oacute;n Continua Online del Instituto Telesup! </span></p>
<p style="background: white;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Queremos felicitarte por tomar los primeros pasos en conseguir los conocimientos que te ayudar&aacute;n a mejorar tu nivel econ&oacute;mico.</span></p>
<?php 
    $materia='Seminario';
    $inscripcion=' a Seminario(s)';
    if( trim($cursos[0]->especialidad)!='' ){
        $materia='Curso';
        $inscripcion=' a Especialidad de '.$cursos[0]->especialidad;
    }
    elseif( trim($cursos[0]->tipo_curso)==1 ){
        $materia='Curso';
        $inscripcion=' a Curso(s)';
    }
?>
<p style="background: white;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Te escribimos para confirmar la inscripci&oacute;n {{$inscripcion}}: </span>
    <ol>
        <?php 
        foreach ($cursos as $key => $value) {
            echo "<li style=\"font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;\">".$value->curso."</li>";
        }
        ?>
    </ol>
</p>
</td>
</tr>
<tr>
<td style="width: 453.35pt; padding: 0cm 5.4pt 0cm 5.4pt;" colspan="3" width="616">
<p><strong><span style="font-size: 14.0pt; font-family: 'Helvetica',sans-serif; color: #002060;">Deseamos inf&oacute;rmate c&oacute;mo funciona nuestro modelo</span></strong></p>
</td>
</tr>
<tr>
<td style="width: 453.35pt; padding: 0cm 5.4pt 0cm 5.4pt;" colspan="3" width="616">
<?php
$imagen="http://formacioncontinua.pe/img/inscripcion/img1.png";
//$message->embed($imagen);
?>
<img src={{ $imagen }}></td>
</tr>
<tr>
<td style="width: 282.05pt; padding: 0cm 5.4pt 0cm 5.4pt;" width="355">
<p style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;"> 
<?php 
$imagen="http://formacioncontinua.pe/img/inscripcion/img2.png";
?>
<img src={{ $imagen }}>
</span></p>
<p style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Todo el material que necesitas, incluyendo gu&iacute;as de aprendizaje, Textos, Bibliotecas virtuales y videos de diferentes autores, estar&aacute;n disponibles las 24 horas del d&iacute;a, todos los d&iacute;as del a&ntilde;o, en la plataforma de aprendizaje Online.</span></p>
<p style="text-align: justify; line-height: normal; margin: 0cm 0cm .0001pt 7.1pt;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">&nbsp;</span></p>
</td>
<td style="width: 171.3pt; padding: 0cm 5.4pt 0cm 5.4pt;" colspan="2" width="261">
<?php
$imagen="http://formacioncontinua.pe/img/inscripcion/foto1.jpg";
?>
<img src={{ $imagen }}></td>
</tr>
<!--tr>
<td style="width: 453.35pt; padding: 0cm 5.4pt 0cm 5.4pt;" colspan="3" width="616">
<p style="line-height: normal; margin: 0cm 0cm .0001pt 7.1pt;"><strong><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Paso 1: Acceda a su Plataforma Virtual</span></strong></p>
<p style="margin-bottom: .0001pt; text-indent: -18.0pt; line-height: normal; tab-stops: 14.2pt;"><span style="font-size: 13.0pt; font-family: Symbol; color: #333333;">&middot;<span style="font: 7.0pt 'Times New Roman';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Acceda a su plataforma virtual, all&iacute; encontrar&aacute; los cursos a los que se ha inscrito.</span></p>
<p style="margin-bottom: .0001pt; text-indent: -18.0pt; line-height: normal; tab-stops: 14.2pt;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">&nbsp;</span></p>
<p style="line-height: normal; margin: 0cm 0cm .0001pt 7.1pt;"><strong><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Paso 2: Lea e investigue</span></strong></p>
<p style="margin-bottom: .0001pt; text-indent: -18.0pt; line-height: normal;"><span style="font-size: 13.0pt; font-family: Symbol; color: #333333;">&middot;<span style="font: 7.0pt 'Times New Roman';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">La Gu&iacute;a de aprendizaje virtual, nos da las pautas para el correcto manejo de los tiempos y la metodolog&iacute;a que permita alcanzar las competencias establecidas en el silabo.</span></p>
<p style="margin-bottom: .0001pt; text-indent: -18.0pt; line-height: normal;"><span style="font-size: 13.0pt; font-family: Symbol; color: #333333;">&middot;<span style="font: 7.0pt 'Times New Roman';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">El Silabo del curso establece los contenidos y competencias a alcanzar.</span></p>
<p style="margin-bottom: .0001pt; text-indent: -18.0pt; line-height: normal;"><span style="font-size: 13.0pt; font-family: Symbol; color: #333333;">&middot;<span style="font: 7.0pt 'Times New Roman';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">El texto del curso, contiene el desarrollo acad&eacute;mico del curso, este documento ha sido desarrollado con la precisa metodolog&iacute;a para el aprendizaje virtual.</span></p>
<p style="margin-bottom: .0001pt; text-indent: -18.0pt; line-height: normal;"><span style="font-size: 13.0pt; font-family: Symbol; color: #333333;">&middot;<span style="font: 7.0pt 'Times New Roman';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">A trav&eacute;s de nuestra biblioteca virtual, el participante tendr&aacute; acceso para poder profundizar la investigaci&oacute;n de los temas.&nbsp; </span></p>
<p style="margin-bottom: .0001pt; text-indent: -18.0pt; line-height: normal;"><span style="font-size: 13.0pt; font-family: Symbol; color: #333333;">&middot;<span style="font: 7.0pt 'Times New Roman';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Los videos de otros autores, busca brindar mayor informaci&oacute;n que le permita al participante fortalecer las competencias.</span></p>
<p style="text-align: justify; line-height: normal; margin: 0cm 0cm .0001pt 7.1pt;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">&nbsp;</span></p>
<p style="text-align: justify; line-height: normal; margin: 0cm 0cm .0001pt 7.1pt;"><strong><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Paso 3: Resuelva los casos y problemas propuestos</span></strong></p>
<p style="text-indent: -18.0pt; line-height: normal; margin: 0cm 0cm .0001pt 35.45pt;"><span style="font-size: 13.0pt; font-family: Symbol; color: #333333;">&middot;<span style="font: 7.0pt 'Times New Roman';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">La tarea misma de resolver los casos y problemas propuestos, permite al participante desarrollar el proceso de an&aacute;lisis y por ende interiorizar las competencias del curso.</span></p>
<p style="line-height: normal; margin: 0cm 0cm .0001pt 35.45pt;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">&nbsp;</span></p>
<p style="line-height: normal; margin: 0cm 0cm .0001pt 7.1pt;"><strong><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Paso 4: Realice su examen para la certificaci&oacute;n</span></strong></p>
<p style="margin-bottom: .0001pt; text-indent: -18.0pt; line-height: normal;"><span style="font-size: 13.0pt; font-family: Symbol; color: #333333;">&middot;<span style="font: 7.0pt 'Times New Roman';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Al final del proceso del aprendizaje virtual, a trav&eacute;s de su plataforma virtual el participante rinde el examen de certificaci&oacute;n. La nota lo observar&aacute; en la plataforma de inmediato haya concluido el examen. La nota aprobatoria es 13.</span></p>
<p style="margin-bottom: .0001pt; text-indent: -18.0pt; line-height: normal;"><span style="font-size: 13.0pt; font-family: Symbol; color: #333333;">&middot;<span style="font: 7.0pt 'Times New Roman';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">En un d&iacute;a solo podr&aacute; realizar 2 evaluaciones del mismo curso. Si el curso ya se aprob&oacute;, ya no podr&aacute; rendir nuevamente el examen.</span></p>
</td>
</tr-->
<tr style="height: 148.75pt;">
<td style="width: 171.3pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 148.75pt;" colspan="2" width="261">
<?php
$imagen="http://formacioncontinua.pe/img/inscripcion/foto2.png";
?>
<img src={{ $imagen }}></td>
<td style="width: 282.05pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 148.75pt;" width="355">
<p style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;"> <strong>
<?php
$imagen="http://formacioncontinua.pe/img/inscripcion/img3.png";
?>
<img src={{ $imagen }}></strong></span></p>
<p style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Aprobado el examen de certificaci&oacute;n, obtendr&aacute; su respectivo certificado del curso.</span></p>
<p style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Podr&aacute; certificarte tambi&eacute;n como Especialista una vez que hayas aprobado los cursos requeridos por estos m&oacute;dulos. Estos certificados son respaldados por el Instituto de Educaci&oacute;n Superior Telesup. </span></p>
</td>
</tr>
<!--tr>
<td style="width: 453.35pt; padding: 0cm 5.4pt 0cm 5.4pt;" colspan="3" width="616">
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal;"><strong><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Informaci&oacute;n previa</span></strong></p>
<p style="margin-bottom: .0001pt; text-align: justify; text-indent: -18.0pt; line-height: normal;"><span style="font-size: 13.0pt; font-family: Symbol; color: #333333;">&middot;<span style="font: 7.0pt 'Times New Roman';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">La nota aprobatoria para la obtenci&oacute;n del certificado de los cursos es de 13, Habiendo aprobado el examen, ya est&aacute; expedito para obtener el certificado del curso.</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; text-indent: -18.0pt; line-height: normal;"><span style="font-size: 13.0pt; font-family: Symbol; color: #333333;">&middot;<span style="font: 7.0pt 'Times New Roman';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Al aprobar los ex&aacute;menes de certificaci&oacute;n de los cursos que componen la especializaci&oacute;n, podr&aacute; realizar el pago correspondiente y solicitar el certificado.&nbsp; </span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal;"><strong><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Emisi&oacute;n de certificados Digital de los Cursos aprobados </span></strong></p>
<p style="text-align: justify; line-height: normal; margin: 0cm 0cm .0001pt 14.2pt;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Los certificados en formato digital de los cursos, estar&aacute;n disponibles en su plataforma virtual una vez que el participante haya aprobado el examen de certificaci&oacute;n del curso, esto lo podr&aacute; descargar e imprimir. Este certificado poseer&aacute; el c&oacute;digo QR.</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal;"><strong><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Emisi&oacute;n e impresi&oacute;n de certificados </span></strong></p>
<p style="text-align: justify; line-height: normal; margin: 0cm 0cm .0001pt 15.55pt;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Los certificados impresos y registrados estar&aacute;n listos dentro de las 24 horas de haber aprobado el examen de certificaci&oacute;n del curso.</span><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">&nbsp;</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal;"><strong><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Entrega del Certificado del Curso</span></strong></p>
<p style="text-align: justify; line-height: normal; margin: 0cm 0cm .0001pt 14.2pt;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #002060;">&nbsp;</span></p>
<p style="text-align: justify; line-height: normal; margin: 0cm 0cm .0001pt 14.2pt;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #002060;">Entrega de certificados en nuestro local institucional</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">El participante podr&aacute; recoger sus certificados impresos en Av. Arequipa N&deg; 1938 Lima, de lunes a s&aacute;bado de 09:00 a 20:00 horas. </span></p>
<p style="text-align: justify; line-height: normal; margin: 0cm 0cm .0001pt 14.2pt;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #002060;">&nbsp;</span></p>
<p style="text-align: justify; line-height: normal; margin: 0cm 0cm .0001pt 14.2pt;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #002060;">Entrega a domicilio</span></p>
<p style="text-align: justify; line-height: normal; margin: 0cm 0cm .0001pt 36.8pt;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">El costo para la entrega a domicilio es de S/. 10.0 soles&nbsp; </span></p>
<p style="text-align: justify; line-height: normal; margin: 0cm 0cm .0001pt 14.2pt;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #002060;">&nbsp;</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal;"><strong><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Para Lima metropolitana:</span></strong><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;"> El plazo m&aacute;ximo es de 3 d&iacute;as posterior al registro de su solicitud.</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal;"><strong><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Para provincias (zona urbana): </span></strong><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">El plazo m&aacute;ximo es de 9 d&iacute;as h&aacute;biles, posterior al registro de su solicitud.</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">&nbsp;</span></p>
</td>
</tr-->
<tr>
<td style="width: 304.55pt; padding: 0cm 5.4pt 0cm 5.4pt;" colspan="2" width="376">
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">&nbsp;</span><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">
<?php 
$imagen="http://formacioncontinua.pe/img/inscripcion/img4.png";
?>
<img src={{ $imagen }}></span></p>
<p style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Cuando obtengas el certificado de especialidad, tendr&aacute;n acceso a la bolsa de trabajo de Formaci&oacute;n Continua del Instituto de Educaci&oacute;n Superior Telesup y podr&aacute;n ser presentados por nuestra instituci&oacute;n ante ofertantes de plazas laborales.</span></p>
</td>
<td style="width: 148.8pt; padding: 0cm 5.4pt 0cm 5.4pt;" width="240">
<?php
$imagen="http://formacioncontinua.pe/img/inscripcion/foto3.png";
?>
<img src={{ $imagen }}></td>
</tr>
<!--tr>
<td style="width: 453.35pt; padding: 0cm 5.4pt 0cm 5.4pt;" colspan="3" width="616">
<p style="margin-bottom: .0001pt; line-height: normal;"><strong><span style="font-family: 'Open Sans',sans-serif; color: #c00000; background: white;">&nbsp;</span></strong><strong><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Te entregamos una Carta de Recomendaci&oacute;n</span></strong></p>
<p style="line-height: normal; margin: 0cm 0cm .0001pt 14.2pt;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Completamente gratis, emitiremos una carta de recomendaci&oacute;n, en formato digital, dirigida a la empresa a la que desee postular.</span></p>
<p style="margin-bottom: .0001pt; line-height: normal;"><strong><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">&nbsp;</span></strong><strong><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Apoyamos en la elaboraci&oacute;n del CV de alto impacto</span></strong></p>
<p style="line-height: normal; margin: 0cm 0cm .0001pt 14.2pt;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Completamente gratis, te ayudaremos a elaborar tu CV de alto impacto, el cual contribuir&aacute; a la obtenci&oacute;n del trabajo deseado.</span></p>
</td>
</tr-->
<tr>
<td style="width: 453.35pt; padding: 0cm 5.4pt 0cm 5.4pt;" colspan="3" width="616">
<p style="margin-bottom: .0001pt; line-height: normal;"><strong><span style="font-family: 'Open Sans',sans-serif; color: #c00000; background: white;">&nbsp;</span></strong><strong><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #002060;">Ahora s&iacute;, inicia tu formaci&oacute;n:</span></strong></p>
<p style="margin-bottom: .0001pt; line-height: normal;"><strong><span style="font-family: 'Open Sans',sans-serif; color: #c00000; background: white;">&nbsp;</span></strong><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Tu usuario y contrase&ntilde;a es tu n&uacute;mero de DNI, no olvides que debes de cambiar tu contrase&ntilde;a. </span></p>
</tbody>
</table>
<?php
$local='localhost/pae/public';
if( $_SERVER['SERVER_NAME']=='formacioncontinua.pe' ){
    $local='formacioncontinua.pe';
}
elseif( $_SERVER['SERVER_NAME']=='capa.formacioncontinua.pe' ){
    $local='capa.formacioncontinua.pe';
}
$key=bcrypt($persona->id.'-'.$matricula_id);
?>
<a href="http://{{ $local }}/validar/inscripcion?key={{$key}}&persona={{$persona->id}}&matricula={{$matricula_id}}">
<p style="margin-bottom: .0001pt; line-height: normal;"><strong><span style="font-family: 'Open Sans',sans-serif; color: #c00000; background: white;"> 
<?php
$imagen="http://formacioncontinua.pe/img/inscripcion/img5.png";
?>
<img src={{ $imagen }}>
</span></strong></p>
</a>
</td>
</tr>
</tbody>
</table>
<p><span style="font-size: 13.0pt; line-height: 107%;">&nbsp;</span></p>
