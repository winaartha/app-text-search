<?php
$row_document = $objDokumen->getAmountDocument();
$dokumens = $objDokumen->getAllDokumen();
?>
<!-- dashboard -->
<div class="col-md-10 p-5 pt-2">
  <div class="container-fluid">
    <div class="row">
      <div class="col">
        <h3><i class="fas fa-tachometer-alt mr-2"></i>DASHBOARD<hr></h3>
        <div class="row text-white">
          <div class="card bg-warning" style="width: 18rem;">
            <div class="card-body">
              <h5 class="card-title  text-uppercase">Jumlah Dokumen</h5>
              <div class="display-4"><b><?= $row_document; ?></b></div>
              <a href="dashboard.php?page=list"><p class="card-text text-white text-uppercase ml-1  ">lihat detail<i class="fas fa-angle-double-right ml-2"></i></p></a>
            </div>
          </div> 
        </div>
      </div>
    </div>
  </div>
</div>
<!-- akhir dashboard -->