var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
    var tableTag = $('#example');
    var url = 'selectController.php';

    $.fn.dataTable.Editor.fieldTypes.autoComplete = $.extend(true, {}, $.fn.dataTable.Editor.models.fieldType, {
        "create": function (conf) {
            conf._input = $('<input type="text" id="' + conf.id + '">')
            .autocomplete(conf.opts || {});

            return conf._input[0];
        },

        "get": function (conf) {
            return conf._input.val();
        },

        "set": function (conf, val) {
            conf._input.val(val);
        },

        "enable": function (conf) {
            conf._input.autocomplete('enable');
        },

        "disable": function (conf) {
            conf._input.autocomplete('disable');
        },

        // Non-standard Editor method - custom to this plug-in
        "node": function (conf) {
            return conf._input;
        }
    })


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
                name: "cofins.vigenciaInicio"
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
            /*
            *
            * >>> fazer autocomplete
            */
            // , {
            //     data: "table.table_id",
            //     title: "Relacionamento de tabelas com autoComplete"
            //     type: "autoComplete",
            //     opts: {
            //         source: function (request, response) {
            //             var url = '/participante/process-participante-list';
            //             getListParticipante(url, request, response);
            //         }
            //     }
            // }
            /*
            *
            * <<< fazer autocomplete
            */


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
    tableTag.DataTable({
        "bDeferRender": true,
        "iDisplayLength": 10,
        dom: "Bfrtip", // Mostrar botões NEW | Edit | Delete
        bProcessing: false,
        ajax: url,
        columns: [
            { data: "cofins.id" }
            , { data: "cofins.vigenciaInicio" }
            , { data: "cofins.vigenciaFim" }
            , { data: "cofins.lpValor", render: $.fn.dataTable.render.number( '.', ',', 2, '$' ) }
            , { data: "cofins.lpMonofasico", render: $.fn.dataTable.render.number( '.', ',', 2, '$' ) }
            , { data: "cofins.lrValor", render: $.fn.dataTable.render.number( '.', ',', 2, '$' ) }
            , { data: "cofins.lrMonofasico", render: $.fn.dataTable.render.number( '.', ',', 2, '$' ) }
            , { data: "imposto.extipi" }
            // , { data: "imposto.extipi", editField: "cofins.imposto_id" } // no doubleClick, mostrar o comboSelect
        ],
        order: [[ 0, 'desc' ]],// Ordenar coluna de indice 0.
        select: true,// selecionar a linha inteira.
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

} );





function getListParticipante(url, request, response) {
    var villeArray= new Array();
    $.ajax({
        url: url,
        method: 'POST',
        data:{dado: request},
        success: function (json) {
            var villeArray= new Array();
            json = $.parseJSON(json);

            for(var i=0;i<json.length;i++){
                obj= {
                    "label" : json[i].nome, "value" : json[i].nome,
                    "value" : json[i].id
                };
                // obj= { "value" : json[i].id};
                villeArray.push(obj);
            }
            response ( villeArray )
            }
    });
}
