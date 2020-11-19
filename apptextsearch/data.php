<?php
// include init.php for db
require_once 'config/class.php';
// instansiasi objek Dokumen
$objDokumen = new Dokumen($conn);
if (isset($_POST['btn_editdokumen'])) {
   $id_dokumen = $_POST['id_dokumen'];
   $dokumen = $objDokumen->getSpesificDokumen($id_dokumen);
   echo json_encode($dokumen);
}


?>