<script type="text/javascript">
var AjaxAEDocente={
    AgregarEditar1:function(evento){
        var data=$("#ModalDocenteForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Mantenimiento.DocenteEM@New';
        if(AddEdit1==0){
            url='AjaxDinamic/Mantenimiento.DocenteEM@Edit';
        }
        masterG.postAjax(url,data,evento);
    },


};
</script>
