<!-- Upload Dokumen -->
<div class="col-md-10 p-5 pt-2">
  <h3><i class="fas fa-newspaper mr-2"></i>UPLOAD DOKUMEN<hr></h3>
  <div class="container">
    <div class="card">
      <div class="card-header bg-primary text-white">Tambah Dokumen Baru</div>
      <div class="card-body">
        <form action="dashboard.php?page=upload" method="post">
          <div class="form-group">
            <label for="kode_dokumen">Kode Dokumen</label>
            <input type="text" class="form-control" id="kode_dokumen" name="kode_dokumen" aria-describedby="kode_dokumenhelp" maxlength="40" required="true">
            <small id="kode_dokumenhelp" class="form-text text-muted">Misal : doc1, doc2</small>
          </div>
          <div class="form-group">
            <label for="judul_dokumen">Judul Dokumen</label>
            <textarea class="form-control" required="true" name="judul_dokumen" id="judul_dokumen"></textarea>
          </div>
          <div class="form-group">
            <label for="isi_dokumen">Isi Dokumen</label>
            <textarea class="form-control" required="true" name="isi_dokumen" id="isi_dokumen"></textarea>
          </div>
          <button type="submit" class="btn btn-primary" name="btnTambahDokumenBaru" id="btnTambahDokumenBaru" value="">Tambah</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Upload Dokumen -->
<?php
if (isset($_POST['btnTambahDokumenBaru'])) {
  $data_dokumen = array();
  $data_dokumen['kode_dokumen'] = $_POST['kode_dokumen'];
  $data_dokumen['judul_dokumen'] = $_POST['judul_dokumen'];
  $data_dokumen['isi_dokumen'] = $_POST['isi_dokumen'];
  $returnRunQueryInsertNewDokumen = $objDokumen->insertNewDokumen($data_dokumen);
  if ($returnRunQueryInsertNewDokumen == 1) {
    $objFlash->showSimpleFlash("BERHASIL UPLOAD","success","Dokumen baru berhasil ditambahkan!","dashboard.php?page=upload",$confirmButtonColor="#4BB543",$cancelButtonColor = "#d33","Upload");
  }else{
    $objFlash->showSimpleFlash("GAGAL UPLOAD","error","Dokumen baru gagal ditambahkan!","dashboard.php?page=upload",$confirmButtonColor="#d33",$cancelButtonColor = "#d33","Upload Ulang");
  }

}

?>