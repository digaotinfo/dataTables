var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
    var tableTag = $('#example');
    var url = 'responsiveController.php';

    /*
    *
    * o que sera editado/adicionado qdo aparecer o modal
    */
    editor = new $.fn.dataTable.Editor( {
        ajax: url,
        table: "#example",
        "i18n":
            {
                edit:
                    {
                        "button": "Editar Item",
                        "title":  "Editar",
                        "submit": "Salvar"
                    },
                create:
                    {
                        "button": "Add Item",
                        "title":  "Add Novo Item",
                        "submit": "Salvar"
                    },
                remove: {
                       button: "Excluir",
                       title:  "Deletar Item",
                       submit: "Delete",
                       confirm: {
                           _: "Tem certeza que deseja excluir essas %d Linhas?",
                           1: "Tem certeza que deseja exluir apenas 1 Item?"
                       }
                   },
                error: {
                    system: "Erro no sistema, Entrar em contato com o Administrador."
                },
                selectRows: {
                    title: "Selecionar linha."
                },
            },
        fields: [
            {
                label: "ID:",
                name: "cofins.id"
            }
            , {
                label: "Vigencia Início:",
                name: "cofins.vigenciaInicio",
                type:  'datetime', // ao usar o date, utilizar o dateFormat
                def:   function () { return new Date(); },
                format: 'DD/MM/YYYY',// utilizar qdo não inserir icon calendar.png
            }
            , {
                label: "Vigencia Fim:",
                name: "cofins.vigenciaFim"
            }
            , {
                label: "IP Valor:",
                name: "cofins.lpValor"
            }
            , {
                label: "IP Monofasico:",
                name: "cofins.lpMonofasico"
            }
            , {
                label: "IR Valor:",
                name: "cofins.lrValor"
            }
            , {
                label: "IR Monofasico:",
                name: "cofins.lrMonofasico"
            }
            /*
            *
            * Select alimentado pelos dados do BD.
            */
            ,{
                label: "Imposto:",
                name: "cofins.imposto_id",
                type: "select"
            }
            , {
                label: "Ativo",
                name: "cofins.ativo",
                type: "radio",
                options: [
                    { "label": "Sim", "value": "1" },
                    { "label": "Não", "value": "0"},
                ]
            }

            /*
            *
            * Select com valores fixos.
            */
            , {
                label: "Type Developers:",
                name: "type_main",
                editField: "nameSelect",//ex: imposto_id
                "pageLength": 2,
                type: "radio",// pode ser checkbox, select, radio
                ipOpts: [
                    { "label": "PHP", "value": "1" },
                    { "label": "Android", "value": "2"},
                    { "label": "Python", "value": "3"},
                    { "label": "Android", "value": "4"},
                    { "label": "Python", "value": "5"}
                ]
            }

        ]
    } ) ;

    /*
    *
    * tratar dados antes de salvar(=validate)
    */
    editor.on( 'preSubmit', function ( e, data, action ) {
        if ( action === 'create' ) {
            if ( !$.isNumeric(data.data[0].cofins.id) ) {
                alert('Apenas Numeros. Verifique o campo ID');
                return false;
            }
        }
    });



    /*
    *
    * Habilita a Edição inLine
    */
    tableTag.on( 'dblclick', 'tbody td:not(:first-child)', function (e) {
        // Ignore the Responsive control and checkbox columns
        if ( $(this).hasClass( 'control' ) || $(this).hasClass('select-checkbox') ) {
            return;
        }

        editor.inline( this );
    } );

    /*
    *
    * bloquear campos
    */
    editor.on( 'onInitEdit', function () {
        editor.disable('cofins.id');
        // editor.disable('cofins.vigenciaInicio');
    });




    /*
    *
    * Alimentar table
    */
    var table = tableTag.DataTable({
        // responsive: true,// fazer o resposivo de forma dinamica porém, titulos em negrito.
        responsive: {
            details: {
                renderer: function( api, rowIdx, columns ){
                    var data = $.map( columns, function( col, i ){
                        return col.hidden ?
                            '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                                '<td>'+col.title+':'+'</td> '+
                                '<td>'+col.data+'</td>'+
                            '</tr>' :
                            '';
                    }).join('');

                    return data ?
                        $('<table/>').append( data ) :
                        false;
                }
            }
        },
        "bDeferRender": true,
        "iDisplayLength": 10,
        dom: "BCfrtip", // Mostrar botões NEW | Edit | Delete ? ColVis
        // dom: "Bfrtip", // Mostrar botões NEW | Edit | Delete
        bProcessing: false,
        ajax: url,
        columns: [
            {   // Responsive control column
               data: null,
               defaultContent: '',
               className: 'control',
               orderable: false
            },
            {   // Checkbox select column
               data: null,
               defaultContent: '',
               className: 'select-checkbox',
               orderable: false
            },
            { data: "cofins.id", title: "ID" }
            , {
                data: "cofins.vigenciaInicio",
                title: "Inínio de Vigencia" ,
            }
            , { data: "cofins.vigenciaFim", title: "Fim de Vigencia" }
            , {
                data: "cofins.lpValor",
                render: $.fn.dataTable.render.number( '.', ',', 2, '$' ),
                title: "Lp Valor"
            }
            , {
                data: "cofins.lpMonofasico",
                render: $.fn.dataTable.render.number( '.', ',', 2, '$' ),
                title: "Monofasico Monofasico"
            }
            , {
                data: "cofins.lrValor",
                render: $.fn.dataTable.render.number( '.', ',', 2, '$' ),
                title: "LR Valor"
            }
            , {
                data: "cofins.lrMonofasico",
                render: $.fn.dataTable.render.number( '.', ',', 2, '$' ),
                title: "Lr Monofasico"
            }
            , { data: "imposto.extipi", title: "Extipi" }
            , {
                data: "cofins.ativo",
                title: "Ativo",
                render: function (val) {
                    return val == 1 ? "Sim" : "Não";
                }
            }
        ],
        order: [ 2, 'asc' ],
        select: true,// selecionar a linha inteira.
        columnDefs: [
            {
                visible: false,
                targets: [2, 3, 5, 7]
            }
        ],
        colVis: {
            buttonText: "Colunas",
            exclude: [ 0 ]
        },
        buttons: [
            { extend: "create", editor: editor }
            , { extend: "edit",   editor: editor }
            , { extend: "remove", editor: editor }
            , {extend: "selectRows", text: 'Selecionar Linha',}
            , {extend: "selectColumns", text: 'Selecionar Coluna',}
            , {extend: "selectCells", text: 'Selecionar Celula',}
            , {extend: "selectNone", text: 'Remover Seleção',}
            , {
                extend: "selectedSingle",
                text: 'Duplicar Item',
                action: function ( e, dt, node, config ) {
                    // Place the selected row into edit mode (but hidden),
                    // then get the values for all fields in the form
                    var values = editor.edit(
                            this.row( { selected: true } ).index(),
                            false
                        )
                        .val();

                    // Create a new entry (discarding the previous edit) and
                    // set the values from the read values
                    editor
                        .create( {
                            title: 'Duplicar Registro',
                            buttons: 'Criar'
                        } )
                        .set( values );
                }
            },
        ],
        language: {
            processing:     "Aguarde...",
            search:         "Buscar:",
            lengthMenu:     "Afficher _MENU_ &eacute;l&eacute;ments",
            info:           "Mostrando _START_ à _END_ de um total de _TOTAL_ Elementos",
            infoEmpty:      "Mostrando 0 de 0 Elementos",
            infoFiltered:   "(encontramos _MAX_ elementos)",
            infoPostFix:    "",
            loadingRecords: "Carregando...",
            zeroRecords:    "Nenhum Elemento Encontrado.",
            emptyTable:     "Tabela Vazia.",
            paginate: {
                first:      "Primeiro",
                previous:   "Anterior",
                next:       "Próximo",
                last:       "Ultimo"
            },
            aria: {
                sortAscending:  ": activer pour trier la colonne par ordre croissant",
                sortDescending: ": activer pour trier la colonne par ordre décroissant"
            },
            select: {
                rows: {
                    _: "Você selecionou %d Linhas",
                    0: "Selecione alguma linha.",
                    1: "Apenas 1 linha selecionada."
                }
            }
        }
    });


     var colvis = new $.fn.dataTable.ColVis( table, {
        buttonText: 'Select columns'
    } );

    $( colvis.button() ).insertAfter('div.info');

} );
