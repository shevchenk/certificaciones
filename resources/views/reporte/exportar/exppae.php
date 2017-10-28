<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="author" content="Jorge Salcedo (Shevchenko)">
        <meta name="description" content="Software Eficiencia de Entrega de Certificados ">
    </head>

    <body>
        <h1 colspan='15'>PAE Matrícula</h1>

        <table border="1">
            <thead>
                <tr style="background-color:#A7C0DC;">
                    <?php
                        for ($i = 0; $i < count($cabecera1); $i++){
                            echo "<th colspan='".$cabecant[$i]."' style='text-align: center;'>".$cabecera1[$i]."</th>";
                        }
                    ?>
                </tr>
                <tr style="background-color:#A7C0DC;">
                    <?php 
                        foreach ( $cabecera2 as $cab){
                            echo "<th style='text-align: center;'>".$cab."</th>";
                        }
                    ?>
                </tr>
            </thead>
            <tbody>
            <?php $cursos=array(); 
            foreach ( $data as $key => $val){
                $ndet=$val["ndet"];
                echo    "<tr>".
                            "<td rowspan='".$ndet."'>".($key+1)."</td>";
                            foreach ( $campos as $ca ){
                                if( $ca=='cursos' AND $ndet>1 ){ 
                                    $cursos=explode("\n",$val["cursos"]);
                                    echo "<td>".$cursos[0]."</td>";
                                }
                                else{ 
                                    echo "<td rowspan='".$ndet."''>".$val[$ca]."</td>";
                                }
                            }
                echo    "</tr>";

                for ($i = 1; $i < $ndet; $i++){
                    echo "<tr>".
                            "<td>".$cursos[$i]."</td>".
                        "</tr>";
                }
            }
            ?>
            </tbody>
        </table>
    </body>
</html>






