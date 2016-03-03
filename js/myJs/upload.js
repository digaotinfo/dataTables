var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
    var tableTag = $('#example');
    var url = 'uploadController.php';

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
                label: "Image:",
                name: "image",
                type: "upload",
                display: function ( file_id ) {
                    return file_id ?
                       '<img src="'+table.file( 'files', file_id ).web_path+'"/>' :
                       'Vazio';
                },
                clearText: "Limpar",
                noImageText: 'Sem Imagem'
            }
            , {
                label: "Galeria:",
                name: "arquivos[].id",
                type: "uploadMany",
                display: function ( fileId, counter ) {
                    return '<img src="'+table.file( 'arquivos', fileId ).web_path+'"/>';
                },
                noImageText: 'No images'
            }
        ]
    } );

    var table = tableTag.DataTable( {
        dom: "Bfrtip",
        ajax: url,
        columns: [
            {
                data: "id",
                title: "ID"
            }
            , {
                data: "descricao",
                title: "Descrição",
                className: "editable"
            }
            , {
                data: "tipo",
                title: "Tipo",
                className: "editable"
            }
            , {
                data: "operacao",
                title: "Operação"
            }
            , {
                data: "image",
                render: function ( file_id ) {
                    return file_id ?
                        '<img src="'+table.file( 'files', file_id ).web_path+'"/>' :
                        null;
                },
                defaultContent: "Sem Imagem",
                title: "Imagem"
            }
            , {
                data: "arquivos",
                render: function ( d ) {
                    return d.length ?
                        d.length+' arquivos(s)' :
                        'Sem Arquivos';
                },
                title: "Galeria"
            }

        ],
        select: true,
        buttons: [
            { extend: "create", editor: editor },
            { extend: "edit",   editor: editor },
            { extend: "remove", editor: editor }
        ]
    } );


    table.on('dblclick', 'tbody td.editable', function(e) {
        editor.inline(this, {
            submitOnBlur: true
        });
    });


} );
