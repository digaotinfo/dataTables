var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
    var tableTag = $('#example');
    var url = 'valorController.php';

    editor = new $.fn.dataTable.Editor( {
        ajax: url,
        table: "#example",
        fields: [
            {
                label: "ID:",
                name: "id"
            }
            , {
                label: "Descrição:",
                name: "descricao"
            }
            , {
                label: "Tipo:",
                name: "tipo"
            }
            , {
                label: "Operação:",
                name: "operacao"
            }
            , {
                label: "Valor:",
                name: "valor",
                def: "3000"
            }
        ]
    } );

    // Habilita a Edição inLine
    tableTag.on( 'dblclick', 'tbody td:not(:first-child)', function (e) {
        editor.inline( this );
    } );

    tableTag.DataTable({
        dom: "Bfrtip", // Mostrar botões NEW | Edit | Delete
        ajax: url,
        columns: [
            { data: "id" }
            , { data: "descricao" }
            , { data: "tipo" }
            , { data: "operacao" }
            , { data: "valor", render: $.fn.dataTable.render.number( '.', ',', 2, '$' ) }
        ],
        order: [[ 0, 'desc' ]],
        select: true,
        buttons: [
           { extend: "create", editor: editor }
           , { extend: "edit",   editor: editor }
           , { extend: "remove", editor: editor }
        ],

    });


} );
