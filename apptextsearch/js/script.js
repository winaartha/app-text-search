$(document).ready(function(){
	function removeRequiredInput(arrInput){
		for ( let i = 0 ; i< arrInput.length ;i++) {
			let id_input = "#" + arrInput[i];
			$(id_input).removeAttr("required");
		}
	}
	function setModalProperties (modal_properties) {
		$(".modal-title").html(modal_properties.modaltitle);
		$(".btn-aksi").html(modal_properties.modalfooter.btnAksi.btnName);
		$(".btn-aksi").val(modal_properties.modalfooter.btnAksi.value);
		$(".btn-aksi").removeClass(modal_properties.modalfooter.btnAksi.removeColor);
		$(".btn-aksi").addClass(modal_properties.modalfooter.btnAksi.btnColor);
		$(".btn-back").html(modal_properties.modalfooter.btnBack.btnName);
	}
	function anticipateSubmitWithEnter(){
		$(window).keydown(function(event){
		  if(event.keyCode == 13) {
				event.preventDefault();
				return false;
			}
		});
	}
	$(".btn-editdokumen").on('click',function(){
		//get id_dokumen
		let id_dokumen = $(this).data("iddokumen");
		// Hide modal body hapus dokumen
		$("#modalBody-hapusdokumen").css("display","none");
		//Munculkan modal body edit dokumen
		$("#modalBody-editdokumen").css("display","block");
		var modal_properties = {
			modaltitle : "Edit Dokumen",
			modalfooter : {
				btnAksi : {
					removeColor : "btn-danger",
					btnColor :"btn-success",
					btnName : "Edit",
					value : "edit"
				},
				btnBack :{
					btnName : "Kembali"
				}
			}
		}
		setModalProperties(modal_properties);
		// Load data dokumen
		$.ajax({
			url: 'http://localhost/apptextsearch/data.php',
			data: {id_dokumen : id_dokumen,btn_editdokumen : true },
			method: 'post',
			datatype : 'json',
			success: function(data){
				let dokumen = jQuery.parseJSON(data);
				dokumen = dokumen[0];
				$("#id_dokumen").val(dokumen['id_dokumen']);
				$("#edit_kode_dokumen").val(dokumen['kode_dokumen']);
				$("#edit_judul_dokumen").val(dokumen['judul_dokumen']);
				$("#edit_isi_dokumen").val(dokumen['isi_dokumen']);
			},
			error: function(data){
				
			}
		});
	});
	$(".btn-hapusdokumen").on('click',function(){
		//get id_dokumen
		let id_dokumen = $(this).data("iddokumen");
		console.log(id_dokumen);
		// Hide modal body edit dokumen
		$("#modalBody-editdokumen").css("display","none");
		//Munculkan modal body hapus dokumen
		$("#modalBody-hapusdokumen").css("display","block");
		var modal_properties = {
			modaltitle : "Hapus Dokumen",
			modalfooter : {
				btnAksi : {
					removeColor : "btn-primary",
					btnColor :"btn-danger",
					btnName : "Hapus",
					value :"hapus"
				},
				btnBack :{
					btnName : "Kembali",
				}
			}
		}
		setModalProperties(modal_properties);
		$("#id_dokumen").val(id_dokumen);
		let required_input = ["edit_kode_dokumen","edit_judul_dokumen","edit_isi_dokumen"];
		removeRequiredInput(required_input);


	});

});