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
    DataTables\Editor\Mjoin,// <<< import class
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
    		Field::inst( 'operacao' ),
            Field::inst( 'image' )
                ->setFormatter( 'Format::ifEmpty', null )
                ->upload( Upload::inst( $_SERVER['DOCUMENT_ROOT'].'/colecao/upload/__NAME__' )
                // ->upload( Upload::inst( $_SERVER['DOCUMENT_ROOT'].'/upload/__ID__.__EXTN__' )
                    ->db( 'files', 'id', array(
                        'filename'    => Upload::DB_FILE_NAME,
                        'filesize'    => Upload::DB_FILE_SIZE,
                        'web_path'    => Upload::DB_WEB_PATH,
                        'system_path' => Upload::DB_SYSTEM_PATH
                    ) )
                    ->validator( function ( $file ) {
                        return$file['size'] >= 50000 ?
                            "Os arquivos devem ter menos de 50K" :
                            null;
                    } )
                    ->allowedExtensions( [ 'png', 'jpg', 'gif' ], "Please upload an image" )
                )

    	)
        ->join(
            Mjoin::inst( 'arquivos' )
                ->link( 'cfop.id', 'cfop_arquivos.cfop_id' )
                ->link( 'arquivos.id', 'cfop_arquivos.arquivo_id' )
                ->fields(
                    Field::inst( 'id' )
                        ->upload( Upload::inst( $_SERVER['DOCUMENT_ROOT'].'/colecao/upload/__ID__.__EXTN__' )
                            ->db( 'arquivos', 'id', array(
                                'filename'    => Upload::DB_FILE_NAME,
                                'filesize'    => Upload::DB_FILE_SIZE,
                                'web_path'    => Upload::DB_WEB_PATH,
                                'system_path' => Upload::DB_SYSTEM_PATH
                            ) )
                            ->validator( function ( $file ) {
                                return$file['size'] >= 50000 ?
                                    "Use arquivos de até 50K" :
                                    null;
                            } )
                            ->allowedExtensions( [ 'png', 'jpg' ], "Favor, utilizar imagens" )
                        )
                )
        )
    	// ->where( 'id', $this->contaSelecionada )
    	->process( $_POST )
    	->json();
?>
