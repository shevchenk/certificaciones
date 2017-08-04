<script type="text/javascript">
var AjaxCargo={
    AgregarEditar:function(evento){
        var data=$("#ModalCargoForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/ExpertManage.CargoEM@New';
        if(AddEdit==0){
            url='AjaxDinamic/ExpertManage.CargoEM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#CargoForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#CargoForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#CargoForm input[type='hidden']").remove();
        url='AjaxDinamic/ExpertManage.CargoEM@Load';
        masterG.postAjax(url,data,evento);
    },
    CambiarEstado:function(evento,AI,id){
        $("#ModalCargoForm").append("<input type='hidden' value='"+AI+"' name='estadof'>");
        $("#ModalCargoForm").append("<input type='hidden' value='"+id+"' name='id'>");
        var data=$("#ModalCargoForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ModalCargoForm input[type='hidden']").remove();
        url='AjaxDinamic/ExpertManage.CargoEM@EditStatus';
        masterG.postAjax(url,data,evento);
    }
};
</script>
