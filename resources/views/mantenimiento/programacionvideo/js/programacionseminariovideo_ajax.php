<script type="text/javascript">
var AjaxProgramacion={
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#ProgramacionForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        $("#ProgramacionForm").append("<input type='hidden' value='1' name='estado'>");
        data=$("#ProgramacionForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ProgramacionForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.ProgramacionEM@Load';
        masterG.postAjax(url,data,evento);
    },
    RegistrarArchivo:function(evento){
        var data=$("#ModalArchivoForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Mantenimiento.ProgramacionEM@RegistrarArchivo';
        masterG.postAjax(url,data, evento);
    },
};
</script>
