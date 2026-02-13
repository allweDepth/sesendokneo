<?php
class print_pdf
{
	private $_jenis = '';
	private $_tbl = '';
	private $_tabel_pakai = '';
	private $_data = array();
	private function __construct($data)
	{
		$this->_data = $data;
		if (isset($this->_data['jenis'])) {
			$this->_jenis = trim($this->_data['jenis']);
		}
		if (isset($this->_data['tbl'])) {
			$this->_tbl = trim($this->_data['tbl']);
			require 'init.php';
			$Fungsi = new MasterFungsi();
			$this->_tabel_pakai = $Fungsi->tabel_pakai($this->_tbl)['tabel_pakai'];
		}
		if (array_key_exists('cry', $this->_data)) {
			require 'init.php';
			$query = new Query($data);
			foreach ($this->_data as $key => $rowValue) {
				switch ($key) {
					case 'tbl':
						$this->_tbl = $rowValue;
						break;
					case 'jenis':
						$this->_jenis = $rowValue;
						break;
					default:
						$formValue = $query->encrypt($rowValue);
						$this->_data[$key] = $formValue;
						break;
				}
			}
		}
	}
	public static function createInstance($data)
	{
		return new self($data);
	}
	public static function print_pdf()
	{
		require 'init.php';
		$user = new User();
		$Fungsi = new MasterFungsi();
		$user->cekUserSession();
		$type_user = $_SESSION["user"]["type_user"];
		$username = $_SESSION["user"]["username"];
		$id_user = $_SESSION["user"]["id"];
		$keyEncrypt = $_SESSION["user"]["key_encrypt"];
		$kd_opd = $_SESSION["user"]["kd_organisasi"];
		//var_dump($keyEncrypt);
		//var_dump(sys_get_temp_dir());lokasi tempoerer
		$DB = DB::getInstance();
		$message_tambah = '';
		$sukses = false;
		$code = 39;
		$pesan = 'posting kosong';
		$item = array('code' => "1", 'message' => $pesan);
		$json = array('success' => $sukses, 'error' => $item);
		$data = array();
		$dataJson = array();
		//ambil row user
		$rowUsername = $DB->getWhereOnce('user_sesendok_biila', ['username', '=', $username]);
		// var_dump($_SESSION["user"]);
		$dataJson['results'] = [];
		$rowPengaturan = false;
		$tambahan_pesan = [];
		$nama_wilayah = '';
		$status_wilayah = '';
		$nama_opd = '';
		$alamat_opd = '';
		$file_logo_daerah = 'img/avatar/logo.png';
		if ($rowUsername !== false) {
			foreach ($rowUsername as $key => $value) {
				${$key} = $value;
			}
			$tahun = (int) $rowUsername->tahun;
			$kd_opd = $rowUsername->kd_organisasi;
			$id_user = $rowUsername->id;
			$rowPengaturan = $DB->getWhereOnce('pengaturan_neo', ['tahun', '=', $tahun]);
			if ($rowPengaturan !== false) {
				foreach ($rowPengaturan as $key => $value) {
					${$key} = $value;
				}
			} else {
				// $id_user = 0;
				// $code = 407;
			}
			//wilayah
			$nama_wilayah = '';
			$tabel_get_row = $Fungsi->tabel_pakai('wilayah')['tabel_pakai'];
			$rowWilayah = $DB->getWhereOnce($tabel_get_row, ['kode', '=', $kd_wilayah]);
			if ($rowWilayah !== false) {
				$nama_wilayah = $rowWilayah->uraian;
				$status = $rowWilayah->status;
				$file_logo_daerah = $rowWilayah->logo;
				switch ($status) {
					case 'prov':
						$status_wilayah = 'provinsi';
						break;
					case 'kab':
						$status_wilayah = 'kabupaten';
						break;
					default:
						# code...
						break;
				}
				//ambil data opd
				$tabel_get_row = $Fungsi->tabel_pakai('organisasi')['tabel_pakai'];
				$rowOrganisasi = $DB->getWhereOnceCustom($tabel_get_row, [['kd_wilayah', '=', $kd_wilayah], ['kode', '=', $kd_opd, 'AND']]);
				if ($rowOrganisasi !== false) {
					$nama_opd = $rowOrganisasi->uraian;
					$alamat_opd = $rowOrganisasi->alamat;
				}
			}
		} else {
			$id_user = 0;
			$code = 407;
		}
		if (!empty($_POST) && $id_user > 0 && $code != 407) {
			if (isset($_POST['jenis']) && isset($_POST['tbl'])) {
				$code = 40;
				$validate = new Validate($_POST);
				$jenis = $validate->setRules('jenis', 'jenis', [
					'sanitize' => 'string',
					'required' => true,
					'min_char' => 1,
					'max_char' => 100
				]);
				$tbl = $validate->setRules('tbl', 'tbl', [
					'sanitize' => 'string',
					'required' => true,
					'min_char' => 1,
					'max_char' => 100
				]);
				$Fungsi = new MasterFungsi();
				//================
				//PROSES VALIDASI
				//================
				require_once('class/TCPDF-main/tcpdf.php');
				switch ($jenis) {
					case 'cetak':

						$page_size = $validate->setRules('ukuran_kertas', 'ukuran kertas', [
							'required' => true,
							// 'sanitize' => 'string',
							'min_char' => 1,
							'max_char' => 100,
							'in_array' => ['F4', 'custom', 'A3', 'A4', 'LETTER', 'LEGAL', 'f4', 'CUSTOM', 'a3', 'a4', 'letter', 'legal']
						]);
						$ukuran_huruf = $validate->setRules('ukuran_huruf', 'ukuran huruf', [
							'numeric' => true,
							'required' => true
						]);
						$ukuran_huruf = $ukuran_huruf / 100;
						$PDF_MARGIN_LEFT = $validate->setRules('margin_kanan', 'margin kanan', [
							'numeric' => true,
							'required' => true
						]);
						$PDF_MARGIN_RIGHT = $validate->setRules('margin_kiri', 'margin kiri', [
							'numeric' => true,
							'required' => true
						]);
						$PDF_MARGIN_TOP = $validate->setRules('margin_top', 'margin atas', [
							'numeric' => true,
							'required' => true
						]);
						$PDF_MARGIN_BOTTOM = $validate->setRules('margin_bottom', 'margin bawah', [
							'numeric' => true,
							'required' => true
						]);
						$orientasi = $validate->setRules('orientasi', 'orientasi', [
							'sanitize' => 'string',
							'required' => true,
							'in_array' => ['portrait', 'lanscape'],
						]);
						// var_dump($page_size);
						$orientasi = ($orientasi == 'portrait') ? 'P' : 'L';
						if ($page_size == 'CUSTOM') {
							//$lebar = ( float )$_POST[ 'tinggi' ]; // lebar kertas
							$tinggi = $validate->setRules('tinggi', 'tinggi custom page size', [
								'required' => true,
								'numeric' => true
							]);
							$lebar = $validate->setRules('lebar', 'lebar custom page size', [
								'required' => true,
								'numeric' => true
							]);

							if ($tinggi <= 0) {
								$tinggi = 330;
							}
							if ($lebar <= 0) {
								$lebar = 215;
							}
						} else {
							if ($orientasi == 'P') {
								$lebar = $Fungsi->kertas($page_size)[0];
								$tinggi = $Fungsi->kertas($page_size)[1];
							} else {
								$lebar = $Fungsi->kertas($page_size)[1];
								$tinggi = $Fungsi->kertas($page_size)[0];
							}
						}
						// if ($orientasi == 'portrait') {
						//     $lebar = $pf[0]; // lebar kertas
						// } else {
						//     $lebar = $pf[1]; // tinggi kertas
						// }
						// var_dump($lebar);
						$ukuran_kertas = ($page_size == 'custom') ? array($lebar, $tinggi) : [$lebar, $tinggi];
						// var_dump($ukuran_kertas);
						// $lebar = $Fungsi->kertas($page_size)[0];
						// $tinggi = $Fungsi->kertas($page_size)[1];

						//========================
						// create a PDF object
						//========================
						$pdf = new TCPDF($orientasi, 'mm', $ukuran_kertas, true, 'UTF-8', false);

						$header = $validate->setRules('header', 'header', [
							'sanitize' => 'string',
							'numeric' => true,
							'in_array' => ['off', 'on']
						]);


						$footer = $validate->setRules('footer', 'footer', [
							'sanitize' => 'string',
							'numeric' => true,
							'in_array' => ['off', 'on']
						]);
						if ($footer === 'on') {
							$PDF_MARGIN_FOOTER = $validate->setRules('margin_footer', 'margin header', [
								'numeric' => true,
								'required' => true
							]);
						} else {
							$pdf->setPrintFooter(false);
							$PDF_MARGIN_FOOTER = 20;
						}
						$cetak_kop = $validate->setRules('cetak_kop', 'cetak kop', [
							'sanitize' => 'string',
							'numeric' => true,
							'in_array' => ['off', 'on']
						]);
						$kop_dns = $validate->setRules('kop_dns', 'kop OPD', [
							'sanitize' => 'string',
							'numeric' => true,
							'in_array' => ['standar', 'custom']
						]);

						// var_dump($lebar);
						$lebar_net = $lebar - floatval($PDF_MARGIN_LEFT) - floatval($PDF_MARGIN_RIGHT);
						// var_dump($lebar_net);
						switch ($tbl) {
							case 'sk_asn':
								$tabel_pakai_temporer = $Fungsi->tabel_pakai($tbl)['tabel_pakai'];
								$id_row = $validate->setRules('id_row', 'pilih surat keputusan', [
									'numeric' => true,
									'required' => true
								]);
								$id_row = $validate->setRules('id_row', 'pilih surat keputusan', [
									'inDB' => [$tabel_pakai_temporer, 'id', [['id', "=", $id_row], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND']]]
								]);
								break;
							default:
								break;
						}
						break;
					default:
						# code...
						break;
				}
				if ($validate->passed()) {
					//tentukan tabel yang digunakan
					$tabel_pakai = $Fungsi->tabel_pakai($tbl)['tabel_pakai'];
					// set document information
					$pdf->SetCreator('allwe');
					$pdf->SetAuthor('Alwi Mansyur');
					$pdf->SetTitle('dokumen');
					$pdf->SetSubject('dokumen siSendok');
					$pdf->SetKeywords('dokumen siSendok');
					if ($header === 'on') {
						$PDF_MARGIN_HEADER = $validate->setRules('margin_header', 'margin header', [
							'numeric' => true,
							'required' => true
						]);
					} else {
						$PDF_MARGIN_HEADER = 20;
						$pdf->setPrintHeader(false);
					}
					// set margins
					$pdf->SetMargins($PDF_MARGIN_LEFT, $PDF_MARGIN_TOP, $PDF_MARGIN_RIGHT);
					$pdf->SetHeaderMargin($PDF_MARGIN_HEADER);
					$pdf->SetFooterMargin($PDF_MARGIN_FOOTER);
					// set auto page breaks
					$pdf->SetAutoPageBreak(true, $PDF_MARGIN_BOTTOM);
					// kop surat
					//dd content

					$code = 55;
					$pdf->SetFont('helvetica', '', 11);
					$pdf->setListIndentWidth(5);
					$html = '';
					// $html = '<style>' . file_get_contents(pathURL . '/vendor/node_modules/fomantic-ui/dist/semantic.min.css') . '</style>';
					//cetak kop dinas
					$pdf->AddPage();
					if ($kop_dns == 'standar' && $cetak_kop == 'on') {
						$pdf->SetY(5);
						//gunakan self memanggil methode didalam kelas
						$kop_dinas = self::kop_dinas($status_wilayah, $nama_wilayah, $nama_opd, $alamat_opd, $file_logo_daerah);
						// var_dump($kop_dinas);
						$pdf->writeHTML($kop_dinas, true, false, false, false, '');
					} else {
					}
					switch ($jenis) {
						case 'cetak':
							switch ($tbl) {
								case 'sk_asn':
									//ambil data
									$row_sk_asn = $DB->getWhereOnceCustom($tabel_pakai, [['id', '=', $id_row], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']]);
									$pdf->SetFont('helvetica', '', 11 * $ukuran_huruf);
									$pdf->SetFillColor(255, 255, 255);
									// $pdf->setListIndentWidth(5);
									// create new PDF document
									// Add a page
									// This method has several options, check the source code documentation for more information.

									// ---------------------------------------------------------

									// set default font subsetting mode
									$pdf->setFontSubsetting(true);

									// Set font
									// dejavusans is a UTF-8 Unicode font, if you only need to
									// print standard ASCII chars, you can use core fonts like
									// helvetica or times to reduce file size.
									$pdf->SetFont('helvetica', '', 12 * $ukuran_huruf, '', true);

									// Add a page
									// This method has several options, check the source code documentation for more information.


									// set text shadow effect
									// $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

									// Set some content to print
									if ($row_sk_asn !== false) {
										//menimbang
										foreach ($row_sk_asn as $key => $value) {
											${$key} = $value;
										}
										$bentuk_lampiran = $row_sk_asn->bentuk_lampiran;
										$menimbang = json_decode($row_sk_asn->menimbang, true);
										$mengingat = json_decode($row_sk_asn->mengingat, true);
										$menetapkan_1 = json_decode($row_sk_asn->menetapkan_1, true);
										$menetapkan_2 = json_decode($row_sk_asn->menetapkan_2, true);
										$menetapkan_3 = json_decode($row_sk_asn->menetapkan_3, true);
										$menetapkan_4 = json_decode($row_sk_asn->menetapkan_4, true);
										$tembusan = json_decode($row_sk_asn->tembusan, true);
										$nama_ditugaskan = json_decode($row_sk_asn->nama_ditugaskan, true);
										$array_json_decod = ['menimbang' => $menimbang, 'mengingat' => $mengingat, 'menetapkan_1' => $menetapkan_1, 'menetapkan_2' => $menetapkan_2, 'menetapkan_3' => $menetapkan_3, 'menetapkan_4' => $menetapkan_4, 'tembusan' => $tembusan, 'nama_ditugaskan' => $nama_ditugaskan];
										$hasil_decode = [];
										// var_dump($array_json_decod);
										foreach ($array_json_decod as $key_decode => $value_decode) {
											$count = count($value_decode);
											$p_l_awal = 'P'; //paragraf or list
											$hasil_decode[$key_decode] = '';
											// var_dump($key_decode);

											if ($count > 0) {
												$jumlah = 0;
												switch ($key_decode) {
													case 'nama_ditugaskan':
														$hasil_decode[$key_decode] = '<hr><br><table cellpadding="3">';
														$border = 'border: 0.5px solid black;';
														if ($bentuk_lampiran > 0) {
															$hasil_decode[$key_decode] .= '<thead><tr style="text-align:center;line-height:11px;font-weight:bold;"><th style="width: ' . (0.7 / 10 * $lebar_net) . 'mm;' . $border . '">&nbsp;<br/>NO.&nbsp;<br/></th>
                                                                <th style="width: ' . (3.5 / 10 * $lebar_net) . 'mm;' . $border . '">&nbsp;<br/>NAMA&nbsp;<br/></th>
                                                                <th style="width: ' . (2.3 / 10 * $lebar_net) . 'mm;' . $border . '">&nbsp;<br/>JABATAN&nbsp;<br/></th>
                                                                <th style="width: ' . (3.5 / 10 * $lebar_net) . 'mm;' . $border . '">&nbsp;<br/>KETERANGAN&nbsp;<br/></th>
                                                                </tr></thead>';
														}
														break;
												}
												foreach ($value_decode as $key => $value) {
													// var_dump($value);
													switch ($key_decode) {
														case 'nama_ditugaskan':
															// var_dump($value_decode);
															$hasil_decode[$key_decode] .= '<tbody>';
															$jumlah++;
															$jabatan_post = $value["jabatan"];
															if ($bentuk_lampiran > 0) {
																switch ($jabatan_post) {
																	case 'honorer':
																	case 'honor':
																		$hasil_decode[$key_decode] .= '<tr nobr="true">
                                                                                <td style="line-height: 15em;width:' . (0.7 / 10 * $lebar_net) . 'mm;' . $border . '">' . $jumlah . '.</td>
                                                                                <td style="line-height: 15em;width:' . (3.5 / 10 * $lebar_net) . 'mm;' . $border . '"><span style="font-weight:bold;">' . $value["nama"] . '</span><br>NIP. -(Non ASN)</td>
                                                                                <td style="line-height: 15em;width:' . (2.3 / 10 * $lebar_net) . 'mm;' . $border . '">Non ASN</td>
                                                                                <td style="line-height: 15em;width:' . (3.5 / 10 * $lebar_net) . 'mm;' . $border . '">' . ucwords($value["jabatan_sk"]) . '</td>
                                                                            </tr>';
																		break;
																	default:
																		$hasil_decode[$key_decode] .= '<tr nobr="true">
                                                                                <td style="line-height: 15em;width:' . (0.7 / 10 * $lebar_net) . 'mm;' . $border . '">' . $jumlah . '.</td>
                                                                                <td style="line-height: 15em;width:' . (3.5 / 10 * $lebar_net) . 'mm;' . $border . '"><span style="font-weight:bold;">' . $value["nama"] . '</span><br>' . $value["pangkat"] . '<br>NIP. ' . $value["nip"] . '</td>
                                                                                <td style="line-height: 15em;width:' . (2.3 / 10 * $lebar_net) . 'mm;' . $border . '">' . ucwords($value["jabatan"]) . '</td>
                                                                                <td style="line-height: 15em;width:' . (3.5 / 10 * $lebar_net) . 'mm;' . $border . '">' . ucwords($value["jabatan_sk"]) . '</td>
                                                                            </tr>';
																};
															} else {
																switch ($jabatan_post) {
																	case 'honorer':
																	case 'honor':
																		$hasil_decode[$key_decode] .= '<tr nobr="true">
                                                                        <td style="line-height: 20em;width:' . (0.5 / 10 * $lebar_net) . 'mm;">' . $jumlah . '.</td>
                                                                        <td style="line-height: 20em;width:' . (2 / 10 * $lebar_net) . 'mm;">Nama<br>Pangkat/Gol<br>NIP<br>Jabatan<br>Keterangan</td>
                                                                        <td style="line-height: 20em;width:' . (0.5 / 10 * $lebar_net) . 'mm;">:<br>:<br>:<br>:<br>:</td>
                                                                        <td style="line-height: 20em;width:' . (7 / 10 * $lebar_net) . 'mm; text-align: justify;"><span style="font-weight:bold;">' . $value["nama"] . '</span><br>' . $value["pangkat"] . '<br>' . $value["nip"] . '<br>' . ucwords($value["jabatan"]) . '<br>' . ucwords($value["jabatan_sk"]) . '</td>
                                                                    </tr>';
																		break;
																	default:
																		$hasil_decode[$key_decode] .= '<tr nobr="true">
                                                                        <td style="line-height: 20em;width:' . (0.5 / 10 * $lebar_net) . 'mm;">' . $jumlah . '.</td>
                                                                        <td style="line-height: 20em;width:' . (2 / 10 * $lebar_net) . 'mm;">Nama<br>Pangkat/Gol<br>NIP<br>Jabatan<br>Keterangan</td>
                                                                        <td style="line-height: 20em;width:' . (0.5 / 10 * $lebar_net) . 'mm;">:<br>:<br>:<br>:<br>:</td>
                                                                        <td style="line-height: 20em;width:' . (7 / 10 * $lebar_net) . 'mm; text-align: justify;"><span style="font-weight:bold;">' . $value["nama"] . '</span><br>' . $value["pangkat"] . '<br>' . $value["nip"] . '<br>' . ucwords($value["jabatan"]) . '<br>' . ucwords($value["jabatan_sk"]) . '</td>
                                                                    </tr>';
																};
															}
															break;
														default:
															if (array_key_exists("isi", $value)) {
																$isi = $value['isi'];
															} else {
																$isi = $value['tembusan'];
															}
															if (array_key_exists("p_l", $value)) {
																$p_l = $value['p_l'];

																if ($p_l == 'L') {
																	if ($p_l_awal == 'P') {
																		$hasil_decode[$key_decode] .= '<ol>';
																		//Tembusan : Kepada Yth.
																	}
																	$hasil_decode[$key_decode] .= '<li style="text-align:justify;">' . $isi . '</li>';
																} else {
																	if ($p_l_awal == 'L') {
																		$hasil_decode[$key_decode] .= '</ol>';
																	}
																	$hasil_decode[$key_decode] .= '<p>' . $isi . '</p>';
																}
																$p_l_awal = $p_l;
															} else {
																$hasil_decode[$key_decode]  .= '<li style="text-align:justify;">' . $isi . '</li>';
															}
															break;
													}
												}
												switch ($key_decode) {
													case 'nama_ditugaskan':
														$hasil_decode[$key_decode] .= '</tbody></table><br><br>';
														//untuk tabel yang ditugaskan
														break;
													default:
														if (array_key_exists("p_l", $value) && $p_l_awal == 'L') {
															$hasil_decode[$key_decode] .= '</ol>';
														} else {
															// var_dump('masuk sini');
															// var_dump(strpos('</li>', $hasil_decode[$key_decode]));
															// var_dump($hasil_decode[$key_decode]);
															if (strpos('</li>', $hasil_decode[$key_decode]) !== false) {
																$hasil_decode[$key_decode]  = '<ol>' . $hasil_decode[$key_decode] . '</ol>';
															}
														}
														break;
												}
											}
										}
										// CUSTOM PADDING
										// set color for background
										// $pdf->SetFillColor(255, 255, 255);
										foreach ($hasil_decode as $key => $value) {
											$hasil_decode[$key] = trim(preg_replace('/(\s\s+|\t|\n)/', ' ', $value));
										}
										// var_dump($hasil_decode );
										// // set font
										$pdf->SetFont('helvetica', 'B', 11 * $ukuran_huruf);

										// set cell padding
										$pdf->setCellPaddings(0, 0, 0, 0);

										$pdf->MultiCell($lebar_net, 0, "KEPUTUSAN " . strtoupper($jbt_pemberi_tgs . ' ' . $nama_opd), 0, 'C', 1, 1, '', '', true);
										$pdf->MultiCell($lebar_net, 0, "Nomor : " . $nomor, 0, 'C', 1, 1, '', '', true);

										$pdf->Ln(4);
										$pdf->MultiCell($lebar_net, 0, "TENTANG", 0, 'C', 1, 1, '', '', true);
										$pdf->MultiCell($lebar_net, 0, strtoupper($tentang), 0, 'C', 1, 1, '', '', true);
										$thn_sk = $Fungsi->tanggal($tgl_surat_dibuat)['tahun'];
										$pdf->MultiCell($lebar_net, 0, strtoupper($nama_opd . ' tahun ') . $thn_sk, 0, 'C', 1, 1, '', '', true);
										$pdf->Ln(4);
										$pdf->MultiCell($lebar_net, 0, strtoupper($jbt_pemberi_tgs) . " ORGANISASI PERANGKAT DAERAH (OPD)", 0, 'C', 1, 1, '', '', true);
										$pdf->setCellPaddings(0, 1, 1, 1);
										$pdf->MultiCell($lebar_net, 0, strtoupper($nama_opd), 0, 'C', 1, 1, '', '', true);
										$pdf->setListIndentWidth(5);
										$pdf->SetFont('helvetica', '', 11 * $ukuran_huruf);
										$pdf->Ln(4);
										$pdf->MultiCell($lebar_net / 7, 0, 'Menimbang', 0, 'J', 1, 0, '', '', true);
										$pdf->MultiCell($lebar_net * 3 / 100, 1, ':', 0, 'C', 1, 0, '', '', true);
										$pdf->writeHTMLCell($lebar_net * 6 / 7, 1, '', '', $hasil_decode['menimbang'], 0, 1, 0, true, 'J', true);

										$pdf->MultiCell($lebar_net / 7, 1, 'Mengingat', 0, 'J', 1, 0, '', '', true);
										$pdf->MultiCell($lebar_net * 3 / 100, 1, ':', 0, 'C', 1, 0, '', '', true);
										$pdf->writeHTMLCell($lebar_net * 6 / 7, 1, '', '', $hasil_decode['mengingat'], 0, 1, 0, true, 'J', true);
										//$pdf->MultiCell( $lebar_net * 7 / 8, 1, $tabel_menimbang[ 0 ][ "isi" ], 0, 'J', 1, 1, '', '', true, 1 );
										$pdf->SetFont('helvetica', 'B', 11 * $ukuran_huruf);
										$pdf->Ln(4);
										$pdf->Write(0, "MEMUTUSKAN", '', 0, 'C', true, 0, false, false, 0);
										$pdf->SetFont('helvetica', '', 11 * $ukuran_huruf);
										$pdf->MultiCell($lebar_net / 7, 1, 'Menetapkan', 0, 'J', 1, 0, '', '', true);
										$pdf->MultiCell($lebar_net * 3 / 100, 1, ':', 0, 'C', 1, 0, '', '', true);
										$pdf->writeHTMLCell($lebar_net * 6 / 7, 1, '', '', "KEPUTUSAN " . strtoupper($jbt_pemberi_tgs . ' ' . $nama_opd . ' tentang ' . $tentang), 0, 1, 0, true, 'J', true);
										//kesatu
										if ($hasil_decode['menetapkan_1'] != '') {
											$pdf->MultiCell($lebar_net / 7, 1, 'KESATU', 0, 'J', 1, 0, '', '', true);
											$pdf->MultiCell($lebar_net * 3 / 100, 1, ':', 0, 'C', 1, 0, '', '', true);
											$pdf->writeHTMLCell($lebar_net * 6 / 7, 1, '', '', $hasil_decode['menetapkan_1'], 0, 1, 0, true, 'J', true);
										}

										//kedua
										if (strlen($hasil_decode['menetapkan_2']) > 5) {
											$pdf->MultiCell($lebar_net / 7, 1, 'KEDUA', 0, 'J', 1, 0, '', '', true);
											$pdf->MultiCell($lebar_net * 3 / 100, 1, ':', 0, 'C', 1, 0, '', '', true);
											$pdf->writeHTMLCell($lebar_net * 6 / 7, 1, '', '', trim($hasil_decode['menetapkan_2']), 0, 1, 0, true, 'J', true);
										}

										//ketiga
										if ($hasil_decode['menetapkan_3'] != '') {
											$pdf->MultiCell($lebar_net / 7, 1, 'KETIGA', 0, 'J', 1, 0, '', '', true);
											$pdf->MultiCell($lebar_net * 3 / 100, 1, ':', 0, 'C', 1, 0, '', '', true);
											$pdf->writeHTMLCell($lebar_net * 6 / 7, 1, '', '', $hasil_decode['menetapkan_3'], 0, 1, 0, true, 'J', true);
										}

										//keempat
										if ($hasil_decode['menetapkan_4'] != '') {
											$pdf->MultiCell($lebar_net / 7, 1, 'KEEMPAT', 0, 'J', 1, 0, '', '', true);
											$pdf->MultiCell($lebar_net * 3 / 100, 1, ':', 0, 'C', 1, 0, '', '', true);
											$pdf->writeHTMLCell($lebar_net * 6 / 7, 1, '', '', $hasil_decode['menetapkan_4'], 0, 1, 0, true, 'J', true);
										}

										$tgl_surat_dibuat = $Fungsi->tanggal($tgl_surat_dibuat)['tgl_plus_add'];;
										$int_nip = $pemberi_tgs;
										$isi_nip = 'NIP. ' . $pemberi_tgs;
										if ($int_nip <= 0) {
											$isi_nip = '';
										}
										// set_time_limit(0);
										// ini_set('memory_limit', '-1');

										$html .= '<table><tbody>
                                            <tr nobr="true">
                                                <td style="border: none;width:' . (6 / 10 * $lebar_net) . 'mm;"></td>
                                                <td style="line-height: 14em;width:' . (4 / 10 * $lebar_net) . 'mm;border: none;">Ditetapkan di ' . ucfirst(strtolower($nama_wilayah)) . '<br><span style="text-decoration: underline;">pada Tanggal ' . $tgl_surat_dibuat . '</span><br><span style="font-weight:bold;border: none;">' . ucwords($jbt_pemberi_tgs . ' ' . $nama_opd) . '<br>Selaku pengguna anggaran</span><br><br><br><br><br><span style="text-decoration: underline;font-weight:bold;">' . $nama_pemberi_tgs . '</span><br>' . $pangkat_pemberi_tgs . '<br>' . $isi_nip . '</td>
                                            </tr>';
										$html .= '</tbody></table><p><i>Tembusan : Kepada Yth.</i></p>' . $hasil_decode['tembusan'];
									} else {
										$html .= '<h1 class="color">Welcome to Pasangkayu</h1><p>hubungi administrator </p>';
									}
									$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
									//lembar lampiran
									// Start First Page Group
									$pdf->startPageGroup();

									// add a page
									$pdf->AddPage();
									// $pdf->SetCellPadding(0);
									$pdf->SetFont('helvetica', '', 11 * $ukuran_huruf);
									$pdf->MultiCell($lebar_net / 8, 0, 'Lampiran', 0, 'J', 1, 0, '', '', true);
									$pdf->MultiCell($lebar_net * 3 / 100, 0, ':', 0, 'C', 1, 0, '', '', true);
									$pdf->writeHTMLCell($lebar_net * 6.7 / 8, 0, '', '', "Keputusan " . ucwords($jbt_pemberi_tgs . ' ' . $nama_opd . ' ' . $status_wilayah . ' ' . $nama_wilayah), 0, 1, 0, true, 'J', true);
									$pdf->MultiCell($lebar_net / 8, 0, 'Nomor', 0, 'J', 1, 0, '', '', true);
									$pdf->MultiCell($lebar_net * 3 / 100, 0, ':', 0, 'C', 1, 0, '', '', true);
									$pdf->writeHTMLCell($lebar_net * 7 / 8, 0, '', '', $nomor, 0, 1, 0, true, 'J', true);
									$pdf->MultiCell($lebar_net / 8, 0, 'Tanggal', 0, 'J', 1, 0, '', '', true);
									$pdf->MultiCell($lebar_net * 3 / 100, 0, ':', 0, 'C', 1, 0, '', '', true);
									$pdf->writeHTMLCell($lebar_net * 7 / 8, 0, '', '', $tgl_surat_dibuat, 0, 1, 0, true, 'J', true);

									$pdf->MultiCell($lebar_net / 8, 0, 'Tentang', 0, 'J', 1, 0, '', '', true);
									$pdf->MultiCell($lebar_net * 3 / 100, 0, ':', 0, 'C', 1, 0, '', '', true);
									$pdf->writeHTMLCell($lebar_net * 6.7 / 8, 0, '', '', ucwords($tentang), 0, 1, 0, true, 'J', true);

									// asn
									//$pdf->MultiCell( $lebar_net, 1, $_POST[ 'serialized' ], 0, 'J', 1, 0, '', '', true );
									//$pdf->SetFont( 'helvetica', '', 10 );

									$html = $hasil_decode['nama_ditugaskan'];

									$html .= '<table><tbody>
                                            <tr nobr="true">
                                                <td style="border: none;width:' . (6 / 10 * $lebar_net) . 'mm;"></td>
                                                <td style="line-height: 14em;width:' . (4 / 10 * $lebar_net) . 'mm;border: none;">Ditetapkan di ' . ucfirst(strtolower($nama_wilayah)) . '<br><span style="text-decoration: underline;">pada Tanggal ' . $tgl_surat_dibuat . '</span><br><span style="font-weight:bold;border: none;">' . ucwords($jbt_pemberi_tgs . ' ' . $nama_opd) . '<br>Selaku pengguna anggaran</span><br><br><br><br><br><span style="text-decoration: underline;font-weight:bold;">' . $nama_pemberi_tgs . '</span><br>' . $pangkat_pemberi_tgs . '<br>' . $isi_nip . '</td>
                                            </tr>';
									$html .= '</tbody></table>';
									// var_dump($html);
									break;
								default:
									$pdf->SetFillColor(255, 255, 255);
									$pdf->setListIndentWidth(5);
									$pdf->setFontSubsetting(true);
									$pdf->SetFont('dejavusans', '', 14 * $ukuran_huruf, '', true);
									$pdf->AddPage();
									$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
									$html = '<h1>Welcome to Pasangkayu</h1><p>hubungi administrator </p>';
									break;
							}
							break;
						default:
							$html .= '<h1>Welcome to Pasangkayu</h1><p>hubungi administrator </p>';
							break;
					}
					// Print text using writeHTMLCell()
					$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
					// ---------------------------------------------------------
					// output PDF dokumen
					$sukses = true;
					$data['pdf'] = base64_encode($pdf->Output('dokumenku', 'S'));
					//============================================================+
					// END OF FILE
					//============================================================+
				} else {
					$code = 29;
					$pesan = $validate->getError();
					//var_dump($pesan);
					$data['error_validate'][] = $pesan;
					$keterangan = '<ol class="ui horizontal ordered suffixed list">';
					foreach ($pesan as $key => $value) {
						$keterangan .= '<li class="item">' . $value . '</li>';
					}
					$keterangan .= '</ol>';
					$tambahan_pesan = [$code => 'Validasi Kembali :<br>' . $keterangan];
				}
			} else {
				$pesan = 'tidak didefinisikan';
				$code = 39;
			}
		} else {
			$code = 407;
		}
		$tambahanNote = (is_array($tambahan_pesan)) ? implode($tambahan_pesan) : $tambahan_pesan;
		$item = array('code' => $code, 'message' => hasilServer[$code] . ", " . $tambahanNote, "note" => $tambahan_pesan);
		$json = array('success' => $sukses, 'data' => $data, 'error' => $item);
		return json_encode($json, JSON_HEX_APOS);
	}
	//kop dinas
	public static function kop_dinas($status_wilayah, $nama_wilayah, $nama_opd, $alamat_opd, $file_logo_daerah)
	{
		$image_file = '<img src="' . $file_logo_daerah . '" style="display:block;" width="50" height="50"/>';
		$alamat_opd = $alamat_opd !== null ? ucwords($alamat_opd) : '';
		$nama_opd = $nama_opd !== null ? ucwords($nama_opd) : '';
		return '<table>
                <tbody>
					<tr nobr="true">
						<td rowspan="4" width="12%" style="border-bottom: 1rem double #000000;">' . $image_file . '</td>
                        <td height="10" width="88%" align="center" style="font-family: Arial; font-size: 12px; width:100%;font-weight:bold;">PEMERINTAH ' . strtoupper($status_wilayah) . ' ' . strtoupper($nama_wilayah) . '</td>
					</tr>
                    <tr nobr="true">
                        <td height="20" width="88%" align="center"  style="font-family: Arial; font-size: 16px; width:100%;font-weight:bold">' .  $nama_opd . '</td>
					</tr>
                    <tr nobr="true">
                        <td height="20" width="88%" align="center"  style="font-family: Arial; font-size: 8px; width:100%;border-bottom: 1rem double #000000;padding: 0px 0px 0px 9px;">' . $alamat_opd . '</td>
					</tr>
                </tbody>
            </table>';
		// $pdf->writeHTML($isi_header, true, false, false, false, '');
	}
	public static function style_css($lebar_net)
	{
		$style_table = "<style>
            .border{
                border:1rem solid #000000;
                }
            .borderCenter{
                border:1rem solid #000000;
                text-align: center;
            }
            .borderBoldCenter{
                border:1rem solid #000000;
                text-align: center;
                font-weight:bold;
            }
            .borderJustify{
                border:1rem solid #000000;
                text-align: justify;
            }
            .borderNone{
                text-align: justify;
            }
            .borderCenter{
                border:1rem solid #000000;
                text-align: center;
            }
            .borderLRJustify{
                border-left:1rem solid #000000;
                border-right:1rem solid #000000;
                text-align: justify;
            }
            .borderLJustify{
                border-left:1rem solid #000000;
                text-align: justify;
            }
            .borderRJustify{
                border-right:1rem solid #000000;
                text-align: justify;
            }
            .borderLBJustify{
                border-leff:1rem solid #000000;
                border-bottom:1rem solid #000000;
                text-align: justify;
            }
            .borderRBJustify{
                border-right:1rem solid #000000;
                border-bottom:1rem solid #000000;
                text-align: justify;
            }
            .borderRTJustify{
                border-right:1rem solid #000000;
                border-top:1rem solid #000000;
                text-align: justify;
            }
            .borderLTJustify{
                border-left:1rem solid #000000;
                border-top:1rem solid #000000;
                text-align: justify;
            }
            .borderTJustify{
                border-top:1rem solid #000000;
                text-align: justify;
            }
            .borderBJustify{
                border-bottom:1rem solid #000000;
                text-align: justify;
            }
            .borderRBJustify{
                border-right:1rem solid #000000;
                border-bottom:1rem solid #000000;
                text-align: justify;
            }
            .borderLRBotJustify{
                border-left:1rem solid #000000;
                border-right:1rem solid #000000;
                border-bottom:1rem solid #000000;
                text-align: justify;
            }
            .borderTBLJustify{
                border-left:1rem solid #000000;
                border-top:1rem solid #000000;
                border-bottom:1rem solid #000000;
                text-align: justify;
            }
            .borderTBRJustify{
                border-right:1rem solid #000000;
                border-top:1rem solid #000000;
                border-bottom:1rem solid #000000;
                text-align: justify;
            }
            .borderTBJustify{
                border-top:1rem solid #000000;
                border-bottom:1rem solid #000000;
                text-align: justify;
            }
            .borderLRTJustify{
                border-top:1rem solid #000000;
                border-left:1rem solid #000000;
                border-right:1rem solid #000000;
                text-align: justify;
            }
            .bordertbgray{
                border-top:0.5rem solid lightgray;
                border-bottom:1rem solid lightgray;
                text-align: justify;
            }
            .bordergray{
                border:0.5rem solid lightgray;
                text-align: justify;
            }
            .kop {
                
                font-family: helvetica;
                font-size: 12pt;
                border-left: 1px solid red;
                border-right: 1px solid #FF00FF;
                border-top: 1px solid green;
                border-bottom: 1px solid blue;
                background-color: #ccffcc;
                vertical-align: middle;
            }
            .kopkiri {
                font-family: helvetica;
                font-size: 12pt;
                border-left: none;
                border-right: 1rem solid #000000;
                border-top: 1rem solid #000000;
                border-bottom: 1rem solid #000000;
                background-color: #ccffcc;
                vertical-align: middle;
            }
            .kopmid {
                font-family: helvetica;
                font-size: 12pt;
                border-left: 1rem solid #000000;
                border-right: 1rem solid #000000;
                border-top: 1rem solid #000000;
                border-bottom: 1rem solid #000000;
                background-color: #ccffcc;
                vertical-align: middle;
            }
            .kopkanan {
                
                font-family: helvetica;
                font-size: 12pt;
                border-left: 1rem solid #000000;
                border-right: none;
                border-top: 1rem solid #000000;
                border-bottom: 1rem solid #000000;
                background-color: #ccffcc;
                vertical-align: middle;
            }
            .nonekiri {
                
                font-family: helvetica;
                font-size: 12pt;
                border-left: none;
                border-right: 1rem solid #000000;
                border-top: 1rem solid #000000;
                border-bottom: 1rem solid #000000;
                
            }
            .nonemid {
                
                font-family: helvetica;
                font-size: 12pt;
                border-left: 1rem solid #000000;
                border-right: 1rem solid #000000;
                border-top: 1rem solid #000000;
                border-bottom: 1rem solid #000000;
                
            }
            .nonekanan {
                
                font-family: helvetica;
                font-size: 12pt;
                border-left: 1rem solid #000000;
                border-right: none;
                border-top: 1rem solid #000000;
                border-bottom: 1rem solid #000000;
            }
            </style>";
		$html = '<style>
            h1.color {
                color: navy;
                font-family: times;
                font-size: 24pt;
            }

            h1.color {
                color: navy;
                font-family: times;
                font-size: 24pt;
                text-decoration: underline
            }

            p.first {
                color: #003300;
                font-family: helvetica;
                font-size: 12pt
            }

            p.first span {
                color: #006600;
                font-style: italic
            }

            p#second {
                color: rgb(00, 63, 127);
                font-family: times;
                font-size: 12pt;
                text-align: justify
            }

            p#second>span {
                background-color: #FFFFAA
            }

            table.first {
                color: #003300;
                font-family: helvetica;
                font-size: 8pt;
                border-left: 3px solid red;
                border-right: 3px solid #FF00FF;
                border-top: 3px solid green;
                border-bottom: 3px solid blue;
                background-color: #ccffcc;
                vertical-align: middle
            }

            th {
                border: 0.5px solid black;
                font-family: helvetica
            }

            .thback {
                border: 0.5px solid black;
                background-color: grey;
                font-family: helvetica
            }

            td {
                border: 0.5px solid black;
                height: 20px;
                line-height: 10px
            }

            .tdback {
                border: 0.5px solid grey;
                background-color: #ffffee;
                height: 20px;
                line-height: 10px
            }

            td.second {
                border: 2px dashed green
            }

            div.test {
                color: #CC0000;
                background-color: #FFFF66;
                font-family: helvetica;
                font-size: 10pt;
                border-style: solid solid solid solid;
                border-width: 2px 2px 2px 2px;
                border-color: green #FF00FF blue red;
                text-align: center
            }

            .lowercase {
                text-transform: lowercase
            }

            .uppercase {
                text-transform: uppercase
            }

            .capitalize {
                text-transform: capitalize
            }

            .w-2 {
                width: ' . (0.2 / 10 * $lebar_net) . '
            }

            .w-5 {
                width: ' . (0.5 / 10 * $lebar_net) . '
            }

            .w-7 {
                width: ' . (0.75 / 10 * $lebar_net) . '
            }

            .w-10 {
                width: ' . (1 / 10 * $lebar_net) . '
            }

            .w-15 {
                width: ' . (1.5 / 10 * $lebar_net) . '
            }

            .w-20 {
                width: ' . (2 / 10 * $lebar_net) . '
            }

            .w-25 {
                width: ' . (2.5 / 10 * $lebar_net) . '
            }

            .w-30 {
                width: ' . (3 / 10 * $lebar_net) . '
            }

            .w-35 {
                width: ' . (3.5 / 10 * $lebar_net) . '
            }

            .w-40 {
                width: ' . (4 / 10 * $lebar_net) . '
            }

            .w-45 {
                width: ' . (4.5 / 10 * $lebar_net) . '
            }

            .w-50 {
                width: ' . (5 / 10 * $lebar_net) . '
            }

            .w-60 {
                width: ' . (6 / 10 * $lebar_net) . '
            }

            .w-65 {
                width: ' . (6.5 / 10 * $lebar_net) . '
            }

            .w-70 {
                width: ' . (7 / 10 * $lebar_net) . '
            }

            .w-75 {
                width: ' . (7.5 / 10 * $lebar_net) . '
            }

            .w-80 {
                width: ' . (8 / 10 * $lebar_net) . '
            }

            .w-85 {
                width: ' . (8.5 / 10 * $lebar_net) . '
            }

            .w-90 {
                width: ' . (9 / 10 * $lebar_net) . '
            }

            .w-95 {
                width: ' . (9.5 / 10 * $lebar_net) . '
            }

            .w-100 {
                width: ' . ($lebar_net) . '
            }
        </style>';
		$tujuh = 0.75 / 10 * $lebar_net;
		$dua = 0.2 / 10 * $lebar_net;
		$tiga = 0.3 / 10 * $lebar_net;
		$empat = 0.4 / 10 * $lebar_net;
		$lima = 0.5 / 10 * $lebar_net;
		$tujuh = 0.7 / 10 * $lebar_net;
		$sepuluh = 1 / 10 * $lebar_net;
		$lima_belas = 1.5 / 10 * $lebar_net;
		$dua_puluh = 2 / 10 * $lebar_net;
		$dua_lima = 2.5 / 10 * $lebar_net;
		$tiga_puluh = 3 / 10 * $lebar_net;
		$tiga_lima = 3.5 / 10 * $lebar_net;
		$empat_puluh = 4 / 10 * $lebar_net;
		$empat_lima = 4.5 / 10 * $lebar_net;
		$lima_puluh = 5 / 10 * $lebar_net;
		$lima_lima = 5.5 / 10 * $lebar_net;
		$enam_puluh = 6 / 10 * $lebar_net;
		$enam_lima = 6.5 / 10 * $lebar_net;
		$tujuh_puluh = 7 / 10 * $lebar_net;
		$tujuh_lima = 7.5 / 10 * $lebar_net;
		$delapan_puluh = 8 / 10 * $lebar_net;
		$delapan_lima = 8.5 / 10 * $lebar_net;
		$sembilan_puluh = 9 / 10 * $lebar_net;
		$sembilan_lima = 9.5 / 10 * $lebar_net;
		$seratus = $lebar_net;
		$style_css = "<style>.w-2{ width: $dua;} .w-3{ width: $dua;} .w-4{ width: $empat;} .w-5{ width: $lima;} .w-7{ width: $tujuh;} .w-10{ width: $sepuluh;} .w-15{ width: $lima_belas;} .w-20{ width: $dua_puluh;} .w-25{ width: $dua_lima;} .w-30{ width: $tiga_puluh;} .w-35{ width: $tiga_lima;} .w-40{ width: $empat_puluh;} .w-45{ width: $empat_lima;} .w-50{ width: $lima_puluh;} .w-60{ width: $enam_puluh;} .w-65{ width: $enam_lima;} .w-70{ width: $tujuh_puluh;} .w-75{ width: $tujuh_lima;} .w-80{ width: $delapan_puluh;} .w-85{ width: $delapan_lima ;} .w-90{ width: $sembilan_puluh;} .w-95{ width: $sembilan_lima;} .w-100{ width: $seratus;}</style>";
		$style_table = "<style></style>";
		return ['style_umum' => $html, 'style_css' => $style_css, 'style_tabel' => $style_table];
	}
}