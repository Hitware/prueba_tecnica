function guardar(){
    var nombres = $("#nombres").val().trim();
    var apellidos = $("#apellidos").val().trim();
    
    var formData = new FormData();

    formData.append('nombres',nombres);
    formData.append('apellidos',apellidos);

    if(nombres!="" & apellidos!=""){
        $.ajax({
            url: 'php/registrarUsuario.php',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function(e) {
                alert("Registro exitoso");
                $("#nombres").val("");
                $("#apellidos").val("");
                listarUsuarios();
                }
                
            
        });
    }
    else{
        alert("Debes completar todos los campos");
    }
   
}

function listarUsuarios() {
    $.ajax({
        url: 'php/listarUsuarios.php',
        type: 'GET',
        dataType: "html",
        success: function(e) {
            var data=JSON.parse(e);
            var lista="";
            for (let i = 0; i < data.length; i++) {
                lista+="<tr>"+
                "<td>"+
                    "<p>"+data[i].nombres+"</p>"+
                "</td>"+
                "<td>"+
                    "<p>"+data[i].apellidos+"</p>"+
                "</td>" +
                "</tr>";
            }
            $("#lista-usuarios").html(lista);
            
        }
    });
    
}
