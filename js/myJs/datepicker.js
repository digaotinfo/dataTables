var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
    var tableTag = $('#example');
    var url = 'datepickerController.php';


    editor = new $.fn.dataTable.Editor( {
        i18n: {
            /*
            *
            * tradução das datas
            */
            datetime: {
                previous: 'Aterior',
                next:     'Próximo',
                months:   [ 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro' ],
                weekdays: [ 'Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab' ]
            }
        },
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
                label: "Data Inicio:",
                name: "data_inicio",
                type:  'datetime', // ao usar o date, utilizar o dateFormat
                def:   function () { return new Date(); },
                // "dateFormat": $.datepicker.ISO_8601,// utilizar qdo não inserir icon calendar.png
                format: 'DD/MM/YYYY HH:mm:ss',// utilizar qdo não inserir icon calendar.png
                // format: 'DD/MM/YYYY HH:mm',// utilizar qdo não inserir icon calendar.png
                // dateFormat: 'dd/mm/yy',// usando o icon calendar.png
                // fieldInfo: 'Utilizar o formato padrão DD/MM/YYYY 00:00:00',
                opts:  {
                    disableDays: [ 0, 6 ],// bloquear dias da semana
                    minDate: new Date('2015-12-01'),
                    maxDate: new Date('2016-12-31')
                }
                // dateFormat: $.datepicker.ISO_8601
            }
        ]
    } );

    /*
    *
    * bloquear campos
    */
    editor.on( 'onInitEdit', function () {
        editor.disable('id');
        // editor.disable('data_inicio');
    });

    var table = tableTag.DataTable({
        bDeferRender: true,
        dom: "Bfrtip", // Mostrar botões NEW | Edit | Delete
        ajax: url,
        columns: [
            {
                data: null,
                defaultContent: '',
                className: 'select-checkbox',
                orderable: false
            }
            , { data: "id" }
            , { data: "descricao" }
            , { data: "tipo" }
            , { data: "operacao" }
            , { data: "data_inicio" }
        ],
        order: [ 1, 'asc' ],
        // Focus no Tab >>>
        keys: {
            columns: ':not(:first-child)',
            keys: [ 9 ]
        },
        // <<< Focus no Tab
        select: true,
        buttons: [
           { extend: "create", editor: editor }
           , { extend: "edit",   editor: editor }
           , { extend: "remove", editor: editor }
        ],

    });

    // Habilita a Edição inLine com double click
    tableTag.on( 'dblclick', 'tbody td:not(:first-child)', function (e) {
        editor.inline( this );
    } );

    // Inline editing on tab focus
    table.on( 'key-focus', function ( e, datatable, cell ) {
        editor.inline( cell.index(), {
            onBlur: 'submit'
        } );
    } );

} );
