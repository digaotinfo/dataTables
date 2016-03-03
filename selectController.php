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


	$editor = Editor::inst($db, 'cofins' )
    	->fields(
    		Field::inst( 'cofins.id' )
                ->validator( 'Validate::notEmpty' ),
    		Field::inst( 'cofins.vigenciaInicio' ),
    		Field::inst( 'cofins.vigenciaFim' ),
    		Field::inst( 'cofins.lpValor' ),
    		Field::inst( 'cofins.lpMonofasico' ),
    		Field::inst( 'cofins.lrValor' ),
            Field::inst( 'cofins.lrMonofasico' ),
            Field::inst( 'cofins.imposto_id' )
                ->options( 'imposto', 'id', 'extipi'),// campos da tabela relacionada
            Field::inst( 'imposto.extipi' )// campo a ser mostrado no select
    	)
        ->leftJoin( 'imposto', 'imposto.id', '=', 'cofins.imposto_id' )
    	->process( $_POST )
    	->json();
?>
