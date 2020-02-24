<script type="text/javascript">
var AjaxMedioCaptacion={
    AgregarEditar:function(evento){
        var data=$("#ModalMedioCaptacionForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Mantenimiento.MedioCaptacionMA@New';
        if(AddEdit==0){
            url='AjaxDinamic/Mantenimiento.MedioCaptacionMA@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#MedioCaptacionForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#MedioCaptacionForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#MedioCaptacionForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.MedioCaptacionMA@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalMedioCaptacionForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalMedioCaptacionForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalMedioCaptacionForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalMedioCaptacionForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Mantenimiento.MedioCaptacionMA@EditStatus';
        masterG.postAjax(url,data,evento);
    }
};
</script>
