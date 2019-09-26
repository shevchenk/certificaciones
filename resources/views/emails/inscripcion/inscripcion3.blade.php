<p>&nbsp;</p>
<table style="width: 453.35pt; border-collapse: collapse; border: none; margin-left: 4.8pt; margin-right: 4.8pt;" width="0">
<tbody>
<tr>
<td style="width: 471.55pt; padding: 0cm 5.4pt 0cm 5.4pt;" colspan="3" width="616">
<center><strong><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #002060;"> 
<?php
    $imagen="http://formacioncontinua.pe/img/inscripcion/logo3.png";
?>
<img style="width: 600px;" src={{ $imagen }}></span></strong></center></td>
</tr>
<tr>
<td style="width: 453.35pt; padding: 0cm 5.4pt 0cm 5.4pt;" colspan="3" width="616">
<p style="background: white;"><strong><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #002060;">Hola <?php echo $persona->nombre." ".$persona->paterno; ?>,</span></strong></p>
<p><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">&nbsp;</span></p>
</td>
</tr>
<tr>
<td style="width: 453.35pt; padding: 0cm 5.4pt 0cm 5.4pt;" colspan="3" width="616">
<p style="background: white;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">&iexcl;Bienvenido a Formaci&oacute;n Continua Online de la Escuela Internacional de Posgrado! </span></p>
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
<td style="width: 282.05pt; padding: 0cm 5.4pt 0cm 5.4pt;text-align: justify;" width="355">
<p style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;"> 
<?php 
$imagen="http://formacioncontinua.pe/img/inscripcion/img2.png";
?>
<img src={{ $imagen }}>
</span></p>
<p style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">
    Todo el material que necesitas, incluyendo Guías de Aprendizaje, Textos, y videos de diferentes autores, estarán disponibles las 24 horas del día, todos los días del año, en la plataforma de aprendizaje Online
</span></p>
<p style="text-align: justify; line-height: normal; margin: 0cm 0cm .0001pt 7.1pt;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">&nbsp;</span></p>
</td>
<td style="width: 171.3pt; padding: 0cm 5.4pt 0cm 5.4pt;" colspan="2" width="261">
<?php
$imagen="http://formacioncontinua.pe/img/inscripcion/foto1.jpg";
?>
<img style="width: 95%;" src={{ $imagen }}></td>
</tr>
<tr style="height: 148.75pt;">
<td style="width: 171.3pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 148.75pt;" colspan="2" width="261">
<?php
$imagen="http://formacioncontinua.pe/img/inscripcion/foto3.png";
?>
<img style="width: 95%;" src={{ $imagen }}></td>
<td style="width: 282.05pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 148.75pt;text-align: justify;" width="355">
<p style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;"> <strong>
<?php
$imagen="http://formacioncontinua.pe/img/inscripcion/img3.png";
?>
<img src={{ $imagen }}></strong></span></p>
<p style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">
    Aprobado el examen de certificación, obtendrá su respectivo certificado del curso. Estos certificados son respaldados por la Escuela Internacional de Posgrado. 
</span></p>
</td>
</tr>
<tr>
<td style="width: 304.55pt; padding: 0cm 5.4pt 0cm 5.4pt;text-align: justify;" colspan="2" width="376">
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">&nbsp;</span><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">
<?php 
$imagen="http://formacioncontinua.pe/img/inscripcion/img4.png";
?>
<img src={{ $imagen }}></span></p>
<p style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">
    Cuando obtengas el certificado del curso, tendrán acceso a la bolsa de trabajo de Formación Continua de la Escuela Internacional de Posgrado y podrán ser presentados por nuestra institución ante ofertantes de plazas laborales.
</span></p>
</td>
<td style="width: 148.8pt; padding: 0cm 5.4pt 0cm 5.4pt;" width="240">
<?php
$imagen="http://formacioncontinua.pe/img/inscripcion/foto2.png";
?>
<img style="width: 95%;" src={{ $imagen }}></td>
</tr>
<tr>
<td style="width: 453.35pt; padding: 0cm 5.4pt 0cm 5.4pt;" colspan="3" width="616">
<p style="margin-bottom: .0001pt; line-height: normal;"><strong><span style="font-family: 'Open Sans',sans-serif; color: #c00000; background: white;">&nbsp;</span></strong><strong><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #002060;">Ahora s&iacute;, inicia tu formaci&oacute;n:</span></strong></p>
<p style="margin-bottom: .0001pt; line-height: normal;"><strong><span style="font-family: 'Open Sans',sans-serif; color: #c00000; background: white;">&nbsp;</span></strong><span style="font-size: 13.0pt; font-family: 'Helvetica',sans-serif; color: #333333;">Tu usuario y contrase&ntilde;a es tu n&uacute;mero de DNI, no olvides que debes de cambiar tu contrase&ntilde;a. </span></p>
</tbody>
</table>
<?php
$local='localhost/pae/public';
if( $_SERVER['SERVER_NAME']=='formacioncontinua.pe' ){
    $local='miaula.formacioncontinua.pe';
}
elseif( $_SERVER['SERVER_NAME']=='capa.formacioncontinua.pe' ){
    $local='capamiaula.formacioncontinua.pe';
}
$key=bcrypt($persona->id.'-'.$matricula_id);
/*<a href="http://{{ $local }}/validar/inscripcion?key={{$key}}&persona={{$persona->id}}&matricula={{$matricula_id}}">*/
?>
<a href="http://{{ $local }}">
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
