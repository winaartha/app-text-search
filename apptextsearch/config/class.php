<?php
require_once 'init.php';
class Dokumen{
	private $conn;
	function __construct($conn){
		$this->conn = $conn;
	}
	function insertNewDokumen($data){
		$kode_dokumen = $data['kode_dokumen'];
		$judul_dokumen = $data['judul_dokumen'];
		$isi_dokumen = $data['isi_dokumen'];
		$queryInsertNewDokumen = "INSERT INTO dokumen (kode_dokumen,judul_dokumen,isi_dokumen) VALUES ('$kode_dokumen','$judul_dokumen','$isi_dokumen')";
		$runQueryInsertNewDokumen = $this->conn->query($queryInsertNewDokumen);
		if ($runQueryInsertNewDokumen==true) {
			return 1;
		}else{
			return 0;
		}
	}
	function insertDokumenIntoNewDatabase($data){
		$kode_dokumen = $data['kode_dokumen'];
		$judul_dokumen = $data['judul_dokumen'];
		$isi_dokumen = $data['isi_dokumen'];
		$queryInsertNewDokumen = "INSERT INTO dokumen (kode_dokumen,judul_dokumen,isi_dokumen) VALUES ('$kode_dokumen','$judul_dokumen','$isi_dokumen')";
		$runQueryInsertNewDokumen = $this->conn->query($queryInsertNewDokumen);
		if ($runQueryInsertNewDokumen==true) {
			return 1;
		}else{
			return 0;
		}
	}
	function updateSpesificDokumen($dokumen){
		$id_dokumen = $dokumen['id_dokumen'];
		$isi_dokumen = $dokumen['isi_dokumen'];
		$kode_dokumen = $dokumen['kode_dokumen'];
		$judul_dokumen = $dokumen['judul_dokumen'];
		$queryUpdateSpesificDokumen = "UPDATE dokumen SET kode_dokumen = '$kode_dokumen', judul_dokumen = '$judul_dokumen', isi_dokumen = '$isi_dokumen' WHERE id_dokumen = '$id_dokumen'";
		$runqueryUpdateSpesificDokumen = $this->conn->query($queryUpdateSpesificDokumen);
		return $runqueryUpdateSpesificDokumen;
	}
	function deleteSpesificDokumen($id_dokumen){
		$queryDeleteSpesificDokumen = "DELETE FROM dokumen WHERE id_dokumen = '$id_dokumen'";
		$runqueryDeleteSpesificDokumen = $this->conn->query($queryDeleteSpesificDokumen);
		return $runqueryDeleteSpesificDokumen;
	}
	function getAllDokumen(){
		$queryGetAllDocument = "SELECT * FROM dokumen";
		$runqueryGetAllDocument = $this->conn->query($queryGetAllDocument);
		$document = array();
		while ($data = $runqueryGetAllDocument->fetch_assoc()) {
			array_push($document, $data);
		}
		return $document;
	}
	function getAllDokumenWithKeywords($clausa){
		$queryGetAllDokumenWithKeywords = "SELECT * FROM dokumen WHERE ".$clausa;
		$runqueryGetAllDokumenWithKeywords = $this->conn->query($queryGetAllDokumenWithKeywords);
		$document = array();
		while ($data = $runqueryGetAllDokumenWithKeywords->fetch_assoc()) {
			array_push($document, $data);
		}
		return $document;
	}
	function getAmountDokumenWithKeywords($clausa){
		$queryGetAmountDokumenWithKeywords = "SELECT COUNT(*) AS jumlah FROM dokumen WHERE ".$clausa;
		$runqueryGetAmountDokumenWithKeywords = $this->conn->query($queryGetAmountDokumenWithKeywords);
		$data = $runqueryGetAmountDokumenWithKeywords->fetch_assoc();
		$amount = $data['jumlah'];
		return $amount;
	}
	function getSpesificDokumen($id_dokumen){
		$queryGetSpesificDokumen = "SELECT * FROM dokumen WHERE id_dokumen = '$id_dokumen'";
		$runqueryGetSpesificDokumen = $this->conn->query($queryGetSpesificDokumen);
		$document = array();
		while ($data = $runqueryGetSpesificDokumen->fetch_assoc()) {
			array_push($document, $data);
		}
		return $document;
	}
	function getAmountDocument(){
		$queryGetAmountDocument = "SELECT COUNT(*) AS amount_doc FROM dokumen";
		$runQueryGetAmountDocument = $this->conn->query($queryGetAmountDocument);
		$data = $runQueryGetAmountDocument->fetch_assoc();
		$amount_doc = $data['amount_doc'];
		return $amount_doc;
	}
}
class NFA{
	function delta($fungsi_transisi,$state,$simbol_input){
		//i_row dan i_col untuk mencatan indeks baris dan indeks kolom pada matriks fungsi transisi
		$i_row = null;
		$i_col = null;
		//cari letak indeks nama state dan simbol input
		for ($indeks_row = 0; $indeks_row < count($fungsi_transisi); $indeks_row++) {
		//cek nama state
			if ($state == $fungsi_transisi[$indeks_row][0]) {
				$i_row = $indeks_row;
			}
		}
		for ($indeks_col = 0; $indeks_col < count($fungsi_transisi[0]); $indeks_col++) {
			if ($simbol_input == $fungsi_transisi[0][$indeks_col]) {
				$i_col = $indeks_col;
			}
		}
		//return value berupa himpunan state
		$return_delta = array();
		$return_delta = $fungsi_transisi[$i_row][$i_col];
		return $return_delta;
	}
	function delta_topi($result_set_before,$simbol_input,$fungsi_transisi){
		$result_set = array();
		$return_delta = array();
		foreach ($result_set_before as $state) {
			if (!empty($state)) {
				$return_delta = $this->delta($fungsi_transisi,$state,$simbol_input);
				//unionkan
				$this->union($result_set,$return_delta);
			}

		}
		return $result_set;
	}
	function union(&$wadah_data,$isi_data){
		//pass by reference untuk result_set_before
		//return_delta akan masuk ke result_set (wadah)
		foreach ($isi_data as $isi) {
			$isanyvalue = false;
			foreach ($wadah_data as $wadah) {
				if ($isi == $wadah || $isi=="") {
					$isanyvalue=true;
					break;
				}
			}
			if ($isanyvalue==false) {
			//masukin isi ke wadah
				array_push($wadah_data, $isi);
			}
		}
	}
	function checkIfStringAccepted($last_result_set, $final_state){
		$ifAcceptedString = false;
		foreach ($final_state as $f_state) {
			foreach ($last_result_set as $last_state) {
				if ($f_state==$last_state) {
					$ifAcceptedString = true;
					break;
				}
			}
		}
		if ($ifAcceptedString==true) {
			return 1;
		}else{
			return 0;
		}
	}
	function checkIfCharIsSpecialCharacter($char){
		//sc -> [33-47],[58-64],[91-96],[123-126]
		//spc -> 32
		$ascii_char = ord($char);
		if(($ascii_char >= 33 && $ascii_char <=47) || ($ascii_char >= 58 && $ascii_char <=64) || ($ascii_char >= 91 && $ascii_char <=96) || ($ascii_char >= 123 && $ascii_char <=126) || $ascii_char == 32){
			return 1;
		}else{
			return 0;
		}
	}
	function inputStateParams($keywords){
		 /*
		q0 --> pasti state awal
		q1 --> state penampung
		q2,q3,...,qn --> state keyword[1]
		q(n+1),...,qm --> state keyword[2]
		dst
		*/
		$set_state = array(); //Q
		$final_state = array(); //F
		$start_state = "q0"; //q0
		$state_penampung = "q1"; //q1
		array_push($set_state, $start_state);
		array_push($set_state, $state_penampung);
		$indeks_q = 2;
		foreach ($keywords as $keyword) {
			for ($i = 0; $i < strlen($keyword); $i++) {
				$str_q = "q".$indeks_q;
				$indeks_q++;
				array_push($set_state, $str_q);
				//state yang paling terakhir untuk setiap keyword akan masuk sebagai final state
				if($i==strlen($keyword)-1){
					array_push($final_state, $str_q);
				}
			}
		}
		return array($set_state,$start_state,$final_state,$state_penampung);
	}
	function findFungsiTransisi($set_state,$sigma,$start_state,$state_penampung,$final_state,$keywords){
		/*
		baris 1 --> q0
		baris 2 --> q1 (state penampung)
		baris 3,4,..,n --> keyword 1
		baris (n+1),...,m --> keyword 2
		dst
		*/
		$fungsi_transisi = array(array());
		//penamaan tabel fungsi transisi
		for ($row = 0; $row <= count($set_state); $row++) {
			for ($col = 0; $col <= count($sigma); $col++) {
				if ($row == 0 && $col == 0) {
					//skip
					$fungsi_transisi[$row][$col] = null;
				}elseif ($row==0) {
					//penamaan kolom
					$fungsi_transisi[$row][$col] = $sigma[$col-1];
				}elseif ($col==0) {
					//penamaan baris
					$fungsi_transisi[$row][$col] = $set_state[$row-1];
				}else{
					//here at least row = 1, col = 1
					//fungsi transisi delt(qx,sigma)
					//siapkan array pada koordinat ini yang nantinya menampung hasil pemetaan
					$fungsi_transisi[$row][$col]= array();
				}
			}
		}
		//Membuat mesin NFA otomatis dari setiap keywords
		/*
		1. Untuk q0 (start_state) untuk sc dan spc sudah pasti ke q0
		sc --> indeks sigma 0-31
		spc --> indeks sigma 94
		*/
		for ($col = 1; $col <= count($sigma) ; $col++) {
			$indeks_sigma = $col-1;
			if (($indeks_sigma>=0 && $indeks_sigma<=31)||($indeks_sigma==94)) {
				array_push($fungsi_transisi[1][$col], $start_state);
			}
			array_push($fungsi_transisi[1][$col], $state_penampung);
		}
		/*
		2. untuk q1 (state_penampung) untuk spc ke q0 untuk semua sigma ke q1
		*/
		for ($col = 1; $col <= count($sigma); $col++) {
			$indeks_sigma = $col-1;
			if ($indeks_sigma==94) {
				//karakter spasi
				array_push($fungsi_transisi[2][$col], $start_state);
			}
			//for all sigma
			array_push($fungsi_transisi[2][$col], $state_penampung);
		}
		/*
		3. Cari fungsi transisi untuk setiap keyword yang ada.
		q2,q3,...,qn -->keyword 1
		q(n+1),...,qm --> keyword 2
		dst
		*/
		$kumulatif_index_set_state = 0;
		$index_start_state = 0;
		$index_last_state = 2;
		for ($idx_keywords = 0; $idx_keywords < count($keywords); $idx_keywords++) {
			$keyword = $keywords[$idx_keywords]; //memecah array keywords menjadi setiap keyword
			$index_start_state = $index_last_state + 1;
			//Pemetaan karakter pertama dari setiap keyword pada q0 sudah pasti ke q(index_start_state-1)
			$nama_state = "q".($index_start_state-1);
			array_push($fungsi_transisi[1][array_search($keyword[0], $sigma)+1],$nama_state);
			//pemetaan untuk karakter sisa dari setiap keyword
			for ($idx_keyword = 1; $idx_keyword < strlen($keyword); $idx_keyword++) {
				$keyword_char = $keyword[$idx_keyword];
				$nama_state = "q".$index_start_state;
				array_push($fungsi_transisi[$index_start_state][array_search($keyword_char, $sigma)+1],$nama_state);
				$index_start_state++;
			}
			//pemetaan untuk setiap final state pada setiap keyword
			for ($col = 1; $col <= count($sigma); $col++) {
				$indeks_sigma = $col-1;
				//jika special karakter atau spasi maka ke q(indeks_start_state)
				if (($indeks_sigma>=0 && $indeks_sigma<=31) || ($indeks_sigma == 94)) {
					array_push($fungsi_transisi[$index_start_state][$col], $final_state[$idx_keywords]);
				}
				//untuk seluruh sigma maka ke q1
				array_push($fungsi_transisi[$index_start_state][$col], $state_penampung);
			}
			//perbarui index_last_state 
			$index_last_state = $index_start_state;
		}
		return $fungsi_transisi;
	}
	function inputSigma(){
		$temp = array(); //untuk mengkategorikan sementara, nantinya baru dipush ke array sebenarnya
		$temp['sc'] = array();
		$temp['nc'] = array();
		$temp['ac'] = array();
		$temp['spc'] = array();
		/* ASCII
		sc -> [33-47],[58-64],[91-96],[123-126]
		ac -> [65-90], [97-122]
		nc -> [48-57]
		spc -> [32]
		*/
		//Input for sigma
		for ($desimal_ascii = 32; $desimal_ascii <= 126; $desimal_ascii++) {
			if ($desimal_ascii==32) {
				array_push($temp['spc'], chr($desimal_ascii));
			}elseif (($desimal_ascii>=33 && $desimal_ascii<=47) || ($desimal_ascii>=58 && $desimal_ascii<=64) || ($desimal_ascii>=91 && $desimal_ascii<=96) || ($desimal_ascii>=123 && $desimal_ascii<=126)) {
				array_push($temp['sc'], chr($desimal_ascii));
			}elseif (($desimal_ascii>=65 && $desimal_ascii<=90) || ($desimal_ascii>=97 && $desimal_ascii<=122)) {
				array_push($temp['ac'], chr($desimal_ascii));
			}elseif(($desimal_ascii>=48 && $desimal_ascii<=57)){
				array_push($temp['nc'], chr($desimal_ascii));
			}
		}
		$sigma = array();
		foreach ($temp as $indeks_temp => $data) {
			foreach ($data as $karakter) {
				array_push($sigma, $karakter);
			}
		}
		return $sigma;
	}
	function main($kata_kunci,$dokumen_uji){
		$keywords = array();
		$quintuples = array();
		$quintuples['zigma'] = array();
		$quintuples['start_state'] = array();
		$quintuples['final_state'] = array();
		$quintuples['fungsi_transisi'] = array();
		$quintuples['set_state'] = array();
		//pecah kata kunci dengan spasi
		$keywords = explode(" ", $kata_kunci);
		//1. Input Sigma with printable ASCII
		/*
		indeks 0 - 31 = sc for special karakter
		indeks 32 - 41 = nc for numerical karakter
		indeks 42 - 93 = ac for alphabet karakter
		indeks 94 = spc for space karakter
		*/
		$sigma = $this->inputSigma();
		$quintuples['zigma'] = $sigma;
		//2. Input Q, q0, F
		$set_state = array();
		$final_state = array();
		list($set_state,$start_state,$final_state,$state_penampung) = $this->inputStateParams($keywords);
		$quintuples['set_state'] = $set_state;
		$quintuples['start_state'] = $start_state;
		$quintuples['final_state'] = $final_state;
		//3. Find Fungsi transisi delta
		$fungsi_transisi = array();
		$fungsi_transisi = $this->findFungsiTransisi($set_state,$sigma,$start_state,$state_penampung,$final_state,$keywords);
		$quintuples['fungsi_transisi'] = $fungsi_transisi;

		//-langkah basis induksi
		$result_set_before = array();
		array_push($result_set_before, $start_state);
		//4. Input dokumen_uji sebagai string_uji
		$string_uji = $dokumen_uji;
		$cuplikan_teks = array();
		//5. Algoritma Inti
		$ifAcceptString = null;
		for ($index_string_uji = 0; $index_string_uji < strlen($string_uji); $index_string_uji++) {
			$ifAcceptString = false;
			//langkah induksi
			$result_set_before =  $this->delta_topi($result_set_before,$string_uji[$index_string_uji],$fungsi_transisi);
			//masukkan karakter per karakter ke dalam cuplikan_teks
			array_push($cuplikan_teks, $string_uji[$index_string_uji]);
			//check jika result_set_before telah ada yang mengandung final state 
			if($this->checkIfStringAccepted($result_set_before,$final_state)){
				//kalau indeks saat ini sudah berada diakhir atau indeks berikutnya belum berada di akhir dokumen
				if($index_string_uji == strlen($string_uji)-1){
					$ifAcceptString = true;
					break;
				}elseif (($index_string_uji+1) < strlen($string_uji)-1) {
					//cek jika karakter berikutnya merupakan spesial karakter
					if($this->checkIfCharIsSpecialCharacter($string_uji[$index_string_uji+1])){
						$ifAcceptString = true;
						break;
					}
				}
			}
		}
		return array($ifAcceptString,$cuplikan_teks,$quintuples);
	}
}

class Flasher{
	function showSimpleFlash($title,$icon,$text,$url,$confirmButtonColor="#22bb33",$cancelButtonColor = "#d33",$confirmButtonText){
		echo "<script>Swal.fire({
			title: '$title',
			icon:'$icon',
			text: '$text',
			showCancelButton: false,
			confirmButtonColor: '$confirmButtonColor',
			cancelButtonColor: '$cancelButtonColor',
			confirmButtonText: '$confirmButtonText'
			}).then((result) => {
				document.location.href = '$url';
			})";
			echo ' </script>';
		}
	}
// instansiasi objek Dokumen
	$objDokumen = new Dokumen($conn);
	$objFlash = new Flasher();
	$objNFA = new NFA();


	?>