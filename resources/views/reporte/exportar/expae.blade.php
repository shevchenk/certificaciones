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

        <table style="border: 1;">
            <thead>
                <tr style="background-color:#A7C0DC;">
                    @for ($i = 0; $i < count($cabecera1); $i++)
                        <th colspan='{{ $cabecant[$i] }}' style="text-align: center;">{{ $cabecera1[$i] }}</th>
                    @endfor
                </tr>
                <tr style="background-color:#A7C0DC;">
                    @foreach ( $cabecera2 as $cab)
                        <th style="text-align: center;">{{ $cab }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            @php $cursos=array(); 
            @endphp
            @foreach ( $data as $key => $val)

                @php $ndet=$val["ndet"]; @endphp
                <tr>
                    <td rowspan="{{ $ndet }}">{{ $key+1 }}</td>
                    @foreach ( $campos as $ca )
                        @php if( $ca=='cursos' AND $ndet>1 ){ 
                            $cursos=explode("\n",$val["cursos"]);
                        @endphp
                        <td>{{ $cursos[0] }}</td>
                        @php
                        }
                        else{ 
                        @endphp
                        <td rowspan="{{ $ndet }}">{{ $val[$ca] }}</td>
                        @php } @endphp
                    @endforeach
                </tr>

                @for ($i = 1; $i < $ndet; $i++)
                <tr>
                    <td>{{ $cursos[$i] }}</td>
                </tr>
                @endfor
            @endforeach
            </tbody>
        </table>
    </body>
</html>






