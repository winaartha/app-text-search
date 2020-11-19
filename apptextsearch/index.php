<?php
require_once 'headerindex.php';
//select all dokumen
$dokumens = $objDokumen->getAllDokumen();

function cekAkurasi($jumlah_dokumen_by_query,$jumlah_dokumen_per_tahap){
	//mengcek jika jumlah dokumen yang dihasilkannya sama antara NFA dan query
	if ($jumlah_dokumen_by_query != $jumlah_dokumen_per_tahap) {
		if ($jumlah_dokumen_by_query == 0) {
			$akurasi = 0;
		}else{
			$akurasi = ($jumlah_dokumen_per_tahap / $jumlah_dokumen_by_query);
		}
	}else{
		$akurasi = 1;
	}
	return $akurasi;
}
function getAmountDokumenWithKeywordsByQuery($keywords){
	global $objDokumen;
	//cari query yang judul atau isi dokumennya LIKE keywords
	$clausa_isi_dokumen = "";
	$clausa_judul_dokumen = "";
	$clausa_all = "";
	foreach ($keywords as $indeks_keyword => $keyword) {
		$clausa_isi_dokumen.= "isi_dokumen LIKE '%$keyword%' OR ";
	}
	$clausa_all .= $clausa_isi_dokumen;
	foreach ($keywords as $indeks_keyword => $keyword) {
		$clausa_judul_dokumen.= "judul_dokumen LIKE '%$keyword%'";
		if ($indeks_keyword != count($keywords)-1) {
			$clausa_judul_dokumen.=" OR ";
		}
	}
	$clausa_all .= $clausa_judul_dokumen;
	$jumlah_dokumen_by_query = $objDokumen->getAmountDokumenWithKeywords($clausa_all);
	return $jumlah_dokumen_by_query;
}
function tampilInformasiAKhirTahap($start_time,$end_time,$jumlah_dokumen_per_tahap,$jumlah_dokumen_by_query){
	//Waktu Komputasi Per Tahap
	$waktu_komputasi = $end_time - $start_time;
	$akurasi = cekAkurasi($jumlah_dokumen_by_query,$jumlah_dokumen_per_tahap);
	echo '<div class="row mb-5">
	<div class="col">
	<div class="card bg-info text-white">
	<div class="card-footer">
	<p>
	Waktu Komputasi <strong>'.$waktu_komputasi.'</strong> sekon
	</p>
	<p>
	Jumlah dokumen ditemukan NFA : <strong>'.$jumlah_dokumen_per_tahap.'</strong> dokumen
	</p>
	<p>
	Jumlah dokumen ditemukan By Query : <strong>'.$jumlah_dokumen_by_query.'</strong> dokumen
	</p>
	<p>
	Akurasi Sistem : <strong>'.($akurasi*100).' %</strong> dokumen
	</p>
	</div>
	</div>
	</div>
	</div>';
	// Akhir waktu komputasi per tahap
}
function tampilCardSkenario($dokumen,$href,$cuplikanTeks){
	$id_dokumen = $dokumen['id_dokumen'];
	$kode_dokumen = $dokumen['kode_dokumen'];
	$judul_dokumen = $dokumen['judul_dokumen'];
	echo '
	<div class="col mb-4">
	<div class="card h-100 mb-3">
	<div class="card-body">
	<h5 class="card-title">
	<strong>['.strtoupper($kode_dokumen).']</strong>'.$judul_dokumen.'
	</h5>
	<p class="card-text">'.substr(implode("", $cuplikanTeks), 0,200).'...'.'
	</p>
	</div>
	<div class="card-footer bg-transparent">
	<a class="btn btn-primary" href='.$href.'>
	Lihat Detail
	</a>
	</div>
	</div>
	</div>';
}


?>
<!--navbar -->
<nav class="navbar navbar-expand-lg navbar-light  ">
	<div class="container">
		<a class="navbar-b" href="index.php">TEXT SEARCH</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarIndexHome" aria-controls="navbarIndexHome" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarIndexHome">
			<div class="navbar-nav ml-auto">
				<a class="nav-link text-white" href="index.php">Beranda</a>
				<a class="nav-link btn btn-primary text-white" href="dashboard.php">Upload Dokumen</a>
			</div>
		</div>
	</div>
</nav>
<!-- akhir navbar -->

<!-- search -->
<form action="index.php" method="get">
	<div class="cari text-uppercase text-center">
		<h1>Cari Artikel</h1>
	</div>
	<!-- <div class="search-box1">
		<input class="search-txt1" type="search" name="search" placeholder="Masukkan kata kunci">
		<a class="search-btn1" href="#"><i class="fas fa-search"></i></a>
	</div> -->
	<div class="container-search">
		<div class="row justify-content-center">
			<div class="col-lg-6">
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<button class="btn btn-light" type="submit" title="Semua Skenario Pencarian">
							<i class="fa fa-search" aria-hidden="true"></i>
						</button>
					</div>
					<input type="search" name="search" class="form-control input-search" placeholder="Masukkan kata kunci" aria-label="Username" required="true" title="Masukkan kata kunci. Pisahkan dengan spasi">
					<!-- Button Skenario 1 -->
					<div class="input-group-prepend">
						<button class="btn btn-info" type="submit" name="skenario1" id="skenario1" value="true" title="Pengujian Skenario 1">Skenario 1</button>
					</div>
					<!-- Akhir button skenario 1 -->
					<!-- Button skenario 2 -->
					<div class="input-group-prepend">
						<button class="btn btn-danger " type="submit" name="skenario2" id="skenario2" value="true" title="Pengujian Skenario 2">Skenario 2</button>
					</div>
					<!-- Akhir button skenario 2 -->
				</div>
			</div>
		</div>
	</div>
</form>
<!-- akhir seacrh -->

<!-- info panel -->
<div class="container info-panel">
	<div class="row " id="info-panel">
		<div class="col">
			<?php if (isset($_GET['search'])): ?>
				<?php
				// Menyambung keyword dengan tanda + untuk URL
				$keywords = explode(' ', $_GET['search']);
				$search_keywords = "";
				foreach ($keywords as $indeks_keyword => $keyword) {
					$search_keywords .= $keyword;
					if ($indeks_keyword != count($keywords)-1) {
						$search_keywords.="+";
					}
				}
				?>
				<div class="row">
					<div class="col">
						<div class="alert alert-danger" role="alert">
							<h2>Hasil Pencarian Untuk : <?= $_GET['search']; ?></h2>
						</div>
					</div>
				</div>
				<!-- Baris untuk detail dokumen -->
				<?php if (isset($_GET['id'])): ?>
					<?php
					// Cari di array dokumen yang id nya diklik
					foreach ($dokumens as $index_doc => $dokumen) {
						if ($dokumen['id_dokumen'] == $_GET['id']) {
							$row_doc = $index_doc;
							break;
						}
					}
					?>
					<!-- Baris untuk detail dokumen yang ingin dilihat -->
					<div class="row mb-5">
						<div class="col">
							<div class="card">
								<h5 class="card-header"><?= $dokumens[$row_doc]['judul_dokumen']; ?></h5>
								<div class="card-body">
									<h5 class="card-title"><strong>[<?= strtoupper($dokumens[$row_doc]['kode_dokumen']); ?>]</strong></h5>
									<p class="card-text"><?= $dokumens[$row_doc]['isi_dokumen']; ?></p>
								</div>
							</div>
						</div>
					</div>
					<!-- Akhir baris untuk detail dokumen yang ingin dilihat -->
				<?php endif ?>
				<!-- AKhir baris untuk detail dokumen -->
				<!-- Skenario Card -->
				<?php
				if (isset($_GET['skenario1']) || isset($_GET['skenario2'])) {
					$start_time_seluruhTahap = microtime(true);
					//Hitung jumlah dokumen by query
					$keywords = explode("+", $search_keywords);
					$jumlah_dokumen_by_query = getAmountDokumenWithKeywordsByQuery($keywords);
					if (isset($_GET['skenario1'])) {
						echo '
						<div class="alert alert-light" role="alert">
						<h2>SKENARIO PENGUJIAN 1</h2>
						</div>';
						echo "";
						/*
						Kumulatif :
						Tahap 1 : 0 - 49 dokumen
						Tahap 2 : 0 - 99 dokumen
						Tahap 3 : 0 - 149 dokumen
						dst (penambahan 50 dokumen)
						*/
						//1. Hitung jumlah dokumen keseluruhan
						$amount_doc = count($dokumens);
						$indeks_dokumen = 0;
						$tahap = 1;
						while ($indeks_dokumen < $amount_doc) {
							$indeks_dokumen += 50; //interval penambahan setiap tahap
							//Mengantisipasi penambahan kumulatif yang melebih total dokumen yang ada
							if ($indeks_dokumen >= $amount_doc) {
								$indeks_dokumen = $amount_doc;
							}
							echo '
							<div class="alert alert-secondary" role="alert">
							<strong>Tahap '.$tahap.'</strong>
							</div>
							';
							$start_time = microtime(true);
							$jumlah_dokumen_per_tahap = 0;
							// Dokumen Hasil Pencarian NFA
							echo '<div class="row row-cols-1 row-cols-md-3">';
							for ($i = 0; $i < $indeks_dokumen; $i++) {
								$dokumen = array();
								$dokumen = $dokumens[$i];
								$id_dokumen = $dokumen['id_dokumen'];
								$doc = $dokumen['judul_dokumen']." ".$dokumen['isi_dokumen'];
								list($ifAcceptString,$cuplikanTeks,$quintuples) = $objNFA->main($_GET['search'],$doc);
								if ($ifAcceptString==true) {
									$jumlah_dokumen_per_tahap++;
									$href = "index.php?search=".$search_keywords."&id=".$id_dokumen."&skenario1=true";
									tampilCardSkenario($dokumen,$href,$cuplikanTeks);
								}
							}
							echo '</div>';
							// AKhir dokumen Hasil pencarian NFA
							$end_time = microtime(true);
							tampilInformasiAKhirTahap($start_time,$end_time,$jumlah_dokumen_per_tahap,$jumlah_dokumen_by_query);
							$tahap++;
							echo '<br>';
						}
					}elseif (isset($_GET['skenario2'])) {
						echo '
						<div class="alert alert-light" role="alert">
						<h2>SKENARIO PENGUJIAN 2</h2>
						</div>';
						echo "";
						/*
						Penggantian :
						Tahap 1 : 0 - 49 dokumen
						Tahap 2 : 50 - 99 dokumen
						Tahap 3 : 100 - 149 dokumen
						dst (penambahan 50 dokumen)
						*/
						//1. Hitung jumlah dokumen keseluruhan
						$amount_doc = count($dokumens);
						$indeks_start_dokumen = -1;
						$indeks_end_dokumen = 0;
						$tahap = 1;
						while ($indeks_end_dokumen < $amount_doc) {
							$indeks_start_dokumen = $indeks_end_dokumen;
							$indeks_end_dokumen += 50; //interval penambahan setiap tahap
							//Mengantisipasi penambahan kumulatif yang melebih total dokumen yang ada
							if ($indeks_end_dokumen >= $amount_doc) {
								$indeks_end_dokumen = $amount_doc;
							}
							echo '
							<div class="alert alert-secondary" role="alert">
							<strong>Tahap '.$tahap.'</strong>
							</div>
							';
							$start_time = microtime(true);
							$jumlah_dokumen_per_tahap = 0;
							// Dokumen Hasil Pencarian NFA
							echo '<div class="row row-cols-1 row-cols-md-3">';
							for ($i = $indeks_start_dokumen; $i < $indeks_end_dokumen; $i++) {
								$dokumen = array();
								$dokumen = $dokumens[$i];
								$id_dokumen = $dokumen['id_dokumen'];
								$doc = $dokumen['judul_dokumen']." ".$dokumen['isi_dokumen'];
								list($ifAcceptString,$cuplikanTeks,$quintuples) = $objNFA->main($_GET['search'],$doc);
								if ($ifAcceptString==true) {
									$jumlah_dokumen_per_tahap++;
									$href = "index.php?search=".$search_keywords."&id=".$id_dokumen."&skenario2=true";
									tampilCardSkenario($dokumen,$href,$cuplikanTeks);
								}
							}
							echo '</div>';
							// AKhir dokumen Hasil pencarian NFA
							$end_time = microtime(true);
							tampilInformasiAKhirTahap($start_time,$end_time,$jumlah_dokumen_per_tahap,$jumlah_dokumen_by_query);
							$tahap++;
							echo '<br>';
						}
					}
					$end_time_seluruhTahap = microtime(true);
					//baris untuk waktu komputasi seluruh tahap
					$waktu_komputasi = $end_time_seluruhTahap - $start_time_seluruhTahap;
					echo '<div class="alert alert-danger" role="alert">
					<h2>Waktu Komputasi Keseluruhan : '.$waktu_komputasi.' detik</h2>
					</div>';
					// Akhir baris untuk waktu komputasi seluruh tahap
				}
				?>
				<!-- Akhir skenario Card -->

				<!-- Baris untuk bukan skenario 1 dan skenario 2 (all)-->
				<?php if (!isset($_GET['skenario1']) && !isset($_GET['skenario2'])): ?>
				<div class="row row-cols-1 row-cols-md-3">
					<?php 
					//catat waktu komputasi
					$start_time = microtime(true);
					$jumlah_dokumen_per_tahap = 0;
					$keywords = explode("+", $search_keywords);
					$jumlah_dokumen_by_query = getAmountDokumenWithKeywordsByQuery($keywords);
					?>
					<?php foreach ($dokumens as $dokumen): ?>
						<?php
						$id_dokumen = $dokumen['id_dokumen'];
						$doc = $dokumen['judul_dokumen']." ".$dokumen['isi_dokumen'];
						list($ifAcceptString,$cuplikanTeks,$quintuples) = $objNFA->main($_GET['search'],$doc);
						?>
						<?php if ($ifAcceptString==true): ?>
							<?php
							$jumlah_dokumen_per_tahap++;
							$href = "index.php?search=".$search_keywords."&id=".$id_dokumen;
							tampilCardSkenario($dokumen,$href,$cuplikanTeks);
							?>
						<?php endif ?>
					<?php endforeach ?>
					<?php 
						//catat waktu komputasi
					$end_time = microtime(true);
					$waktu_komputasi = $end_time - $start_time;
					?>
				</div>
				<!-- Baris untuk waktu komputasi -->
				<?php
				tampilInformasiAKhirTahap($start_time,$end_time,$jumlah_dokumen_per_tahap,$jumlah_dokumen_by_query);
				?>
				
				<!-- Akhir Baris untuk waktu komputasi -->
			<?php endif ?>
			<!-- Akhir baris untuk bukan skenario 1 dan skenario 2 (all) -->

			<!-- Baris untuk quintuples NFA -->
			<div class="row">
				<div class="col">
					<div class="alert alert-danger" role="alert">
						<h2>Quintuples NFA</h2>
						<!-- Zigma -->
						<p>
							<strong>Zigma</strong><br>
							{
								<?php
								foreach ($quintuples['zigma'] as $zigma) {
									echo $zigma." ";
								}
								?>
							}
						</p>
						<!-- End Zigma -->
						<!-- Set States -->
						<p>
							<strong>Himpunan State (Q)</strong><br>
							{
								<?php
								foreach ($quintuples['set_state'] as $indeks_states => $state) {
									echo $state;
									if ($indeks_states != count($quintuples['set_state'])-1) {
										echo ', ';
									}
								}
								?>
							}
						</p>
						<!-- End Set States -->
						<!-- Start State -->
						<p>
							<strong>Start State (q0)</strong><br>
							<?= $quintuples['start_state']; ?>
						</p>
						<!-- End Start State -->
						<!-- Final State -->
						<p>
							<strong>Final State (F)</strong><br>
							{
								<?php
								foreach ($quintuples['final_state'] as $indeks_finalstate => $final_state) {
									echo $final_state;
									if ($indeks_finalstate != count($quintuples['final_state'])-1) {
										echo ', ';
									}
								}
								?>
							}
						</p>
						<!-- End Final State -->
						<!-- Fungsi Transisi -->
						<p>
							<strong>Fungsi Transisi (delta)</strong><br>
							<?php
							for ($row = 0; $row <= count($quintuples['set_state']); $row++) {
								for ($col = 0; $col <= count($quintuples['zigma']); $col++) {
									if ($row == 0 && $col == 0) {
									}elseif ($row==0) {
									}elseif ($col==0) {
									}else{
										echo "delta(".$quintuples['fungsi_transisi'][$row][0].", ".$quintuples['fungsi_transisi'][0][$col].") = "."{".implode(', ', $quintuples['fungsi_transisi'][$row][$col])."}";
										echo '<br>';
									}
								}
							}
							?>
						</p>
						<!-- End Fungsi Transisi -->
					</div>
				</div>
			</div>
			<!-- Akhir baris untuk quintuples NFA -->
			<?php else: ?>
				<div class="alert alert-info" role="alert">
					<h4 class="display-4">Aplikasi Text Search</h4><hr>
					<h5>Anggota Kelompok</h5>
					<h6>
						[1808561004] &nbsp; Gusti Ayu Vidjaretha Wardana  <br>
						[1808561009] &nbsp; Karel Leo Rivaldo <br>
						[1808561013] &nbsp; I Made Satria Bimantara <br>
						[1808561017] &nbsp; I Nyoman Wina Artha Setiawan <br>
						[1808561022] &nbsp; Putu Bayu Baskara <br>
					</h6>
				</div>
			<?php endif ?>
			<!-- Test cetak dokumen -->
			<!-- <div class="row">
				<?php foreach ($dokumens as $dokumen): ?>
					<div class="col">
						<div class="card-body">
							<h3><?= $dokumen['kode_dokumen']; ?></h3>
							<p class="lead">
								<?php
								$string = $dokumen['judul_dokumen'];
								for ($i = 0; $i < strlen($string); $i++) {
									if (ord($string[$i]) < 32 || ord($string[$i]) > 126) {
										echo '<h1>ERROR!</h1>';
										break;
									}else{
										echo $string[$i];
									}
								}
								?>
							</p>
							<p>
								<?php
								$string = $dokumen['isi_dokumen'];
								for ($i = 0; $i < strlen($string); $i++) {
									if (ord($string[$i]) < 32 || ord($string[$i]) > 126) {
										echo '<h1>ERROR!</h1>';
										break;
									}else{
										echo $string[$i];
									}
								}
								?>
								
							</p>
						</div>
					</div>
				<?php endforeach ?>
			</div> -->
			<!-- AKhir test cetak dokumen -->
		</div>
	</div>
</div>
<!-- Akhir info panel -->

<?php
require_once 'footerindex.php';
?>