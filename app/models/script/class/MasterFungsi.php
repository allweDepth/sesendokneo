<?php
// require_once("class/FormulaParser.php");
// require 'init.php';
use FormulaParser\FormulaParser;

class MasterFungsi
{
	//get tabel data
	public function getTabel($tbl = '', $nama_tabel = '', $get_data = [], $jmlhalaman = 0, $halaman = 1, $jumlah_kolom = 1, $type_user = 'user', $value_dinamic = [])
	{
		//var_dump("dmn($get_data)");
		//ambil data user untuk warna
		$user = new User();
		$user->cekUserSession();
		$type_user = $_SESSION["user"]["type_user"];
		$id_user = $_SESSION["user"]["id"];
		$DB = DB::getInstance();
		$userAktif = $DB->getWhereCustom('user_sesendok_biila', [['id', '=', $id_user]]);
		$jumlahArray = is_array($userAktif) ? count($userAktif) : 0;
		$classRow = '';

		if ($jumlahArray > 0) {
			foreach ($userAktif[0] as $key => $value) {
				${$key} = $value;
			}
		}
		if ($warna_tbl != '' && $warna_tbl != 'non') {
			$classRow = ' class="' . $warna_tbl . '"';
		}
		$pagination = '';
		$pagination1 = '';
		$pagination = '';
		$paginationnext = '';
		$pagination2 = '';
		$rowData = ['tbody' => '', 'tfoot' => ''];
		//var_dump($nama_tabel,$get_data, $jmlhalaman , $halaman,$jumlah_kolom);
		//var_dump($jumlah_kolom);
		//$rowData['sumData'] =sizeof($get_data);
		$warna = 'green';
		//var_dump("dmn($myrow)");
		// jika tabel mengganti thead
		switch ($tbl) {
			case 'create_surat':
				$rowData['thead'] = trim('<tr>
                        <th>NOMOR</th>
                        <th>TANGGAL</th>
                        <th>URAIAN</th>
                        <th>JENIS</th>
                        <th>FILE</th>
                        <th>KETERANGAN</th>
                        <th class="collapsing">AKSI</th>
                    </tr>');
				break;
			case 'register_surat':
				$rowData['thead'] = trim('<tr>
                        <th>NOMOR</th>
                        <th>TANGGAL</th>
                        <th>URAIAN</th>
                        <th>JENIS</th>
                        <th>SUB JENIS</th>
                        <th>FILE</th>
                        <th>KETERANGAN</th>
                        <th class="collapsing">AKSI</th>
                    </tr>');
				break;
			case 'sk_asn':
				$rowData['thead'] = trim('<tr>
                        <th>SURAT KEPUTUSAN</th>
                        <th>NOMOR</th>
                        <th>TANGGAL</th>
                        <th>PEMBERI TUGAS</th>
                        <th>FILE</th>
                        <th>KETERANGAN</th>
                        <th class="collapsing">AKSI</th>
                    </tr>');
				break;
			case 'realisasi':
				$rowData['thead'] = trim('<tr>
                        <th>NAMA PAKET</th>
                        <th>URAIAN KOMPONEN</th>
                        <th>VOLUME</th>
                        <th>REALISASI</th>
                        <th>TANGGAL</th>
                        <th>FILE</th>
                        <th>KETERANGAN</th>
                        <th class="collapsing">AKSI</th>
                    </tr>');
				break;
			case 'berita':
				$rowData['thead'] = trim('<tr>
                <th>KELOMPOK</th>
                <th>JUDUL</th>
                <th>KETERANGAN</th>
                <th class="collapsing">AKSI</th>
            </tr>');
				break;
			case 'users':
				$rowData['thead'] = trim('<tr>
                <th>NAMA</th>
                <th>NIP</th>
                <th>USERNAME</th>
                <th>ORGANISASI</th>
                <th>TAHUN</th>
                <th>LOGIN</th>
                <th>KONTAK PERSON</th>
                <th>KETERANGAN</th>
                <th class="collapsing">AKSI</th>
            </tr>');
				break;
			case 'asn':
				$rowData['thead'] = trim('<tr>
                            <th>NAMA</th>
                            <th>NIP</th>
                            <th>JABATAN</th>
                            <th>TEMPAT LAHIR</th>
                            <th>STATUS</th>
                            <th>KETERANGAN</th>
                            <th class="collapsing">AKSI</th>
                        </tr>');
				break;
			case 'daftar_paket':
				$rowData['thead'] = trim('<tr>
                            <th>URAIAN</th>
                            <th>PAGU</th>
                            <th>NILAI KONTRAK</th>
                            <th>PPK</th>
                            <th>KETERANGAN</th>
                            <th class="collapsing">AKSI</th>
                        </tr>');
				break;
			case 'dppa':
			case 'renja_p':
			case 'dpa':
			case 'renja':
				$rowData['thead'] = trim('<tr>
                            <th>KODE KOMPONEN</th>
                            <th>URAIAN</th>
                            <th>KOMPONEN</th>
                            <th>KOEFISIEN</th>
                            <th>HARGA SATUAN</th>
                            <th>TOTAL</th>
                            <th>KETERANGAN</th>
                            <th class="collapsing">AKSI</th>
                        </tr>');
				break;
			case 'sub_keg_dpa':
			case 'sub_keg_renja':
				$rowData['thead'] = trim('<tr>
                            <th>KODE KOMPONEN</th>
                            <th>URAIAN</th>
                            <th>PAGU ANGGARAN</th>
                            <th>JUMLAH RINCIAN</th>
                            <th>PAGU ANGGARAN PERUBAHAN</th>
                            <th>JUMLAH RINCIAN PERUBAHAN</th>
                            <th class="collapsing">AKSI</th>
                        </tr>');
				break;
			case 'renstra':
				$rowData['thead'] = '<tr class="center aligned">
                <th>Kode</th>
                <th>Program dan Kegiatan</th>
                <th>Satuan</th>
                <th>Indikator Kinerja</th>
                <th>Data Capaian Awal</th>
                <th class="collapsing">Jumlah</th>
                <th>Kondisi Kinerja Akhir Renstra</th>
                <th class="collapsing">AKSI</th>
                </tr>';
				// $rowData['thead'] = '<tr class="center aligned">
				// <th rowspan="3">Kode</th>
				// <th rowspan="3" class="collapsing">Program dan Kegiatan</th>
				// <th rowspan="3">Satuan</th>
				// <th rowspan="3">Indikator Kinerja</th>
				// <th rowspan="3">Data Capaian Awal</th>
				// <th colspan="11">Target Kinerja Program dan Kerangka Pendanaan</th>
				// <th class="collapsing" rowspan="3">Jumlah</th>
				// <th class="collapsing" rowspan="3">AKSI</th>
				// </tr>
				// <tr class="center aligned">
				//     <th colspan="2">Tahun-1</th>
				//     <th colspan="2">Tahun-2</th>
				//     <th colspan="2">Tahun-3</th>
				//     <th colspan="2">Tahun-4</th>
				//     <th colspan="2">Tahun-5</th>
				//     <th rowspan="2">Kondisi Kinerja Akhir Renstra</th>
				// </tr>
				// <tr class="center aligned">
				//     <th>Target</th>
				//     <th>Rp.</th>
				//     <th>Target</th>
				//     <th>Rp.</th>
				//     <th>Target</th>
				//     <th>Rp.</th>
				//     <th>Target</th>
				//     <th>Rp.</th>
				//     <th>Target</th>
				//     <th>Rp.</th> </tr>';

				break;
			case 'tujuan_sasaran_renstra':
				$rowData['thead'] = trim('<tr>
                            <th>KELOMPOK</th>
                            <th>URAIAN</th>
                            <th>KETERANGAN</th>
                            <th class="collapsing">AKSI</th>
                        </tr>');
				break;
			case 'rekanan':
				$rowData['thead'] = '<tr class="center aligned">
                <th rowspan="2">URAIAN</th>
                <th rowspan="2">ALAMAT</th>
                <th rowspan="2">NPWP</th>
                <th rowspan="2">DIREKTUR</th>
                <th colspan="4">AKTA PENDIRIAN</th>
                <th rowspan="2" class="collapsing">FILE</th>
                <th rowspan="2" class="collapsing">KETERANGAN</th>
                <th class="collapsing" rowspan="2">AKSI</th>
            </tr>';
				break;
			case 'hspk':
			case 'sbu':
			case 'asb':
			case 'ssh':
				//Kode Komponen	Uraian Komponen	Spesifikasi	Satuan	Harga Satuan	TKDN	Aksi
				$rowData['thead'] = trim('<tr>
                                <th class="collapsing">Kode Komponen</th>
                                <th class="five wide">Uraian Komponen</th>
                                <th>Spesifikasi</th>
                                <th>Satuan</th>
                                <th>Harga Satuan</th>
                                <th>TKDN</th>
                                <th class="collapsing">AKSI</th>
                            </tr>');
				break;
			case 'wilayah':
				$rowData['thead'] = trim('<tr class="center aligned">
                            <th rowspan="2" class="collapsing">Kode</th>
                            <th rowspan="2">Uraian</th>
                            <th rowspan="2">Status</th>
                            <th colspan="3">Jumlah</th>
                            <th rowspan="2">Luas Wilayah (km2)</th>
                            <th rowspan="2">Jumlah Penduduk (jiwa)</th>
                            <th rowspan="2">keterangan</th>
                            <th rowspan="2">File</th>
                            <th rowspan="2" class="collapsing">AKSI</th>
                        </tr>
                        <tr class="center aligned">
                            <th>KEC</th>
                            <th>KEL</th>
                            <th>DESA</th>
                    </tr>');
				break;
			case 'satuan':
				$rowData['thead'] = trim('<tr>
                            <th>VALUE</th>
                            <th>ITEM</th>
                            <th>SEBUTAN LAIN</th>
                            <th>KETERANGAN</th>
                            <th class="collapsing">AKSI</th>
                        </tr>');
				break;
			case 'organisasi':
				$rowData['thead'] = trim('<tr>
                            <th class="collapsing">Kode</th>
                            <th>Uraian</th>
                            <th>Kepala SKPD</th>
                            <th>keterangan</th>
                            <th class="collapsing">AKSI</th>
                        </tr>');
				break;
			case 'mapping':
				$rowData['thead'] = trim('<tr>
                        <th class="collapsing">Kode Neraca</th>
                        <th>Uraian Neraca</th>
                        <th>Kode Akun</th>
                        <th>Uraian Akun</th>
                        <th>kelompok</th>
                        <th>keterangan</th>
                        <th class="collapsing">AKSI</th>
                    </tr>');
				break;
			case 'sumber_dana':
				$rowData['thead'] = trim('<tr><th class="collapsing">KODE KOMPONEN</th>
                <th class="five wide">KOMPONEN</th>
                <th>KETERANGAN</th>
                        <th class="collapsing">AKSI</th>
                    </tr>');
				break;
			case 'aset':
			case 'akun_belanja':
				$rowData['thead'] = trim('<tr>
                        <th class="collapsing">KODE KOMPONEN</th>
                        <th class="five wide">KOMPONEN</th>
                        <th>KETERANGAN</th>
                        <th class="collapsing">AKSI</th>
                    </tr>');
				break;
			case 'bidang_urusan':
			case 'prog':
			case 'keg':
			case 'sub_keg':
				$rowData['thead'] = trim('<tr>
                <th class="collapsing">KODE KOMPONEN</th>
                <th>NOMENKLATUR URUSAN</th>
                <th>KINERJA</th>
                <th>INDIKATOR</th>
                <th>SATUAN</th>
                <th>KETERANGAN</th>
                <th class="collapsing">AKSI</th>
            </tr>');
				break;
			case 'user':
				break;
			default:
				# code...
				break;
		}
		if (isset($rowData['thead'])) {
			$rowData['thead'] = preg_replace('/(\s\s+|\t|\n)/', ' ', $rowData['thead']);
		}

		$jumlahArray = is_array($get_data) ? count($get_data) : 0;
		if ($jumlahArray > 0) {
			$myrow = 0;
			switch ($tbl) {
				case 'daftar_paket':
				case 'sub_keg_renja':
				case 'sub_keg_dpa':
				case 'renja':
				case 'renja_p':
				case 'dpa':
				case 'dppa':
				case 'renstra':
				case 'tujuan_renstra':
				case 'sasaran_renstra':
				case 'tujuan_sasaran_renstra':
				case 'realisasi':
					$kunci_renstra = $value_dinamic['kunci_renstra'];
					$kunci_renja = $value_dinamic['kunci_renja'];
					$kunci_dpa = $value_dinamic['kunci_dpa'];
					$kunci_renja_p = $value_dinamic['kunci_renja_p'];
					$kunci_dppa = $value_dinamic['kunci_dppa'];
					$kunci_paket = $value_dinamic['kunci_paket'];
					$kunci_realisasi = $value_dinamic['kunci_realisasi'];
					$setujui_renstra = $value_dinamic['setujui_renstra'];
					$setujui_renja = $value_dinamic['setujui_renja'];
					$setujui_dpa = $value_dinamic['setujui_dpa'];
					$setujui_renja_p = $value_dinamic['setujui_renja_p'];
					$setujui_dppa = $value_dinamic['setujui_dppa'];
					break;

				default:
					# code...
					break;
			}
			foreach ($get_data as $row) {
				$myrow++;
				$divAwalAngka  = '<div contenteditable rms onkeypress="return rumus(event);">';
				switch ($tbl) {
					case 'create_surat':
						$file = $row->file;
						$fileTag = '';
						if (strlen($file ?? '')) {
							$fileTag = '<a class="ui primary label" href="' . $file . '" target="_blank">Ungguh</a>';
						}
						$buttons = '<div class="ui icon basic mini buttons">
                        <button class="ui button" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                        <button class="ui red button" name="del_row"  jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
						$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="nomor">'  . $row->nomor . '</td>
                                    <td klm="tgl_surat_dibuat">'  . $row->tanggal . '</td>
                                    <td klm="uraian">' . $row->uraian . '</td>
                                    <td klm="jenis_naskah_dinas">'  . $row->jenis_naskah_dinas . '</td>
                                    <td klm="sub_sifat">'  . $row->sub_sifat . '</td>
                                    <td klm="file">' . $fileTag . '</td>
                                    <td klm="keterangan">' . $row->keterangan . '</td>
                                    <td>' . $buttons . '</td>
                                </tr>');
						break;
					case 'register_surat':
						$file = $row->file;
						$fileTag = '';
						if (strlen($file ?? '')) {
							$fileTag = '<a class="ui primary label" href="' . $file . '" target="_blank">Ungguh</a>';
						}
						$buttons = '<div class="ui icon basic mini buttons">
                        <button class="ui button" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                        <button class="ui red button" name="del_row"  jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
						$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="nomor">'  . $row->nomor . '</td>
                                    <td klm="tgl_surat_dibuat">'  . $row->tanggal . '</td>
                                    <td klm="uraian">' . $row->uraian . '</td>
                                    <td klm="jenis_naskah_dinas">'  . $row->jenis_naskah_dinas . '</td>
                                    <td klm="sub_sifat">'  . $row->sub_sifat . '</td>
                                    <td klm="file">' . $fileTag . '</td>
                                    <td klm="keterangan">' . $row->keterangan . '</td>
                                    <td>' . $buttons . '</td>
                                </tr>');
						break;
					case 'sk_asn':
						$buttons = '';
						$buttonEdit = '';
						$divAwal = '';
						$divAkhir = '';
						$deactivate = '';
						$divAwal = '<div contenteditable>';
						$divAkhir = '</div>';
						$divAwalAngka  = '<div contenteditable rms onkeypress="return rumus(event);">';
						$file = $row->file;
						$fileTag = '';
						if (strlen($file ?? '')) {
							$fileTag = '<a class="ui primary label" href="' . $file . '" target="_blank">Ungguh</a>';
						}

						$buttons = '<div class="ui icon basic mini buttons">
                        <button class="ui button" name="modal_show" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"' . $deactivate . '><i class="edit outline blue icon"></i></button>
                        <button class="ui red button" name="del_row"  jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
						$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="tentang">' . $row->tentang . '</td>
                                    <td klm="nomor">'  . $row->nomor . '</td>
                                    <td klm="tgl_surat_dibuat">'  . $row->tgl_surat_dibuat . '</td>
                                    <td klm="nama_pemberi_tgs">'  . $row->nama_pemberi_tgs . '</td>
                                    <td klm="file">' . $fileTag . '</td>
                                    <td klm="keterangan">' . $row->keterangan . '</td>
                                    <td>' . $buttons . '</td>
                                </tr>');
						break;

					case 'realisasi':
						// deactivate="1" maksudnya form disabled tidak bisa dirubah
						$buttons = '';
						$buttonEdit = '';
						$divAwal = '';
						$divAkhir = '';
						$deactivate = '';
						if ($kunci_realisasi <= 0) {
							$divAwal = '<div contenteditable>';
							$divAkhir = '</div>';
							$divAwalAngka  = '<div contenteditable rms onkeypress="return rumus(event);">';
						} else {
							$deactivate = ' deactivate="1"';
						}
						$buttons = '<div class="ui icon basic mini buttons">
                        <button class="ui button" name="modal_show" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"' . $deactivate . '><i class="edit outline blue icon"></i></button>
                        <button class="ui red button" name="del_row"  jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
						$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="ket_paket">' . $row->ket_paket . '</td>
                                    <td klm="ket_uraian_paket">'  . $row->ket_uraian_paket . '</td>
                                    <td klm="vol">'  . number_format((float)$row->vol, 2, ',', '.') . '</td>
                                    <td klm="jumlah">'  . number_format((float)$row->jumlah, 2, ',', '.') . '</td>
                                    <td klm="tanggal">' . $row->tanggal . '</td>
                                    <td klm="file">' . $row->file . '</td>
                                    <td klm="keterangan">' . $row->keterangan . '</td>
                                    <td>' . $buttons . '</td>
                                </tr>');
						break;
					case 'berita':

						if ($type_user == 'admin') {
							$divAwal = '<div contenteditable>';
							$divAkhir = '</div>';
							$buttons = '<div class="ui icon basic mini buttons">
                        <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                        <button class="ui red button" name="del_row"  jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
							$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                <td klm="kelompok">' .  $row->kelompok . '</td>
                                <td klm="judul">' . $row->judul . '</td>
                                <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                <td>' . $buttons . '</td>
                            </tr>');
						}
						break;
					case 'users':
						if ($type_user == 'admin') {
							$divAwal = '<div contenteditable>';
							$divAkhir = '</div>';
							$buttons = '<div class="ui icon basic mini buttons">
                            <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                            <button class="ui red button" name="del_row"  jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
							$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="nama">' . $divAwal . $row->nama . $divAkhir . '</td>
                                    <td klm="nip">' . $row->nip . '</td>
                                    <td>' . $row->username  . '</td>
                                    <td klm="nama_org">' . $divAwal . $row->nama_org . $divAkhir . '</td>
                                    <td klm="tahun">' . $row->tahun . '</td>
                                    <td klm="tgl_login">' . $row->tgl_login  . '</td>
                                    <td klm="kontak_person">' . $row->kontak_person  . '</td>
                                    <td klm="ket">' . $divAwal . $row->ket . $divAkhir . '</td>
                                    <td>' . $buttons . '</td>
                                </tr>');
						}

						break;
					case 'asn':
						$buttons = '';
						$divAwal = '';
						$divAkhir = '';
						$file = $row->file_photo;
						$fileTag = '';
						if (strlen($file ?? '')) {
							$fileTag = '<a class="ui primary label" href="' . $file . '" target="_blank">Ungguh</a>';
						}
						if ($type_user == 'admin') {
							$divAwal = '<div contenteditable>';
							$divAkhir = '</div>';
							$buttons = '<div class="ui icon basic mini buttons">
                            <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                            <button class="ui red button" name="del_row"  jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
						}
						$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="nama">' . $divAwal . $row->nama . $divAkhir . '</td>
                                    <td klm="nip">' . $row->nip . '</td>
                                    <td klm="jabatan">' . $divAwal . $row->jabatan . $divAkhir . '</td>
                                    <td klm="t4_lahir">' . $divAwal . $row->t4_lahir . $divAkhir . '</td>
                                    <td klm="status">' . $divAwal . $row->status . $divAkhir . '</td>
                                    <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                    <td>' . $buttons . '</td>
                                </tr>');
						break;
					case 'daftar_paket':
						$tbl_button = ($tbl == 'sub_keg_renja') ? 'renja' : 'dpa';
						$tbl_button_p = ($tbl == 'sub_keg_renja') ? 'renja_p' : 'dppa';
						$buttons = '';
						$buttonEdit = '';
						$divAwal = '';
						$divAkhir = '';
						$deactivate = '';
						if ($kunci_realisasi <= 0) {
							$divAwal = '<div contenteditable>';
							$divAkhir = '</div>';
							$divAwalAngka  = '<div contenteditable rms onkeypress="return rumus(event);">';
							$buttonDel = '<button class="ui red button" name="del_row"  jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"' . $deactivate . '><i class="trash alternate outline red icon"></i></button>';
						} else {
							$buttonDel = '';
							$deactivate = ' deactivate="1"';
						}
						$buttonEdit = '<div class="ui floating scrolling dropdown icon button lainnya">
                            <i class="wrench icon"></i>
                                <div class="menu">
                                    <div class="item" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"' . $deactivate . '><i class="edit outline blue icon"></i>Edit</div>
                                    <div class="divider"></div>
                                    <div class="item" name="flyout" jns="upload" tbl="' . $tbl . '" dok="file_kontrak"' . $deactivate . '><i class="upload blue icon"></i>Unggah File Kontrak</div>
                                    <div class="item" name="flyout" jns="upload" tbl="' . $tbl . '" dok="file_addendum"' . $deactivate . '><i class="upload blue icon"></i>Unggah Addendum</div>
                                    <div class="item" name="flyout" jns="upload" tbl="' . $tbl . '" dok="file_pho"' . $deactivate . '><i class="upload blue icon"></i>Unggah PHO</div>
                                    <div class="item" name="flyout" jns="upload" tbl="' . $tbl . '" dok="file_fho"' . $deactivate . '><i class="upload blue icon"></i>Unggah FHO</div>
                                    <div class="divider"></div>
                                    <div class="item" name="flyout" jns="upload" tbl="' . $tbl . '" dok="file_laporan"' . $deactivate . '><i class="upload blue icon"></i>Unggah Laporan</div>
                                    <div class="divider"></div>
                                    <div class="item" name="flyout" jns="upload" tbl="' . $tbl . '" dok="file_dokumentasi0"' . $deactivate . '><i class="upload blue icon"></i>Unggah Dokumentasi 0%</div>
                                    <div class="item" name="flyout" jns="upload" tbl="' . $tbl . '" dok="file_dokumentasi50"' . $deactivate . '><i class="upload blue icon"></i>Unggah Dokumentasi 50%</div>
                                    <div class="item" name="flyout" jns="upload" tbl="' . $tbl . '" dok="file_dokumentasi100"' . $deactivate . '><i class="upload blue icon"></i>Unggah Dokumentasi 100%</div>
                                </div>
                            </div>';
						$buttons = '<div class="ui icon basic mini buttons">' . $buttonEdit . $buttonDel . '</div>';
						$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="uraian">' . $row->uraian . '</td>
                                    <td klm="pagu">' . $divAwalAngka  . number_format((float)$row->pagu, 2, ',', '.') . $divAkhir .  '</td>
                                    <td klm="jumlah">' . $divAwalAngka  . number_format((float)$row->jumlah, 2, ',', '.') . $divAkhir .  '</td>
                                    <td klm="nama_ppk">' . $row->nama_ppk . '</td>
                                    <td klm="keterangan">' . $row->keterangan . '</td>
                                    <td>' . $buttons . '</td>
                                </tr>');
						break;
					case 'dppa':
					case 'renja_p':
					case 'dpa':
					case 'renja':
						$buttonDel = '</div>';
						switch ($tbl) {
							case 'dpa':
							case 'renja':
								$kolomJumlah = 'jumlah';
								$kolomVol_1 = 'vol_1';
								$kolomVol_2 = 'vol_2';
								$kolomVol_3 = 'vol_3';
								$kolomVol_4 = 'vol_4';
								$kolomVol_5 = 'vol_5';
								$kolomHarga_satuan = 'harga_satuan';
								$kolomSat_1 = 'sat_1';
								$kolomSat_2 = 'sat_2';
								$kolomSat_3 = 'sat_3';
								$kolomSat_4 = 'sat_4';
								$kolomSat_5 = 'sat_5';
								$buttonDel = '<button class="ui red button" name="del_row"  jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '" id_sub_keg="' . $value_dinamic['id_sub_keg'] . '"><i class="trash alternate outline red icon"></i></button></div>';
								break;
							case 'dppa':
							case 'renja_p':
								$id_dok = 'id_dpa';
								$kolomJumlah = 'jumlah_p';
								$kolomVol_1 = 'vol_1_p';
								$kolomVol_2 = 'vol_2_p';
								$kolomVol_3 = 'vol_3_p';
								$kolomVol_4 = 'vol_4_p';
								$kolomVol_5 = 'vol_5_p';
								$kolomHarga_satuan = 'harga_satuan';
								$kolomSat_1 = 'sat_1_p';
								$kolomSat_2 = 'sat_2_p';
								$kolomSat_3 = 'sat_3_p';
								$kolomSat_4 = 'sat_4_p';
								$kolomSat_5 = 'sat_5_p';
								if ($row->id_dpa <= 0) {
									$buttonDel = '<button class="ui red button" name="del_row"  jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '" id_sub_keg="' . $value_dinamic['id_sub_keg'] . '"><i class="trash alternate outline red icon"></i></button>';
								}
								break;
							default:
								break;
						};
						$tbl_button = ($tbl == 'sub_keg_renja') ? 'renja' : 'dpa';
						$tbl_button_p = ($tbl == 'sub_keg_renja') ? 'renja_p' : 'dppa';
						$buttons = '';
						$divAwal = '';
						$divAwalAngka = '';
						$divAkhir = '';
						$deactivate = '';
						if (${"kunci_$tbl"} <= 0 && ${"setujui_$tbl"} <= 0) {
							$divAwal = '<div contenteditable>';
							$divAkhir = '</div>';
							$divAwalAngka  = '<div contenteditable rms onkeypress="return rumus(event);">';
						} else {
							$buttonDel = '';
							$deactivate = ' deactivate="1"';
						}
						$buttonEdit = ($row->kel_rek != 'uraian') ? '<div class="ui floating dropdown icon button lainnya" id_sub_keg="' . $value_dinamic['id_sub_keg'] . '">
                            <i class="wrench icon"></i>
                                <div class="menu">
                                    <div class="item" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"' . $deactivate . '><i class="edit outline blue icon"></i>Edit</div>
                                    <div class="divider"></div>
                                    <a class="item" data-tab="tab_renja" name="get_tbl" jns="rincian_pokok" tbl="' . $tbl_button . '"' . $deactivate . '><i class="pen square blue icon"></i>Rincian</a>
                                    <a class="item" data-tab="tab_renja" name="get_tbl" jns="rincian_perubahan" tbl="' . $tbl_button_p . '"' . $deactivate . '><i class="pen square red icon"></i>Rincian Perubahan</a>
                                    <div class="item"><div class="ui red empty circular label"></div>Help</div>
                                </div>
                            </div>' : '<button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '" id_sub_keg="' . $value_dinamic['id_sub_keg'] . '"' . $deactivate . '><i class="edit outline blue icon"></i></button>';
						$buttons = '<div class="ui icon basic mini buttons">' . $buttonEdit . $buttonDel . '</div>';
						// var_dump($row->{$kolomVol_1});
						$koefisien = number_format((float)$row->{$kolomVol_1}, 2, ',', '.');
						$koefisien .= ($row->{$kolomVol_2} > 0) ? ' x ' . number_format((float)$row->{$kolomVol_2}, 2, ',', '.') : '';
						$koefisien .= ($row->{$kolomVol_3} > 0) ? ' x ' . number_format((float)$row->{$kolomVol_3}, 2, ',', '.') : '';
						$koefisien .= ($row->{$kolomVol_4} > 0) ? ' x ' . number_format((float)$row->{$kolomVol_4}, 2, ',', '.') : '';
						$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="kd_akun">' . $row->kd_akun . '</td>
                                    <td klm="uraian">' .  $row->uraian .  '</td>
                                    <td klm="komponen">' .  $row->komponen .  '</td>
                                    <td>' . $koefisien .  '</td>
                                    <td klm="' . $kolomHarga_satuan . '">' . $divAwalAngka  . number_format((float)$row->{$kolomHarga_satuan}, 2, ',', '.') . $divAkhir .  '</td>
                                    <td klm="' . $kolomJumlah . '">' . $divAwalAngka  . number_format((float)$row->{$kolomJumlah}, 2, ',', '.') . $divAkhir .  '</td>
                                    <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                    <td>' . $buttons . '</td>
                                </tr>');
						break;
					case 'sub_keg_dpa':
						$disable_button = 0;
						if ($kunci_dppa > 0 || $setujui_dppa > 0) {
							$disable_button = 1;
						}
					case 'sub_keg_renja':
						if ($tbl == 'sub_keg_renja') {
							if ($kunci_renja_p > 0 || $setujui_renja_p > 0) {
								$disable_button = 1;
							} else {
								$disable_button = 0;
							}
						}
						$tbl_button = ($tbl == 'sub_keg_renja') ? 'renja' : 'dpa';
						$tbl_button_p = ($tbl == 'sub_keg_renja') ? 'renja_p' : 'dppa';
						$buttons = '';
						$divAwal = '';
						$divAwalAngka = '';
						$divAkhir = '';
						$buttonEditFlyout = '';
						$btnDivEdit = '';
						$buttonDel = '';
						if ($disable_button <= 0) {
							$divAwal = '<div contenteditable>';
							$divAkhir = '</div>';
							$divAwalAngka  = '<div contenteditable rms onkeypress="return rumus(event);">';
							$buttonEditFlyout = '<button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>';
							$btnDivEdit = '<div class="item" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i>Edit</div>';
							$buttonDel = '<button class="ui red button" name="del_row"  jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button>';
						}
						$buttonEdit = ($row->kel_rek == 'sub_keg') ? '<div class="ui floating dropdown icon button lainnya">
                        <i class="wrench icon"></i>
                            <div class="menu">
                                ' . $btnDivEdit . '<div class="divider"></div>
                                <a class="item" data-tab="tab_renja" name="get_tbl" jns="rincian_pokok" tbl="' . $tbl_button . '"><i class="pen square blue icon"></i>Rincian</a>
                                <a class="item" data-tab="tab_renja" name="get_tbl" jns="rincian_perubahan" tbl="' . $tbl_button_p . '"><i class="pen square red icon"></i>Rincian Perubahan</a>
                                <div class="item"><div class="ui red empty circular label"></div>Help</div>
                            </div>
                        </div>' : $buttonEditFlyout;
						$buttons = '<div class="ui icon basic mini buttons">' . $buttonEdit . $buttonDel . '</div>';
						$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="kd_sub_keg">' . $row->kd_sub_keg . '</td>
                                    <td klm="uraian">' .  $row->uraian .  '</td>
                                    <td klm="jumlah_pagu">' . $divAwalAngka  . number_format((float)$row->jumlah_pagu, 2, ',', '.') . $divAkhir .  '</td>
                                    <td klm="jumlah_rincian">' . $divAwalAngka  . number_format((float)$row->jumlah_rincian, 2, ',', '.') . $divAkhir .  '</td>
                                    <td klm="jumlah_pagu_p">' . $divAwalAngka  . number_format((float)$row->jumlah_pagu, 2, ',', '.') . $divAkhir .  '</td>
                                    <td klm="jumlah_rincian_p">' . $divAwalAngka  . number_format((float)$row->jumlah_rincian, 2, ',', '.') . $divAkhir .  '</td>
                                    <td>' . $buttons . '</td>
                                </tr>');
						break;
					case 'renstra':
						$desimal = ($this->countDecimals($row->jumlah) <= 2) ? 2 : $this->countDecimals($row->harga_satuan); //memanggil fungsi kelas sendiri
						$buttons = '';
						$divAwal = '';
						$divAkhir = '';
						$divAwalAngka = '';
						$deactivate = '';
						if (${"kunci_$tbl"} <= 0 && ${"setujui_$tbl"} <= 0) {
							$divAwal = '<div contenteditable>';
							$divAkhir = '</div>';

							$buttonDel = '<button class="ui red button" name="del_row"  jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"' . $deactivate . '><i class="trash alternate outline red icon"></i></button>';
						} else {
							$buttonDel = '';
							$deactivate = ' deactivate="1"';
						};
						$divAwalAngka  = '<div contenteditable rms onkeypress="return rumus(event);">';

						$kel_rek = $row->kel_rek;
						if ($kel_rek == 'sub_keg') {
							$buttons = '<div class="ui icon basic mini buttons">
                            <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"' . $deactivate . '><i class="edit outline blue icon"></i></button>' . $buttonDel . '</div>';
						} else {
							$buttons = '<div class="ui icon basic mini buttons">' . $buttonDel . '</div>';
						}

						$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="kd_sub_keg">' .  $row->kd_sub_keg . '</td>
                                    <td klm="uraian_prog_keg">' .  $row->uraian_prog_keg . '</td>
                                    <td klm="satuan">' . $divAwal . $row->satuan . $divAkhir . '</td>
                                    <td klm="indikator">' . $divAwal . $row->indikator . $divAkhir . '</td>
                                    <td klm="data_capaian_awal">' . $divAwalAngka . number_format((float)$row->data_capaian_awal, 2, ',', '.') . $divAkhir . '</td>
                                    <td klm="kondisi_akhir">' .  number_format((float)$row->kondisi_akhir, 2, ',', '.') . '</td>
                                    <td klm="jumlah">' .  number_format((float)$row->jumlah, $desimal, ',', '.') . '</td>
                                    <td class="center aligned">' . $buttons . '</td>
                                </tr>');
						// $rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
						//             <td klm="kd_sub_keg">' .  $row->kd_sub_keg . '</td>
						//             <td klm="uraian_prog_keg">' .  $row->uraian_prog_keg . '</td>
						//             <td klm="satuan">' . $divAwal . $row->satuan . $divAkhir . '</td>
						//             <td klm="indikator">' . $divAwal . $row->indikator . $divAkhir . '</td>
						//             <td klm="data_capaian_awal">' . $divAwalAngka . number_format((float)$row->data_capaian_awal, 2, ',', '.') . $divAkhir . '</td>
						//             <td klm="target_thn_1">' . $divAwalAngka . number_format((float)$row->target_thn_1, 2, ',', '.') . $divAkhir . '</td>
						//             <td klm="dana_thn_1">' . $divAwalAngka . number_format((float)$row->dana_thn_1, 2, ',', '.') . $divAkhir . '</td>
						//             <td klm="target_thn_2">' . $divAwalAngka . number_format((float)$row->target_thn_2, 2, ',', '.') . $divAkhir . '</td>
						//             <td klm="dana_thn_2">' . $divAwalAngka . number_format((float)$row->dana_thn_2, 2, ',', '.') . $divAkhir . '</td>
						//             <td klm="target_thn_3">' . $divAwalAngka . number_format((float)$row->target_thn_3, 2, ',', '.') . $divAkhir . '</td>
						//             <td klm="dana_thn_3">' . $divAwalAngka . number_format((float)$row->dana_thn_3, 2, ',', '.') . $divAkhir . '</td>
						//             <td klm="target_thn_4">' . $divAwalAngka . number_format((float)$row->target_thn_4, 2, ',', '.') . $divAkhir . '</td>
						//             <td klm="dana_thn_4">' . $divAwalAngka . number_format((float)$row->dana_thn_4, 2, ',', '.') . $divAkhir . '</td>
						//             <td klm="target_thn_5">' . $divAwalAngka . number_format((float)$row->target_thn_5, 2, ',', '.') . $divAkhir . '</td>
						//             <td klm="dana_thn_5">' . $divAwalAngka . number_format((float)$row->dana_thn_5, 2, ',', '.') . $divAkhir . '</td>
						//             <td klm="kondisi_akhir">' .  number_format((float)$row->kondisi_akhir, 2, ',', '.') . '</td>
						//             <td klm="jumlah">' .  number_format((float)$row->jumlah, $desimal, ',', '.') . '</td>
						//             <td>' . $buttons . '</td>
						//         </tr>');
						break;
					case 'tujuan_sasaran_renstra':
						$buttons = '';
						$divAwal = '';
						$divAkhir = '';
						if ($type_user == 'admin') {
							$divAwal = '<div contenteditable>';
							$divAkhir = '</div>';
							$buttons = '<div class="ui icon basic mini buttons">
                            <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                            <button class="ui red button" name="del_row"  jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
						}
						$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="kelompok">' . $row->kelompok . '</td>
                                    <td klm="text">' . $divAwal . $row->text . $divAkhir . '</td>
                                    <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                    <td>' . $buttons . '</td>
                                </tr>');
						break;
					case 'rekanan':
						$buttons = '';
						$divAwal = '';
						$divAkhir = '';
						$file = $row->file;
						$fileTag = '';
						if (strlen($file ?? '')) {
							$fileTag = '<a class="ui primary label" href="' . $file . '" target="_blank">Ungguh</a>';
						}
						if ($type_user == 'admin') {
							$divAwal = '<div contenteditable>';
							$divAkhir = '</div>';
							$buttons = '<div class="ui icon basic mini buttons">
                            <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                            <button class="ui red button" name="del_row"  jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
						}
						$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="nama_perusahaan">' . $divAwal . $row->nama_perusahaan . $divAkhir . '</td>
                                    <td klm="alamat">' . $divAwal . $row->alamat . $divAkhir . '</td>
                                    <td klm="npwp">' . $divAwal . $row->npwp . $divAkhir . '</td>
                                    <td klm="direktur">' . $divAwal . $row->direktur . $divAkhir . '</td>
                                    <td klm="no_akta_pendirian">' . $divAwal . $row->no_akta_pendirian . $divAkhir . '</td>
                                    <td klm="tgl_akta_pendirian">' . $row->tgl_akta_pendirian  . '</td>
                                    <td klm="lokasi_notaris_pendirian">' . $divAwal . $row->lokasi_notaris_pendirian . $divAkhir . '</td>
                                    <td klm="nama_notaris_pendirian">' . $divAwal . $row->nama_notaris_pendirian . $divAkhir . '</td>
                                    <td klm="file">' . $fileTag . '</td>
                                    <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                    <td>' . $buttons . '</td>
                                </tr>');
						break;
					case 'hspk':
					case 'sbu':
					case 'asb':
					case 'ssh':
						$buttons = '';
						$divAwal = '';
						$divAkhir = '';
						if ($type_user == 'admin') {
							$divAwal = '<div contenteditable>';
							$divAkhir = '</div>';
							$buttons = '<div class="ui icon basic mini buttons">
                                <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                                <button class="ui red button" name="del_row" jns="del_row" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
						}
						$desimal = ($this->countDecimals($row->harga_satuan) < 2) ? 2 : $this->countDecimals($row->harga_satuan);
						$rowData['tbody'] .= preg_replace('/(\s\s+|\t|\n)/', ' ', '<tr id_row="' . $row->id . '">
                        <td klm="kd_aset">' . $row->kd_aset . '</td>
                        <td klm="uraian_barang">' . $divAwal . $row->uraian_barang . $divAkhir . '</td>
                        <td klm="spesifikasi">' . $divAwal . $row->spesifikasi . $divAkhir . '</td>
                        <td klm="satuan">' . $divAwal . $row->satuan . $divAkhir . '</td>
                        <td klm="harga_satuan">' . $divAwalAngka . number_format((float)$row->harga_satuan, $desimal, ',', '.') . $divAkhir . '</td>
                        <td klm="tkdn">' . $divAwal . $row->tkdn . $divAkhir . '</td>
                        <td>' . $buttons . '</td></tr>');
						break;
					case 'satuan':
						$buttons = '';
						$divAwal = '';
						$divAkhir = '';
						if ($type_user == 'admin') {
							$divAwal = '<div contenteditable>';
							$divAkhir = '</div>';
							$buttons = '<div class="ui icon basic mini buttons">
                            <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                            <button class="ui red button" name="del_row" jns="del_row" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
						}
						$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="value">' . $divAwal . $row->value . $divAkhir . '</td>
                                    <td klm="item">' . $divAwal . $row->item . $divAkhir . '</td>
                                    <td klm="sebutan_lain">' . $divAwal . $row->sebutan_lain . $divAkhir . '</td>
                                    <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                    <td>' . $buttons . '</td>
                                </tr>');
						break;
					case 'wilayah':
						$buttons = '';
						$divAwal = '';
						$divAkhir = '';
						if ($type_user == 'admin') {
							$divAwal = '<div contenteditable>';
							$divAkhir = '</div>';
							$buttons = '<div class="ui icon basic mini buttons">
                            <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                            <button class="ui red button" name="del_row" jns="del_row" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
						}
						$file = $row->file;
						$fileTag = '';
						if (strlen($file ?? '')) {
							$fileTag = '<a class="ui primary label" href="' . $file . '" target="_blank">Ungguh</a>';
						}
						$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="kode">' . $divAwal . $row->kode . $divAkhir . '</td>
                                    <td klm="uraian">' . $divAwal . $row->uraian . $divAkhir . '</td>
                                    <td klm="status">' . $divAwal . $row->status . $divAkhir . '</td>
                                    <td klm="jml_kec">' . $divAwal . $row->jml_kec . $divAkhir . '</td>
                                    <td klm="jml_kel">' . $divAwal . $row->jml_kel . $divAkhir . '</td>
                                    <td klm="jml_desa">' . $divAwal . $row->jml_desa . $divAkhir . '</td>
                                    <td klm="luas">' . $divAwal . $row->luas . $divAkhir . '</td>
                                    <td klm="penduduk">' . $divAwal . $row->penduduk . $divAkhir . '</td>
                                    <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                    <td>' . $fileTag . '</td>
                                    <td>' . $buttons . '</td>
                                </tr>');
						break;
					case 'peraturan':
						$buttons = '';
						$divAwal = '';
						$divAkhir = '';
						$file = $row->file;
						$fileTag = '';
						if (strlen($file ?? '')) {
							$fileTag = '<a class="ui primary label" href="' . $file . '" target="_blank">Ungguh</a>';
						}
						if ($type_user == 'admin') {
							$divAwal = '<div contenteditable>';
							$divAkhir = '</div>';
							$buttons = '<div class="ui icon basic mini buttons">
                            <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                            <button class="ui red button" name="del_row" jns="del_row" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
						}
						$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="nomor">' . $divAwal . $row->nomor . $divAkhir . '</td>
                                    <td klm="judul">' . $divAwal . $row->judul . $divAkhir . '</td>
                                    <td klm="tgl_pengundangan">' . $divAwal . $row->tgl_pengundangan . $divAkhir . '</td>
                                    <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                    <td>' . $fileTag . '</td>
                                    <td>' . $buttons . '</td>
                                </tr>');
						break;
					case 'sumber_dana':
						$buttons = '';
						$divAwal = '';
						$divAkhir = '';
						if ($type_user == 'admin') {
							$divAwal = '<div contenteditable>';
							$divAkhir = '</div>';
							$buttons = '<div class="ui icon basic mini buttons">
                                <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                                <button class="ui red button" name="del_row" jns="del_row" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
						}
						// $k = ((int)$row->kelompok > 0) ? $row->kelompok : '';
						// $j = ((int)$row->jenis > 0) ? $row->jenis : '';
						// $o = ((int)$row->objek > 0) ? $Fungsi->zero_pad($row->objek, 2) : '';
						// $ro = ((int)$row->rincian_objek > 0) ? $Fungsi->zero_pad($row->rincian_objek, 2) : '';
						// $sro = ((int)$row->sub_rincian_objek > 0) ? $Fungsi->zero_pad($row->sub_rincian_objek, 2) : '';
						$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                        <td klm="kode">' . $divAwal . $row->kode . $divAkhir . '</td>
                                        <td klm="uraian">' . $divAwal . $row->uraian . $divAkhir . '</td>
                                        <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                        <td>' . $buttons . '</td>
                                    </tr>');
						break;
					case 'aset':
					case 'akun_belanja':
						$buttons = '';
						$divAwal = '';
						$divAkhir = '';
						if ($type_user == 'admin') {
							$divAwal = '<div contenteditable>';
							$divAkhir = '</div>';
							$buttons = '<div class="ui icon basic mini buttons">
                                    <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                                    <button class="ui red button" name="del_row" jns="del_row" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
						}
						// $n = ((int)$row->kelompok > 0) ? $row->kelompok : '';
						// $g = ((int)$row->jenis > 0) ? $row->jenis : '';
						// $s = ((int)$row->objek > 0) ? $Fungsi->zero_pad($row->objek, 2) : '';
						// $se = ((int)$row->rincian_objek > 0) ? $Fungsi->zero_pad($row->rincian_objek, 2) : '';
						// $sr = ((int)$row->sub_rincian_objek > 0) ? $Fungsi->zero_pad($row->sub_rincian_objek, 2) : '';
						$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                            <td klm="sub_rincian_objek">' . $divAwal . $row->kode . $divAkhir . '</td>
                                            <td klm="uraian">' . $divAwal . $row->uraian . $divAkhir . '</td>
                                            <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                            <td>' . $buttons . '</td>
                                        </tr>');
						break;
					case 'bidang_urusan':
					case 'prog':
					case 'keg':
					case 'sub_keg':
						$buttons = '';
						$divAwal = '';
						$divAkhir = '';
						if ($type_user == 'admin') {
							$divAwal = '<div contenteditable>';
							$divAkhir = '</div>';
							$buttons = '<div class="ui icon basic mini buttons">
                                        <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                                        <button class="ui red button" name="del_row" jns="del_row" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
						}
						$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                            <td klm="kode">' . $divAwal . $row->kode . $divAkhir . '</td>
                            <td klm="nomenklatur_urusan">' . $divAwal . $row->nomenklatur_urusan . $divAkhir . '</td>
                            <td klm="kinerja">' . $divAwal . $row->kinerja . $divAkhir . '</td>
                            <td klm="indikator">' . $divAwal . $row->indikator . $divAkhir . '</td>
                            <td klm="satuan">' . $divAwal . $row->satuan . $divAkhir . '</td>
                            <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                            <td>' . $buttons . '</td>
                            </tr>');
						break;
					case 'mapping':
						$buttons = '';
						$divAwal = '';
						$divAkhir = '';
						if ($type_user == 'admin') {
							$divAwal = '<div contenteditable>';
							$divAkhir = '</div>';
							$buttons = '<div class="ui icon basic mini buttons">
                                            <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                                            <button class="ui red button" name="del_row" jns="del_row" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
						}
						$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                                    <td klm="kd_aset">'  . $row->kd_aset . '</td>
                                                    <td klm="uraian_aset">' . $divAwal . $row->uraian_aset . $divAkhir . '</td>
                                                    <td klm="kode_akun">' . $divAwal . $row->kd_akun . $divAkhir . '</td>
                                                    <td klm="uraian_akun">' . $divAwal . $row->uraian_akun . $divAkhir . '</td>
                                                    <td klm="kelompok">' . $divAwal . $row->kelompok . $divAkhir . '</td>
                                                    <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                                    <td>' . $buttons . '</td>
                                                </tr>');
						break;
					case 'organisasi':
						$buttons = '';
						$divAwal = '';
						$divAkhir = '';
						if ($type_user == 'admin') {
							$divAwal = '<div contenteditable>';
							$divAkhir = '</div>';
							$buttons = '<div class="ui icon basic mini buttons">
                                                <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                                                <button class="ui red button" name="del_row" jns="del_row" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
						}
						$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                                        <td klm="kode">' . $divAwal . $row->kode . $divAkhir . '</td>
                                                        <td klm="uraian">' . $divAwal . $row->uraian . $divAkhir . '</td>
                                                        <td klm="nama_kepala">' . $row->nama_kepala  . '</td>
                                                        <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                                        <td>' . $buttons . '</td>
                                                    </tr>');
						break;
					case 'rekanan':
						$buttons = '';
						$divAwal = '';
						$divAkhir = '';
						$file = $row->file;
						$fileTag = '';
						if (strlen($file ?? '')) {
							$fileTag = '<a class="ui primary label" href="' . $file . '" target="_blank">Ungguh</a>';
						}
						if ($type_user == 'admin') {
							$divAwal = '<div contenteditable>';
							$divAkhir = '</div>';
							$buttons = '<div class="ui icon basic mini buttons">
                            <button class="ui button" name="flyout" name="flyout" jns="' . $jenis . '" tbl="edit" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                            <button class="ui red button" name="del_row" jns="' . $jenis . '" tbl="del_row" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
						}
						$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="nama_perusahaan">' . $divAwal . $row->nama_perusahaan . $divAkhir . '</td>
                                    <td klm="alamat">' . $divAwal . $row->alamat . $divAkhir . '</td>
                                    <td klm="npwp">' . $divAwal . $row->npwp . $divAkhir . '</td>
                                    <td klm="direktur">' . $divAwal . $row->direktur . $divAkhir . '</td>
                                    <td klm="no_akta_pendirian">' . $divAwal . $row->no_akta_pendirian . $divAkhir . '</td>
                                    <td klm="tgl_akta_pendirian">' . $row->tgl_akta_pendirian  . '</td>
                                    <td klm="lokasi_notaris_pendirian">' . $divAwal . $row->lokasi_notaris_pendirian . $divAkhir . '</td>
                                    <td klm="nama_notaris_pendirian">' . $divAwal . $row->nama_notaris_pendirian . $divAkhir . '</td>
                                    <td klm="file">' . $fileTag . '</td>
                                    <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                    <td>' . $buttons . '</td>
                                </tr>');
						break;
					case 'user':
						$phpdate = strtotime($row->tgl_daftar);
						$mysqldate = date('d-m-Y H:i:s', $phpdate);
						$phpdate2 = strtotime($row->tgl_login);
						$mysqldate2 = date('d-m-Y H:i:s', $phpdate2);
						$aktif = ($row->aktif > 0) ? 'checked="checked"' : '';
						$aktif_edit = ($row->aktif_edit > 0) ? 'checked="checked"' : '';
						$aktif_chat = ($row->aktif_chat > 0) ? 'checked="checked"' : '';
						$rowData['tbody'] .= '<tr id_row="' . $row->id . '">
                        <td klm="username">' . $row->username  . '</td>
                        <td klm="email">' . $row->email . '</td>
                        <td klm="nama">' . $row->nama . '</td>
                        <td klm="kontak_person">' . $row->kontak_person . '</td>
                        <td klm="nama_org">' . $row->nama_org . '</td>
                        <td klm="type_user">' . $row->type_user . '</td>
                        <td klm="photo">' . $row->photo . '</td>
                        <td klm="tgl_daftar">' . $mysqldate . '</td>
                        <td klm="tgl_login">' . $mysqldate2 . '</td>
                        <td klm="thn_aktif_anggaran">' . $row->thn_aktif_anggaran . '</td>
                        <td klm="kd_proyek_aktif">' . $row->kd_proyek_aktif . '</td>
                        <td><div class="ui toggle checkbox user" jns="profil" klm="aktif" tbl="edit"><input type="checkbox" tabindex="0" class="hidden" ' . $aktif . '"><label></label></td>
                        <td><div class="ui toggle checkbox user" jns="profil" klm="aktif_edit" tbl="edit"><input type="checkbox" tabindex="0" class="hidden" ' . $aktif_edit . '"><label></label></td>
                        <td><div class="ui toggle checkbox user" jns="profil" klm="aktif_chat" tbl="edit"><input type="checkbox" tabindex="0" class="hidden" ' . $aktif_chat . '"><label></label></td>
                        <td klm="ket">' . $row->ket . '</td>
                        <td> <div class="ui icon basic mini buttons"><button class="ui button" name="flyout" name="flyout" jns="profil" tbl="edit"><i class="edit outline ' . $warna . ' icon"></i></button><button class="ui red button" name="del_row" tbl="del_row" jns="profil"><i class="trash alternate outline red icon"></i></button></div></td></tr>';
						break;
					case 'harga_satuan':
						$desimal = ($this->countDecimals($row->harga_satuan) < 2) ? 2 : $this->countDecimals($row->harga_satuan); //memanggil fungsi kelas sendiri
						$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                <td>' . $row->kode . '</td>
                                <td>' . $row->jenis . '</td>
                                <td klm="uraian"><div contenteditable>' . $row->uraian . '</div></td>
                                <td>' . $row->satuan . '</td>
                                <td klm="harga_satuan"><div contenteditable rms onkeypress="return rumus(event);">' . number_format((float)$row->harga_satuan, $desimal, ',', '.') . '</div></td>
                                <td klm="sumber_data"><div contenteditable>' . $row->sumber_data . '</div></td>
                                <td klm="spesifikasi"><div contenteditable>' . $row->spesifikasi . '</div></td>
                                <td klm="keterangan"><div contenteditable>' . $row->keterangan . '</div></td>
                                <td>
                                    <div class="ui icon basic mini buttons">
                                        <button class="ui button" name="flyout" name="flyout" jns="harga_satuan" tbl="edit" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                                        <button class="ui button" name="del_row" jns="harga_satuan" tbl="del_row" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button>
                                        <button class="ui button up_row"><i class="angle up icon"></i></button>
                                    </div>
                                </td>
                            </tr>');
						break;
					case 'satuan':
						if ($type_user == 'admin') {
							$row = (array)$row;
							$rowData['tbody'] .= '<tr id_row="' . $row['id'] . '"><td klm="value"><div contenteditable>' . $row['value'] . '</div></td><td klm="item"><div contenteditable>' . $row['item'] . '</div></td><td klm="keterangan"><div contenteditable>' . $row['keterangan'] . '</div></td><td klm="sebutan_lain"><div contenteditable>' . $row['sebutan_lain'] . '</div></td><td><div class="ui icon basic mini buttons"><button class="ui button" name="flyout" name="flyout" jns="satuan" tbl="edit" id_row="' . $row['id'] . '"><i class="edit blue outline icon"></i></button><button class="ui button" name="del_row" jns="satuan" tbl="del_row" id_row="' . $row['id'] . '"><i class="trash alternate outline red icon"></i></button></div></td></tr>';
						} else {
							$rowData['tbody'] .= '<tr><td>' . $row['value'] . '</td><td>' . $row['item'] . '</td><td>' . $row['keterangan'] . '</td><td>' . $row['sebutan_lain'] . '</td><td></td></tr>';
						}
						break;
					case 'analisa_ck':
						$koef = $row->koefisien;
						if ($koef > 0) {
							$desimal = ($this->countDecimals($row->koefisien) < 2) ? 2 : $this->countDecimals($row->koefisien);
							$koef = '<td klm="koefisien"><div contenteditable rms>' . number_format((float)$row->koefisien, $desimal, ',', '.') . '</div></td>';
						} else {
							$koef = '<td klm="koefisien"></td>';
						}
						if ($row->harga_satuan > 0) {
							$desimal = ($this->countDecimals($row->harga_satuan) < 2) ? 2 : $this->countDecimals($row->harga_satuan);
							$harga_sat = number_format((float)$row->harga_satuan, $desimal, ',', '.');
						} else {
							$harga_sat = '';
						}
						if (strlen($row->rumus ?? '') > 0 || ($row->kode) == ($row->kd_analisa)) {
							$desimal = ($this->countDecimals($row->koefisien) < 2) ? 2 : $this->countDecimals($row->koefisien);
							$koef = '<td klm="koefisien">' . number_format((float)$row->koefisien, $desimal, ',', '.') . '</td>';
						}
						$desimal = ($this->countDecimals($row->jumlah_harga) < 2) ? 2 : $this->countDecimals($row->jumlah_harga);
						if (($row->kode) == ($row->kd_analisa)) {
							$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '" class="warning"><td klm="uraian"><div contenteditable>' . $row->uraian . '</div></td><td klm="kode">' . $row->kode . '</td><td klm="satuan"><div contenteditable>' . $row->satuan . '</div></td><td></td><td klm="harga_satuan"></td>' . $koef . '<td klm="jumlah_harga"></td><td></td></tr>');
						} else {
							$rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                            <td klm="uraian"><div contenteditable>' . $row->uraian . '</div></td>
                            <td klm="kode"><div contenteditable>' . $row->kode . '</div></td>
                            <td klm="satuan"><div contenteditable>' . $row->satuan . '</div></td>
                            ' . $koef . '
                            <td klm="harga_satuan">' . $harga_sat . '</td>
                            <td klm="jumlah_harga">' . number_format((float)$row->jumlah_harga, $desimal, ',', '.') . '</td>
                            <td klm="rumus"><div contenteditable>' . $row->rumus . '</div></td>
                            <td> <div class="ui mini basic icon buttons"><button class="ui button" name="modal_2"><i class="edit blue icon"></i></button><button class="ui button" name="del_row" jns="direct" tbl="del_row"><i class="trash alternate outline icon"></i></button><button class="ui button up_row"><i class="angle up icon"></i></button></div></td></tr>');
						}
						break;
					case 'value2':
						# code...
						break;
					default:
						# code...
						break;
				}
			}
		} else { //jika data tidak ditemukan
			$rowData['tbody'] .= '<tr><td colspan="' . $jumlah_kolom . '"><div class="ui icon info message"><i class="yellow exclamation circle icon"></i><div class="content"><div class="header">Data Tidak ditemukan </div><p>input data baru atau hubungi administrator</p></div></div></td></tr>';
			$rowData['tfoot'] = '<tr></tr>';
		}
		// pagination
		if ($jmlhalaman > 1) {
			$id_aktif = 0;
			$batas_bawah = $halaman - 2;
			$batas_atas = $halaman + 2;
			if ($batas_bawah <= 1) {
				$batas_bawah = 1;
			} else {
				$pagination .= '<a class="item" name="page" hal="1" ret="go" tbl="' . $tbl . '"><i class="angle double left chevron icon"></i></a>';
			}
			$paginationnext = "";
			if ($batas_atas < $jmlhalaman) {
				$batas_atas = $halaman + 2;
				$paginationnext = '<a class="item" name="page" hal="' . $jmlhalaman . '" ret="go" tbl="' . $tbl . '"><i class="angle double right chevron icon"></i></a>';
			}
			//var_dump( $paginationnext );
			if ($batas_atas > $jmlhalaman) {
				$batas_atas = $jmlhalaman;
			}
			for ($i = $batas_bawah; $i <= $batas_atas; $i++) {
				if ($i != $halaman) {
					$pagination .= '<a class="item" name="page" hal="' . $i . '" ret="go" tbl="' . $tbl . '">' . $i . '</a>';
				} else {
					$pagination .= '<a class="active item" tbl="' . $tbl . '">' . $i . '</a>';
					$id_aktif = $i;
				}
			}
			// panah preview
			if ($halaman == 1) {
				$pagination1 .= '<a class="disabled icon item"><i class="angle left icon"></i></a>';
			} else {
				$pagination1 .= '<a class="icon item" name="page" hal="' . $id_aktif . '" ret="prev" tbl="' . $tbl . '"><i class="angle left icon"></i></a>';
			}
			// panah next
			if ($halaman == $jmlhalaman) {
				$pagination2 .= '<a class="disabled icon item"><i class="angle right icon"></i></a>';
			} else {
				$pagination2 .= '<a class="icon item" name="page" hal="' . $id_aktif . '" ret="next" tbl="' . $tbl . '"><i class="angle right icon"></i></a>';
			}
			//var_dump( $pagination );
			$rowData['tfoot'] = '<tr' . $classRow . '><th class="right aligned" colspan="' . $jumlah_kolom . '"><div class="ui center pagination menu">' . $pagination1 . $pagination . $paginationnext . $pagination2 . '</div></th></tr>';
		} else {
			//preg_replace('/(\s\s+|\t|\n)/', ' ', $data['tr_sub_keg']);
			$rowData['tfoot'] = str_replace('/(\s\s+|\t|\n)/', "", '<tr' . $classRow . '><th class="right aligned" colspan="' . $jumlah_kolom . '"></th></tr>');
		}
		if (isset($rowData['tbody'])) {
			$rowData['tbody'] = preg_replace('/(\s\s+|\t|\n)/', ' ', $rowData['tbody']);
		}
		// /if (property_exists($object, 'a_property'))
		//cek data ada atau tidak
		if (isset($rowData['tfoot'])) {
			$rowData['tfoot'] = preg_replace('/(\s\s+|\t|\n)/', ' ', $rowData['tfoot']);
		}
		//$rowData['tbody'] = str_replace("\r\n", "", $rowData['tbody']); //trim(preg_replace('/^\p{Z}+|\p{Z}+$/u', '', ($rowData['tbody'])), "\r\n");
		return $rowData;
	}
	public function tabel_pakai($tbl)
	{
		$tabel_pakai = '';
		$jumlah_kolom = 20;
		switch ($tbl) {
			case 'create_surat':
				$tabel_pakai = 'naskah_dinas_neo';
				break;
			case 'register_surat':
				$tabel_pakai = 'register_naskah_dinas';
				break;
			case 'logo':
				$tabel_pakai = 'wilayah_neo';
				break;
			case 'sk_asn':
				$tabel_pakai = 'sk_asn_neo';
				$jumlah_kolom = 9;
				break;
			case 'uraian_paket':
				$tabel_pakai = 'daftar_uraian_paket';
				$jumlah_kolom = 9;
				break;
			case 'realisasi':
				$tabel_pakai = 'daftar_realisasi_neo';
				$jumlah_kolom = 9;
				break;
			case 'berita':
				$tabel_pakai = 'berita_neo';
				$jumlah_kolom = 9;
				break;
			case 'profil':
			case 'users':
				$tabel_pakai = 'user_sesendok_biila';
				$jumlah_kolom = 9;
				break;
			case 'asn':
				$tabel_pakai = 'db_asn_pemda_neo';
				$jumlah_kolom = 7;
				break;
			case 'dpa_dppa':
				$tabel_pakai = '';
				$user = new User();
				$DB = DB::getInstance();
				$user->cekUserSession();
				$username = $_SESSION["user"]["username"];
				$rowUsername = $DB->getWhereOnce('user_sesendok_biila', ['username', '=', $username]);
				$dataJson['results'] = [];
				if ($rowUsername != false) {
					foreach ($rowUsername as $key => $value) {
						${$key} = $value;
					}
					$tahun = (int) $rowUsername->tahun;
					$kd_wilayah = $rowUsername->kd_wilayah;
					$kd_opd = $rowUsername->kd_organisasi;
					$id_user = $rowUsername->id;
					$rowPengaturan = $DB->getWhereOnce('pengaturan_neo', ['tahun', '=', $tahun]);
					if ($rowPengaturan != false) {
						foreach ($rowPengaturan as $key => $value) {
							${$key} = $value;
						}
						$tabel_pakai = ($setujui_dppa) ? 'dppa_neo' : 'dpa_neo';
					}
				}
				break;
			case 'daftar_kontrak':
			case 'daftar_paket':
				$tabel_pakai = 'daftar_paket_neo';
				$jumlah_kolom = 7;
				break;
			case 'dppa':
				$tabel_pakai = 'dppa_neo';
				$jumlah_kolom = 8;
				break;
			case 'dpa':
				$tabel_pakai = 'dpa_neo';
				$jumlah_kolom = 8;
				break;
			case 'renja_p':
				$tabel_pakai = 'renja_p_neo';
				$jumlah_kolom = 8;
				break;
			case 'renja':
				$tabel_pakai = 'renja_neo';
				$jumlah_kolom = 8;
				break;
			case 'sub_keg_dpa':
				$tabel_pakai = 'sub_keg_dpa_neo';
				$jumlah_kolom = 8;
				break;
			case 'sub_keg_renja':
				$tabel_pakai = 'sub_keg_renja_neo';
				$jumlah_kolom = 8;
				break;
			case 'prog_keg_renstra':
				$tabel_pakai = 'renstra_skpd_neo';
				$jumlah_kolom = 6;
				break;
			case 'renstra':
				$tabel_pakai = 'renstra_skpd_neo';
				$jumlah_kolom = 22;
				break;
			case 'tujuan_renstra':
			case 'sasaran_renstra':
			case 'tujuan_sasaran_renstra':
			case 'tujuan_sasaran':
				$tabel_pakai = 'tujuan_sasaran_renstra_neo';
				$jumlah_kolom = 4;
				break;
			case 'rekanan':
				$tabel_pakai = 'rekanan_neo';
				$jumlah_kolom = 11;
				break;
			case 'peraturan':
				$tabel_pakai = 'peraturan_neo';
				$jumlah_kolom = 4;
				break;
			case "akun":
			case "akun_belanja":
			case 'akun_belanja_val':
				$tabel_pakai = 'akun_neo';
				$jumlah_kolom = 4;
				break;
			case "aset":
				$tabel_pakai = 'aset_neo';
				$jumlah_kolom = 4;
				break;
			case 'dpa':
				$tabel_pakai = 'dpa_neo';
				break;
			case 'mapping_aset':
			case 'mapping':
				$tabel_pakai = 'mapping_aset_akun';
				$jumlah_kolom = 10;
				break;
			case 'wilayah_logo':
			case 'wilayah':
				$tabel_pakai = 'wilayah_neo';
				$jumlah_kolom = 11;
				break;
			case 'satuan':
				$tabel_pakai = 'satuan_neo';
				$jumlah_kolom = 5;
				break;
			case 'organisasi':
				$tabel_pakai = 'organisasi_neo';
				break;
			case "pengaturan":
				$tabel_pakai = 'pengaturan_neo';
				break;
			case "peraturan":
				$tabel_pakai = 'peraturan_neo';
				break;
			case 'program':
				$tabel_pakai = 'program_neo';
				break;
			case 'satuan':
				$tabel_pakai = 'satuan_neo';
				break;
			case 'hspk':
				$tabel_pakai = 'hspk_neo';
				break;
			case 'sbu':
				$tabel_pakai = 'sbu_neo';
				break;
			case 'asb':
				$tabel_pakai = 'asb_neo';
				break;
			case 'ssh':
				$tabel_pakai = 'ssh_neo';
				break;
			case 'bidang_urusan':
			case 'prog':
			case 'keg':
			case 'sub_keg':
			case 'sub_kegiatan':
				$tabel_pakai = 'sub_kegiatan_neo';
				break;
			case 'sumber_dana':
				$tabel_pakai = 'sumber_dana_neo';
				break;
			case 'user':
				$tabel_pakai = 'user_sesendok_biila';
				break;
			case 'inbox':
			case 'outbox':
			case 'wall':
				$tabel_pakai = 'ruang_chat';
				break;
			default:
				$tabel_pakai = '';
				break;
		}
		return ['tabel_pakai' => $tabel_pakai, 'jumlah_kolom' => $jumlah_kolom];
	}
	// ambil data row bidang s/d sub kegiatan
	public function get_bidang_sd_sub_keg($dinamic = [])
	{
		$user = new User();
		$DB = DB::getInstance();
		$user->cekUserSession();
		$tbl = $dinamic['tbl'];
		$kode = $dinamic['kode'];
		$id_user = $_SESSION["user"]["id"];
		$userAktif = $DB->getWhereCustom('user_sesendok_biila', [['id', '=', $id_user]]);
		$jumlahArray = is_array($userAktif) ? count($userAktif) : 0;
		if ($jumlahArray > 0) {
			foreach ($userAktif[0] as $key => $value) {
				${$key} = $value;
			}
		}
		$column =  ($dinamic['column']) ? $dinamic['column'] : '*';
		// var_dump($column);
		$DB->select($column);
		$tabel_pakai = $this->tabel_pakai($tbl)['tabel_pakai'];
		$explodeAwal = explode('.', $kode);
		// cari di tabel jika tidak ditemukan tambahkan, jika ada update tabel
		//call methode in class 
		$Sumprogkeg = [];
		$rek_Proses = $this->kelolaRek($dinamic);
		// var_dump($rek_Proses);
		for ($i = 0; $i <= $rek_Proses['sum_rek']; $i++) {
			if ($i == 4) {
				continue;
			}
			switch ($i) {
				case 0:

					$kel_rek = 'opd';
					break;
				case 1:
					$kd_sub_kegOk = $rek_Proses['kd_urusan'];
					$kel_rek = 'kd_urusan';
					break;
				case 2:
					$kd_sub_kegOk = $rek_Proses['kd_bidang'];
					$kel_rek = 'kd_bidang';
					break;
				case 3:
					$kd_sub_kegOk = $rek_Proses['kd_prog'];
					$kel_rek = 'kd_prog';
					break;
				case 5:
					$kd_sub_kegOk = $rek_Proses['kd_keg'];
					$kel_rek = 'kd_keg';
					break;
				case 6:
					$kd_sub_kegOk = $rek_Proses['kd_sub_keg'];
					$kel_rek = 'kd_sub_keg';
					break;
				default:
					#code...
					break;
			};

			if ($i == 0) {
				$DB->select("SUM(jumlah_rincian_p) AS jumlah_rincian_p,SUM(jumlah_rincian) AS jumlah_rincian");
				$kondisi = [['disable', '<=', 0], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_organisasi, 'AND'], ['tahun', '=', $tahun, 'AND'], ['kel_rek', '=', 'urusan', 'AND']];
			} else {
				$DB->select($column);
				$kondisi = [['disable', '<=', 0], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_organisasi, 'AND'], ['tahun', '=', $tahun, 'AND'], ['kd_sub_keg', '=', $kd_sub_kegOk, 'AND']];
			}
			$Sumprogkeg[$kel_rek] = $DB->getWhereOnceCustom($tabel_pakai, $kondisi);

			// $Sumprogkeg[$kel_rek] = $DB->getWhereCustom($tabel_pakai, $kondisi);
			// var_dump($Sumprogkeg);
		}

		// $DB->select('*');
		return $Sumprogkeg;
	}
	// kelola rekening sub kegiatan
	public function kelolaRek($dinamic = ['kode' => ''])
	{
		$kode = $dinamic['kode'];
		$explodeAwal = explode('.', $kode);
		$count = count($explodeAwal);
		// cari di tabel jika tidak ditemukan tambahkan, jika ada update tabel
		$kd_rek = '';
		$kel_rek = '';
		$dataRek = [];
		$dataRek['kd_sub_keg_x_xx'] = '';
		switch ($count) {
			case 6: //sub keg
				if ((int)$explodeAwal[5]) {
					$dataRek['sub_keg'] = (int)$explodeAwal[5];
					$kd_rek = $this->zero_pad((int)$explodeAwal[5], 4);
				}
			case 5: //keg
				if ((int)$explodeAwal[4]) {
					$bantuan = (strlen($kd_rek) > 0) ? "." : "";
					$kd_rek = $this->zero_pad((int)$explodeAwal[4], 2) . $bantuan . $kd_rek;
				}
			case 4: //keg
				if ((int)$explodeAwal[3]) {
					$bantuan = (strlen($kd_rek) > 0) ? "." : "";
					$kd_rek = $this->zero_pad((int)$explodeAwal[3], 1) . $bantuan . $kd_rek;
					$dataRek['keg'] = $this->zero_pad((int)$explodeAwal[3], 1) . "." . $this->zero_pad((int)$explodeAwal[4], 2);
				}
			case 3: //prog
				if ((int)$explodeAwal[2]) {
					$bantuan = (strlen($kd_rek) > 0) ? "." : "";
					$kd_rek = $this->zero_pad((int)$explodeAwal[2], 2) . $bantuan . $kd_rek;
					$dataRek['prog'] = (int)$explodeAwal[2];
				}
			case 2: //bidang
				if ($explodeAwal[1]) {
					$bantuan = (strlen($kd_rek) > 0) ? "." : "";
					if (is_numeric($explodeAwal[1])) {
						$bidang = $this->zero_pad((int)$explodeAwal[1], 1);
					} elseif (is_string($explodeAwal[1])) {
						$bidang = $explodeAwal[1];
					}
					$kd_rek = $bidang . $bantuan . $kd_rek;
					$dataRek['bidang'] = $bidang;
				}
			case 1: //urusan
				if ($explodeAwal[0]) {
					$bantuan = (strlen($kd_rek) > 0) ? "." : "";
					if (is_numeric($explodeAwal[0])) {
						$urusan = $this->zero_pad((int)$explodeAwal[0], 1);
					} elseif (is_string($explodeAwal[0])) {
						$urusan = $explodeAwal[0];
					}
					$kd_rek = $urusan . $bantuan . $kd_rek;
					$dataRek['urusan'] = $urusan;
				}
				$dataRek['kode'] = $kd_rek;
				break;
			default:
				break;
		};
		$explodeAwal = explode('.', $dataRek['kode']);
		// var_dump($explodeAwal);
		$dataRek['sum_rek'] = count($explodeAwal);
		if ($explodeAwal[0]) {
			$dataRek['kd_urusan'] = $explodeAwal[0];
			$dataRek['kd_sub_keg_x_xx'] = "x";
			$kel_rek = 'urusan';
		}
		if (isset($explodeAwal[1])) {
			$dataRek['kd_bidang'] = $explodeAwal[0] . "." . $explodeAwal[1];
			$dataRek['kd_sub_keg_x_xx'] = "x.xx.";
			$kel_rek = 'bidang';
		}
		if (isset($explodeAwal[2])) {
			$dataRek['kd_prog'] = $explodeAwal[0] . "." . $explodeAwal[1] . "." . $explodeAwal[2];
			$dataRek['kd_sub_keg_x_xx'] = "x.xx." . $explodeAwal[2];
			$kel_rek = 'prog';
		}
		if (isset($explodeAwal[4]) && isset($explodeAwal[3])) {
			$dataRek['kd_keg'] = $explodeAwal[0] . "." . $explodeAwal[1] . "." . $explodeAwal[2] . "." . $explodeAwal[3] . "." . $explodeAwal[4];
			$dataRek['kd_sub_keg_x_xx'] = "x.xx." . $explodeAwal[2] . "." . $explodeAwal[3] . "." . $explodeAwal[4];
			$kel_rek = 'keg';
		}
		if (isset($explodeAwal[5])) {
			$dataRek['kd_sub_keg'] = $explodeAwal[0] . "." . $explodeAwal[1] . "." . $explodeAwal[2] . "." . $explodeAwal[3] . "." . $explodeAwal[4] . "." . $explodeAwal[5];
			$dataRek['kd_sub_keg_x_xx'] = "x.xx." . $explodeAwal[2] . "." . $explodeAwal[3] . "." . $explodeAwal[4] . "." . $explodeAwal[5];
			$kel_rek = 'sub_keg';
		}
		$dataRek['kel_rek'] = $kel_rek;
		return $dataRek;
	}
	// kelola rekening akun
	public function kelolaRekAkun($dinamic = ['kd_akun' => ''])
	{
		if (isset($dinamic['kd_akun'])) {
			$kd_akun_data = $dinamic['kd_akun'];
			$explode_kd_akun = explode('.', $kd_akun_data);
			$i = 1;
			$kd_akun_olah_explode = [];
			foreach ($explode_kd_akun as $key => $value) {
				switch ($i) {
					case 6:
						$rek = $this->zero_pad((int)$value, 4);
						break;
					case 5:
						$rek = $this->zero_pad((int)$value, 2);
						break;
					case 4:
						$rek = $this->zero_pad((int)$value, 2);
						break;
					case 3:
						$rek = $this->zero_pad((int)$value, 2);
						break;
					case 2:
						$rek = (int)$value;
						break;
					case 1:
						$rek = (int)$value;
						break;
				}
				$kd_akun_olah_explode[] = $rek;
				$i++;
			}
			//satukan rekening
			$sizeOfRekening = sizeof($kd_akun_olah_explode);
			$rekening_gabung = implode('.', $kd_akun_olah_explode);
			$DB = DB::getInstance();
			$row_progkeg = $DB->getWhereOnceCustom('akun_neo', [['kode', '=', $rekening_gabung]]);
			$uraian = ($row_progkeg !== false) ? $row_progkeg->uraian : 'uraian NA';
			switch ($sizeOfRekening) {
				case 6:
					$kel_rek = 'sub_rincian';
					break;
				case 5:
					$kel_rek = 'rincian_objek';
					break;
				case 4:
					$kel_rek = 'objek';
					break;
				case 3:
					$kel_rek = 'jenis';
					break;
				case 2:
					$kel_rek = 'kelompok';
					break;
				case 1:
					$kel_rek = 'akun';
					break;
			};
			return ['$kel_rek' => $kel_rek, 'kd_akun' => $rekening_gabung, 'uraian' => $uraian];
		} else {
			return ['kd_akun' => 'NA'];
		}
	}
	// kelola rekening akun
	public function kelolaRekSubKeg($dinamic = ['kd_sub_keg' => ''])
	{
		if (isset($dinamic['kd_sub_keg'])) {
			$kd_sub_keg_data = $dinamic['kd_sub_keg'];
			$explode_kd_sub_keg = explode('.', $kd_sub_keg_data);
			$i = 1;
			$kd_sub_keg_olah_explode = [];
			foreach ($explode_kd_sub_keg as $key => $value) {
				switch ($i) {
					case 6:
						$rek = $this->zero_pad((int)$value, 4);
						break;
					case 5:
						$rek = $this->zero_pad((int)$value, 2);
						break;
					case 4:
						$rek = (int)$value;
						break;
					case 3:
						$rek = $this->zero_pad((int)$value, 2);
						break;
					case 2:
						$rek = (int)$value;
						break;
					case 1:
						$rek = (int)$value;
						break;
				}
				$kd_sub_keg_olah_explode[] = $rek;
				$i++;
			}
			$sizeOfRekening = sizeof($kd_sub_keg_olah_explode);
			//jika ada kegiatan satukan array 4 dan 5
			if ($sizeOfRekening > 3) {
				$kode_keg_gabung = [$kd_sub_keg_olah_explode[3] . '.' . $kd_sub_keg_olah_explode[4]];
				//hapus larik 3 sebanyak 2 artinya larik 3 dan 4 di hapus dan masukkan larik baru $kode_keg_gabung
				array_splice($kd_sub_keg_olah_explode, 3, 2, $kode_keg_gabung);
			}
			//satukan rekening
			$sizeOfRekening = sizeof($kd_sub_keg_olah_explode);
			$rekening_gabung = implode('.', $kd_sub_keg_olah_explode);
			$DB = DB::getInstance();
			$row_progkeg = $DB->getWhereOnceCustom('akun_neo', [['kode', '=', $rekening_gabung]]);
			$uraian = ($row_progkeg !== false) ? $row_progkeg->uraian : 'uraian NA';
			switch ($sizeOfRekening) {
				case 5:
					$kel_rek = 'sub_keg';
					break;
				case 4:
					$kel_rek = 'keg';
					break;
				case 3:
					$kel_rek = 'prog';
					break;
				case 2:
					$kel_rek = 'bidang';
					break;
				case 1:
					$kel_rek = 'urusan';
					break;
			};
			return ['$kel_rek' => $kel_rek, 'kd_sub_keg' => $rekening_gabung, 'uraian' => $uraian];
		} else {
			return ['kd_sub_keg' => 'NA'];
		}
	}
	//==========================================
	//update insert akun sampe urusan
	//==========================================
	public function kelolaRekSubKegDanAkun($dinamic = [
		'kd_sub_keg' => [], 'kd_akun' => [], 'tbl' => '', 'kd_wilayah' => '', 'kd_opd' => '', 'tahun' => 0, 'set' => []
	])
	{
		$kd_sub_keg_data = $dinamic['kd_sub_keg'];
		$kd_akun_data = [];
		$sizeOfKd_akun = 0;
		if (isset($dinamic['kd_akun'])) {
			$kd_akun_data = $dinamic['kd_akun'];
			$explode_kd_akun = explode('.', $kd_akun_data);
			$sizeOfKd_akun = sizeof($explode_kd_akun);
		}
		$explode_kd_sub_keg = explode('.', $kd_sub_keg_data);
		$tbl = $dinamic['tbl'];
		$sizeOfKd_sub_keg = sizeof($explode_kd_sub_keg);
		//jika ada kegiatan satukan array 4 dan 5
		if ($sizeOfKd_sub_keg > 3) {
			$kode_keg_gabung = [$explode_kd_sub_keg[3] . '.' . $explode_kd_sub_keg[4]];
			//hapus larik 3 sebanyak 2 artinya larik 3 dan 4 di hapus dan masukkan larik baru $kode_keg_gabung
			array_splice($explode_kd_sub_keg, 3, 2, $kode_keg_gabung);
		}
		$sizeOfKd_sub_keg = sizeof($explode_kd_sub_keg);
		$user = new User();
		$DB = DB::getInstance();
		$user->cekUserSession();
		$id_user = $_SESSION["user"]["id"];
		$userAktif = $DB->getWhereCustom('user_sesendok_biila', [['id', '=', $id_user]]);
		$jumlahArray = is_array($userAktif) ? count($userAktif) : 0;
		if ($jumlahArray > 0) {
			foreach ($userAktif[0] as $key => $value) {
				${$key} = $value;
			}
		}
		$data = ['note' => ['add row' => [], 'update' => []]];
		$kd_wilayah = $dinamic['kd_wilayah'];
		$kd_opd = $dinamic['kd_opd'];
		$tahun = $dinamic['tahun'];
		$set = $dinamic['set'];
		// cari nama opd, nama urusan dan bidang untuk 2 rekening pertama opd
		$xpldeKdOPD = explode('.', $kd_opd);
		$imploderek1_2 = (int)$xpldeKdOPD[0] . '.' . (int)$xpldeKdOPD[1];
		$row_result = $DB->getWhereOnceCustom('sub_kegiatan_neo', [['kode', '=', $xpldeKdOPD[0]]]);
		$uraian_sub_keg_x = ($row_result) ? 'PENUNJANG ' . $row_result->nomenklatur_urusan : 'penunjang data sub kegiatan tidak ditemukan';
		$row_result = $DB->getWhereOnceCustom('sub_kegiatan_neo', [['kode', '=', $imploderek1_2]]);
		$uraian_sub_keg_x_xx = ($row_result) ? 'PENUNJANG ' . $row_result->nomenklatur_urusan : 'penunjang data sub kegiatan tidak ditemukan';
		//
		if ($sizeOfKd_sub_keg) {
			$tabel_pakai = $this->tabel_pakai($tbl)['tabel_pakai'];
			$kolomVol_1 = '';
			$kolomVol_2 = '';
			$kolomVol_3 = '';
			$kolomVol_4 = '';
			$kolomVol_5 = '';
			$klmSumberDana = 'sumber_dana';
			switch ($tabel_pakai) {
				case 'sub_keg_dpa_neo':
				case 'sub_keg_renja_neo':
					$kolomJumlah = 'jumlah_pagu';
					$kolomJumlah_p = 'jumlah_pagu_p';
					$kd_akun = [];
					$sizeOfKd_akun = 0;
					$kolomUraian = 'uraian';
					break;
				case 'dpa_neo':
				case 'renja_neo':
					$kolomJumlah = 'jumlah';
					$kolomVol_1 = 'Vol_1';
					$kolomVol_2 = 'Vol_2';
					$kolomVol_3 = 'Vol_3';
					$kolomVol_4 = 'Vol_4';
					$kolomVol_5 = 'Vol_5';
					$kolomUraian = 'uraian';
					break;
				case 'dppa_neo':
				case 'renja_p_neo':
					$klmSumberDana = 'sumber_dana_p';
					$kolomJumlah = 'jumlah_p';
					$kolomUraian = 'uraian';
					$kolomVol_1 = 'Vol_1_p';
					$kolomVol_2 = 'Vol_2_p';
					$kolomVol_3 = 'Vol_3_p';
					$kolomVol_4 = 'Vol_4_p';
					$kolomVol_5 = 'Vol_5_p';
					break;
				case 'renstra_skpd_neo':
					$kolomJumlah = 'jumlah';
					$kd_akun = [];
					$sizeOfKd_akun = 0;
					$kolomUraian = 'uraian_prog_keg';
					break;
				default:
					break;
			};
			if ($sizeOfKd_akun < 5) { //urusan, bidang, prog, keg(x.xx), sub_keg
				$kd_akun = [];
				$sizeOfKd_akun = 0;
			} else {
				//insert update kd_akun uraian
				$kd_akun_olah = $explode_kd_akun;
				if ($sizeOfKd_akun == 6 && isset($set['kel_rek'])  && $set['kel_rek'] == 'uraian') {
					# input kel_rek=uraian
					// cek dulu sumber_dana, jenis_kelompok, kelompok, uraian jika sama update
					switch ($tabel_pakai) {
						case 'dpa_neo':
						case 'renja_neo':
						case 'dppa_neo':
						case 'renja_p_neo':
							switch ($tabel_pakai) {
								case 'dppa_neo':
								case 'renja_p_neo':
									$kolomHarga_satuan = 'harga_satuan';
									break;
								default:
									$kolomHarga_satuan = 'harga_satuan';
									break;
							}
							$kondisi = [['kd_sub_keg', '=', $set['kd_sub_keg']], ['kd_akun', '=', $set['kd_akun'], 'AND'], ['kel_rek', '=',  $set['kel_rek'], 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], [$klmSumberDana, '=', $set[$klmSumberDana], 'AND'], ['jenis_kelompok', '=', $set['jenis_kelompok'], 'AND'], ['kelompok', '=', $set['kelompok'], 'AND'], ['uraian', '=', $set['uraian'], 'AND'], ['komponen', '=', $set['komponen'], 'AND'], [$kolomHarga_satuan, '=', $set[$kolomHarga_satuan], 'AND'], ['jenis_standar_harga', '=', $set['jenis_standar_harga'], 'AND']];

							if (isset($dinamic['id_row'])) {
								$kondisi = [['kd_sub_keg', '=', $set['kd_sub_keg']], ['kd_akun', '=', $set['kd_akun'], 'AND'], ['kel_rek', '=',  $set['kel_rek'], 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], ['id', '=', $dinamic['id_row'], 'AND']];
							}
							break;
						case 'value':
							break;
						default:
							break;
					};
					// var_dump($kondisi);
					$row_result = $DB->getWhereOnceCustom($tabel_pakai, $kondisi);
					// var_dump($row_result->id);
					// var_dump($row_result);
					//
					if ($row_result === false && !isset($dinamic['id_row'])) {
						# insert baru
						$DB->insert($tabel_pakai, $set);
						$data['note']['add row'][] = $DB->lastInsertId();
					} else {
						# update jumlah
						// var_dump($set);
						$id_sdh_ada = (isset($dinamic['id_row'])) ? $dinamic['id_row'] : $row_result->id;
						$kondisi = [['kd_sub_keg', '=', $set['kd_sub_keg']], ['kd_akun', '=', $set['kd_akun'], 'AND'], ['kel_rek', '=',  $set['kel_rek'], 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], ['id', '=', $id_sdh_ada, 'AND']];
						$row_result = $DB->update_array($tabel_pakai, $set, $kondisi);
						// var_dump($row_result);
						if ($DB->count()) {
							$code = 3;
							$data['note']['update'][] = $id_sdh_ada;
							// $data['update'] = $DB->count(); //$DB->count();
							// var_dump( $data['update']);
						} else {
							$code = 33;
						}
					}
				}
				$i = 0;
				//=========================
				//== mulai inser kd akun ==
				//=========================
				$rekening_gabung_sub_keg = implode('.', $explode_kd_sub_keg);
				$rekening_gabung = implode('.', $kd_akun_olah);
				foreach ($explode_kd_akun as $key => $value) {
					$kd_akun_olah_sebelum = $rekening_gabung;
					if ($i != 0) {
						array_pop($kd_akun_olah); //hapus elemen terakhir
					}
					//satukan rekening
					$sizeOfRekening = sizeof($kd_akun_olah);
					$rekening_gabung = implode('.', $kd_akun_olah);
					// $akun = $kd_akun_olah[0];
					// $kelompok = isset($kd_akun_olah[1]) ? $kd_akun_olah[1] : null;
					// $jenis = isset($kd_akun_olah[2]) ? $kd_akun_olah[2] : null;
					// $objek = isset($kd_akun_olah[3]) ? $kd_akun_olah[3] : null;
					// $rincian_objek = isset($kd_akun_olah[4]) ? $kd_akun_olah[4] : null;
					// $sub_rincian = isset($kd_akun_olah[5]) ? $kd_akun_olah[5] : null;
					switch ($sizeOfRekening) {
						case 6:
							$kel_rekening = 'sub_rincian';
							$kel_rek_sum = 'uraian';
							break;
						case 5:
							$kel_rekening = 'rincian_objek';
							$kel_rek_sum = 'sub_rincian';
							break;
						case 4:
							$kel_rekening = 'objek';
							$kel_rek_sum = 'rincian_objek';
							break;
						case 3:
							$kel_rekening = 'jenis';
							$kel_rek_sum = 'objek';
							break;
						case 2:
							$kel_rekening = 'kelompok';
							$kel_rek_sum = 'jenis';
							break;
						case 1:
							$kel_rekening = 'akun'; //kel_rek
							$kel_rek_sum = 'kelompok';
							break;
					};
					$kondisi_sum = [['kd_sub_keg', '=', $rekening_gabung_sub_keg], ['kd_akun', 'LIKE',  "$rekening_gabung%", 'AND'], ['kel_rek', '=',  $kel_rek_sum, 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
					//ambil jumlah 
					$DB->select("SUM({$kolomJumlah}) AS jumlah");
					$row_sum = $DB->getWhereArray($tabel_pakai, $kondisi_sum);
					$jumlah = $row_sum[0]->jumlah;
					//selesai ambil jumlah
					$DB->select("*");
					switch ($tabel_pakai) {
						case 'dpa_neo':
						case 'renja_neo':
							$arraySum = [
								$kolomJumlah => (float)$jumlah,
							];
						case 'dppa_neo':
						case 'renja_p_neo':
							$row_progkeg = $DB->getWhereOnceCustom('akun_neo', [['kode', '=', $rekening_gabung]]);
							$uraian = ($row_progkeg) ? $row_progkeg->uraian : 'data akun tidak ditemukan';
							$set_insert = [
								'kd_wilayah' => $kd_wilayah,
								'kd_opd' => $kd_opd,
								'tahun' => $tahun,
								'kd_sub_keg' => $rekening_gabung_sub_keg,
								'kd_akun' => $rekening_gabung,
								'kel_rek' => $kel_rekening, //kd
								'objek_belanja' => $set['objek_belanja'],
								'uraian' => $uraian,
								'jenis_kelompok' => '',
								'kelompok' => '',
								'jenis_standar_harga' => '',
								'id_standar_harga' => 0,
								'harga_satuan' => 0,
								'vol_1' => 0,
								'vol_2' => 0,
								'vol_3' => 0,
								'vol_4' => 0,
								'sat_1' => '',
								$kolomJumlah => (float)$jumlah,
								'disable' => 0,
								'tgl_insert' => date('Y-m-d H:i:s'),
								'tgl_update' => date('Y-m-d H:i:s'),
								'username_insert' => $_SESSION["user"]["username"],
								'username_update' => $_SESSION["user"]["username"]
							];
							$kondisi = [['kd_sub_keg', '=', $rekening_gabung_sub_keg], ['kd_akun', '=',  $rekening_gabung, 'AND'], ['kel_rek', '=',  $kel_rekening, 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
							// select sum
							if ($sizeOfRekening == 6) {
								# sum jumlah kd akun yang 
							}
							break;
						default:
							break;
					};
					$row_uraian = $DB->getWhereOnceCustom($tabel_pakai, $kondisi);
					if ($row_uraian === false) {
						# insert baru
						$DB->insert($tabel_pakai, $set_insert);
						$data['note']['add row'][] = $DB->lastInsertId();
					} else {
						# update jumlah
						$DB->update_array($tabel_pakai, $set_insert, $kondisi);
						if ($DB->count()) {
							$code = 3;
							$data['update'] = $DB->count(); //$DB->count();
						} else {
							$code = 33;
						}
					}
					// var_dump($row_sum);
					$i++;
				}
			}
			//==============================
			//insert bidang s/d sub kegiatan
			//==============================
			$i = 0;
			$kd_subKeg_olah = $explode_kd_sub_keg;
			$rekening_gabung = implode('.', $kd_subKeg_olah);
			// var_dump($tabel_pakai);
			$kondisi_sum = '';
			$jumlah = $set[$kolomJumlah];
			foreach ($explode_kd_sub_keg as $key => $value) {
				$kd_subKeg_olah_sebelum = $rekening_gabung;
				// var_dump($kd_subKeg_olah_sebelum);
				if ($i != 0) {
					array_pop($kd_subKeg_olah); //hapus elemen terakhir
				}
				//satukan rekening
				$sizeOfRekening = sizeof($kd_subKeg_olah);
				$rekening_gabung = implode('.', $kd_subKeg_olah);
				// uraikan kd_sub_keg
				// $kd_urusan = $kd_subKeg_olah[0];
				// $kd_bidang = isset($kd_subKeg_olah[1]) ? $kd_subKeg_olah[1] : null;
				// $kd_prog = isset($kd_subKeg_olah[2]) ? $kd_subKeg_olah[2] : null;
				// $kd_keg = isset($kd_subKeg_olah[3]) ? $kd_subKeg_olah[3] : null;
				// $kd_sub_keg = isset($kd_subKeg_olah[4]) ? $kd_subKeg_olah[4] : null;
				switch ($sizeOfRekening) {
					case 5:
						$kel_rekening = 'sub_keg'; //kelompok rekening/kel_rek
						$kel_rek_sum = 'akun';
						break;
					case 4:
						$kel_rekening = 'keg'; //kelompok rekening
						$kel_rek_sum = 'sub_keg';
						break;
					case 3:
						$kel_rekening = 'prog';
						$kel_rek_sum = 'keg';
						break;
					case 2:
						$kel_rekening = 'bidang';
						$kel_rek_sum = 'prog';
						break;
					case 1:
						$kel_rekening = 'urusan';
						$kel_rek_sum = 'bidang';
						break;
				};
				$row_progkeg = $DB->getWhereOnceCustom('sub_kegiatan_neo', [['kode', '=', $rekening_gabung]]);
				$uraian = ($row_progkeg) ? $row_progkeg->nomenklatur_urusan : 'data sub kegiatan tidak ditemukan';
				switch ($rekening_gabung) {
					case 'x':
						$uraian = $uraian_sub_keg_x;
						break;
					case 'x.xx':
						$uraian = $uraian_sub_keg_x_xx;
						break;
				};
				switch ($tabel_pakai) {
					case 'dpa_neo':
					case 'renja_neo':
					case 'dppa_neo':
					case 'renja_p_neo':
						$kondisi_sum = [['kd_sub_keg', "LIKE", "$rekening_gabung%"], ['kel_rek', '=',  $kel_rek_sum, 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
						break;
					case 'sub_keg_dpa_neo':
					case 'sub_keg_renja_neo':
					case 'renstra_skpd_neo':
						if ($sizeOfRekening == 5) {
							$kondisi_sum = '';
							$set_insert = $set;
						} else {
							$kondisi_sum = [['kd_sub_keg', "LIKE", "$rekening_gabung%"], ['kel_rek', '=',  $kel_rek_sum, 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
						}
						$kondisi = [['kd_sub_keg', '=', $rekening_gabung], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
						// select sum
						break;
					default:
						break;
				};
				//ambil jumlah 
				// var_dump($set_insert);
				if ($kondisi_sum != '') {
					$DB->select("SUM({$kolomJumlah}) AS jumlah");
					$row_sum = $DB->getWhereArray($tabel_pakai, $kondisi_sum);
					$jumlah = $row_sum[0]->jumlah;
					// var_dump($jumlah);
					$DB->select("*");
				} else {
					$jumlah = $set[$kolomJumlah];
					// var_dump($jumlah);
				}
				// var_dump($sizeOfRekening);
				switch ($tabel_pakai) {
					case 'dpa_neo':
					case 'renja_neo':
					case 'dppa_neo':
					case 'renja_p_neo':
						$kondisi = [['kd_sub_keg', '=', $rekening_gabung], ['kd_akun', '=', '', 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
						$set_insert = [
							'kd_wilayah' => $kd_wilayah,
							'kd_opd' => $kd_opd,
							'tahun' => $tahun,
							'kd_sub_keg' => $rekening_gabung,
							'kd_akun' => '',
							'kel_rek' => $kel_rekening, //kd
							'objek_belanja' => $set['objek_belanja'],
							'uraian' => $uraian,
							'jenis_kelompok' => '',
							'kelompok' => '',
							'jenis_standar_harga' => '',
							'id_standar_harga' => 0,
							'harga_satuan' => 0,
							'vol_1' => 0,
							'vol_2' => 0,
							'vol_3' => 0,
							'vol_4' => 0,
							'sat_1' => '',
							$kolomJumlah => (float)$jumlah,
							'disable' => 0,
							'tgl_insert' => date('Y-m-d H:i:s'),
							'tgl_update' => date('Y-m-d H:i:s'),
							'username_insert' => $_SESSION["user"]["username"],
							'username_update' => $_SESSION["user"]["username"]
						];
						// select sum
						if ($sizeOfRekening == 5) {
							# sum jumlah kd akun yang 
						}
						$kondisi_sum = [['kd_sub_keg', "LIKE", "$rekening_gabung%"], ['kel_rek', '=',  $kel_rek_sum, 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
						break;
					case 'sub_keg_dpa_neo':
					case 'sub_keg_renja_neo':
					case 'renstra_skpd_neo':
						if ($sizeOfRekening == 5) {
							$kondisi_sum = '';
							$set_insert = $set;
						} else {
							$kondisi_sum = [['kd_sub_keg', "LIKE", "$rekening_gabung%"], ['kel_rek', '=',  $kel_rek_sum, 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
							// jika nomor rek adalah x atau x.xx maka urusan dan bidang disesuaian
							switch ($rekening_gabung) {
								case 'x':
									$uraian = $uraian_sub_keg_x;
									break;
								case 'x.xx':
									$uraian = $uraian_sub_keg_x_xx;
									break;
							};
							$set_insert = [
								'kd_wilayah' => $kd_wilayah,
								'kd_opd' => $kd_opd,
								'tahun' => $tahun,
								'kd_sub_keg' => $rekening_gabung,
								$kolomUraian => $uraian,
								'kel_rek' => $kel_rekening, //kd
								$kolomJumlah => (float)$jumlah,
								'disable' => 0,
								'tgl_insert' => date('Y-m-d H:i:s'),
								'tgl_update' => date('Y-m-d H:i:s'),
								'username_update' => $_SESSION["user"]["username"],
								'username_insert' => $_SESSION["user"]["username"]
							];
						}
						$kondisi = [['kd_sub_keg', '=', $rekening_gabung], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
						// select sum
						break;
					default:
						break;
				};
				//selesai ambil jumlah
				$DB->select("*");
				$row_uraian = $DB->getWhereOnceCustom($tabel_pakai, $kondisi);
				// var_dump($row_uraian);
				if ($row_uraian === false) {
					# insert baru
					$DB->insert($tabel_pakai, $set_insert);
					$data['note']['add row'][] = $DB->lastInsertId();
				} else {
					# update jumlah
					$DB->update_array($tabel_pakai, $set_insert, $kondisi);
					if ($DB->count()) {
						$code = 3;
						$data['update'] = $DB->count(); //$DB->count();
					} else {
						$code = 33;
					}
				}
				switch ($tabel_pakai) {
					case 'sub_keg_dpa_neo':
					case 'sub_keg_renja_neo':
						break;
					case 'renja_p_neo':
					case 'renja_neo':
						$tabel_update = 'sub_keg_renja_neo';
					case 'dpa_neo':
					case 'dppa_neo':
						if ($tabel_pakai == 'dpa_neo' || $tabel_pakai == 'dppa_neo') {
							$tabel_update = 'sub_keg_dpa_neo';
						}
						$kolom_jumlah_update = 'jumlah_rincian';
						if ($tabel_pakai == 'renja_p_neo' || $tabel_pakai == 'dppa_neo') {
							$kolom_jumlah_update = 'jumlah_rincian_p';
						}
						// update jumlah tabel sub_keg_dpa_neo atau sub_keg_renja_neo
						$set_update = [
							$kolom_jumlah_update => (float)$jumlah,
							'tgl_update' => date('Y-m-d H:i:s'),
							'username_insert' => $_SESSION["user"]["username"]
						];

						$kondisi_update = [['kd_sub_keg', '=', $rekening_gabung], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
						$DB->update_array($tabel_update, $set_update, $kondisi_update);
						break;
					case 'renstra_neo':
						break;
					default:
						break;
				};
				$i++;
			}
		}
		return $data;
	}
	//==========================================
	//sum akun sampe kegiatan
	//==========================================
	public function sumAkunSubKeg($dinamic = [
		'kd_sub_keg' => [], 'kd_akun' => [], 'tbl' => '', 'kd_wilayah' => '', 'kd_opd' => '', 'tahun' => 0, 'set' => []
	])
	{
		$kd_sub_keg_data = $dinamic['kd_sub_keg'];
		$kd_akun_data = [];
		$sizeOfKd_akun = 0;
		if (isset($dinamic['kd_akun'])) {
			$kd_akun_data = $dinamic['kd_akun'];
			$explode_kd_akun = explode('.', $kd_akun_data);
			$sizeOfKd_akun = sizeof($explode_kd_akun);
		}
		$explode_kd_sub_keg = explode('.', $kd_sub_keg_data);
		$tbl = $dinamic['tbl'];
		$sizeOfKd_sub_keg = sizeof($explode_kd_sub_keg);
		//jika ada kegiatan satukan array 4 dan 5
		if ($sizeOfKd_sub_keg > 3) {
			$kode_keg_gabung = [$explode_kd_sub_keg[3] . '.' . $explode_kd_sub_keg[4]];
			//hapus larik 3 sebanyak 2 artinya larik 3 dan 4 di hapus dan masukkan larik baru $kode_keg_gabung
			array_splice($explode_kd_sub_keg, 3, 2, $kode_keg_gabung);
		}
		$sizeOfKd_sub_keg = sizeof($explode_kd_sub_keg);
		$user = new User();
		$DB = DB::getInstance();
		$user->cekUserSession();
		$id_user = $_SESSION["user"]["id"];
		$userAktif = $DB->getWhereCustom('user_sesendok_biila', [['id', '=', $id_user]]);
		$jumlahArray = is_array($userAktif) ? count($userAktif) : 0;
		if ($jumlahArray > 0) {
			foreach ($userAktif[0] as $key => $value) {
				${$key} = $value;
			}
		}
		$data = ['note' => ['add row' => [], 'update' => []]];
		$kd_wilayah = $dinamic['kd_wilayah'];
		$kd_opd = $dinamic['kd_opd'];
		$tahun = $dinamic['tahun'];
		$set = $dinamic['set'];
		// cari nama opd, nama urusan dan bidang untuk 2 rekening pertama opd
		$xpldeKdOPD = explode('.', $kd_opd);
		$imploderek1_2 = (int)$xpldeKdOPD[0] . '.' . (int)$xpldeKdOPD[1];
		$row_result = $DB->getWhereOnceCustom('sub_kegiatan_neo', [['kode', '=', $xpldeKdOPD[0]]]);
		$uraian_sub_keg_x = ($row_result) ? 'PENUNJANG ' . $row_result->nomenklatur_urusan : 'penunjang data sub kegiatan tidak ditemukan';
		$row_result = $DB->getWhereOnceCustom('sub_kegiatan_neo', [['kode', '=', $imploderek1_2]]);
		$uraian_sub_keg_x_xx = ($row_result) ? 'PENUNJANG ' . $row_result->nomenklatur_urusan : 'penunjang data sub kegiatan tidak ditemukan';
		//
		if ($sizeOfKd_sub_keg) {
			$tabel_pakai = $this->tabel_pakai($tbl)['tabel_pakai'];
			$kolomVol_1 = '';
			$kolomVol_2 = '';
			$kolomVol_3 = '';
			$kolomVol_4 = '';
			$kolomVol_5 = '';
			$klmSumberDana = 'sumber_dana';
			switch ($tabel_pakai) {
				case 'sub_keg_dpa_neo':
				case 'sub_keg_renja_neo':
					$kolomJumlah = 'jumlah_pagu';
					$kolomJumlah_p = 'jumlah_pagu_p';
					$kd_akun = [];
					$sizeOfKd_akun = 0;
					$kolomUraian = 'uraian';
					break;
				case 'dpa_neo':
				case 'renja_neo':
					$kolomJumlah = 'jumlah';
					$kolomVol_1 = 'Vol_1';
					$kolomVol_2 = 'Vol_2';
					$kolomVol_3 = 'Vol_3';
					$kolomVol_4 = 'Vol_4';
					$kolomVol_5 = 'Vol_5';
					$kolomUraian = 'uraian';
					break;
				case 'dppa_neo':
				case 'renja_p_neo':
					$klmSumberDana = 'sumber_dana_p';
					$kolomJumlah = 'jumlah_p';
					$kolomUraian = 'uraian';
					$kolomVol_1 = 'Vol_1_p';
					$kolomVol_2 = 'Vol_2_p';
					$kolomVol_3 = 'Vol_3_p';
					$kolomVol_4 = 'Vol_4_p';
					$kolomVol_5 = 'Vol_5_p';
					break;
				case 'renstra_skpd_neo':
					$kolomJumlah = 'jumlah';
					$kd_akun = [];
					$sizeOfKd_akun = 0;
					$kolomUraian = 'uraian_prog_keg';
					break;
				default:
					break;
			};
			if ($sizeOfKd_akun < 5) { //urusan, bidang, prog, keg(x.xx), sub_keg
				$kd_akun = [];
				$sizeOfKd_akun = 0;
			} else {
				//insert update kd_akun uraian
				$kd_akun_olah = $explode_kd_akun;
				if ($sizeOfKd_akun == 6 && isset($set['kel_rek'])  && $set['kel_rek'] == 'uraian') {
					# input kel_rek=uraian
					// cek dulu sumber_dana, jenis_kelompok, kelompok, uraian jika sama update
					switch ($tabel_pakai) {
						case 'dpa_neo':
						case 'renja_neo':
						case 'dppa_neo':
						case 'renja_p_neo':
							switch ($tabel_pakai) {
								case 'dppa_neo':
								case 'renja_p_neo':
									$kolomHarga_satuan = 'harga_satuan';
									break;
								default:
									$kolomHarga_satuan = 'harga_satuan';
									break;
							}
							$kondisi = [['kd_sub_keg', '=', $set['kd_sub_keg']], ['kd_akun', '=', $set['kd_akun'], 'AND'], ['kel_rek', '=',  $set['kel_rek'], 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], [$klmSumberDana, '=', $set[$klmSumberDana], 'AND'], ['jenis_kelompok', '=', $set['jenis_kelompok'], 'AND'], ['kelompok', '=', $set['kelompok'], 'AND'], ['uraian', '=', $set['uraian'], 'AND'], ['komponen', '=', $set['komponen'], 'AND'], [$kolomHarga_satuan, '=', $set[$kolomHarga_satuan], 'AND'], ['jenis_standar_harga', '=', $set['jenis_standar_harga'], 'AND']];

							if (isset($dinamic['id_row'])) {
								$kondisi = [['kd_sub_keg', '=', $set['kd_sub_keg']], ['kd_akun', '=', $set['kd_akun'], 'AND'], ['kel_rek', '=',  $set['kel_rek'], 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], ['id', '=', $dinamic['id_row'], 'AND']];
							}
							break;
						case 'value':
							break;
						default:
							break;
					};
					// var_dump($kondisi);
					$row_result = $DB->getWhereOnceCustom($tabel_pakai, $kondisi);
				}
				$i = 0;
				//=========================
				//== mulai inser kd akun ==
				//=========================
				$rekening_gabung_sub_keg = implode('.', $explode_kd_sub_keg);
				$rekening_gabung = implode('.', $kd_akun_olah);
				foreach ($explode_kd_akun as $key => $value) {
					$kd_akun_olah_sebelum = $rekening_gabung;
					if ($i != 0) {
						array_pop($kd_akun_olah); //hapus elemen terakhir
					}
					//satukan rekening
					$sizeOfRekening = sizeof($kd_akun_olah);
					$rekening_gabung = implode('.', $kd_akun_olah);
					switch ($sizeOfRekening) {
						case 6:
							$kel_rekening = 'sub_rincian';
							$kel_rek_sum = 'uraian';
							break;
						case 5:
							$kel_rekening = 'rincian_objek';
							$kel_rek_sum = 'sub_rincian';
							break;
						case 4:
							$kel_rekening = 'objek';
							$kel_rek_sum = 'rincian_objek';
							break;
						case 3:
							$kel_rekening = 'jenis';
							$kel_rek_sum = 'objek';
							break;
						case 2:
							$kel_rekening = 'kelompok';
							$kel_rek_sum = 'jenis';
							break;
						case 1:
							$kel_rekening = 'akun'; //kel_rek
							$kel_rek_sum = 'kelompok';
							break;
					};
					$kondisi_sum = [['kd_sub_keg', '=', $rekening_gabung_sub_keg], ['kd_akun', 'LIKE',  "$rekening_gabung%", 'AND'], ['kel_rek', '=',  $kel_rek_sum, 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
					//ambil jumlah 
					$DB->select("SUM({$kolomJumlah}) AS jumlah");
					$row_sum = $DB->getWhereArray($tabel_pakai, $kondisi_sum);
					$jumlah = $row_sum[0]->jumlah;
					//selesai ambil jumlah
					$DB->select("*");
					switch ($tabel_pakai) {
						case 'dpa_neo':
						case 'renja_neo':
						case 'dppa_neo':
						case 'renja_p_neo':
							$row_progkeg = $DB->getWhereOnceCustom('akun_neo', [['kode', '=', $rekening_gabung]]);
							$uraian = ($row_progkeg) ? $row_progkeg->uraian : 'data akun tidak ditemukan';
							$set_insert = [
								'kd_wilayah' => $kd_wilayah,
								'kd_opd' => $kd_opd,
								'tahun' => $tahun,
								'kd_sub_keg' => $rekening_gabung_sub_keg,
								'kd_akun' => $rekening_gabung,
								'kel_rek' => $kel_rekening, //kd
								'objek_belanja' => $set['objek_belanja'],
								'uraian' => $uraian,
								'jenis_kelompok' => '',
								'kelompok' => '',
								'jenis_standar_harga' => '',
								'id_standar_harga' => 0,
								'harga_satuan' => 0,
								'vol_1' => 0,
								'vol_2' => 0,
								'vol_3' => 0,
								'vol_4' => 0,
								'sat_1' => '',
								$kolomJumlah => (float)$jumlah
							];
							$kondisi = [['kd_sub_keg', '=', $rekening_gabung_sub_keg], ['kd_akun', '=',  $rekening_gabung, 'AND'], ['kel_rek', '=',  $kel_rekening, 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
							// select sum
							if ($sizeOfRekening == 6) {
								# sum jumlah kd akun yang 
							}
							break;
						default:
							break;
					};
					// $row_uraian = $DB->getWhereOnceCustom($tabel_pakai, $kondisi);
					// if ($row_uraian === false) {
					//     # insert baru
					//     $DB->insert($tabel_pakai, $set_insert);
					//     $data['note']['add row'][] = $DB->lastInsertId();
					// } else {
					//     # update jumlah
					//     $DB->update_array($tabel_pakai, $set_insert, $kondisi);
					//     if ($DB->count()) {
					//         $code = 3;
					//         $data['update'] = $DB->count(); //$DB->count();
					//     } else {
					//         $code = 33;
					//     }
					// }
					// var_dump($row_sum);
					$i++;
				}
			}
			//==============================
			//insert bidang s/d sub kegiatan
			//==============================
			$i = 0;
			$kd_subKeg_olah = $explode_kd_sub_keg;
			$rekening_gabung = implode('.', $kd_subKeg_olah);
			// var_dump($tabel_pakai);
			$kondisi_sum = '';
			$jumlah = $set[$kolomJumlah];
			foreach ($explode_kd_sub_keg as $key => $value) {
				$kd_subKeg_olah_sebelum = $rekening_gabung;
				// var_dump($kd_subKeg_olah_sebelum);
				if ($i != 0) {
					array_pop($kd_subKeg_olah); //hapus elemen terakhir
				}
				//satukan rekening
				$sizeOfRekening = sizeof($kd_subKeg_olah);
				$rekening_gabung = implode('.', $kd_subKeg_olah);
				// uraikan kd_sub_keg
				// $kd_urusan = $kd_subKeg_olah[0];
				// $kd_bidang = isset($kd_subKeg_olah[1]) ? $kd_subKeg_olah[1] : null;
				// $kd_prog = isset($kd_subKeg_olah[2]) ? $kd_subKeg_olah[2] : null;
				// $kd_keg = isset($kd_subKeg_olah[3]) ? $kd_subKeg_olah[3] : null;
				// $kd_sub_keg = isset($kd_subKeg_olah[4]) ? $kd_subKeg_olah[4] : null;
				switch ($sizeOfRekening) {
					case 5:
						$kel_rekening = 'sub_keg'; //kelompok rekening/kel_rek
						$kel_rek_sum = 'akun';
						break;
					case 4:
						$kel_rekening = 'keg'; //kelompok rekening
						$kel_rek_sum = 'sub_keg';
						break;
					case 3:
						$kel_rekening = 'prog';
						$kel_rek_sum = 'keg';
						break;
					case 2:
						$kel_rekening = 'bidang';
						$kel_rek_sum = 'prog';
						break;
					case 1:
						$kel_rekening = 'urusan';
						$kel_rek_sum = 'bidang';
						break;
				};
				$row_progkeg = $DB->getWhereOnceCustom('sub_kegiatan_neo', [['kode', '=', $rekening_gabung]]);
				$uraian = ($row_progkeg) ? $row_progkeg->nomenklatur_urusan : 'data sub kegiatan tidak ditemukan';
				switch ($rekening_gabung) {
					case 'x':
						$uraian = $uraian_sub_keg_x;
						break;
					case 'x.xx':
						$uraian = $uraian_sub_keg_x_xx;
						break;
				};
				switch ($tabel_pakai) {
					case 'dpa_neo':
					case 'renja_neo':
					case 'dppa_neo':
					case 'renja_p_neo':
						$kondisi_sum = [['kd_sub_keg', "LIKE", "$rekening_gabung%"], ['kel_rek', '=',  $kel_rek_sum, 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
						break;
					case 'sub_keg_dpa_neo':
					case 'sub_keg_renja_neo':
					case 'renstra_skpd_neo':
						if ($sizeOfRekening == 5) {
							$kondisi_sum = '';
							$set_insert = $set;
						} else {
							$kondisi_sum = [['kd_sub_keg', "LIKE", "$rekening_gabung%"], ['kel_rek', '=',  $kel_rek_sum, 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
						}
						$kondisi = [['kd_sub_keg', '=', $rekening_gabung], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
						// select sum
						break;
					default:
						break;
				};
				//ambil jumlah 
				// var_dump($set_insert);
				if ($kondisi_sum != '') {
					$DB->select("SUM({$kolomJumlah}) AS jumlah");
					$row_sum = $DB->getWhereArray($tabel_pakai, $kondisi_sum);
					$jumlah = $row_sum[0]->jumlah;
					// var_dump($jumlah);
					$DB->select("*");
				} else {
					$jumlah = $set[$kolomJumlah];
					// var_dump($jumlah);
				}
				// var_dump($sizeOfRekening);
				switch ($tabel_pakai) {
					case 'dpa_neo':
					case 'renja_neo':
					case 'dppa_neo':
					case 'renja_p_neo':
						$kondisi = [['kd_sub_keg', '=', $rekening_gabung], ['kd_akun', '=', '', 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
						$set_insert = [
							'kd_wilayah' => $kd_wilayah,
							'kd_opd' => $kd_opd,
							'tahun' => $tahun,
							'kd_sub_keg' => $rekening_gabung,
							'kd_akun' => '',
							'kel_rek' => $kel_rekening, //kd
							'objek_belanja' => $set['objek_belanja'],
							'uraian' => $uraian,
							'jenis_kelompok' => '',
							'kelompok' => '',
							'jenis_standar_harga' => '',
							'id_standar_harga' => 0,
							'harga_satuan' => 0,
							'vol_1' => 0,
							'vol_2' => 0,
							'vol_3' => 0,
							'vol_4' => 0,
							'sat_1' => '',
							$kolomJumlah => (float)$jumlah,
							'disable' => 0,
							'tgl_insert' => date('Y-m-d H:i:s'),
							'tgl_update' => date('Y-m-d H:i:s'),
							'username_insert' => $_SESSION["user"]["username"],
							'username_update' => $_SESSION["user"]["username"]
						];
						// select sum
						if ($sizeOfRekening == 5) {
							# sum jumlah kd akun yang 
						}
						$kondisi_sum = [['kd_sub_keg', "LIKE", "$rekening_gabung%"], ['kel_rek', '=',  $kel_rek_sum, 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
						break;
					case 'sub_keg_dpa_neo':
					case 'sub_keg_renja_neo':
					case 'renstra_skpd_neo':
						if ($sizeOfRekening == 5) {
							$kondisi_sum = '';
							$set_insert = $set;
						} else {
							$kondisi_sum = [['kd_sub_keg', "LIKE", "$rekening_gabung%"], ['kel_rek', '=',  $kel_rek_sum, 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
							// jika nomor rek adalah x atau x.xx maka urusan dan bidang disesuaian
							switch ($rekening_gabung) {
								case 'x':
									$uraian = $uraian_sub_keg_x;
									break;
								case 'x.xx':
									$uraian = $uraian_sub_keg_x_xx;
									break;
							};
							$set_insert = [
								'kd_wilayah' => $kd_wilayah,
								'kd_opd' => $kd_opd,
								'tahun' => $tahun,
								'kd_sub_keg' => $rekening_gabung,
								$kolomUraian => $uraian,
								'kel_rek' => $kel_rekening, //kd
								$kolomJumlah => (float)$jumlah,
								'disable' => 0,
								'tgl_insert' => date('Y-m-d H:i:s'),
								'tgl_update' => date('Y-m-d H:i:s'),
								'username_insert' => $_SESSION["user"]["username"],
								'username_update' => $_SESSION["user"]["username"]
							];
						}
						$kondisi = [['kd_sub_keg', '=', $rekening_gabung], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
						// select sum
						break;
					default:
						break;
				};
				//selesai ambil jumlah
				$DB->select("*");
				$row_uraian = $DB->getWhereOnceCustom($tabel_pakai, $kondisi);
				// var_dump($row_uraian);
				if ($row_uraian === false) {
					# insert baru
					// $DB->insert($tabel_pakai, $set_insert);
					// $data['note']['add row'][] = $DB->lastInsertId();
				} else {
					# update jumlah
					// $DB->update_array($tabel_pakai, $set_insert, $kondisi);
					// if ($DB->count()) {
					//     $code = 3;
					//     $data['update'] = $DB->count(); //$DB->count();
					// } else {
					//     $code = 33;
					// }
				}
				switch ($tabel_pakai) {
					case 'sub_keg_dpa_neo':
					case 'sub_keg_renja_neo':
						break;
					case 'renja_p_neo':
					case 'renja_neo':
						$tabel_update = 'sub_keg_renja_neo';
					case 'dpa_neo':
					case 'dppa_neo':
						// if ($tabel_pakai == 'dpa_neo' || $tabel_pakai == 'dppa_neo') {
						//     $tabel_update = 'sub_keg_dpa_neo';
						// }
						// $kolom_jumlah_update = 'jumlah_rincian';
						// if ($tabel_pakai == 'renja_p_neo' || $tabel_pakai == 'dppa_neo') {
						//     $kolom_jumlah_update = 'jumlah_rincian_p';
						// }
						// update jumlah tabel sub_keg_dpa_neo atau sub_keg_renja_neo
						// $set_update = [
						//     $kolom_jumlah_update => (float)$jumlah,
						//     'tgl_update' => date('Y-m-d H:i:s'),
						//     'username_insert' => $_SESSION["user"]["username"]
						// ];

						// $kondisi_update = [['kd_sub_keg', '=', $rekening_gabung], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
						// $DB->update_array($tabel_update, $set_update, $kondisi_update);
						break;
					case 'renstra_neo':
						break;
					default:
						break;
				};
				$i++;
			}
		}
		return $data;
	}
	//CRUD kolom type json
	public function add_update_field_json($dinamic = [
		'tabel_pakai' => '', 'nama_kolom' => 'keterangan_json', 'jenis_kelompok' => 'keterangan', 'uraian_field' => '', 'dataKondisiField' => []
	])
	{
		$DB = DB::getInstance();
		//buat variable
		$tabel_pakai = $dinamic['tabel_pakai'];
		$nama_kolom = $dinamic['nama_kolom'];
		$jenis_kelompok = $dinamic['jenis_kelompok'];
		$dataKondisiField = $dinamic['dataKondisiField'];
		$uraian_field = $dinamic['uraian_field'];
		//ambil data
		$data_klm = $DB->readJSONField($tabel_pakai, $nama_kolom, $jenis_kelompok, $dataKondisiField); //sdh ok
		// Menghapus tanda kutip tunggal yang tidak valid
		//cari index di array
		$key = false;
		if ($data_klm) {
			$data_klm = json_decode($data_klm, true);
			$key = array_search($uraian_field, $data_klm, true);
			$kode_Field = 'updateJSONField';
		} else {
			$data_klm = array();
			$kode_Field  = 'insertJSONField';
		}
		if ($key === false) {
			$data_klm[] = $uraian_field;
			$uraian_field_insert = json_encode($data_klm);
			// var_dump($uraian_field_insert);
			if ($kode_Field  == 'insertJSONField') {
				$tambah = $DB->insertJSONField($tabel_pakai, $nama_kolom, $uraian_field_insert, $jenis_kelompok, $dataKondisiField);
			} else {
				$tambah = $DB->updateJSONField($tabel_pakai, $nama_kolom, $uraian_field_insert, $jenis_kelompok, $dataKondisiField);
			}
			if ($tambah) {
				$code = 3;
			} else {
				$code = 33;
			}
		} else {
			$code = 37;
		}
	}
	//pangkat golongan asn
	public function golongan_ruang($dinamic = ['golongan' => 1, 'ruang' => 'a'])
	{
		$golongan = $dinamic['golongan'];
		$ruang = strtolower($dinamic['ruang']);
		$pangkat = '';
		$pangkat2 = '';
		switch ($golongan) {
			case 1:
				$pangkat = 'juru';
				$pangkat2 = 'I';
				break;
			case 2:
				$pangkat2 = 'II';
				$pangkat = 'pengatur';
				break;
			case 3:
				$pangkat2 = 'III';
				$pangkat = 'penata';
				break;
			case 4:
				$pangkat2 = 'IV';
				$pangkat = 'pembina';
				break;
			default:
				# code...
				break;
		}
		switch ($golongan) {
			case 1:
			case 2:
			case 3:
				switch ($ruang) {
					case 'a':
						$pangkat .= ' muda';
						break;
					case 'b':
						$pangkat .= ' muda tingkat I';
						break;
					case 'c':
						$pangkat .= '';
						break;
					case 'd':
						$pangkat .= ' tingkat I';
						break;
					default:
						# code...
						break;
				}
				break;
			case 4:
				switch ($ruang) {
					case 'a':
						$pangkat .= '';
						break;
					case 'b':
						$pangkat .= ' tingkat I';
						break;
					case 'c':
						$pangkat .= ' muda';
						break;
					case 'd':
						$pangkat .= ' madya';
						break;
					case 'e':
						$pangkat .= ' utama';
						break;
					default:
						# code...
						break;
				}
				break;
		}
		$pangkat2 .= "/$ruang";
		return ['pangkat' => $pangkat, 'singkat' => $pangkat2];
	}
	//ukuran kertas dalam mm untuk tcpdf
	public function kertas($kertas = 'A4')
	{
		$ukuran = [210, 297];
		switch (strtolower($kertas)) {
			case 'a0':
				$ukuran = [841, 1189];
				break;
			case 'a4':
				break;
			case 'a3':
				$ukuran = [297, 420];
				break;
			case 'a1':
				$ukuran = [594, 841];
				break;
			case 'b0':
				$ukuran = [1000, 1414];
				break;
			case 'b3':
				$ukuran = [353, 500];
				break;
			case 'b4':
				$ukuran = [250, 353];
				break;
			case 'b0':
				$ukuran = [1000, 1414];
				break;
			case 'c0':
				$ukuran = [917, 1297];
				break;
			case 'c4':
				$ukuran = [229, 324];
				break;
			case '2r':
				$ukuran = [63.5, 88.9];
				break;
			case '3r':
				$ukuran = [88.9, 127];
				break;
			case '4r':
				$ukuran = [102, 152];
				break;
			case 'f4':
				$ukuran = [210, 330];
				break;
			case 'legal':
				$ukuran = [216, 356];
				break;
			case 'letter':
				$ukuran = [216, 279];
				break;
			case 'ledger':
				$ukuran = [432, 279];
				break;
			case 'tabloid':
				$ukuran = [279, 432];
				break;
			case 'ansi a':
				$ukuran = [216, 279];
				break;
			case 'ansi b':
				$ukuran = [432, 279]; //432 × 279,279 × 432
				break;
			case 'ansi c':
				$ukuran = [432, 559];
				break;
			case 'ansi d':
				$ukuran = [559, 864];
				break;
			case 'ansi e':
				$ukuran = [864, 1118];
				break;
			case 'arch a':
				$ukuran = [229, 305];
				break;
			default:

				break;
		}
		return $ukuran;
	}
	// mencari file di folder dan sub folder
	public function cariFile($f, $p = null, $l = 1000)
	{ // Recursively find a file $f in directory $p (compare up to $l files)
		// Returns the full path of the first occurrence (relative to current directory)
		//$fileku = cariFile('renja.pdf','../dokumen_upl');
		//var_dump($f);
		//var_dump($p);
		$cd = $p == null ? getcwd() : $p;
		if (substr($cd, -1, 1) != '/') {
			$cd .= '/';
		}
		if (is_dir($cd)) {
			$dh = opendir($cd);
			while ($fn = readdir($dh)) { // traverse directories and compare files:
				if (is_file($cd . $fn) && $fn == $f) {
					closedir($dh);
					return $cd . $fn;
				}
				if ($fn != '.' && $fn != '..' && is_dir($cd . $fn)) {
					$m = $this->cariFile($f, $cd . $fn, $l);
					if ($m) {
						closedir($dh);
						return $m;
					}
				}
			}
			closedir($dh);
		}
		return false;
	}
	public function getFileAll($dir, $pola, $results = array())
	{
		$files = scandir($dir);
		foreach ($files as $key => $value) {
			$path = realpath($dir . DIRECTORY_SEPARATOR . $value);
			if ($value != '.' && $value != '..' && !is_dir($path)) {
				$this->getDirContents($path, $results);
				// var_dump($value);
				if ($value == $pola) {
					$results[] = $path;
				}
				if (preg_match("/$pola/", $value)) {
					$results[] = $path;
				}
			}
		}
		return $results;
	}
	public function getDirContents($dir, &$results = array())
	{
		$files = scandir($dir);
		foreach ($files as $key => $value) {
			$path = realpath($dir . DIRECTORY_SEPARATOR . $value);
			if (!is_dir($path)) {
				$results[] = $path;
			} elseif ($value != '.' && $value != '..') {
				$this->getDirContents($path, $results);
				$results[] = $path;
			}
		}
		return $results;
	}
	// fungsi zero padding
	/*
    1. %s = String.
    2. %d = desimal.
    3. %x = hexadesimal.
    4. %o = Octal.
    5. %f = Float.
    */
	// impor file ke folder dari spa
	public function impor_file($jenis)
	{ // contoh $ok =impor_file( array('jenis'=>'umum','tahun'=>$tahun_sk) );
		global $koneksi;
		//var_dump( $_FILES );
		if (isset($_POST['jenis'])) {
			$maxsize = 1024 * 8000; // maksimal 2000 KB (1KB = 1024 Byte)
			// tentukan key $_FILES default jika hanya 1 file per form
			switch ($jenis['jenis']) {
				case "ktp":
					$key_files = "file_ktp";
					break;
				case "npwp":
					$key_files = "file_npwp";
					break;
				case "akta_lahir":
					$key_files = "file_akta";
					break;
				case "buku_nikah":
					$key_files = "file_buku_nikah";
					break;
				case "karpeg":
					$key_files = "file_karpeg";
					break;
				case "karsi_karsu":
					$key_files = "file_karsi";
					break;
				case "kk":
					$key_files = "file_kk";
					break;
				default:
					$key_files = array_keys($_FILES)[0]; // mengambil key associate array jika cuman 1 file yang dikirim
					// jika 2 file maka jgn default
			}
			//var_dump( $key_files );
			if ($_FILES[$key_files]['size'] <= $maxsize) {
				if (!empty($_FILES[$key_files]['name']) && !empty($_FILES[$key_files]["type"])) {
					$uploadedFile  = '';
					$valid_extensi = array("pdf", "PDF", "jpeg", "jpg", "png", "JPEG", "JPG", "PNG");
					$temporary = explode(".", $_FILES[$key_files]["name"]);
					$file_extensi = strtolower(end($temporary));
					//$fileHapus = "realin_" . $id_kont . "_";
					//$fileName = "realin_" . $id_kont . "_" . time() . "." . $file_extensi;
					$folder_target = 'images';
					$inser_db = 0; // jika 0 maka tdk perlu inser
					//var_dump( realpath( dirname( __FILE__ ) ) ); // mendapatkan path file
					$real_path = realpath(dirname(__FILE__));

					if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' || strtoupper(substr(PHP_OS, 0, 3)) === 'WINNT') {
						$os_c = 'windows';
						$fileee = str_replace("\\script", '', $real_path); //untuk windows
					} else {
						$os_c = 'os_xx';
						$fileee = str_replace("/script", '', $real_path); // akali ut linux osx
					}
					$jenis_file = $jenis['jenis'];
					switch ($jenis_file) {
						case "logo":
							$valid_extensi = array("png", "PNG");
							$fileName = "logo." . $file_extensi;
							$folder_target = 'images';
							break;
						case "umum":
							$fileName = "sk_opd_" . $jenis['tahun'] . "." . $file_extensi;
							$folder_target = 'dokumen';
							break;
						case "photo_user":
							$fileName = "photo_profil_" . $jenis['nip'] . "." . $file_extensi;
							break;
						case "sk_pejabat":
							$folder_target = 'dokumen';
							$fileName = "sk_jabatan_id_" . $jenis['id'] . "." . $file_extensi;
							break;
						case "sk":
							$folder_web = 'dok/' . $jenis['nip'];
							$folder_target = 'dok/' . $jenis['nip'];
							if ($os_c == 'windows') {
								$folder_target = 'dok\\' . $jenis['nip'];
							}
							$fileName = "sk_id_" . $jenis['id'] . "." . $file_extensi;
							break;
						case "pend":
							$folder_web = 'dok/' . $jenis['nip'];
							$folder_target = 'dok/' . $jenis['nip'];
							if ($os_c == 'windows') {
								$folder_target = 'dok\\' . $jenis['nip'];
							}
							$fileName = "pend_id_" . $jenis['id'] . "." . $file_extensi;
							break;
						case "kel":
							$folder_web = 'dok/' . $jenis['nip'];
							$folder_target = 'dok/' . $jenis['nip'];
							if ($os_c == 'windows') {
								$folder_target = 'dok\\' . $jenis['nip'];
							}
							$fileName = "kel_id_" . $jenis['id'] . "." . $file_extensi;
							break;
						case "lain":
							$folder_web = 'dok/' . $jenis['nip'];
							$folder_target = 'dok/' . $jenis['nip'];
							if ($os_c == 'windows') {
								$folder_target = 'dok\\' . $jenis['nip'];
							}
							$fileName = "lain_id_" . $jenis['id'] . "." . $file_extensi;
							break;
						case "temporer":
							$folder_web = 'images/' . $jenis['nip'];
							$folder_target = 'images';
							if ($os_c == 'windows') {
								$folder_target = 'images\\' . $jenis['nip'];
							}
							$fileName = "temporer." . $file_extensi;
							break;
						case "akta_lahir":
							$folder_web = 'dok/' . $jenis['nip'];
							$folder_target = 'dok/' . $jenis['nip'];
							if ($os_c == 'windows') {
								$folder_target = 'dok\\' . $jenis['nip'];
							}
							$fileName = "akta_lahir." . $file_extensi;
							break;
						case "ktp":
							$folder_web = 'dok/' . $jenis['nip'];
							$folder_target = 'dok/' . $jenis['nip'];
							if ($os_c == 'windows') {
								$folder_target = 'dok\\' . $jenis['nip'];
							}
							$fileName = "ktp." . $file_extensi;
							break;
						case "npwp":
							$folder_web = 'dok/' . $jenis['nip'];
							$folder_target = 'dok/' . $jenis['nip'];
							if ($os_c == 'windows') {
								$folder_target = 'dok\\' . $jenis['nip'];
							}
							$fileName = "npwp." . $file_extensi;
							break;
						case "buku_nikah":
							$folder_web = 'dok/' . $jenis['nip'];
							$folder_target = 'dok/' . $jenis['nip'];
							if ($os_c == 'windows') {
								$folder_target = 'dok\\' . $jenis['nip'];
							}
							$fileName = "buku_nikah." . $file_extensi;
							break;
						case "srt_keluar":
						case "srt_masuk":
							$folder_web = 'dokumen/' . $jenis['nip'];
							$folder_target = 'dokumen/' . $jenis['jenis'];
							if ($os_c == 'windows') {
								$folder_target = 'dok\\' . $jenis['jenis'];
							}
							$fileName = $jenis['jenis'] . '_' . $jenis['id'] . "." . $file_extensi;
							break;
						default:
							$folder_web = 'dok/' . $jenis['nip'];
							$folder_target = 'dok/' . $jenis['nip'];
							if ($os_c == 'windows') {
								$folder_target = 'dok\\' . $jenis['nip'];
							}
							$fileName = "$jenis_file." . $file_extensi;
					}

					//var_dump( $fileee );
					//var_dump( $_FILES[ "file" ][ "type" ] == "image/jpeg" );
					if (in_array($file_extensi, $valid_extensi)) {
						$sourcePath = $_FILES[$key_files]['tmp_name'];
						$path_web = $folder_web . '/' . $fileName;
						if ($os_c == 'windows') {
							//$fileee = str_replace( "...win", '', $fileee );
							//$path_web = $folder_target . '\\' . $fileName;

							$targetPath = $fileee . '\\' . $folder_target . '\\' . $fileName;
						} else {
							$targetPath = $fileee . '/' . $folder_target . '/' . $fileName;
						}
						$filename = $sourcePath;
						/*
                    if ($file_extensi == 'jpg' || $file_extensi == 'jpeg') {
                        imagejpeg($thumb, $filename, 100); // 100 persen bagus khusus jpg klo png hilangkan
                    }
                    if ($file_extensi == 'png') {
                        imagepng($thumb, $filename);
                    }*/
						//var_dump( "target path = " . $targetPath );
						//var_dump( "web path = " . $path_web );
						if (isset($jenis['nip'])) {
							$nip = $jenis['nip'];
							$folder = realpath(dirname(__FILE__));

							if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' || strtoupper(substr(PHP_OS, 0, 3)) === 'WINNT') {
								$os_c = 'windows';
								$folder = str_replace("\\script", '', $folder); //untuk windows
								$nama_folder = $folder . '\\dok\\' . $nip;
							} else {
								$os_c = 'os_xx';
								$folder = str_replace("/script", '', $folder); // akali ut linux osx
								$nama_folder = $folder . '/dok/' . $nip;
							}
							if (!file_exists($nama_folder)) {
								mkdir($nama_folder, 0777, true);
							}
						}



						if (move_uploaded_file($sourcePath, $targetPath)) {
							$uploadedFile = $fileName;
							$data = array('name' => $fileName, 'path' => $path_web);
							$item = array('code' => "0", 'message' => 'file berhasil di impor');
							$json = array('success' => true, 'data' => $data, 'error' => $item);
						} else {
							$item = array('code' => "111", 'message' => 'folder tujuan tidak ditemukan');
							$json = array('success' => false, 'error' => $item);
						}
					} else {
						$item = array('code' => "5", 'message' => 'periksa type file');
						$json = array('success' => false, 'error' => $item);
					}
				} else {
					$item = array('code' => "3", 'message' => 'file tidak ditemukan');
					$json = array('success' => false, 'error' => $item);
				}
			} else {
				$item = array('code' => "2", 'message' => 'ukuran file harus < ' . $maxsize / 1000 . ' MB');
				$json = array('success' => false, 'error' => $item);
			}
		}
		return $json;
	}
	public function zero_pad($angka, $jumlahChar)
	{
		$fzeropadded = sprintf('%0' . $jumlahChar . 'd', $angka);
		return  $fzeropadded;
	}
	public function safe_json_encode($value, $options = 0, $depth = 512, $utfErrorFlag = false)
	{
		$encoded = json_encode($value, $options, $depth);
		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				return $encoded;
			case JSON_ERROR_DEPTH:
				return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_STATE_MISMATCH:
				return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_CTRL_CHAR:
				return 'Unexpected control character found';
			case JSON_ERROR_SYNTAX:
				return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_UTF8:
				$clean = $this->utf8ize($value);
				if ($utfErrorFlag) {
					return 'UTF8 encoding error'; // or trigger_error() or throw new Exception()
				}
				return $this->safe_json_encode($clean, $options, $depth, true);
			default:
				return 'Unknown error'; // or trigger_error() or throw new Exception()
		}
	}
	public function utf8ize($mixed)
	{
		if (is_array($mixed)) {
			foreach ($mixed as $key => $value) {
				$mixed[$key] = $this->utf8ize($value);
			}
		} else if (is_string($mixed)) {
			return mb_convert_encoding($mixed, "UTF-8");
		}
		return $mixed;
	}
	public function enskripsiText($simple_string, $encryption_key = "AlwiMansyur", $encryption_iv = '1107807891011121')
	{
		$ciphering = "AES-128-CTR"; //AES-256-GCM//AES-256-GCM
		$iv_length = openssl_cipher_iv_length($ciphering);
		$options = OPENSSL_RAW_DATA;
		//$tag_length: It holds the length of the authentication tag. The length of authentication tag lies between 4 to 16 for GCM mode.
		$encryption = openssl_encrypt(
			$simple_string,
			$ciphering,
			$encryption_key,
			$options,
			$encryption_iv
		);
		return $encryption;
	}
	public function deskripsiText($encryption, $decryption_key = "AlwiMansyur", $decryption_iv = '1107807891011121')
	{
		$ciphering = "AES-128-CTR"; //"aes-256-cbc" ."AES-128-CTR"//AES-256-GCM
		$iv_length = openssl_cipher_iv_length($ciphering);
		$options = OPENSSL_RAW_DATA; //OPENSSL_RAW_DATA,0
		$decryption = openssl_decrypt(
			$encryption,
			$ciphering,
			$decryption_key,
			$options,
			$decryption_iv
		);
		return $decryption;
	}
	public function selisihWaktu($awal, $akhir)
	{
		//date('Y-m-d H:i:s')
		$awal  = date_create($awal);
		$akhir = date_create($akhir); // waktu sekarang
		$diff  = date_diff($awal, $akhir);
		return ['tahun' => $diff->y, 'bulan' => $diff->m, 'hari' => $diff->d, 'jam' => $diff->h, 'menit' => $diff->i, 'detik' => $diff->s];
	}
	public function recursiveChat($id_row, $type = '')
	{
		$DB = DB::getInstance();
		$user = new User();
		$user->cekUserSession();
		$Fungsi = new MasterFungsi();
		$dataWall = '';
		$id_user = $_SESSION["user"]["id"];
		$edit_user = $_SESSION["user"]["aktif_edit"];
		$DB->orderBy('waktu_input', 'DESC');
		$replyBtn = '<a class="reply" name="chat" jns="wall" tbl="reply">Reply</a>';
		switch ($type) {
			case 'inbox':
				$condition = [['id_reply', '=', $id_row]];
				//$condition = [['id_reply', '=', $id_row], ['id_tujuan', '=', $id_user, 'AND']];
				break;
			case 'outbox':
				$condition = [['id_reply', '=', $id_row]];
				$replyBtn = '';
				//$condition = [['id_reply', '=', $id_row], ['id_pengirim', '=', $id_user, 'AND']];
				break;
			default:
				$condition = [['id_reply', '=', $id_row], ['id_tujuan', '<=', 0, 'AND']];
				break;
		};
		//$sql = "SELECT * FROM ruang_chat WHERE id_reply = $id_chat AND id_tujuan = $id_user_session ORDER by waktu ASC";
		//$sql = "SELECT * FROM ruang_chat WHERE id_reply = $id_chat AND id_tujuan <= 0 ORDER by waktu ASC";
		$rowWall = $DB->getWhereCustom('ruang_chat', $condition);
		//var_dump($rowWall);
		$jumlahArray = is_array($rowWall) ? count($rowWall) : 0;
		if ($jumlahArray) {
			//var_dump($rowWall);
			$dataWall .= '<div class="comments">';
			//jika ada data sebelum post
			//tidak ada data sebelum post
			foreach ($rowWall as $key => $value) {
				$id = $value->id;
				$waktu_input = $value->waktu_input;
				$waktu_edit = $value->waktu_edit;
				$uraian = $value->uraian;
				$id_pengirim = $value->id_pengirim;
				//deskripsi jika jenis=outbox atau inbox
				$id_tujuan = $value->id_tujuan;
				//var_dump($value);
				$userWithIdPengirim = $DB->getWhereOnce('user_sesendok_biila', ['id', '=', $id_pengirim]);
				switch ($type) {
					case 'outbox':
						if ($id_tujuan != 'all') {
							// $userWithId = $DB->getWhereOnce('user_sesendok_biila', ['id', '=', $id_tujuan]);
							$userWithId = $DB->getWhereOnce('user_sesendok_biila', ['id', '=', $id_pengirim]);
						}
						break;
					default:
						$userWithId = $DB->getWhereOnce('user_sesendok_biila', ['id', '=', $id_pengirim]);
						break;
				};
				if ($id_tujuan != 'all') {
					$namaPengirimTampak = $userWithId->nama;
					$photo = $userWithId->photo;
					$photo = explode('/', $photo);
					($photo[0] != 'img') ? $photo[0] = 'img' :  0;
					$photo = implode('/', $photo);
				} else {
					$namaPengirimTampak = 'admin';
					$photo = './img/avatar/default.jpeg';
				}
				$namaPengirim = $userWithIdPengirim->nama;
				$uraian = $this->deskripsiText($uraian, $namaPengirim);
				$dibaca = $value->dibaca;
				$id_reply = $value->id_reply;
				$like = $value->like;
				$selisihWaktu = $Fungsi->selisihWaktu($waktu_input, date('Y-m-d H:i:s'));
				$dateSelisih = $waktu_input;
				if ($selisihWaktu['bulan'] > 0 || $selisihWaktu['tahun'] > 0) {
					$dateSelisih = $waktu_input;
				} else if ($selisihWaktu['hari'] > 0) {
					$dateSelisih = $selisihWaktu['hari'] . ' days ' . $selisihWaktu['jam'] . ' ago';
				} else if ($selisihWaktu['jam'] > 0) {
					$dateSelisih = $selisihWaktu['jam'] . ' hours ago ' . $selisihWaktu['menit'] . " minutes";
				} else if ($selisihWaktu['menit'] > 0) {
					$dateSelisih = $selisihWaktu['menit'] . " minutes ago";
				}
				$btnDel = '';
				if ($id_user == $id_pengirim) {
					$btnDel = '<a class="edit" name="chat" jns="wall" tbl="edit"><i class="edit icon"></i>Edit</a><a class="trash" name="del_row" jns="chat" tbl="del_row"><i class="trash icon"></i>Delete</a>';
				}
				$dataWall .= '<div class="comment" id_row="' . $id . '"><a class="avatar"><img src="' . $photo . '"></a><div class="content"><a class="author">' . $namaPengirimTampak . '</a><div class="metadata"><div class="date">' . $dateSelisih . '</div><div class="rating"><i class="star icon"></i>5 Faves </div></div><div class="text">' . $uraian . '</div><div class="actions"><a class="reply" name="chat" jns="wall" tbl="reply">Reply</a><a class="hide" name="chat" jns="wall" tbl="hide">Hide</a>' . $btnDel . '</div></div>';
				$dataWall .= $this->recursiveChat($id);
				$dataWall .= '</div>';
			}
			$dataWall .= '</div>';
		}
		return $dataWall;
	}
	//chat function
	public function panggilChat($id_row, $posisi, $limit, $jenis, $tabel_pakai = 'ruang_chat')
	{
		$DB = DB::getInstance();
		$user = new User();
		$user->cekUserSession();
		$Fungsi = new MasterFungsi();
		$dataWall = '';
		$id_user = $_SESSION["user"]["id"];
		$edit_user = $_SESSION["user"]["aktif_edit"];
		$dataWall = '';
		$replyBtn = '<a class="reply" name="chat" jns="wall" tbl="reply">Reply</a>';
		if ($id_row > 0) {
			//ambil dahulu row id
			switch ($jenis) {
				case 'inbox':
					$condition = [['id', '=', $id_row], ['id_pengirim', '>', 0, 'AND'], ['id_reply', '<=', 0, 'AND']]; //, ['id_tujuan', '=', 'all', 'OR']
					$DB->tambahQuery("AND (id_tujuan = 'all' OR id_tujuan = $id_user)");
					break;
				case 'outbox':
					$replyBtn = '';
					$condition = [['id', '=', $id_row]];
					break;
				default:
					$condition = [['id', '=', $id_row], ['id_tujuan', '<=', 0, 'AND'], ['id_reply', '<=', 0, 'AND']];
					break;
			};
			$result = $DB->getWhereCustom($tabel_pakai, $condition);
			$jumlahArray = is_array($result) ? count($result) : 0;
			if ($jumlahArray) {
				$waktu_inputId = $result[0]->waktu_input;
				//jalankan
				if ($posisi == 'top') {
					switch ($jenis) {
						case 'inbox':
							$condition = [['id', '>', 0], ['id_pengirim', '>', 0, 'AND'], ['id_reply', '<=', 0, 'AND'], ['waktu_input', '>', $waktu_inputId, 'AND']];
							$DB->tambahQuery("AND (id_tujuan = 'all' OR id_tujuan = $id_user)");
							break;
						case 'outbox':
							$condition = [['id', '>', 0], ['id_pengirim', '=', $id_user, 'AND'], ['id_tujuan', '>', 0, 'AND'], ['id_reply', '<=', 0, 'AND'], ['waktu_input', '>', $waktu_inputId, 'AND']];
							break;
						default:
							$condition = [['id', '>', 0], ['id_tujuan', '<=', 0, 'AND'], ['id_reply', '<=', 0, 'AND'], ['waktu_input', '>', $waktu_inputId, 'AND']];
							break;
					};
				} else {
					switch ($jenis) {
						case 'inbox':
							$condition = [['id', '=', $id_row], ['id_pengirim', '>', 0, 'AND'], ['id_reply', '<=', 0, 'AND'], ['waktu_input', '<', $waktu_inputId, 'AND']];
							$DB->tambahQuery("AND (id_tujuan = 'all' OR id_tujuan = $id_user)");
							break;
						case 'outbox':
							$condition = [['id', '>', 0], ['id_pengirim', '=', $id_user, 'AND'], ['id_reply', '<=', 0, 'AND'], ['waktu_input', '<', $waktu_inputId, 'AND']];
							break;
						default:
							$condition = [['id', '>', 0], ['id_tujuan', '<=', 0, 'AND'], ['id_reply', '<=', 0, 'AND'], ['waktu_input', '<', $waktu_inputId, 'AND']];
							break;
					};
				}
			} else {
				switch ($jenis) {
					case 'inbox':
						$condition = [['id', '>', 0], ['id_pengirim', '>', 0, 'AND'], ['id_tujuan', '=', $id_user, 'AND'], ['id_reply', '<=', 0, 'AND']];
						$DB->tambahQuery("AND (id_tujuan = 'all' OR id_tujuan = $id_user)");
						break;
					case 'outbox':
						$replyBtn = '';
						$condition = [['id', '>', 0], ['id_tujuan', '>', 0, 'AND'], ['id_pengirim', '=', $id_user, 'AND'], ['id_reply', '<=', 0, 'AND']];
						break;
					default:
						$condition = [['id', '>', 0], ['id_tujuan', '<=', 0, 'AND'], ['id_reply', '<=', 0, 'AND']];
						break;
				};
			}
		} else {
			switch ($jenis) {
				case 'inbox':
					$condition = [['id', '>', 0], ['id_pengirim', '>', 0, 'AND'], ['id_reply', '<=', 0, 'AND']];
					$DB->tambahQuery("AND (id_tujuan = 'all' OR id_tujuan = $id_user)");
					break;
				case 'outbox':
					$replyBtn = '';
					$condition = [['id', '>', 0], ['id_tujuan', '>', 0, 'AND'], ['id_pengirim', '=', $id_user, 'AND'], ['id_reply', '<=', 0, 'AND']];
					break;
				default:
					$condition = [['id', '>', 0], ['id_tujuan', '<=', 0, 'AND'], ['id_reply', '<=', 0, 'AND']];
					break;
			};
		}
		$rowWall = $DB->getWhereCustom($tabel_pakai, $condition);
		$posisiLimit = $DB->posisilimit($limit, $rowWall, $halaman = 1);
		$DB->limit([$posisiLimit, $limit]);
		// if ($posisi == 'top') {
		//     $DB->orderBy('waktu_input', 'DESC');
		// } else {
		//     $DB->orderBy('waktu_input', 'ASC');
		// }
		$DB->orderBy('waktu_input', 'DESC');
		$rowWall = $DB->getWhereCustom($tabel_pakai, $condition);
		$jumlahArray = is_array($rowWall) ? count($rowWall) : 0;
		if ($jumlahArray) {
			//jika ada data sebelum post
			//tidak ada data sebelum post
			foreach ($rowWall as $key => $value) {
				$id = $value->id;
				$waktu_input = $value->waktu_input;
				$waktu_edit = $value->waktu_edit;
				$uraian = $value->uraian;
				$id_pengirim = $value->id_pengirim;
				$id_tujuan = $value->id_tujuan;
				$userWithIdPengirim = $DB->getWhereOnce('user_sesendok_biila', ['id', '=', $id_pengirim]);
				switch ($jenis) {
					case 'outbox':
						if ($id_tujuan != 'all') {
							$userWithId = $DB->getWhereOnce('user_sesendok_biila', ['id', '=', $id_tujuan]);
						}
						break;
					default:
						$userWithId = $DB->getWhereOnce('user_sesendok_biila', ['id', '=', $id_pengirim]);
						break;
				};
				//deskripsi jika jenis=outbox atau inbox
				if ($id_tujuan != 'all') {
					$namaPengirimTampak = $userWithId->nama;
					$photo = $userWithId->photo;
					$photo = explode('/', $photo);
					($photo[0] != 'img') ? $photo[0] = 'img' :  0;
					$photo = implode('/', $photo);
				} else {
					$namaPengirimTampak = 'untuk semua user';
					$photo = './img/avatar/default.jpeg';
				}
				$namaPengirim = $userWithIdPengirim->nama;
				$uraian = $this->deskripsiText($uraian, $namaPengirim);
				$dibaca = $value->dibaca;
				$id_reply = $value->id_reply;
				$like = $value->like;
				$btnDel = '';
				$selisihWaktu = $Fungsi->selisihWaktu($waktu_input, date('Y-m-d H:i:s'));
				$dateSelisih = $waktu_input;
				if ($selisihWaktu['bulan'] > 0 || $selisihWaktu['tahun'] > 0) {
					$dateSelisih = $waktu_input;
				} else if ($selisihWaktu['hari'] > 0) {
					$dateSelisih = $selisihWaktu['hari'] . ' days ' . $selisihWaktu['jam'] . ' ago';
				} else if ($selisihWaktu['jam'] > 0) {
					$dateSelisih = $selisihWaktu['jam'] . ' hours ' . $selisihWaktu['menit'] . " minutes ago";
				} else if ($selisihWaktu['menit'] > 0) {
					$dateSelisih = $selisihWaktu['menit'] . " minutes ago";
				} else if ($selisihWaktu['detik'] > 0) {
					$dateSelisih = "just now";
				}
				if ($id_user == $id_pengirim) {
					$btnDel = '<a class="edit" name="chat" jns="wall" tbl="edit"><i class="edit icon"></i>Edit</a><a class="trash" name="del_row" jns="chat" tbl="del_row"><i class="trash icon"></i>Delete</a>';
				}
				$dataWall .= '<div class="comment" id_row="' . $id . '"><a class="avatar"><img src="' . $photo . '"></a><div class="content"><a class="author">' . $namaPengirimTampak . '</a><div class="metadata"><div class="date">' . $dateSelisih . '</div><div class="rating"><i class="star icon"></i>5 Faves </div></div><div class="text">' . $uraian . '</div><div class="actions">' . $replyBtn . '<a class="hide" name="chat" jns="wall" tbl="hide">Hide</a>' . $btnDel . '</div></div>';
				switch ($jenis) {
					case 'inbox':
						$dataWall .= $this->recursiveChat($id, 'inbox');
						break;
					case 'outbox':
						$dataWall .= $this->recursiveChat($id, 'outbox');
						break;
					default:
						$dataWall .= $this->recursiveChat($id);
						break;
				};
				$dataWall .= '</div>';
			}
			//$data['dataWall'] = $dataWall;
			//var_dump($dataWall);
		}
		//svar_dump($dataWall);
		return $dataWall;
	}
	public function importFile($tbl, $set = ['dok' => '', 'nameFileDel' => ''])
	{
		$nameFileDel = '';
		if (isset($set['nameFileDel'])) {
			$nameFileDel = $set['nameFileDel'];
		}
		$dok = 'file';
		if (isset($set['dok'])) {
			$dok = $set['dok'];
		}
		$user = new User();
		$user->cekUserSession();
		$type_user = $_SESSION["user"]["type_user"];
		$id_user = $_SESSION["user"]["id"];
		$maxsize = 1024 * 15000; //15 MB
		$fileName = 'avatar.jpg';
		//jenis os
		/*
        unlink($fileee . $fileee1 . $nama_files_hapus);
        */
		$valid_extension = array(
			'jpg' => 'image/jpeg',
			'png' => 'image/png',
			'gif' => 'image/gif',
			'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'xls' => 'application/vnd.ms-excel',
			'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
			'ppt' => 'application/vnd.ms-powerpoint',
			'doc' => 'application/msword',
			'pdf' => 'application/pdf'
		);
		$path1 = 'upload';
		$path2 = 'users';
		switch ($tbl) {
			case 'profil':
				$path1 = 'img';
				$path2 = 'avatar';
				$valid_extension = array(
					'jpg' => 'image/jpeg',
					'png' => 'image/png',
					'gif' => 'image/gif',
				);
				$fileName = 'avatar.jpg';
				$nama_files_hapus = '';
				break;
			case 'register_surat':
				$path1 = 'upload';
				$path2 = 'register_surat';
				break;
			case 'peraturan':
				//$targetPath = "upload{$slash}peraturan{$slash}";
				$path1 = 'upload';
				$path2 = 'peraturan';
				break;
			case 'monev':
				$path2 = 'realisasi';
				break;
			case 'rekanan':
				$path1 = 'upload';
				$path2 = 'rekanan';
				break;
			case 'asn':
				$path1 = 'upload';
				$path2 = 'asn';
				break;
			case 'wilayah_logo':
				$path1 = 'img'; //upload logo wilayah
				$path2 = 'logo'; //upload logo wilayah
				break;
			case 'organisasi':
				$path1 = 'upload';
				$path2 = 'organisasi';
				break;
			case 'daftar_paket':
				$path2 = 'daftar_paket';
				break;
			case 'user':
				$path1 = 'img';
				$path2 = 'avatar';
				$maxsize = 1024 * 5000;
				break;
			default:
				break;
		}
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$os_c = 'windows';
			$fileee1 = "\\script"; //untuk windows
			$slash = '\\';
			$targetPath = "$path1\\$path2\\";
			$folder = "$path1/$path2/"; //delete file
		} else {
			$os_c = 'os_xx';
			$fileee1 = "/script"; // akali ut linux osx
			$slash = '/';
			$targetPath = "$path1/$path2/";
			$folder = "\\$path1\\$path2\\";
		}
		try {
			// Undefined | Multiple Files | $_FILES Corruption Attack
			// If this request falls under any of them, treat it invalid.
			if (
				!isset($_FILES[$dok]['error']) ||
				is_array($_FILES[$dok]['error'])
			) {
				throw new RuntimeException('Invalid parameters.');
			}
			// Check $_FILES['upfile']['error'] value.
			switch ($_FILES[$dok]['error']) {
				case UPLOAD_ERR_OK:
					break;
				case UPLOAD_ERR_NO_FILE:
					throw new RuntimeException('No file sent.');
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					throw new RuntimeException('Exceeded filesize limit.');
				default:
					throw new RuntimeException('Unknown errors.');
			}
			// You should also check filesize here. 
			if ($_FILES[$dok]['size'] > $maxsize) {
				throw new RuntimeException('Exceeded filesize limit.');
			}
			if ($_FILES[$dok]['size'] <= 0) {
				throw new RuntimeException('Exceeded filesize minimum.');
			}
			// DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
			// Check MIME Type by yourself.
			$finfo = new finfo(FILEINFO_MIME_TYPE);
			if (false === $ext = array_search(
				$finfo->file($_FILES[$dok]['tmp_name']),
				$valid_extension,
				true
			)) {
				throw new RuntimeException('Invalid file format.');
			}
			// You should name it uniquely.
			// DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
			// On this example, obtain safe unique name from its binary data.
			if (isset($set['nama_file'])) {
				$namaFileCustomTambahan = $set['nama_file'];
				$namaFile = sprintf(
					"$targetPath%s.%s", //"./uploads/%s.%s",
					$namaFileCustomTambahan . '_' . sha1_file($_FILES[$dok]['tmp_name']) . "_{$id_user}", //sha1_file($_FILES['file']['tmp_name']),
					$ext
				);
			} else {
				$namaFile = sprintf(
					"$targetPath%s.%s", //"./uploads/%s.%s",
					sha1_file($_FILES[$dok]['tmp_name']) . "_{$id_user}", //sha1_file($_FILES['file']['tmp_name']),
					$ext
				);
			}

			$fileNamePath = "$namaFile";/*sprintf(
                "..$targetPath%s.%s", //"./uploads/%s.%s",
                sha1_file($_FILES['file']['tmp_name']), //sha1_file($_FILES['file']['tmp_name']),
                $ext
            );*/
			// var_dump($_FILES[$dok]['tmp_name']);
			//===============
			//hapus file
			//===============
			if (file_exists($nameFileDel) && strlen($nameFileDel)) {
				// unlink($nameFileDel);
				$status  = unlink($nameFileDel) ? 'The file ' . $nameFileDel . ' has been deleted' : 'Error deleting ' . $nameFileDel;
				return ['result' => 'ok', $dok => $nameFileDel, 'status' => $status];
			} else {
				$status = 'The file ' . $nameFileDel . ' doesnot exist';
				//return ['result' => 'ok', 'file' => $nameFileDel, 'status' => $status];
			}
			//var_dump($fileNamePath);
			if (!move_uploaded_file(
				$_FILES[$dok]['tmp_name'],
				$fileNamePath
			)) {
				throw new RuntimeException('Failed to move uploaded file.');
			}
			//var_dump($nameFileDel);

			//unlink($nameFileDel);
			return ['result' => 'ok', $dok => $namaFile]; //'File is uploaded successfully.'
		} catch (RuntimeException $e) {
			//var_dump($e->getMessage());
			return ['result' => 'error', $dok => $e->getMessage()]; //$e->getMessage();
		}
	}
	//get dropdown
	public function getDropdownItem($get_data, $nama_tabel, $nama_dropdown, $jenisdropdown = 'list', $jumlah_kolom_dropdown = 1, $type_user = 'user')
	{
		$hasil = '';
		switch ($jenisdropdown) {
			case 'search':
				$hasil .= '<div class="ui floating dropdown labeled search icon button">
                <i class="world icon"></i>
                <span class="text">Select Language</span>
                <div class="menu">';
				break;
			case 'input':
				$hasil .= '<div class="ui floating dropdown labeled search icon button">
                                <i class="world icon"></i>
                                <span class="text">Select</span>
                                <div class="menu">';
				break;
			default:
				//list
				$hasil .= '<div class="ui dropdown fluid search selection">
                        <input type="hidden" name="' . $nama_dropdown . '">
                        <div class="default text"></div>
                        <i class="dropdown icon"></i>
                        <div class="menu">';
				break;
		}
		if (sizeof($get_data) > 0) {
			foreach ($get_data as $row) {
				switch ($nama_tabel) {
					case "harga_sat_upah_bahan":
						$hasil .= '<div class="item" data-value="' . $row->kode . '"><span class="text">' . ucwords($row->uraian) . '</span><span class="description">' . $row->kode . '</span></div>';
						break;
					case "renja_p":
						if (count($row) == 2) { //jika kegiatan
							//$kdProkeg = str_pad($row, 2, 0, STR_PAD_LEFT) . '.' . str_pad($row, 2, 0, STR_PAD_LEFT);
							$hasil .= '<div class="item" data-value="' . $row . '"><i class="list icon"></i><span class="text">' . ucwords($row) . '</span><span class="description">' . $row . '</span></div>';
						} else { //jika program
							$kdProkeg = str_pad($row->kd_prog, 2, 0, STR_PAD_LEFT);
							$hasil .= '<div class="header">' . ucwords($row) . '</div>';
						}
						break;
					case "list_2kolom": //dua item
						@$hasil .= '<div class="item" data-value="' . $row . '"><span class="text">' . $row . '</span><span class="description">' . $row . '</span></div>';
						break;
					case "kd_urusan":
						$count = count(explode('.', $row));
						if ($count > 1) {
							$hasil .= '<div class="item" data-value="' . $row . '"><span class="text">' . ucwords($row) . '</span><span class="description">' . $row . '</span></div>';
						} else {
							$hasil .= '<div class="header"><span class="text">' . ucwords($row) . '</span><span class="description">' . $row . '</span></div>';
						}
						break;
					case "kd_prog":
						$hasil .= '<div class="item" data-value="' . $row . '"><span class="text">' . ucwords($row) . '</span><span class="description">' . $row . $row . '</span></div>';
						break;
					case "lokasi":
						if ($row->status == 'kecamatan') {
							$hasil .= '<div class="divider"></div><div class="header">KECAMATAN ' . $row->kecamatan . '</div><div class="divider"></div>';
						} else {
							$hasil .= '<div class="item" data-value="' . $row->desa . '"><i class="list icon"></i>' . $row->desa . '</div>';
						}
						break;
					default:
						$hasil .= '<div class="item" data-value="' . $row . '"><span class="text">' . ucwords($row) . '</span><span class="description">' . $row . '</span></div>';
				}
			}
		} else {
			$hasil = '<div class="header"><i class="tags icon"></i>Data tidak ditemukan</div>';
		}
		$hasil .= '</div></div>';
		return str_replace("\r\n", "", $hasil);
	}
	//============================
	//===========BUAT LIST========
	//============================
	public function getList($jenis, $nama_tabel = '', $get_data = [], $jmlhalaman = 0, $halaman = 1)
	{
		$pagination = '';
		$pagination1 = '';
		$pagination = '';
		$paginationnext = '';
		$pagination2 = '';
		$rowData = ['list' => '', 'foot' => ''];
		$warna = 'green';
		//var_dump($get_data);
		//var_dump($jenis);
		//var_dump($nama_tabel);
		//var_dump($get_data);
		$jumlahArray = is_array($get_data) ? count($get_data) : 0;
		if ($jumlahArray > 0) {
			foreach ($get_data as $row) {
				//var_dump($row);
				switch ($jenis) {
					case 'analisa_ck':
					case 'analisa_bm':
					case 'analisa_sda':
					case 'analisa_quarry':
						$row = (array)$row;
						//var_dump($row['keterangan']);
						$content = '<div class="header">' . $row['kd_analisa']  . ' : ' . $row['uraian'] . '</div>' . $row['kode']  . ' (Rp ' . number_format((float)$row['koefisien'], 2, ',', '.')  . ')';
						if ($jenis == 'analisa_quarry' && strlen($row['keterangan']) > 0) {
							$ket = json_decode($row['keterangan'], TRUE);
							$lokasi = $ket['lokasi'];
							$tujuan = $ket['tujuan'];
							$content = '<div class="header">' . $row['kd_analisa']  . ' : ' . $row['uraian'] . ' : lokasi =>:' . $lokasi . ' : tujuan =>:' . $tujuan . '</div>' . $row['kode']  . ' (Rp ' . number_format((float)$row['koefisien'], 2, ',', '.')  . ')';
						}
						$rowData['list'] .= '<div class="item">
                            <div class="right floated content">
                                <div class="ui icon buttons basic compact">
                                    <button class="ui icon button" name="modal_show" tbl="edit" jns="' . $jenis  . '" id_row="' . $row['kd_analisa']  . '">
                                        <i class="edit blue icon"></i>
                                    </button>
                                    <button class="ui button" name="flyout" jns="copy" tbl="' . $jenis  . '" id_row="' . $row['kd_analisa'] . '"  data-tooltip="salin dan tempel" data-position="left center"><i class="copy icon"></i></button>
                                    <button class="ui icon button" name="del_row" tbl="del_row" jns="' . $jenis  . '" id_row="' . $row['kd_analisa']  . '">
                                        <i class="trash alternate outline red icon"></i>
                                    </button>
                                </div>
                            </div>
                            <i class="teal large money check middle aligned icon"></i>
                            <div class="content">' . $content . '</div>
                        </div>';
						break;
					case 'analisa_ckxx':
						//$desimal = ($this->countDecimals($row->harga_sat) < 2) ? 2 : $this->countDecimals($row->harga_sat);
						$rowData['list'] .= '<div class="item">
                            <div class="right floated content">
                                <div class="ui icon buttons compact ">
                                    <button class="green ui icon button" name="modal_show" tbl="edit" jns="' . $jenis  . '" id_row="' . $row['kd_analisa']  . '">
                                        <i class="edit blue icon"></i>
                                    </button>
                                    <button class="ui icon button" name="del_row" tbl="del_row" jns="' . $jenis  . '" id_row="' . $row['kd_analisa']  . '">
                                        <i class="trash alternate outline red icon"></i>
                                    </button>
                                </div>
                            </div>
                            <i class="large teal money check middle aligned icon"></i>
                            <div class="content">
                                <div class="header">' . $row['kd_analisa']  . ' : ' . $row['keterangan'] . '</div>
                                (Rp ' . number_format((float)$row['jumlah_harga'], 2, ',', '.')  . ')
                            </div>
                        </div>';
						break;
					case 'harga_satuan':
						break;
					case 'daftar_satuan':
						$rowData['list'] .= '<tr>
                                    <td>' . $row->value . '</td>
                                    <td>' . $row->item . '</td>
                                    <td>' . $row->sketerangan . '</td>
                                    <td>
                                        <div class="ui icon basic mini buttons">
                                            <button class="ui ' . $warna . ' button" name="flyout" name="flyout" jns="satuan" tbl="edit" id_row="' . $row->id . '"><i class="edit blue icon"></i></button>
                                            <button class="ui red button" name="del_row" jns="satuan" tbl="del_row" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button>
                                        </div>
                                    </td>
                                </tr>';
						break;
					case 'peraturan':
						$file = $row->file;
						$fileTag = '<a class="ui blue icon button" ><i class="checkmark icon"></i></a>';
						if (strlen($file)) {
							$fileTag = '<a class="ui  icon button" href="' . $file . '" target="_blank"><i class="blue download icon"></i></a>';
						}
						$gabung = $row->keterangan . ' (' . $row->status . ')';
						$rowData['list'] .= trim('<div class="item">
                            <div class="right floated content">
                                <div class="ui icon buttons basic compact">
                                    <button class="ui icon button" name="flyout" tbl="edit" jns="' . $jenis . '" id_row="' . $row->id . '">
                                        <i class="edit blue icon"></i>
                                    </button>' . $fileTag  . '
                                    <button class="ui icon button" name="del_row" tbl="del_row" jns="' . $jenis . '" id_row="' . $row->id . '">
                                        <i class="trash alternate outline red icon"></i>
                                    </button>
                                </div>
                            </div>
                            <i class="teal large money check middle aligned icon"></i>
                            <div class="content">
                            <div class="header">' . $row->uraian . '</div>' . $gabung . '</div>
                        </div>');
						break;
					case 'informasi_umum':
						break;
					case 'y':
						# code...
						break;
					case 'list':
						break;
					case 'value2':
						# code...
						break;
					default:
						# code...
						break;
				}
			}
		} else { //jika data tidak ditemukan
			$rowData['list'] .= '<div class="ui icon info message"><i class="yellow exclamation circle icon"></i><div class="content"><div class="header">Data Tidak ditemukan </div><p>input data baru atau hubungi administrator</p></div></div></td></tr>';
		}
		// pagination
		if ($jmlhalaman > 1) {
			$id_aktif = 0;
			$batas_bawah = $halaman - 2;
			$batas_atas = $halaman + 2;
			if ($batas_bawah <= 1) {
				$batas_bawah = 1;
			} else {
				$pagination .= '<a class="item" name="page" hal="1" ret="go" tbl="' . $nama_tabel . '"><i class="angle double left chevron icon"></i></a>';
			}
			$paginationnext = "";
			if ($batas_atas < $jmlhalaman) {
				$batas_atas = $halaman + 2;
				$paginationnext = '<a class="item" name="page" hal="' . $jmlhalaman . '" ret="go" tbl="' . $nama_tabel . '"><i class="angle double right chevron icon"></i></a>';
			}
			//var_dump( $paginationnext );
			if ($batas_atas > $jmlhalaman) {
				$batas_atas = $jmlhalaman;
			}
			for ($i = $batas_bawah; $i <= $batas_atas; $i++) {
				if ($i != $halaman) {
					$pagination .= '<a class="item" name="page" hal="' . $i . '" ret="go" tbl="' . $nama_tabel . '">' . $i . '</a>';
				} else {
					$pagination .= '<a class="active item" tbl="' . $nama_tabel . '">' . $i . '</a>';
					$id_aktif = $i;
				}
			}
			// panah preview
			if ($halaman == 1) {
				$pagination1 .= '<a class="disabled icon item"><i class="angle left icon"></i></a>';
			} else {
				$pagination1 .= '<a class="icon item"  jns="get_tbl" name="page" hal="' . $id_aktif . '" ret="prev" tbl="' . $nama_tabel . '"><i class="angle left icon"></i></a>';
			}
			// panah next
			if ($halaman == $jmlhalaman) {
				$pagination2 .= '<a class="disabled icon item"><i class="angle right icon"></i></a>';
			} else {
				$pagination2 .= '<a class="icon item" name="page" hal="' . $id_aktif . '" ret="next" jns="get_tbl" tbl="' . $nama_tabel . '"><i class="angle right icon"></i></a>';
			}
			//var_dump( $pagination );
			$rowData['foot'] = trim('<div class="ui center pagination menu">' . $pagination1 . $pagination . $paginationnext . $pagination2 . '</div>');
		} else {
			$rowData['foot'] = '';
		}
		return $rowData;
	}
	#menghitung jumlah decimal
	public function countDecimals($fNumber)
	{
		$fNumber = floatval($fNumber);
		for ($iDecimals = 0; $fNumber != round($fNumber, $iDecimals); $iDecimals++);
		return $iDecimals;
	}
	//
	public static function sortin($a, $b)
	{
		return strlen($b['kode']) - strlen($a['kode']);
	}
	public static function sortinNo_Sortir($a, $b)
	{
		//return $b->Rate <=> $a->Rate;
		return $a['no_sortir'] - $b['no_sortir'];
		/*
        usort($objects, function($a, $b) {
            return $b->Rate <=> $a->Rate;
        });
        */
	}
	public function backup_tables($tables = '*')
	{
		$DB = DB::getInstance();
		$data = "\n/*---------------------------------------------------------------" .
			"\n  TABLES: {$tables}" .
			"\n  ---------------------------------------------------------------*/\n";
		if ($tables == '*') { //get all of the tables
			$tables = array();
			$result = $DB->runQuery2("SHOW TABLES");
			foreach ($result as $row) {
				$tables[] = $row[0];
			}
		} else {
			$tables = is_array($tables) ? $tables : explode(',', $tables);
		}
		foreach ($tables as $table) {
			$data .= "\n/*---------------------------------------------------------------" .
				"\n  TABLE: `{$table}`" .
				"\n  ---------------------------------------------------------------*/\n";
			$data .= "DROP TABLE IF EXISTS `{$table}`;\n";
			$res = $DB->runQuery2("SHOW CREATE TABLE `{$table}`");
			$row = $res[0];
			$data .= $row[1] . ";\n";
			$result = $DB->runQuery2("SELECT * FROM `{$table}`");
			$num_rows = count($result[0]);
			if ($num_rows > 0) {
				$vals = array();
				$z = 0;
				for ($i = 0; $i < $num_rows; $i++) {
					$items = $result[0];
					$vals[$z] = "(";
					for ($j = 0; $j < count($items); $j++) {
						if (isset($items[$j])) {
							$vals[$z] .= "'" . $DB->quote($items[$j]) . "'";
						} else {
							$vals[$z] .= "NULL";
						}
						if ($j < (count($items) - 1)) {
							$vals[$z] .= ",";
						}
					}
					$vals[$z] .= ")";
					$z++;
				}
				$data .= "INSERT INTO `{$table}` VALUES ";
				$data .= "  " . implode(";\nINSERT INTO `{$table}` VALUES ", $vals) . ";\n";
			}
		}
		return $data;
	}
	public function tanggal($tanggal, $add = 0)
	{
		$nama_hari = ["Ahad", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"];
		$nama_bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

		// Ubah tanggal MySQL menjadi timestamp
		$phpdate = strtotime($tanggal);

		// Ambil informasi tanggal dasar
		$hari = date("w", $phpdate);
		$tanggal_output = date("j", $phpdate);
		$bulan = date("n", $phpdate);
		$tahun = date("Y", $phpdate);
		$jam = date("h:i:s A", $phpdate);

		// Tentukan tanggal dalam format MySQL
		$mysqldate = date('Y-m-d', $phpdate);

		// Hitung tanggal dengan penambahan jumlah hari
		$tanggal_add = strtotime("$tanggal + $add days");

		// Jika penambahan tanggal berhasil
		if ($tanggal_add !== false) {
			// Ambil informasi tanggal setelah penambahan
			$hari_add = date("w", $tanggal_add);
			$tanggal_add2 = date('Y-m-d', $tanggal_add);
			$bulan_add = date("n", $tanggal_add);
			$tahun_add = date("Y", $tanggal_add);
		} else {
			// Jika penambahan tanggal gagal, berikan nilai null pada variabel terkait
			$hari_add = null;
			$tanggal_add2 = null;
			$bulan_add = null;
			$tahun_add = null;
		}

		// Kembalikan informasi tanggal dalam format yang diinginkan
		return [
			'hari' => $nama_hari[$hari],
			'tanggal' => $tanggal_output,
			'bulan' => $nama_bulan[$bulan - 1],
			'tahun' => $tahun,
			'jam' => $jam,
			'tanggalMysql' => $mysqldate,
			'tgl' => "$nama_hari[$hari], $tanggal_output {$nama_bulan[$bulan - 1]} $tahun",
			'tanggal_plus_add' => $tanggal_add2,
			'tgl_plus_add' => $tanggal_add2 ? "$nama_hari[$hari_add], $tanggal_output {$nama_bulan[$bulan_add - 1]} $tahun_add" : null
		];
	}
	public function selisihTanggal($tanggal1, $tanggal2) //$tanggal1 = '2000-01-25';$tanggal2 = '2010-02-20';
	{
		$ts1 = strtotime($tanggal1);
		$ts2 = strtotime($tanggal2);
		$year1 = date('Y', $ts1);
		$year2 = date('Y', $ts2);
		$month1 = date('m', $ts1);
		$month2 = date('m', $ts2);
		$diff_moon = (int) (($year2 - $year1) * 12) + ($month2 - $month1);
		$diff_moon = ($diff_moon <= 0) ? 1 : $diff_moon;
		//cari bulan
		// $weekends = 0;
		// $startDate = strtotime($tanggal1);
		// $endDate = strtotime($tanggal2);
		// var_dump($startDate);
		// while ($startDate < $endDate) {
		//     var_dump($startDate);
		//     //"N" gives ISO-8601 numeric representation of the day of the week (added in PHP 5.1.0) 
		//     $day = date("N", $startDate);
		//     if ($day == 6 || $day == 7) {
		//         $weekends++;
		//     }
		//     $startDate = 24 * 60 * 60; //1 day
		// }
		// $interval = $ts1->diff($ts2);
		// echo (int)(($interval->days) / 7);
		$HowManyWeeks = (int) ((strtotime($tanggal2) - strtotime($tanggal1)) / 604800); //date('W', strtotime($tanggal2)) - date('W', strtotime($tanggal1));
		$HowManyWeeks = ($HowManyWeeks <= 0) ? 1 : $HowManyWeeks;
		return ['bulan' => $diff_moon, 'weekends' => $HowManyWeeks, 'tanggal1' => strtotime($tanggal1), 'tanggal2' => strtotime($tanggal2)];
	}
	/*
    * Copyright (c) 2011-2013 Philipp Tempel
    *
    * Permission is hereby granted, free of charge, to any person obtaining a copy
    * of this software and associated documentation files (the "Software"), to deal
    * in the Software without restriction, including without limitation the rights
    * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    * copies of the Software, and to permit persons to whom the Software is
    * furnished to do so, subject to the following conditions:
    *
    * The above copyright notice and this permission notice shall be included in
    * all copies or substantial portions of the Software.
    *
    * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
    * THE SOFTWARE.
    */
	/**
	 * Get the user's operating system.
	 *
	 * @param string $userAgent The user's user agent
	 *
	 * @return string returns the user's operating system as human readable string,
	 *                if it cannot be determined 'n/a' is returned
	 */
	public function getOS($userAgent)
	{
		// Create list of operating systems with operating system name as array key
		$oses = array(
			'iPhone' => '(iPhone)',
			'Windows 3.11' => 'Win16',
			'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)',
			'Windows 98' => '(Windows 98)|(Win98)',
			'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
			'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
			'Windows 2003' => '(Windows NT 5.2)',
			'Windows Vista' => '(Windows NT 6.0)|(Windows Vista)',
			'Windows 7' => '(Windows NT 6.1)|(Windows 7)',
			'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
			'Windows ME' => 'Windows ME',
			'Open BSD' => 'OpenBSD',
			'Sun OS' => 'SunOS',
			'Linux' => '(Linux)|(X11)',
			'Safari' => '(Safari)',
			'Mac OS' => '(Mac_PowerPC)|(Macintosh)',
			'QNX' => 'QNX',
			'BeOS' => 'BeOS',
			'OS/2' => 'OS/2',
			'Search Bot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)',
		);
		// Loop through $oses array
		foreach ($oses as $os => $preg_pattern) {
			// Use regular expressions to check operating system type
			if (preg_match('@' . $preg_pattern . '@', $userAgent)) {
				// Operating system was matched so return $oses key
				return $os;
			}
		}
		return 'n/a';
	}
	public function getBrowser()
	{
		global $user_agent;
		$browser = 'Unknown Browser';
		$browser_array = array(
			'/msie/i' => 'Internet Explorer',
			'/firefox/i' => 'Firefox',
			'/safari/i' => 'Safari',
			'/chrome/i' => 'Chrome',
			'/opera/i' => 'Opera',
			'/netscape/i' => 'Netscape',
			'/maxthon/i' => 'Maxthon',
			'/konqueror/i' => 'Konqueror',
			'/mobile/i' => 'Handheld Browser',
		);
		foreach ($browser_array as $regex => $value) {
			if (preg_match($regex, $user_agent)) {
				$browser = $value;
			}
		}
		return $browser;
	}
}