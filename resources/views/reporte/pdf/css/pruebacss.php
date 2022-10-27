<style>
    body{
        font-family: sans-serif;
        font-size: 14px;
        margin-top: 60px;
    }
    @page {
        margin: 40px 40px;
    }
    header { 
        position: fixed;
        left: 0px;
        top: 0px;
        right: 0px;
        height: 60px;
        width: 70%;
        
        background-color: #20124D;
        color: #FEFFFF;
        text-align: center;
    }
    header h1,header h2,header h3{
        margin: 10px 0 5px 0;
    }
    header h4,header h5{
        margin: 0 0 0 0;
    }
    footer {
        position: fixed;
        left: 0px;
        bottom: -10px;
        right: 0px;
        height: 40px;
        border-bottom: 2px solid #ddd;
    }
    footer .page:after {
        content: counter(page);
    }
    footer table {
        width: 100%;
    }
    footer p {
        text-align: right;
    }
    footer .izq {
        text-align: left;
    }
    .page-break {
        page-break-after: always;
    }
    .logo{
        position: fixed;
        top: -10px;
        right: 0px;
        width: 150px;
        height: 80px;
    }
    .content{
        /*margin-top: 70px;*/
    }
    .negrita{
        font: bold;
    }
    .tab1{
        padding-left: 2em;
    }
    .tab2{
        padding-left: 4em;
    }
    .tab3{
        padding-left: 6em;
    }
    ol.nota{
        font-size: 11px;
    }
</style>