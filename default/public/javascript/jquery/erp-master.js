$(document).on("click", ".open-Modal-pass", function () {
    var myusers_id = $(this).data('id');
    $(".modal-body #users_id").val(myusers_id);
});

//codigo para crear respuesta a soporte y cerrarlo
$(document).on("click", ".open-Modal-soporte", function () {
    var mysoporte_id = $(this).data('id');
    $(".modal-body #ssoporte_id").val(mysoporte_id);
});

//codigo para modal respuesta de soporte
$(document).on("click", ".open-Modal-respuesta", function () {
    var myrespuesta = $(this).data('id');
    var p = document.createElement("p");
    p.innerHTML = myrespuesta;
    var div = document.getElementById("capa1");
    div.appendChild(p);
});
//codigo para agregar patentes al formulario
$(document).on("click", "#add-patente", function () {
    var id = $("#detalle_vehiculos_id").val();
    console.log(id);
    $.ajax({
        type: "POST",
        url: "/deptcombust/addPatente/",
        data: {"id": id},
        success: function (data) {
            $("#detalle").append(data);
        }
    });
});
//codigo para eliminar patentes del formulario
function deletPatente(patente) {
    console.log(patente);

    $('.' + patente).remove();

}
//codigo que limpia modal de respuestas
function limpiar() {
    var d = document.getElementById("capa1");
    while (d.hasChildNodes())
        d.removeChild(d.firstChild);
}
//fin codigo respuesta de soporte
function cambiaEstado(estado, id) {
    console.log(estado, id);
    $.ajax({
        type: "POST",
        url: "cambiaEstado/",
        data: {"id": id, "estado": estado},
        success: function (data) {
        }
    });
}
function cambiaEstado2(estado, id) {
    console.log(estado, id);

    $.ajax({
        type: "POST",
        url: "/ccosto/cambiaEstado2/",
        data: {"id": id, "estado": estado},
        success: function (data) {
        }
    });
}
function cambiaEstado3(estado, id) {
    console.log(estado, id);

    $.ajax({
        type: "POST",
        url: "/items/cambiaEstado3/",
        data: {"id": id, "estado": estado},
        success: function (data) {
        }
    });
}
function cambiaEstado4(estado, id) {
    console.log(estado, id);

    $.ajax({
        type: "POST",
        url: "cambiaEstado4/",
        data: {"id": id, "estado": estado},
        success: function (data) {
        }
    });
}
//codigo para relacionar usuarios con unidad de negocio
function userUne(estado, id, idd) {
    $.ajax({
        type: "POST",
        url: "/users/userUne/",
        data: {"id": id, "idd": idd, "estado": estado},
        success: function (data) {
        }
    });
}

$(document).on("change", ".surtidores", function () {
    $('.surtidor').val(this.value);
});

$(document).on("change", ".unego", function () {
    limpiar();
    id = this.value;
    if (id === '') {
        id = '1000';
    }
    console.log(id);
    $.ajax({
        type: "POST",
        url: "/deptcombust/detmacro/",
        data: {"id": id},
        success: function (data) {
            $("#capa1").append(data);
        }
    });
});
//Codigo para ingresar vales
$(document).on("click", "#buscar-solicitud", function () {
    var id = $("#solicitud_id").val();
    if (id === '') {
        alert("Debe ingresar un numero de solicitud");
    } else {
        console.log(id);
        $.ajax({
            type: "POST",
            url: "/deptcombust/tablavales/",
            data: {"id": id},
            success: function (data) {
                var d = document.getElementById("detalle");
                while (d.hasChildNodes())
                    d.removeChild(d.firstChild);
                $("#detalle").append(data);
            }
        });
    }

});
function noCarga(estado, id) {
    console.log(estado, id);
    $.ajax({
        type: "POST",
        url: "/deptcombust/nocarga/",
        data: {"id": id, "estado": estado},
        success: function (data) {
        }
    });
}
//lista para consulta xunegocio
var listar = function () {
    var unegocio = $("#xunegocio").data('id');
    var desde = $("#desde").data('id');
    var hasta = $("#hasta").data('id');
    console.log(unegocio, desde, hasta);
    var table = $("#dt_datos").DataTable({
        "ajax": {
            "method": "POST",
            "url": "listar",
            "data": {"id": unegocio, "desde": desde, "hasta": hasta}
        },
        "columns": [
            {"data": "id"},
            {"data": "nombune"},
            {"data": "fecha"},
            {"data": "fentrega"},
            {"data": "usuario"},
            {"data": "estado"},
            {"defaultContent": "<button type='button' rel='tooltip' title='Ver Detalle' class='detalle btn btn-success btn-simple btn-xs open-Modal-detalle' data-toggle='modal' data-target='#myModal-detalle'><i class='material-icons'>assignment</i></button>"}
        ],
        "language": idioma_espanol
    });
    detalle("#dt_datos tbody", table);
};
//Fin lista xunegocio
//lista para el modulo de versolicitud
var versol = function () {
    var table1 = $("#dt_datos2").DataTable({
        "ajax": {
            "method": "POST",
            "url": "/deptcombust/listarsol"
        },
        "columns": [
            {"data": "id"},
            {"data": "unegocio"},
            {"data": "fecha"},
            {"data": "fentrega"},
            {"data": "usuario"},
            {"data": "estado"},
            {"defaultContent": "<button type='button' rel='tooltip' title='Ver Detalle' class='detalle btn btn-success btn-simple btn-xs open-Modal-detalle' data-toggle='modal' data-target='#myModal-detalle'><i class='material-icons'>assignment</i></button>\n\
                                <button type='button' rel='tooltip' title='Eliminar' class='eliminar btn btn-danger btn-simple btn-xs open-Modal-detalle' data-toggle='modal' data-target='#myModal-eliminar'><i class='fa fa-times'></i></button>"}
        ],
        "language": idioma_espanol
    });
    detalle("#dt_datos2 tbody", table1);
    anular_solicitud("#dt_datos2 tbody", table1);
};
//fin lista versolicitud
//obtiene detalle solicitud
var detalle = function (tbody, table) {
    $(tbody).on("click", "button.detalle", function () {
        var data = table.row($(this).parents("tr")).data();
        console.log(data.id);
        $.ajax({
            type: "POST",
            url: "/deptcombust/detalle/",
            data: {"id": data.id},
            success: function (html) {
                $(".modal-content").html(html);
            }
        });
    });
};
var anular_solicitud = function (tbody, table) {
    $(tbody).on("click", "button.eliminar", function () {
        var data = table.row($(this).parents("tr")).data();
        $.ajax({
            type: "POST",
            url: "/deptcombust/modaleliminar/",
            data: {"id": data.id},
            success: function (html) {
                $(".eliminar-modal").html(html);
            }
        });
    });
};
//Fin obtiene detalle
var listadq = function () {
    var table2 = $("#dt_fact").DataTable({
        "ajax": {
            "method": "POST",
            "url": "/deptadq/listadq"
        },
        "columns": [
            {"data": "id"},
            {"data": "frecep"},
            {"data": "ffact"},
            {"data": "tdoc"},
            {"data": "ndoc"},
            {"data": "proveedor"},
            {"data": "neto"},
            {"data": "iva"},
            {"data": "adicional"},
            {"data": "unegocio"},
            {"data": "correlativo"},
            {"data": "usuario"},
            {"data": "observ"},
            {"data": "estado"},
            {"data": "fmerca"},
            {"defaultContent": "<button type='button' rel='tooltip' title='Editar' class='editar btn btn-success btn-simple btn-xs open-Modal-detalle' data-toggle='modal' data-target='#myModal-editar'><i class='fa fa-edit'></i></button>\n\
                                <button type='button' rel='tooltip' title='Eliminar' class='eliminar btn btn-danger btn-simple btn-xs open-Modal-detalle' data-toggle='modal' data-target='#myModal-eliminar'><i class='fa fa-times'></i></button>"}
        ],
        "language": idioma_espanol
    });
    obtener_data_editar("#dt_fact tbody", table2);
    obtener_data_eliminar("#dt_fact tbody", table2);
};
var obtener_data_editar = function (tbody, table) {
    $(tbody).on("click", "button.editar", function () {
        var data = table.row($(this).parents("tr")).data();
        $.ajax({
            type: "POST",
            url: "/deptadq/modaleditar/",
            data: {"id": data.id},
            success: function (html) {
                $(".editar-modal").html(html);
            }
        });
    });
};
var obtener_data_eliminar = function (tbody, table) {
    $(tbody).on("click", "button.eliminar", function () {
        var data = table.row($(this).parents("tr")).data();
        $.ajax({
            type: "POST",
            url: "/deptadq/modaleliminar/",
            data: {"id": data.id},
            success: function (html) {
                $(".eliminar-modal").html(html);
            }
        });
    });
};
//Cambia idioma de datatables
var idioma_espanol = {
    "sProcessing": "Procesando...",
    "sLengthMenu": "Mostrar _MENU_ registros",
    "sZeroRecords": "No se encontraron resultados",
    "sEmptyTable": "Ningún dato disponible en esta tabla",
    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix": "",
    "sSearch": "Buscar:",
    "sUrl": "",
    "sInfoThousands": ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
};
//Fin cambia idioma

var listaxpatente = function () {
    var patente = $("#patente").data('id');
    var desde1 = $("#desde1").data('id');
    var hasta1 = $("#hasta1").data('id');
    console.log(patente, desde1, hasta1);
    var table = $("#dt_datos4").DataTable({
        "ajax": {
            "method": "POST",
            "url": "listarxpt",
            "data": {"id": patente, "desde": desde1, "hasta": hasta1}
        },
        "columns": [
            {"data": "id"},
            {"data": "nombre"},
            {"data": "fecha"},
            {"data": "fentrega"},
            {"data": "lcargados"},
            {"data": "marca"},
            {"defaultContent": "<button type='button' rel='tooltip' title='Ver Detalle' class='detalle btn btn-success btn-simple btn-xs open-Modal-detalle' data-toggle='modal' data-target='#myModal-detalle'><i class='material-icons'>assignment</i></button>"}
        ],
        "language": idioma_espanol
    });
    detalle("#dt_datos4 tbody", table);
};
var listaxtipo = function () {
    var tipo = $("#tipo1").data('id');
    var desde1 = $("#desde2").data('id');
    var hasta1 = $("#hasta2").data('id');
    console.log(tipo, desde1, hasta1);
    var table = $("#dt_datos5").DataTable({
        "ajax": {
            "method": "POST",
            "url": "listarxtipo",
            "data": {"id": tipo, "desde": desde1, "hasta": hasta1}
        },
        "columns": [
            {"data": "id"},
            {"data": "nombre"},
            {"data": "fecha"},
            {"data": "fentrega"},
            {"data": "lcargados"},
            {"data": "marca"},
            {"defaultContent": "<button type='button' rel='tooltip' title='Ver Detalle' class='detalle btn btn-success btn-simple btn-xs open-Modal-detalle' data-toggle='modal' data-target='#myModal-detalle'><i class='material-icons'>assignment</i></button>"}
        ],
        "language": idioma_espanol
    });
    detalle("#dt_datos5 tbody", table);
};
var listaxmacrotipo = function () {
    var tipo = $("#macrot").data('id');
    var desde1 = $("#desde").data('id');
    var hasta1 = $("#hasta").data('id');
    console.log(tipo, desde1, hasta1);
    var table = $("#dt_datos6").DataTable({
        "ajax": {
            "method": "POST",
            "url": "listarxmacrotipo",
            "data": {"id": tipo, "desde": desde1, "hasta": hasta1}
        },
        "columns": [
            {"data": "id"},
            {"data": "nombre"},
            {"data": "fecha"},
            {"data": "fentrega"},
            {"data": "lcargados"},
            {"data": "marca"},
            {"defaultContent": "<button type='button' rel='tooltip' title='Ver Detalle' class='detalle btn btn-success btn-simple btn-xs open-Modal-detalle' data-toggle='modal' data-target='#myModal-detalle'><i class='material-icons'>assignment</i></button>"}
        ],
        "language": idioma_espanol
    });
    detalle("#dt_datos6 tbody", table);
};
$(document).on("click", ".open-Modal-detMacro1", function () {
    var idmacro = $(this).data('id');
    var unegocio = $("#une").data('id');
    console.log(idmacro, unegocio);
    $.ajax({
        type: "POST",
        url: "/deptcombust/modaldetalle/",
        data: {"id": idmacro, "idune": unegocio},
        success: function (html) {
            $(".macro-modal").html(html);
            $("#macrotipo_id").val(idmacro);
            $("#macrotipo_id2").val(idmacro);
        }
    });
});

var listavehiculos = function () {
    var tablev = $("#dt_vehi").DataTable({
        "ajax": {
            "method": "POST",
            "url": "/vehiculos/listarvehiculos"
        },
        "columns": [
            {"data": "id"},
            {"data": "patente"},
            {"data": "marca"},
            {"data": "modelo"},
            {"data": "tvehiculo"},
            {"data": "propietario"},
            {"data": "rinde"},
            {"data": "delta"},
            {"data": "tmarcador"},
            {"data": "limite"},
            {"data": "conteo"},
            {"defaultContent": "<button type='button' rel='tooltip' title='Editar' class='editarvehi btn btn-success btn-simple btn-xs open-Modal-detalle' data-toggle='modal' data-target='#myModal-editarvehi'><i class='fa fa-edit'></i></button>\n\
                                <button type='button' rel='tooltip' title='Eliminar' class='eliminarvehi btn btn-danger btn-simple btn-xs open-Modal-detalle' data-toggle='modal' data-target='#myModal-eliminarvehi'><i class='fa fa-times'></i></button>"}
        ],
        "language": idioma_espanol
    });
    obtener_data_editarvehi("#dt_vehi tbody", tablev);
    obtener_data_eliminarvehi("#dt_vehi tbody", tablev);
};
var obtener_data_editarvehi = function (tbody, table) {
    $(tbody).on("click", "button.editarvehi", function () {
        var data = table.row($(this).parents("tr")).data();
        $.ajax({
            type: "POST",
            url: "/vehiculos/modaleditarvehi/",
            data: {"id": data.id},
            success: function (html) {
                $(".editarvehi-modal").html(html);
            }
        });
    });
};
var obtener_data_eliminarvehi = function (tbody, table) {
    $(tbody).on("click", "button.eliminarvehi", function () {
        var data = table.row($(this).parents("tr")).data();
        $.ajax({
            type: "POST",
            url: "/vehiculos/modaleliminarvehi/",
            data: {"id": data.id},
            success: function (html) {
                $(".eliminarvehi-modal").html(html);
            }
        });
    });
};
var listagps = function () {
    var tablegps = $("#dt_gps").DataTable({
        "ajax": {
            "method": "POST",
            "url": "/gps/listargps"
        },
        "columns": [
            {"data": "id"},
            {"data": "patente"},
            {"data": "marca"},
            {"data": "modelo"},
            {"data": "limite"},
            {"data": "conteo"}
        ],
        "language": idioma_espanol
    });

};
$(document).ready(function () {
    $('#tvehiculos').DataTable({
        "language": idioma_espanol
    });
    $('#propietarios').DataTable({
        "language": idioma_espanol
    });
    $('#listasignacion').DataTable({
        "language": idioma_espanol
    });
    $('#relacion_user').DataTable({
        "language": idioma_espanol
    });
    $('#unegocio').DataTable({
        "language": idioma_espanol
    });
    $('#list_gps').DataTable({
        "language": idioma_espanol
    });
});

function tvtb(estado, id, idd) {
    $.ajax({
        type: "POST",
        url: "/vehiculos/tvtb/",
        data: {"id": id, "idd": idd, "estado": estado},
        success: function (data) {
        }
    });
}
//Seccion de funciones modulo de compras
$(document).ready(function () {
    $("#solicitud_unegocio_id").change(function () {
        var unegocio_id = $('#solicitud_unegocio_id').val();

        $.ajax({
            type: "POST",
            url: "getCcosto",
            data: "unegocio_id=" + unegocio_id,
            success: function (html) {
                $("#div_ccosto").html(html);
                $("#div_ccosto").focus;
            }
        });
    });
});
function Une(estado, id, idd) {
    $.ajax({
        type: "POST",
        url: "/unegocio/Une/",
        data: {"id": id, "idd": idd, "estado": estado},
        success: function (data) {
        }
    });
}
$(document).on("click", "#add-producto", function () {
    var id = $("#detalle_productos_id").val();
    console.log(id);
    $.ajax({
        type: "POST",
        url: "/deptadq/addProducto/",
        data: {"id": id},
        success: function (data) {
            $("#detalle").append(data);
        }
    });
});
//Fin seccion de funciones modulo de compras
