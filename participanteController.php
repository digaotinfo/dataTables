<?php
include( "php/DataTables.php" );
include 'classes/Db.php';
include 'classes/ModelBase.php';

// Alias Editor classes so they are easy to use
use
    DataTables\Database,
    DataTables\Editor,
    DataTables\Editor\Field,
    DataTables\Editor\Format,
    DataTables\Editor\Join,
    DataTables\Editor\MJoin,
    DataTables\Editor\Upload,
    DataTables\Editor\Validate;

    $db = new Application\Db('fiscal');
    $db = new Database([ 'type' => 'Mysql', 'pdo' => $db->getConnecion()->getInternalHandler() ]);

	$editor = Editor::inst($db, 'participante' )
    	->fields(
    		Field::inst( 'id' )
                ->validator( 'Validate::notEmpty' ),
    		Field::inst( 'participante.documento' ),
    		Field::inst( 'participante.im' ),
    		Field::inst( 'participante.regime' ),
    		Field::inst( 'participante.atividade' ),
            Field::inst( 'participante.suframa' ),
            Field::inst( 'participante.nome' ),
            Field::inst( 'participante.logradouro' ),
            Field::inst( 'participante.numero' ),
            Field::inst( 'participante.complemento' ),
            Field::inst( 'participante.bairro' ),
            Field::inst( 'participante.cidade' ),
            Field::inst( 'participante.cep' ),
            Field::inst( 'participante.uf' ),
            Field::inst( 'participante.pais' ),
            Field::inst( 'participante.telefone' ),
            Field::inst( 'participante.email' ),
            Field::inst( 'participante.cadastroData' )
                ->validator( 'Validate::dateFormat', array(
                    'empty' => false,
                    'format' => 'd/m/Y',
                    "message" => "Utilizar o formato padrão DD/MM/YYYY"
                ))
                ->getFormatter( 'Format::datetime', array(
                    'from' => 'Y-m-d  H:i:s',
                    'to' =>   'd/m/Y',
                ))
                ->setFormatter( 'Format::datetime', array(
                    'from' => 'd/m/Y',
                    'to' =>   'Y-m-d H:i:s',
                )
            ),
            Field::inst( 'participante.alteracaoData' )
                ->validator( 'Validate::dateFormat', array(
                    'empty' => false,
                    'format' => 'd/m/Y',
                    "message" => "Utilizar o formato padrão DD/MM/YYYY"
                ))
                ->getFormatter( 'Format::datetime', array(
                    'from' => 'Y-m-d  H:i:s',
                    'to' =>   'd/m/Y',
                ))
                ->setFormatter( 'Format::datetime', array(
                    'from' => 'd/m/Y',
                    'to' =>   'Y-m-d H:i:s',
                )
            ),
            Field::inst( 'participante.sincronizacaoData' )
                ->validator( 'Validate::dateFormat', array(
                    'empty' => false,
                    'format' => 'd/m/Y',
                    "message" => "Utilizar o formato padrão DD/MM/YYYY"
                ))
                ->getFormatter( 'Format::datetime', array(
                    'from' => 'Y-m-d H:i:s',
                    'to' =>   'd/m/Y',
                    "message" => "Utilizar o formato padrão DD/MM/YYYY"
                ))
                ->setFormatter( 'Format::datetime', array(
                    'from' => 'd/m/Y',
                    'to' =>   'Y-m-d  H:i:s',
                    "message" => "Utilizar o formato padrão DD/MM/YYYY"
                )
            ),
            Field::inst( 'participante.conta_id' )
    	)
        // ->leftJoin( 'imposto', 'imposto.id', '=', 'cofins.imposto_id' )
        ->where( 'conta_id', '02524733000150' )
        ->process( $_POST )
    	->json();
?>
