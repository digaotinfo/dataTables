var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
    var tableTag = $('#participante');
    var url = 'participanteController.php';

    editor = new $.fn.dataTable.Editor( {
        ajax: url,
        table: "#participante",
        fields: [
            {
                label: "ID:",
                name: "participante.id"
            }
            , {
                label: "Documento:",
                name: "participante.documento"
            }
            , {
                label: "IM:",
                name: "participante.im"
            }
            , {
                label: "Regime:",
                name: "participante.regime"
            }
            , {
                label: "Atividade:",
                name: "participante.atividade"
            }
            , {
                label: "Suframa:",
                name: "participante.suframa"
            }
            , {
                label: "Logradouro:",
                name: "participante.logradouro"
            }
            , {
                label: "Número:",
                name: "participante.numero"
            }
            , {
                label: "Complemento:",
                name: "participante.complemento"
            }
            , {
                label: "Bairro:",
                name: "participante.bairro"
            }
            , {
                label: "Cidade:",
                name: "participante.cidade"
            }
            , {
                label: "CEP:",
                name: "participante.cep"
            }
            , {
                label: "UF:",
                name: "participante.uf"
            }
            , {
                label: "País:",
                name: "participante.pais"
            }
            , {
                label: "Telefone:",
                name: "participante.telefone",
            }
            , {
                label: "E-mail:",
                name: "participante.email"
            }
            , {
                label: "Data de Cadastro:",
                name: "participante.cadastroData",
                type: 'datetime',
                def:   function () { return new Date(); },
                format: 'DD/MM/YYYY',
            }
            , {
                label: "Data de Alteração:",
                name: "participante.alteracaoData",
                type: 'datetime',
                def:   function () { return new Date(); },
                format: 'DD/MM/YYYY',
            }
            , {
                label: "Data de Sinronização:",
                name: "participante.sincronizacaoData",
                type: 'datetime',
                def:   function () { return new Date(); },
                format: 'DD/MM/YYYY',
            }
            , {
                label: "Conta ID:",
                name: "participante.conta_id"
            }
        ]
    } );

    // Habilita a Edição inLine
    tableTag.on( 'dblclick', 'tbody td:not(:first-child)', function (e) {
        editor.inline( this );
    } );

    

    tableTag.DataTable({
        responsive: true,
        dom: "Bfrtip", // Mostrar botões NEW | Edit | Delete
        bDeferRender: true,
        iDisplayLength: 2,
        ajax: url,
        columns: [
            {
                data: "id",
                title: 'ID'
            }
            , {
                data: "participante.documento",
                title: 'Documento'
            }
            , {
                data: "participante.im",
                title: 'IM'
            }
            , {
                data: "participante.regime",
                title: 'Regime'
            }
            , {
                data: "participante.atividade",
                title: 'Atividade'
            }
            , {
                data: "participante.suframa",
                title: 'Suframa'
            }
            , {
                data: "participante.logradouro",
                title: 'Logradouro'
            }
            , {
                data: "participante.numero",
                title: 'Número'
            }
            , {
                data: "participante.complemento",
                title: 'Complemento'
            }
            , {
                data: "participante.bairro",
                title: 'Bairro'
            }
            , {
                data: "participante.cidade",
                title: 'Cidade'
            }
            , {
                data: "participante.cep",
                title: 'CEP'
            }
            , {
                data: "participante.uf",
                title: 'UF'
            }
            , {
                data: "participante.pais",
                title: 'País'
            }
            , {
                data: "participante.telefone",
                title: 'Telefone',

            }
            , {
                data: "participante.email",
                title: 'E-mail'
            }
            , {
                data: "participante.cadastroData",
                title: 'Data de Cadastro'
            }
            , {
                data: "participante.alteracaoData",
                title: 'Data de Alteração'
            }
            , {
                data: "participante.sincronizacaoData",
                title: 'Data de Sinronização'
            }
            , {
                data: "participante.conta_id",
                title: 'Conta ID'
            }
        ],
        select: true,
        buttons: [
           { extend: "create", editor: editor }
           , { extend: "edit",   editor: editor }
           , { extend: "remove", editor: editor }
        ],

    });


} );
