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
        <p>01 de setiembre del 2022</p>
        <br>
        <p>La Srta. PRUEBA PRUEBA XIOMARA, con DNI: 78569412, se encuentra matriculada en:</p>
        <p class="negrita">AVIACIÓN COMERC. Y GEST. TURÍSTICA 2022</p>
        <p><span class="negrita tab1">AVIACIÓN COMERC. Y GEST. TURÍSTICA NOV 2022 </span>(Mod: PRESENCIAL, Fec-inic: 2022-11-23, Fec fin: 2023-02-23, Hora inicio: 17:00:00-21:00:00, Frecuencia: LU,MI Local: LIMA - SAN ISIDRO)</p>
        <p class="negrita">Ha realizado los siguientes pagos</p>
        <p class="negrita">Pensión:</p>
        <p><span class="negrita tab1">Cuota 1: </span>(N° de Boleta/N° de Operación: BOL001-005687, Importe: S/229, Fecha: 24-10-2022,Tipo: Transferencia, Banco: Continental) CANCELADO</p>
        <p><span class="negrita tab1">Cuota 2: </span>(Importe: S/ 349, Fecha de vencimiento: 2022-12-23) PENDIENTE</p>
        <p><span class="negrita tab1">Cuota 3: </span>(Importe: S/ 349, Fecha de vencimiento: 2023-01-23) PENDIENTE</p>
        <p><span class="negrita tab1">Cuota 4: </span>(Importe: S/ 349, Fecha de vencimiento: 2023-02-23) PENDIENTE</p>
        <hr>
    </div>
</body>
</html>
<!--
    <p class="page-break"></p>
-->