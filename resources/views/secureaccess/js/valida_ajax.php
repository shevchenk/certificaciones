<script type="text/javascript">
var MyselfAjax={
    Editar:function(evento){
        var data=$("#MyselfForm").serialize().split("txt_").join("").split("slct_").join("");
        url='../AjaxDinamic/SecureAccess.ValidaSA@ConfirmarInscripcion';
        masterG.postAjax(url,data,evento);
    },
    Validar:function(evento){
        var data=$("#MyselfForm").serialize().split("txt_").join("").split("slct_").join("");
        url='../AjaxDinamic/SecureAccess.ValidaSA@ValidarMatricula';
        masterG.postAjax(url,data,evento);
    }
};
</script>
