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
    DataTables\Editor\Upload,
    DataTables\Editor\Validate;

    $db = new Application\Db('colecao');
    $db = new Database([ 'type' => 'Mysql', 'pdo' => $db->getConnecion()->getInternalHandler() ]);


    // Allow a number of different formats to be submitted for the various demos
    $format = isset( $_GET['format'] ) ?
        $_GET['format'] :
        '';


    if ( $format === 'custom' ) {
        // $update = 'DD/MM/YYYY - H:m ';
        // $update = 'Y-m-d';
        $update = 'n/j/Y';
        $registered = 'l j F Y';
    }
    else {
        $update = "d/m/Y H:i:s";// deve retornar igual ao format
        // $update = Format::DATE_ISO_8601;
        $registered = Format::DATE_ISO_8601;
    }


	$editor = Editor::inst($db, 'cfop' )
    	->fields(
    		Field::inst( 'id' ),
    		Field::inst( 'descricao' ),
    		Field::inst( 'tipo' ),
    		Field::inst( 'operacao' ),
        	Field::inst( 'data_inicio' )
                ->validator( 'Validate::dateFormat', array(
                    'empty' => false,
                    'format' => 'd/m/Y H:i:s',
                    "message" => "Utilizar o formato padrão DD/MM/YYYY 00:00:00"
                ))
                ->getFormatter( 'Format::datetime', array(
                    'from' => 'Y-m-d H:i:s',
                    'to' =>   'd/m/Y H:i:s'
                ))
                ->setFormatter( 'Format::datetime', array(
                    'from' => 'd/m/Y H:i:s',
                    'to' =>   'Y-m-d H:i:s'
                ))
    	)
    	// ->where( 'id', $this->contaSelecionada )
    	->process( $_POST )
    	->json();
?>
