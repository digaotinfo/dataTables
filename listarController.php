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

	$editor = Editor::inst($db, 'cfop' )
    	->fields(
    		Field::inst( 'id' ),
    		Field::inst( 'descricao' ),
    		Field::inst( 'tipo' ),
    		Field::inst( 'operacao' )
    	)
    	// ->where( 'id', $this->contaSelecionada )
    	->process( $_POST )
    	->json();
?>
