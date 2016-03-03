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
        $update = 'n/j/Y';
        $registered = 'l j F Y';
    }
    else {
        $update = Format::DATE_ISO_8601;
        $registered = Format::DATE_ISO_8601;
    }


	$editor = Editor::inst($db, 'cfop' )
    	->fields(
    		Field::inst( 'id' ),
    		Field::inst( 'descricao' ),
    		Field::inst( 'tipo' ),
    		Field::inst( 'operacao' ),
            Field::inst( 'valor' )
                ->validator( 'Validate::numeric' )
                ->setFormatter( 'Format::ifEmpty', null )
    	)
    	// ->where( 'id', $this->contaSelecionada )
    	->process( $_POST )
    	->json();
?>
