<script type="text/javascript">
var AjaxProgramacion={
    AgregarEditar:function(evento){
        var data=$("#ModalProgramacionForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Mantenimiento.ProgramacionEM@New2';
        if(AddEdit==0){
            url='AjaxDinamic/Mantenimiento.ProgramacionEM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#ProgramacionForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#ProgramacionForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ProgramacionForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.ProgramacionEM@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalProgramacionForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalProgramacionForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalProgramacionForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalProgramacionForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.ProgramacionEM@EditStatus';
        masterG.postAjax(url,data,evento);
    },
    CargarSucursal:function(evento){
        url='AjaxDinamic/Mantenimiento.SucursalEM@ListSucursalandusuario';
        data={};
        masterG.postAjax(url,data,evento);
    },
    CargarCurso:function(evento){
        url='AjaxDinamic/Mantenimiento.CursoEM@ListCurso';
        data={tipo_curso:1};
        masterG.postAjax(url,data,evento);
    }
};
</script>
