<?php
$documents = array();
$documents = $objDokumen->getAllDokumen();
$nomor_table = 1;
?>
<!-- list dokumen -->
<div class="col-md-10 p-5 pt-2">
	<h3><i class="fas fa-users-cog mr-2"></i>List Dokumen<hr></h3>
	<table class="table table-hover table-bordered">
		<thead>
			<tr>
				<th scope="col" class="text-center">No</th>
				<th scope="col" class="text-center">Kode Dokumen</th>
				<th scope="col" class="text-center">Judul Dokumen</th>
				<th scope="col" class="text-center">Isi Dokumen</th>
				<th colspan="3" scope="col" class="text-center">Aksi</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($documents as $document): ?>
				<tr>
					<th scope="row"><?= $nomor_table; ?></th>
					<td><?= strtoupper($document['kode_dokumen']); ?></td>
					<td><?= $document['judul_dokumen']; ?></td>
					<td>
						<?= $document['isi_dokumen']; ?>
					</td>
					<td>
						<button class="btn-primary btn btn-editdokumen" type="button" title="Edit Dokumen" data-toggle="modal" data-target="#modalListDokumen" data-iddokumen="<?= $document['id_dokumen']; ?>">
							<i class="fas fa-edit  text-white rounded"></i>
						</button>
					</td>
					<td>
						<button class="btn-danger btn btn-hapusdokumen" type="button" title="Hapus Dokumen" data-toggle="modal" data-target="#modalListDokumen" data-iddokumen="<?= $document['id_dokumen']; ?>">
							<i class="fas fa-trash-alt text-white rounded"></i>
						</button>
					</td>
				</tr>
				<?php $nomor_table++; ?>
			<?php endforeach ?>
			<tr>
				<td colspan="4"></td>
				<td colspan="4"> 
					<a href="" class="btn btn-danger btn-block">Hapus Semua</a>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<!-- akhir list dokumen -->

<!-- Modal -->
<form action="dashboard.php?page=list" method="post">
	<div class="modal fade" id="modalListDokumen" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalListDokumenLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalListDokumenLabel">Modal title</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<input type="hidden" name="id_dokumen" value="" id="id_dokumen">
					<!-- Modal Body For Edit Dokumen -->
					<div id="modalBody-editdokumen">
						<div class="form-group">
							<label for="edit_kode_dokumen">Kode Dokumen</label>
							<input type="text" class="form-control" id="edit_kode_dokumen" name="edit_kode_dokumen" aria-describedby="edit_kode_dokumenhelp" maxlength="40" required="true" value="" >
							<small id="edit_kode_dokumenhelp" class="form-text text-muted">Misal : doc1, doc2</small>
						</div>
						<div class="form-group">
							<label for="edit_judul_dokumen">Judul Dokumen</label>
							<textarea class="form-control" required="true" name="edit_judul_dokumen" id="edit_judul_dokumen" value=""></textarea>
						</div>
						<div class="form-group">
							<label for="edit_isi_dokumen">Isi Dokumen</label>
							<textarea class="form-control" required="true" rows="20" name="edit_isi_dokumen" id="edit_isi_dokumen" value=""></textarea>
						</div>
					</div>
					<!-- End Modal Body For Edit Dokumen -->
					<!-- Modal Body For Delete Dokumen-->
					<div id="modalBody-hapusdokumen">
						<p class="lead">
							Yakin ingin hapus dokumen ini? Anda tidak dapat mengembalikannya!
						</p>
					</div>
					<!-- End Modal Body For Delete Dokumen -->
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary btn-back" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary btn-aksi" value="" name="btnAksi">Understood</button>
				</div>
			</div>
		</div>
	</div>
</form>
<?php
if (isset($_POST['btnAksi'])) {
	if ($_POST['btnAksi']=="edit") {
		$dokumen = array();
		$dokumen['id_dokumen'] = $_POST['id_dokumen'];
		$dokumen['kode_dokumen'] = $_POST['edit_kode_dokumen'];
		$dokumen['judul_dokumen'] = $_POST['edit_judul_dokumen'];
		$dokumen['isi_dokumen'] = $_POST['edit_isi_dokumen'];
		$runqueryUpdateSpesificDokumen = $objDokumen->updateSpesificDokumen($dokumen);
		if ($runqueryUpdateSpesificDokumen == true) {
			$objFlash->showSimpleFlash("BERHASIL UPDATE","success","Data dokumen berhasil diperbarui!","dashboard.php?page=list",$confirmButtonColor="#4BB543",$cancelButtonColor = "#d33","Lihat");
		}else{
			$objFlash->showSimpleFlash("GAGAL UPDATE","error","Dokumen gagal diperbarui!","dashboard.php?page=list",$confirmButtonColor="#d33",$cancelButtonColor = "#d33","Kembali");
		}
	}elseif ($_POST['btnAksi']=="hapus") {
		$id_dokumen = $_POST['id_dokumen'];
		$runQueryDeleteSpesificDokumen = $objDokumen->deleteSpesificDokumen($id_dokumen);
		if ($runQueryDeleteSpesificDokumen == true) {
			$objFlash->showSimpleFlash("BERHASIL HAPUS","success","Data dokumen berhasil dihapus!","dashboard.php?page=list",$confirmButtonColor="#4BB543",$cancelButtonColor = "#d33","Lihat");
		}else{
			$objFlash->showSimpleFlash("GAGAL HAPUS","error","Dokumen gagal dihapus!","dashboard.php?page=list",$confirmButtonColor="#d33",$cancelButtonColor = "#d33","Kembali");
		}
	}
}

?>
