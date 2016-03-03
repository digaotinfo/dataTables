<?php
include 'header.php';
// Parte View
?>
<!-- edit -->
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css" media="screen" title="no title" charset="utf-8">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.1/css/buttons.dataTables.min.css" media="screen" title="no title" charset="utf-8">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.1.0/css/select.dataTables.min.css" media="screen" title="no title" charset="utf-8">
<link rel="stylesheet" href="https://editor.datatables.net/extensions/Editor/css/editor.dataTables.min.css" media="screen" title="no title" charset="utf-8">

<script src="js/datatableEditor/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="js/datatableEditor/jquery.jeditable.js" type="text/javascript"></script>
<script src="js/datatableEditor/jquery-ui.js" type="text/javascript"></script>
<script src="js/datatableEditor/jquery.validate.js" type="text/javascript"></script>
<script src="js/datatableEditor/jquery.dataTables.editable.js" type="text/javascript"></script>
<script src="js/datatableEditor/dataTables.editor.min.js" type="text/javascript"></script>
<script src="js/datatableEditor/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="js/datatableEditor/dataTables.select.min.js" type="text/javascript"></script>
 -->


 <link rel="stylesheet" href="assets/dataTablesEditor/css/jquery.dataTables.min.css" media="screen" title="no title" charset="utf-8">
 <link rel="stylesheet" href="assets/dataTablesEditor/css/buttons.dataTables.min.css" media="screen" title="no title" charset="utf-8">
 <link rel="stylesheet" href="assets/dataTablesEditor/css/select.dataTables.min.css" media="screen" title="no title" charset="utf-8">
 <link rel="stylesheet" href="assets/dataTablesEditor/css/editor.dataTables.min.css" media="screen" title="no title" charset="utf-8">
 <link rel="stylesheet" href="assets/dataTablesEditor/css/responsive.dataTables.min.css" media="screen" title="no title" charset="utf-8">
 <link rel="stylesheet" href="assets/dataTablesEditor/css/editor.bootstrap.min.css" media="screen" title="no title" charset="utf-8">
 <link rel="stylesheet" href="assets/dataTablesEditor/css/buttons.bootstrap.min.css" media="screen" title="no title" charset="utf-8">
 <link rel="stylesheet" href="assets/dataTablesEditor/css/select.bootstrap.min.css" media="screen" title="no title" charset="utf-8">





 <script src="assets/dataTablesEditor/js/jquery.dataTables.min.js" type="text/javascript"></script>
 <script src="assets/dataTablesEditor/js/jquery.jeditable.js" type="text/javascript"></script>
 <script src="assets/dataTablesEditor/js/jquery-ui.js" type="text/javascript"></script>
 <script src="assets/dataTablesEditor/js/jquery.validate.js" type="text/javascript"></script>
 <script src="assets/dataTablesEditor/js/jquery.dataTables.editable.js" type="text/javascript"></script>
 <script src="assets/dataTablesEditor/js/dataTables.editor.min.js" type="text/javascript"></script>
 <script src="assets/dataTablesEditor/js/dataTables.select.min.js" type="text/javascript"></script>
 <script src="assets/dataTablesEditor/js/dataTables.buttons.min.js" type="text/javascript"></script>
 <script src="assets/dataTablesEditor/js/moment.min.js" type="text/javascript"></script><?php //habilita a edição de datas ?>
 <script src="assets/dataTablesEditor/js/dataTables.responsive.min.js" type="text/javascript"></script>
 <script src="js/datatableEditor/dataTables.keyTable.min.js" type="text/javascript"></script><?php //habilita a edição ao teclar no Tab ?>
 <script src="http://cdn.datatables.net/plug-ins/1.10.11/filtering/type-based/phoneNumber.js" type="text/javascript"></script><?php //habilita a edição ao teclar no Tab ?>


 <!-- BOOTSTRAP >>> -->
 <script src="assets/dataTablesEditor/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
 <script src="assets/dataTablesEditor/js/buttons.bootstrap.min.js" type="text/javascript"></script>
 <script src="assets/dataTablesEditor/js/dataTables.select.min.js" type="text/javascript"></script>
 <!-- <<< BOOTSTRAP -->



<script src="js/myJs/valor.js" type="text/javascript"></script>

<div class="row">

	<?php //print_r($regimes); ?>
	<table id="example" class="display_" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th>ID</th>
				<th>Descrição</th>
				<th>Tipo</th>
				<th>Operação</th>
				<th>Data</th>
			</tr>
		</thead>
	</table>
</div>
<?php

include 'footer.php';

?>
