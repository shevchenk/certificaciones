<script type="text/javascript">
var AjaxMedioPublicitario={
    AgregarEditar:function(evento){
        var data=$("#ModalMedioPublicitarioForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Mantenimiento.MedioPublicitarioMA@New';
        if(AddEdit==0){
            url='AjaxDinamic/Mantenimiento.MedioPublicitarioMA@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#MedioPublicitarioForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#MedioPublicitarioForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#MedioPublicitarioForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.MedioPublicitarioMA@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalMedioPublicitarioForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalMedioPublicitarioForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalMedioPublicitarioForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalMedioPublicitarioForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.MedioPublicitarioMA@EditStatus';
        masterG.postAjax(url,data,evento);
    },
};
</script>
