<?php
require '../app/models/script/init.php';
$user = new User();
$user->cekUserSession();
$type_user = $_SESSION["user"]["type_user"];
$id_user = $_SESSION["user"]["id"];
$classRow = '';
$invertedColor = '';
$keyEnc = $_SESSION["user"]["key_encrypt"];
$theme = $_SESSION["user"]["theme"];
?>
<!doctype html>
<html>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>seSendok</title>
  <link rel="stylesheet" href="<?= BASEURL; ?>vendor/node_modules/fomantic-ui/dist/semantic.min.css">
  <link rel="stylesheet" href="<?= BASEURL; ?>css/admin.css">
  <link rel="shortcut icon" href="<?= BASEURL; ?>img/logo.png">
</head>

<body style="overflow: hidden;" class="dimmable">
  <!-- MAIN TOOLBAR MENU -->
  <div class="ui teal top fixed inverted main menu">
    <a class="item nabiila" id="biilainayah">
      <i class="sidebar icon"></i>
    </a>
    <div class="right menu">
      <div class="ui inline inverted dropdown item lain" id="countRow"><span><i class="list icon"></i></span><input type="hidden" name="countRow" value="5">
        <div class="text">5</div>
        <div class="menu">
          <div class="item" data-value="all">All</div>
          <div class="item selected" data-value="5">5</div>
          <div class="item" data-value="10">10</div>
          <div class="item" data-value="15">15</div>
          <div class="item" data-value="20">20</div>
          <div class="item" data-value="30">30</div>
          <div class="item" data-value="40">40</div>
          <div class="item" data-value="50">50</div>
          <div class="item" data-value="100">100</div>
        </div>
      </div>
      <div class="item">
        <div class="ui cari_data inverted transparent icon input">
          <input type="text" placeholder="Search..." name="cari_data" id="cari_data">
          <i class="search link icon"></i>
        </div>
      </div>
      <div class="right inverted menu">
        <div class="ui dropdown item lain"><span><i class="user icon"></i></span><i class="dropdown icon"></i>
          <div class="menu"><a class="item" data-tab="wallchat"><i class="circular comments outline icon"></i>Pesan</a><a class="item" name="change_themes"><i class="circular moon icon"></i>Change Themes</a><a class="item" data-tab="profil"><i class="circular qrcode icon"></i>Pengaturan</a><a class="item" onclick="window.location.href='home/logout'"><i class="circular sign out alternate icon"></i>Log
              Out</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="ui bottom attached stackable pushable" style="height: calc(100vh - 45px) !important;margin-top: 45px;">
    <!-- sidebar-->
    <div class="ui inverted left vertical sidebar menu">
      <div class="item">
        <h2 class="ui inverted center red aligned icon header dash_header"><i class="circular colored blue building icon"></i>
          <div class="content">seSendok <span id="kopku" style="color: darkcyan!important;font-style: italic"></span>
            <div class="sub header">pemerintahan</div>
            <a class="ui blue center basic label inverted" id="set_tahun_anggaran"><?php echo $_SESSION["user"]["tahun"]; ?></a>
          </div>
        </h2>
      </div>
      <div class="item">
        <div class="ui inverted transparent icon input">
          <input type="text" placeholder="Menu...">
          <i class="search icon"></i>
        </div>
      </div>
      <a class="item" href="#" data-tab="tab_home"><i class="home icon"></i>Home</a>
      <div class="ui accordion inverted item">
        <div class="title item"><i class="dropdown icon"></i><span></span>Anggaran </div>
        <div class="content">
          <a class="item nabiila" href="#" data-tab="tab_renstra" tbl="renstra"><span><i class="toggle on icon"></i></span><i class="purple sitemap icon"></i>RENSTRA</a>
          <a class="item nabiila" href="#" data-tab="tab_renja" tbl="sub_keg_renja"><span><i class="toggle on icon"></i></span><i class="violet tag icon"></i>RENJA</a>
          <a class="item nabiila" href="#" data-tab="tab_renja" tbl="sub_keg_dpa"><span><i class="toggle on icon"></i></span><i class="yellow tags icon"></i>DPA</a>
        </div>
      </div>
      <a class="item" href="#" data-tab="tab_kontrak" tbl="daftar_paket"><i class="file contract icon"></i>Kontrak</a>
      <div class="ui accordion inverted item">
        <div class="title item"><i class="dropdown icon"></i><span></span>Realisasi</div>
        <div class="content">
          <a class="item" href="#" data-tab="tab_input_real" tbl="realisasi"><span><i class="toggle on icon"></i></span><i class="purple chart pie icon"></i>Input Realisasi</a>
          <a class="item" href="#" data-tab="tab_input_real" tbl="spj"><span><i class="toggle on icon"></i></span><i class="violet chartline icon"></i>SPJ</a>
          <a class="item" href="#" data-tab="tab_input_real" tbl="laporan"><span><i class="toggle on icon"></i></span><i class="yellow chart bar icon"></i>Laporan</a>
        </div>
      </div>
      <div class="ui accordion inverted item">
        <div class="title item"><i class="dropdown icon"></i>Referensi</div>
        <div class="content">
          <a class="item" href="#" data-tab="tab_ref" tbl="bidang_urusan"><span><i class="toggle on blue icon"></i></span><i class="user plus icon"></i>Bidang Urusan</a>
          <a class="item" href="#" data-tab="tab_ref" tbl="prog"><span><i class="toggle on blue icon"></i></span><i class="users icon"></i>Program</a>
          <a class="item" href="#" data-tab="tab_ref" tbl="keg"><span><i class="toggle on blue icon"></i></span><i class="outdent icon"></i>Kegiatan</a>
          <a class="item" href="#" data-tab="tab_ref" tbl="sub_keg"><span><i class="toggle on blue icon"></i></span><i class="layer group icon"></i>Sub Kegiatan</a>
          <a class="item" href="#" data-tab="tab_ref" tbl="rekanan"><span><i class="toggle on blue icon"></i></span><i class="book reader icon"></i>Rekanan</a>
          <a class="item" href="#" data-tab="tab_ref" tbl="satuan"><span><i class="toggle on blue icon"></i></span><i class="calculator icon"></i>Satuan</a>
          <a class="item" href="#" data-tab="tab_ref" tbl="mapping"><span><i class="toggle on blue icon"></i></span><i class="stream icon"></i>Mapping</a>
          <a class="item" href="#" data-tab="tab_ref" tbl="aset"><span><i class="toggle on blue icon"></i></span><i class="calendar alternate icon"></i>Neraca</a>
          <a class="item" href="#" data-tab="tab_ref" tbl="akun_belanja"><span><i class="toggle on blue icon"></i></span><i class="calendar alternate outline icon"></i>Akun</a>
          <a class="item" href="#" data-tab="tab_ref" tbl="sumber_dana"><span><i class="toggle on blue icon"></i></span><i class="money check alternate icon"></i>Sumber Dana</a>
          <a class="item" href="#" data-tab="tab_ref" tbl="organisasi"><span><i class="toggle on blue icon"></i></span><i class="id card icon"></i>Organisasi</a>
          <a class="item" href="#" data-tab="tab_peraturan" tbl="peraturan"><span><i class="toggle on blue icon"></i></span><i class="balance scale icon"></i>Peraturan</a>
          <a class="item" href="#" data-tab="tab_ref" tbl="wilayah"><span><i class="toggle on blue icon"></i></span><i class="globe icon"></i>Wilayah</a>
        </div>
      </div>
      <div class="ui accordion inverted item">
        <div class="title item"><i class="dropdown icon"></i>Standar Harga Satuan</div>
        <div class="content">
          <a class="item" href="#" data-tab="tab_hargasat" tbl="ssh"><span><i class="toggle on blue icon"></i></span><i class="file icon"></i>SSH</a>
          <a class="item" href="#" data-tab="tab_hargasat" tbl="hspk"><span><i class="toggle on blue icon"></i></span><i class="file alternate icon"></i>HSPK</a>
          <a class="item" href="#" data-tab="tab_hargasat" tbl="asb"><span><i class="toggle on blue icon"></i></span><i class="file alternate outline icon"></i>ASB</a>
          <a class="item" href="#" data-tab="tab_hargasat" tbl="sbu"><span><i class="toggle on blue icon"></i></span><i class="file outline icon"></i>SBU</a>
        </div>
      </div>
      <div class="ui accordion inverted item">
        <div class="title item"><i class="dropdown icon"></i>Kepegawaian</div>
        <div class="content">
          <a class="item" href="#" data-tab="tab_all" tbl="asn"><span><i class="toggle on blue icon"></i></span><i class="users icon"></i>ASN</a>
          <a class="item" href="#" data-tab="tab_all" tbl="sk_asn"><span><i class="toggle on blue icon"></i></span><i class="users icon"></i>Surat Keputusan (SK)</a>
          <a class="item" href="#" data-tab="tab_all" tbl="register_surat"><span><i class="toggle on blue icon"></i></span><i class="users icon"></i>Register Surat</a>
          <a class="item" href="#" data-tab="tab_all" tbl="create_surat"><span><i class="toggle on blue icon"></i></span><i class="users icon"></i>Tata Naskah</a>
        </div>
      </div>
      <!-- ut admin-->
      <?php echo $retVal = ($type_user == 'admin') ? '<a class="item" href="#" data-tab="tab_hargasat" tbl="berita"><i class="newspaper icon"></i>Halaman Berita</a><a class="item" href="#" data-tab="reset"><i class="erase icon"></i>Reset Tabel</a><a class="item" href="#" data-tab="atur_satu"><i class="toolbox icon"></i>Pengaturan</a>' : ''; ?>
      <a class="item" href="#" data-tab="wallchat"><i class="comments outline icon"></i>Pesan</a>
      <a class="item" href="#" data-tab="profil" tbl="user"><i class="user icon"></i>Profil</a>
    </div>
    <!-- flyout-->
    <div class="ui right flyout">
      <i class="close icon"></i>
      <div class="ui header"><i class="folder icon" name="icon_flyout"></i>
        <div class="content" name="content_flyout">Lengkapi Data </div>
      </div>
      <form class="ui form scrolling content" name="form_flyout">
      </form>
      <div class="left actions">
        <div class="ui red cancel button"><i class="remove icon"></i>Tutup </div>
        <div class="ui green ok button"><i class="checkmark icon"></i>Submit </div>
      </div>
    </div>
    <div class="pusher">
      <div class="basic center segment">
        <div class="ui demo page dimmer center light">
          <!-- <div class="ui massive blue text elastic loader"></div> -->
          <h1 class="spin">

            <span class="spin let1">l</span>
            <span class="spin let2">o</span>
            <span class="spin let3">a</span>
            <span class="spin let4">d</span>
            <span class="spin let5">i</span>
            <span class="spin let6">n</span>
            <span class="spin let7">g</span>
          </h1>
          <!-- <div class="ui massive blue text elastic loader">Loading...</div> -->
          <!-- <div class="putar">Loading</div> -->
          <!-- <div class="putar2">
                        <span style="--i:0;"></span>
                        <span style="--i:1;"></span>
                        <span style="--i:2;"></span>
                        <span style="--i:3;"></span>
                        <span style="--i:4;"></span>
                        <span style="--i:5;"></span>
                        <span style="--i:6;"></span>
                        <span style="--i:7;"></span>
                        <span style="--i:8;"></span>
                        <span style="--i:9;"></span>
                        <span style="--i:10;"></span>
                        <span style="--i:11;"></span>
                        <span style="--i:12;"></span>
                        <span style="--i:13;"></span>
                        <span style="--i:14;"></span>
                        <span style="--i:15;"></span>
                    </div> -->
          <!-- <div class="ring">Loading
                        <span class="spin"></span>
                    </div> -->
        </div>
      </div>
      <!-- sticky-->
      <div class="ui sticky">
        <div class="ui icon message dashboard"><i class="home icon"></i>
          <div class="content">
            <div class="header">DASHBOARD</div>
            <div class="pDashboard">seSendok</div>
          </div>
        </div>
      </div>
      <!-- ============== -->
      <!-- tab home -->
      <!-- ============== @audit-ok home -->
      <div class="ui tab basic segment active" data-tab="tab_home">
        <div class="main ui intro container">
          <h2 class="ui dividing header">Pengantar untuk <?php echo $type_user ?> </h2>
          <div class="ui large info message">
            <h2 class="ui header dash_header"><i class="settings icon"></i>
              <div class="content">seSendok <div class="sub header">merupakan aplikasi perencanaan, angaran dan
                  realisasi Berbasis web</div>
              </div>
            </h2>
          </div>
          <div class="ui info message">
            <h3 class="ui header dash_header"><i class="upload icon"></i>
              <div class="content">menginpor file pada aplikasi ? <div class="sub header">
                  <div class="ui divided selection list">
                    <li class="item">file yang di Impor harus extension <a class="ui green label custom csv_format"><i class="file excel icon"></i>xlsx</a>,
                      file template pengimporan dapat di download di <a class="ui teal tag label">menu data
                        umum</a></li>
                    <li class="item">Format angka menggunakan regional Indonesia <a class="ui blue label"><i class="money check icon"></i>pengelompokan " . "</a><a class="ui blue label"><i class="money check icon"></i>desimal " , "</a><a class="ui blue label"><i class="money check icon"></i>contoh "1.200.000,50"</a></li>
                  </div>
                </div>
              </div>
            </h3>
          </div>
          <div class="ui icon buttons mini align">
            <button class="ui button"><i class="align left icon"></i></button>
            <button class="ui button"><i class="align center icon"></i></button>
            <button class="ui button"><i class="align right icon"></i></button>
            <button class="ui button"><i class="align justify icon"></i></button>
          </div>
          <div class="ui icon buttons mini font">
            <button class="ui button"><i class="bold icon"></i></button>
            <button class="ui button"><i class="underline icon"></i></button>
            <button class="ui button"><i class="text width icon"></i></button>
          </div>
          <div class="ui dropdown">
            <div class="text">Pilih Opsi</div>
            <div class="menu">
              <div class="item no-margin no-padding">
                <div class="ui icon buttons mini">
                  <button class="ui button"><i class="align left icon"></i></button>
                  <button class="ui button"><i class="align center icon"></i></button>
                  <button class="ui button"><i class="align right icon"></i></button>
                  <button class="ui button"><i class="align justify icon"></i></button>
                </div>
                <div class="ui icon buttons mini">
                  <button class="ui button"><i class="bold icon"></i></button>
                  <button class="ui button"><i class="underline icon"></i></button>
                  <button class="ui button"><i class="text width icon"></i></button>
                </div>
              </div>
            </div>
          </div>
          <h2 class="ui dividing header">Cara menggunakan <a class="anchor"></a></h2>
          <div class="ui relaxed divided list">
            <div class="item">
              <i class="large list ol middle aligned icon"></i>
              <div class="content">
                <a class="header">Referensi >> Wilayah</a>
                <div class="description">input kode wilayah (admin)</div>
              </div>
            </div>
            <div class="item">
              <i class="large list ol middle aligned icon"></i>
              <div class="content">
                <a class="header">Referensi >> Peraturan</a>
                <div class="description">input peraturan (admin)</div>
              </div>
            </div>
            <div class="item">
              <i class="large list ol middle aligned icon"></i>
              <div class="content">
                <a class="header">Pengaturan >> Tahun Anggaran</a>
                <div class="description">tentukan Peraturan Tahun Anggaran (admin)</div>
              </div>
            </div>
            <div class="item">
              <i class="large list ol middle aligned icon"></i>
              <div class="content">
                <a class="header">Referensi >> Organisasi</a>
                <div class="description">input Organisasi (admin)</div>
              </div>
            </div>
            <div class="item">
              <i class="large list ol middle aligned icon"></i>
              <div class="content">
                <a class="header">Referensi</a>
                <div class="description">import/input referensi lainnya sampai sub kegiatan (admin)</div>
              </div>
            </div>
          </div>
          <p>Tutorial cara menggunakan aplikasi seSendok untuk penyusunan anggaran dapat di download <a href="<?= BASEURL; ?>template/tutorial_user.pdf" target="_blank">disini</a></p>
          <p></p>
          <p></p>
        </div>
        <div class="ui vertical footer segment">
          <div class="three column divided stackable center aligned ui grid">
            <div class="column">
              <div class="ui icon header"><i class="teal rocket circular icon"></i>AHSP : <a href="javascript: void(0)">efisiensi dan efektif</a></div>
            </div>
            <div class="column">
              <div class="ui icon header"><i class="teal theme circular icon"></i>transparansi, <a href="javascript: void(0)">akuntabilitas</a></div>
            </div>
            <div class="column">
              <div class="ui icon header"><i class="teal food circular icon"></i>serta <a href="javascript: void(0)">partisipatif</a></div>
            </div>
          </div>
        </div>
      </div>
      <!-- ============== -->
      <!-- tab_renstra -->
      <!-- ============== -->
      <div class="ui tab basic segment" data-tab="tab_renstra" tbl="">
        <div class="ui stackable grid">
          <div class="two wide left column">
            <div class="ui red secondary vertical pointing fluid menu">
              <a class="active item inayah" data-tab="tab_renstra" tbl="renstra">
                Renstra
              </a>
              <a class="item inayah" data-tab="tab_renstra" tbl="tujuan_sasaran_renstra">
                Tujuan dan Sasaran
              </a>
            </div>
          </div>
          <div class="fourteen wide stretched right column">
            <h1 class="ui header">Rencana Strategis (Renstra) <div class="sub header">dokumen perencanaan
                berorientasi
                pada hasil yang ingin dicapai</div>
            </h1>
            <div class="ui hidden divider"></div>
            <div class="ui stretched stackable five column grid">
              <div class="column">
                <div class="ui orange icon message goyang"><i class="book icon"></i>
                  <div class="content">
                    <div class="header">Total Anggaran</div>
                    <p name="total-anggaran"></p>
                  </div>
                </div>
              </div>
              <div class="column">
                <div class="ui icon yellow message goyang">
                  <i class="chart icon" name="chart-realisasi-fisik-mini"></i>
                  <div class="content">
                    <div class="header">Jumlah Program</div>
                    <p name="realisasi-fisik"></p>
                  </div>
                </div>
              </div>
              <div class="column">
                <div class="ui olive icon message goyang"><i class="chart icon" name="chart-realisasi-keu-mini"></i>
                  <div class="content">
                    <div class="header">Jumlah Kegiatan</div>
                    <p name="realisasi-keu"></p>
                  </div>
                </div>
              </div>
              <div class="column">
                <div class="ui icon red message goyang"><i class="spinner loading icon"></i>
                  <div class="content">
                    <div class="header">Jumlah Sub Kegiatan</div>
                    <p name="sisa-fisik"></p>
                  </div>
                </div>
              </div>
              <div class="column">
                <div class="ui positive icon message goyang"><i class="spinner loading icon"></i>
                  <div class="content">
                    <div class="header">Sisa Keuangan</div>
                    <p name="sisa-keu"></p>
                  </div>
                </div>
              </div>
              <div class="ui fluid container">
                <div class="ui hidden divider"></div>
                <div class="ui right floated basic icon buttons">
                  <?php
                  if ($type_user == 'admin') {
                    echo '<button class="ui button" name="flyout" data-tooltip="Tambah Data" data-position="bottom center" jns="add" tbl="tujuan_sasaran_renstra"><i class="plus icon"></i></button>
                            <button class="ui button" name="flyout" jns="import" data-tooltip="Import XLSX" data-position="bottom center" jns="import"><i class="upload icon"></i></button>';
                  }
                  ?>
                  <button class="ui button" data-tooltip="Download" data-position="bottom center" name="ungguh" jns="dok" tbl="" type="submit"><i class="alternate download icon"></i></button>
                </div>
                <h3 class="ui dividing header"><i class="left align icon"></i>Tabel Dokumen</h3>
                <div class="ui hidden divider"></div>
                <div class="ui hidden divider"></div>
                <div class="ui long scrolling fluid container">
                  <table class="ui head foot stuck unstackable celled striped table insert">
                    <thead>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- ============== -->
      <!-- tab_renja dan dpa -->
      <!-- ============== -->
      <div class="ui tab basic segment" data-tab="tab_renja">
        <div class="ui stackable grid">
          <div class="two wide left column">
            <div class="ui red secondary vertical pointing fluid menu">
              <a class="item inayah" data-tab="tab_renja">
                Sub Kegiatan
              </a>
              <a class="item" data-tab="tab_renja">
                Renja
              </a>
              <a class="item" data-tab="tab_renja">
                Renja Perubahan
              </a>
            </div>
          </div>
          <div class="fourteen wide stretched right column">
            <h1 class="ui header">Rencana Kerja SKPD (Renja) <div class="sub header">dokumen perencanaan
                berorientasi
                pada hasil yang ingin dicapai</div>
            </h1>
            <div class="ui hidden divider"></div>
            <table class="ui celled very basic striped table sub_keg" hidden>
              <tbody>
                <tr>
                  <td class="collapsing">Perangkat Daerah</td>
                  <td>Not Found</td>
                  <td class="right aligned collapsing">Rp. 0,00</td>
                </tr>
                <tr>
                  <td>Bidang</td>
                  <td>Not Found</td>
                  <td class="right aligned collapsing">Rp. 0,00</td>
                </tr>
                <tr>
                  <td>Program</td>
                  <td>Not Found</td>
                  <td class="right aligned collapsing">Rp. 0,00</td>
                </tr>
                <tr>
                  <td>Kegiatan</td>
                  <td>Not Found</td>
                  <td class="right aligned collapsing">Rp. 0,00</td>
                </tr>
                <tr>
                  <td>Sub Kegiatan</td>
                  <td>Not Found</td>
                  <td class="right aligned collapsing">Rp. 0,00</td>
                </tr>
              </tbody>
            </table>
            <div class="ui stretched stackable four column grid">
              <div class="column">
                <div class="ui icon yellow message goyang">
                  <i class="chart icon" name="chart-realisasi-fisik-mini">00</i>
                  <div class="content">
                    <div class="header">Kegiatan</div>
                    <p name="realisasi-fisik">Jumlah Kegiatan pada SKPD</p>
                  </div>
                </div>
              </div>
              <div class="column">
                <div class="ui olive icon message goyang"><i class="chart icon" name="chart-realisasi-keu-mini">00</i>
                  <div class="content">
                    <div class="header">Sub Kegiatan</div>
                    <p name="realisasi-keu">Jumlah Sub Kegiatan pada SKPD</p>
                  </div>
                </div>
              </div>
              <div class="column">
                <div class="ui icon red message goyang"><i class="spinner loading icon"></i>
                  <div class="content">
                    <div class="header">Jumlah Pagu</div>
                    <p name="sisa-fisik"></p>
                  </div>
                </div>
              </div>
              <div class="column">
                <div class="ui positive icon message goyang"><i class="spinner loading icon"></i>
                  <div class="content">
                    <div class="header">Jumlah Rincian</div>
                    <p name="sisa-keu"></p>
                  </div>
                </div>
              </div>
              <div class="ui fluid container">
                <div class="ui hidden divider"></div>
                <div style="height: 1px">
                  <div class="ui right floated basic icon buttons">
                    <button class="ui button" name="flyout" data-tooltip="Tambah Data" data-position="bottom center" jns="add" tbl=""><i class="plus icon"></i></button>
                    <button class="ui button" name="flyout" data-tooltip="Import XLSX" data-position="bottom center" jns="import"><i class="upload icon"></i></button>
                    <button class="ui button" data-tooltip="Download" data-position="bottom center" name="ungguh" jns="dok" tbl="" type="submit"><i class="alternate download icon"></i></button>
                  </div>
                </div>
                <div class="ui hidden divider"></div>
                <h3 class="ui dividing header"></h3>
              </div>
              <div class="ui hidden divider"></div>

            </div>
            <table class="ui celled striped table insert">
              <thead>
              </thead>
              <tbody>
              </tbody>
              <tfoot>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
      <!-- ============== -->
      <!-- tab_kontrak -->
      <!-- ============== -->
      <div class="ui tab basic segment" data-tab="tab_kontrak" tbl="daftar_paket">
        <div class="ui container">
          <div class="ui info message" name="ketref">Nabiilainayah</div>
          <div class="ui hidden divider"></div>
          <div class="ui right floated basic icon buttons">
            <button class="ui button" name="flyout" data-tooltip="Tambah Data" data-position="bottom center" jns="add" tbl="daftar_paket"><i class="plus icon"></i></button>
            <button class="ui button" name="flyout" jns="import" data-tooltip="Import XLSX" data-position="bottom center" jns="import"><i class="upload icon"></i></button>
            <button class="ui button" data-tooltip="Download" data-position="bottom center" name="ungguh" jns="dok" tbl="daftar_paket" type="submit"><i class="alternate download icon"></i></button>
          </div>
          <h3 class="ui dividing header"><i class="left align icon"></i>Tabel Dokumen</h3>
          <div class="ui hidden divider"></div>
          <div class="ui hidden divider"></div>
          <table class="ui celled striped table insert">
            <thead>
              <tr>
                <th>Uraian Komponen</th>
                <th>Pagu</th>
                <th>Nilai Kontrak</th>
                <th>Nama PPK</th>
                <th>Keterangan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
            <tfoot>
            </tfoot>
          </table>
        </div>

      </div>
      <!-- ============== -->
      <!-- tab_input_real -->
      <!-- ============== -->
      <div class="ui tab basic segment" data-tab="tab_input_real">
        <div class="ui container">
          <h1 class="ui header">Realisasi SKPD<div class="sub header">dokumen realisasi fisik dan keuangan</div>
          </h1>
          <div class="ui hidden divider"></div>
          <div class="ui stretched stackable three column grid">
            <div class="column">
              <div class="ui orange icon message goyang"><i class="book icon"></i>
                <div class="content">
                  <div class="header">Total Anggaran</div>
                  <p name="total-anggaran"></p>
                </div>
              </div>
            </div>
            <div class="column">
              <div class="ui icon yellow message goyang">
                <i class="chart icon" name="chart-realisasi-fisik-mini">00</i>
                <div class="content">
                  <div class="header">Total Kontrak</div>
                  <p name="realisasi-fisik">Jumlah kontrak</p>
                </div>
              </div>
            </div>
            <div class="column">
              <div class="ui olive icon message goyang"><i class="chart icon" name="chart-realisasi-keu-mini">00</i>
                <div class="content">
                  <div class="header">Jumlah Realisasi</div>
                  <p name="realisasi-keu">Jumlah realisasi</p>
                </div>
              </div>
            </div>

            <div class="ui fluid container">
              <div class="ui hidden divider"></div>
              <div style="height: 1px">
                <div class="ui right floated basic icon buttons">
                  <button class="ui button" name="modal_show" jns="add" tbl="realisasi" data-tooltip="Tambah Data" data-position="bottom center" jns="add" tbl=""><i class="plus icon"></i></button>
                  <button class="ui button" name="flyout" data-tooltip="Import XLSX" data-position="bottom center" jns="import"><i class="upload icon"></i></button>
                  <button class="ui button" data-tooltip="Download" data-position="bottom center" name="ungguh" jns="dok" tbl="realisasi" type="submit"><i class="alternate download icon"></i></button>
                </div>
              </div>
              <div class="ui hidden divider"></div>
              <h3 class="ui dividing header"></h3>
            </div>
            <div class="ui hidden divider"></div>
            <div class="ui long scrolling fluid container">
              <table class="ui striped head foot stuck unstackable celled striped table insert">
                <thead>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- ============== -->
      <!-- tab_spj -->
      <!-- ============== -->
      <div class="ui tab basic segment" data-tab="tab_spj">
      </div>
      <!-- ============== -->
      <!-- tab_lap -->
      <!-- ============== -->
      <div class="ui tab basic segment" data-tab="tab_lap">
      </div>
      <!-- ============== -->
      <!-- tab_all -->
      <!-- ============== -->
      <div class="ui tab basic segment" data-tab="tab_all">
        <div class="ui container">
          <div class="ui info message" name="ketref">Nabiilainayah</div>
          <div class="ui hidden divider"></div>
          <div class="ui right floated basic icon buttons">
            <button class="ui button" name="" data-tooltip="Tambah Data" data-position="bottom center" jns="add"><i class="plus icon"></i></button>
            <button class="ui button" name="flyout" jns="import" data-tooltip="Import XLSX" data-position="bottom center" jns="import"><i class="upload icon"></i></button>
            <button class="ui button" data-tooltip="Download" data-position="bottom center" name="ungguh" jns="dok" tbl="peraturan" type="submit"><i class="alternate download icon"></i></button>
          </div>
          <h3 class="ui dividing header"><i class="left align icon"></i>Tabel Dokumen</h3>
          <div class="ui hidden divider"></div>
          <div class="ui hidden divider"></div>
          <table class="ui celled striped table insert">
            <thead>
              <tr>
                <th>Kode Komponen</th>
                <th>Uraian Komponen</th>
                <th>Spesifikasi</th>
                <th>Satuan</th>
                <th>Harga Satuan</th>
                <th>TKDN</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
            <tfoot>
            </tfoot>
          </table>
        </div>
      </div>
      <!-- ============== -->
      <!-- tab_referensi -->
      <!-- ============== -->
      <div class="ui tab basic segment" data-tab="tab_ref" tbl="">
        <div class="ui container">
          <div class="ui info message" name="ketref">Nabiilainayah</div>
          <div class="ui hidden divider"></div>
          <div class="ui right floated basic icon buttons">
            <?php
            if ($type_user == 'admin') {
              echo '<button class="ui button" name="flyout" data-tooltip="Tambah Data" data-position="bottom center" jns="add"><i class="plus icon"></i></button>
                            <button class="ui button" name="flyout" jns="import" data-tooltip="Import XLSX" data-position="bottom center" jns="import"><i class="upload icon"></i></button>';
            }
            ?>
            <button class="ui button" data-tooltip="Download" data-position="bottom center" name="ungguh" jns="dok" tbl="peraturan" type="submit"><i class="alternate download icon"></i></button>
          </div>
          <h3 class="ui dividing header"><i class="left align icon"></i>Tabel Dokumen</h3>
          <div class="ui hidden divider"></div>
          <div class="ui hidden divider"></div>
          <table class="ui celled striped table insert">
            <thead>
              <tr>
                <th>Kode Komponen</th>
                <th>Uraian Komponen</th>
                <th>Spesifikasi</th>
                <th>Satuan</th>
                <th>Harga Satuan</th>
                <th>TKDN</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
            <tfoot>
            </tfoot>
          </table>
        </div>
      </div>
      <!-- ============ -->
      <!-- tab_peraturan -->
      <!-- ============== -->
      <div class="ui tab basic segment" data-tab="tab_peraturan" tbl="peraturan">
        <div class="ui container">
          <div class="ui hidden divider"></div>
          <div class="ui right floated basic icon buttons">
            <?php
            if ($type_user == 'admin') {
              echo '<button class="ui button" name="flyout" data-tooltip="Tambah Data" data-position="bottom center" jns="add"><i class="plus icon"></i></button>
                            <button class="ui button" name="flyout" jns="import" data-tooltip="Import XLSX" data-position="bottom center" jns="import"><i class="upload icon"></i></button>';
            }
            ?>
            <button class="ui button" data-tooltip="Download" data-position="bottom center" name="ungguh" jns="dok" tbl="peraturan" type="submit"><i class="alternate download icon"></i></button>
          </div>
          <h3 class="ui dividing header"><i class="left align icon"></i>Tabel Dokumen</h3>
          <div class="ui hidden divider"></div>
          <div class="ui hidden divider"></div>
          <table class="ui celled striped table insert">
            <thead>
              <tr>
                <th>Nomor</th>
                <th>Uraian</th>
                <th>Tanggal Pengundangan</th>
                <th>Keterangan</th>
                <th>Tautan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="4">
                  <div class="ui right floated pagination menu">
                    <a class="icon item">
                      <i class="left chevron icon"></i>
                    </a>
                    <a class="item">1</a>
                    <a class="item">2</a>
                    <a class="item">3</a>
                    <a class="item">4</a>
                    <a class="icon item">
                      <i class="right chevron icon"></i>
                    </a>
                  </div>
                </th>
              </tr>
            </tfoot>
          </table>
        </div>

      </div>
      <!-- ============== -->
      <!-- tab_hargasat -->
      <!-- ============== -->
      <div class="ui tab basic segment" data-tab="tab_hargasat">
        <div class="ui container">
          <div class="ui info message" name="kethargasat">Nabiilainayah</div>
          <div class="ui hidden divider"></div>
          <div class="ui right floated basic icon buttons">
            <?php
            if ($type_user == 'admin') {
              echo '<button class="ui button" name="flyout" data-tooltip="Tambah Data" data-position="bottom center" jns="add"><i class="plus icon"></i></button>
                            <button class="ui button" name="flyout" jns="import" data-tooltip="Import XLSX" data-position="bottom center" jns="import"><i class="upload icon"></i></button>';
            }
            ?>
            <button class="ui button" data-tooltip="Download" data-position="bottom center" name="ungguh" jns="dok" tbl="asb" type="submit"><i class="alternate download icon"></i></button>
          </div>
          <h3 class="ui dividing header"><i class="left align icon"></i>Tabel Dokumen</h3>
          <div class="ui hidden divider"></div>
          <div class="ui hidden divider"></div>
          <table class="ui celled striped table insert">
            <thead>
              <tr>
                <th>Kode Komponen</th>
                <th>Uraian Komponen</th>
                <th>Spesifikasi</th>
                <th>Satuan</th>
                <th>Harga Satuan</th>
                <th>TKDN</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
            <tfoot>
            </tfoot>
          </table>
        </div>
      </div>
      <!-- ============== -->
      <!-- reset -->
      <!-- ============== -->
      <div class="ui tab basic segment" data-tab="reset">
        <div class="ui grid stackable container">
          <div class="column">
            <div class="ui placeholder segment">
              <div class="ui two column stackable center aligned grid">
                <div class="ui vertical divider">Or</div>
                <div class="middle aligned row">
                  <div class="column">
                    <div class="ui icon header">
                      <i class="world icon"></i>
                      Backup Tabel
                    </div>
                    <div class="inline">
                      <div class="ui buttons">
                        <button class="ui blue button">&nbsp;&nbsp;&nbsp;&nbsp;All&nbsp;&nbsp;&nbsp;&nbsp;</button>
                        <div class="or"></div>
                        <button class="ui positive button">Proyek</button>
                      </div>
                    </div>
                  </div>
                  <div class="column">
                    <div class="ui icon header">
                      <i class="world icon"></i>
                      Restore Tabel
                    </div>
                    <div class="inline">
                      <div class="ui buttons">
                        <button class="ui blue button">&nbsp;&nbsp;&nbsp;&nbsp;All&nbsp;&nbsp;&nbsp;&nbsp;</button>
                        <div class="or"></div>
                        <button class="ui positive button">Proyek</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="ui three stackable cards">
              <?php
              $data_card = new stdClass;
              $data_card->peraturan = ['header' => 'Peraturan', 'meta' => 'peraturan terkait aplikasi', 'description' => 'ketentuan yang dengan sendirinya memiliki suatu makna normatif; ketentuan yang menyatakan bahwa sesuatu harus (tidak harus) dilakukan, atau boleh (tidak boleh) dilakukan', 'icon' => 'teal road'];
              $data_card->pengaturan = ['header' => 'Pengaturan', 'meta' => 'pengaturan APBD', 'description' => 'ketentuan yang dengan sendirinya memiliki suatu makna normatif; ketentuan yang menyatakan bahwa sesuatu harus (tidak harus) dilakukan, atau boleh (tidak boleh) dilakukan', 'icon' => 'teal road'];
              $data_card->sumber_dana = ['header' => 'Sumber Dana', 'meta' => 'Sumber dana kegiatan', 'description' => 'Klasifikasi, Kodefikasi, dan Nomenklatur Sumber Pendanaan masing-masing kelompok dana meliputi pengawasan/control, akuntabilitas/accountability dan transparansi/transparency (CAT).', 'icon' => 'purple money'];
              $data_card->akun_belanja = ['header' => 'Akun Belanja', 'meta' => 'Aplikasi Standar Satuan Harga (SSH)', 'description' => 'sebagai perhitungan kebutuhan biaya Tenaga Kerja, bahan, dan peralatan', 'icon' => 'teal money bill alternate outline'];
              $data_card->sub_keg = ['header' => 'Sub Kegiatan', 'meta' => 'SUB KEGIATAN', 'description' => 'sebagai perhitungan kebutuhan biaya Tenaga Kerja, bahan, dan peralatan', 'icon' => 'violet users cog'];
              $data_card->aset = ['header' => 'Neraca', 'meta' => 'neraca/aset', 'description' => 'sebagai perhitungan kebutuhan biaya Tenaga Kerja, bahan, dan peralatan', 'icon' => 'violet users cog'];
              $data_card->mapping = ['header' => 'Mapping', 'meta' => 'mapping neraca dan akun', 'description' => 'mapping (pemetaan) neraca dengan akun belanja', 'icon' => 'violet users cog'];
              $data_card->wilayah = ['header' => 'Kode Wilayah', 'meta' => 'kode wilayah indonesia', 'description' => 'identitas wilayah administrasi pemerintahan', 'icon' => 'violet users cog'];
              $data_card->organisasi = ['header' => 'Organisasi', 'meta' => 'kode organisasi SKPD', 'description' => ' organisasi atau lembaga pada Pemerintah Daerah yang bertanggung jawab kepada Kepala Daerah dalam rangka penyelenggaraan pemerintahan', 'icon' => 'violet users cog'];
              $data_card->sbu = ['header' => 'SBU', 'meta' => 'Standar Biaya Umum (SBU)', 'description' => 'harga satuan setiap unit non barang/jasa seperti honorarium dan perjalanan dinas yang berlaku di suatu daerah', 'icon' => 'violet users cog'];
              $data_card->asb = ['header' => 'ASB', 'meta' => 'Analisis Standar Belanja (ASB)', 'description' => 'penilaian kewajaran atas beban kerja dan biaya yang digunakan untuk melaksanakan suatu kegiatan', 'icon' => 'violet users cog'];
              $data_card->ssh = ['header' => 'SSH', 'meta' => 'Standar Satuan Harga (SSH)', 'description' => 'harga satuan setiap unit barang/jasa yang berlaku disuatu daerah', 'icon' => 'violet users cog'];
              $data_card->hspk = ['header' => 'HSPK', 'meta' => 'Harga Satuan Pokok Kegiatan (HSPK)', 'description' => 'merupakan harga komponen kegiatan fisik/non fisik melalui analisis yang distandarkan untuk setiap jenis komponen kegiatan dengan menggunakan SSH sebagai elemen penyusunannya', 'icon' => 'violet users cog'];
              $data_card->tujuan_sasaran_renstra = ['header' => 'TUJUAN DAN SASARAN RENSTRA', 'meta' => 'renstra', 'description' => 'tujuan dan sasaran renstra', 'icon' => 'violet users cog'];
              $data_card->renstra = ['header' => 'RENSTRA', 'meta' => 'Rencana Strategis (Renstra)', 'description' => 'dokumen perencanaan suatu organisasi yang berorientasi pada hasil yang ingin dicapai', 'icon' => 'violet users cog'];
              $data_card->sub_keg_renja = ['header' => 'SUB KEGIATAN RENJA', 'meta' => 'renja', 'description' => 'renja', 'icon' => 'violet users cog'];
              $data_card->renja = ['header' => 'RENJA', 'meta' => 'renja', 'description' => 'renja', 'icon' => 'violet users cog'];
              $data_card->renja_p = ['header' => 'RENJA PERUBAHAN', 'meta' => 'renja perubahan', 'description' => 'renja perubahan', 'icon' => 'violet users cog'];
              $data_card->sub_keg_dpa = ['header' => 'SUB KEGIATAN DPA', 'meta' => 'dpa', 'description' => 'dpa', 'icon' => 'violet users cog'];
              $data_card->dpa = ['header' => 'DPA', 'meta' => 'dpa', 'description' => 'dpa', 'icon' => 'violet users cog'];
              $data_card->dppa = ['header' => 'DPPA', 'meta' => 'dpa perubahan', 'description' => 'dpa perubahan', 'icon' => 'violet users cog'];
              $data_card->daftar_kontrak = ['header' => 'KONTRAK', 'meta' => 'daftar kontrak', 'description' => 'kontraktual/swakelola', 'icon' => 'violet users cog'];
              if ($type_user == 'admin') {
                $data_card->realisasi = ['header' => 'REALISASI', 'meta' => 'daftar realisasi', 'description' => 'realisasi', 'icon' => 'money cog'];
                $data_card->asn = ['header' => 'Aparatur Sipil Negara', 'meta' => 'ASN', 'description' => 'Aparatur Sipil Negara', 'icon' => 'user plus'];
                $data_card->satuan = ['header' => 'Satuan', 'meta' => 'Ukuran suatu besaran', 'description' => 'Satuan atau satuan ukur atau unit digunakan untuk memastikan kebenaran pengukuran', 'icon' => 'user plus'];
                $data_card->divisi = ['header' => 'Divisi', 'meta' => 'Task HSP', 'description' => 'Pembagian divisi pekerjaan', 'icon' => 'teal users cog'];
                $data_card->chat = ['header' => 'Ruang Chating', 'meta' => 'Chat, message', 'description' => 'ruang di peruntukkan chat', 'icon' => 'comments outline'];
                $data_card->rekanan = ['header' => 'Rekanan', 'meta' => 'Rekanan', 'description' => 'data rekanan yang terdaftar', 'icon' => 'users'];
              }
              foreach ($data_card as $key => $value) {
                if ($type_user == 'user') {
                  $button = '<button class="ui fluid orange button" name="del_row" jns="del_proyek" tbl="' . $key . '">Hapus Dokumen</button>';
                  $nButton = '';
                } else {
                  $button = '<div class="ui three buttons"><div class="ui teal button" name="del_row" jns="' . $key . '" tbl="dell_all">All</div>
                                    <button class="ui blue button" name="del_row" jns="del_proyek" tbl="' . $key . '">Dokumen</button>
                                    <div class="ui violet button" name="del_row" jns="reset" tbl="' . $key . '">Reset</div></div>';
                  $nButton = 'three';
                }
                echo '<div class="card">
                                <div class="content"><i class="right floated large ui bordered colored ' . $value['icon'] . ' icon"></i>
                                    <div class="header">' . $value['header'] . '</div>
                                    <div class="meta">' . $value['meta'] . '</div>
                                    <div class="description">' . $value['description'] . '</div>
                                </div><div class="extra content">' . $button . ' </div></div>';
              }
              ?>
            </div>
          </div>
        </div>
      </div>

      <!-- ============== -->
      <!-- tab_pengaturan -->
      <!-- ============== -->
      <div class="ui tab basic segment" data-tab="atur_satu">
        <div class="ui stackable grid">
          <div class="two wide left column">
            <div class="ui red secondary vertical fluid pointing menu">
              <a class="item inayah" data-tab="pengaturan" tbl="pengaturan">
                Tahun Anggaran
              </a>
              <a class="item inayah" data-tab="atur" tbl="users">
                Users
              </a>
            </div>
          </div>
          <div class="fourteen wide stretched right column">
            <div class="ui tab basic container" data-tab="pengaturan">
              <div class="ui teal inverted segment top attached">
                <h2 class="ui header left">
                  <i class="settings icon"></i>
                  <div class="content">
                    Sesendok Settings
                    <div class="sub header">Manage your preferences</div>
                  </div>
                </h2>
              </div>
              <form class="ui form long scrolling segment attached" jns="add" tbl="pengaturan" name="form_pengaturan">
                <div class="two fields">
                  <div class="field">
                    <label>Tahun</label>
                    <div class="ui calendar year" name="tahun">
                      <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" placeholder="Tahun Anggaran" name="tahun" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="field">
                    <label>Renstra</label>
                    <div class="ui calendar year" name="tahun_renstra">
                      <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" placeholder="Tahun Renstra" name="tahun_renstra" readonly>
                      </div>
                    </div>
                  </div>
                </div>
                <h3 class="ui dividing header">Persetujuan Dokumen</h3>
                <table class="ui very basic table">
                  <thead>
                    <tr>
                      <th></th>
                      <th class="collapsing"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>RENSTRA</td>
                      <td>
                        <div class="ui basic icon buttons">
                          <button class="ui button" name="jalankan" jns="kunci" tbl="renstra" type="button" data-tooltip="kunci" data-position="top center"><i class="lock icon"></i></button>
                          <button class="ui button" name="jalankan" jns="unkunci" tbl="renstra" type="button" data-tooltip="buka kunci" data-position="top center"><i class="unlock icon"></i></button>
                          <button class="ui button" name="jalankan" jns="setujui" tbl="renstra" type="button" data-tooltip="setujui" data-position="top center"><i class="check square icon"></i></button>
                          <button class="ui button" name="jalankan" jns="unsetujui" tbl="renstra" type="button" data-tooltip="batal setujui" data-position="top center"><i class="edit icon"></i></button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>RENJA</td>
                      <td>
                        <div class="ui basic icon buttons">
                          <button class="ui button" name="jalankan" jns="kunci" tbl="renja" type="button"><i class="lock icon"></i></button>
                          <button class="ui button" name="jalankan" jns="unkunci" tbl="renja" type="button"><i class="unlock icon"></i></button>
                          <button class="ui button" name="jalankan" jns="setujui" tbl="renja" type="button"><i class="check square icon"></i></button>
                          <button class="ui button" name="jalankan" jns="unsetujui" tbl="renja" type="button"><i class="edit icon"></i></button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>D P A</td>
                      <td>
                        <div class="ui basic icon buttons">
                          <button class="ui button" name="jalankan" jns="kunci" tbl="dpa" type="button"><i class="lock icon"></i></button>
                          <button class="ui button" name="jalankan" jns="unkunci" tbl="dpa" type="button"><i class="unlock icon"></i></button>
                          <button class="ui button" name="jalankan" jns="setujui" tbl="dpa" type="button"><i class="check square icon"></i></button>
                          <button class="ui button" name="jalankan" jns="unsetujui" tbl="dpa" type="button"><i class="edit icon"></i></button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>RENJA PERUBAHAN</td>
                      <td>
                        <div class="ui basic icon buttons">
                          <button class="ui button" name="jalankan" jns="kunci" tbl="renja_p" type="button"><i class="lock icon"></i></button>
                          <button class="ui button" name="jalankan" jns="unkunci" tbl="renja_p" type="button"><i class="unlock icon"></i></button>
                          <button class="ui button" name="jalankan" jns="setujui" tbl="renja_p" type="button"><i class="check square icon"></i></button>
                          <button class="ui button" name="jalankan" jns="unsetujui" tbl="renja_p" type="button"><i class="edit icon"></i></button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>D P P A</td>
                      <td>
                        <div class="ui basic icon buttons">
                          <button class="ui button" name="jalankan" jns="kunci" tbl="dppa" type="button"><i class="lock icon"></i></button>
                          <button class="ui button" name="jalankan" jns="unkunci" tbl="dppa" type="button"><i class="unlock icon"></i></button>
                          <button class="ui button" name="jalankan" jns="setujui" tbl="dppa" type="button"><i class="check square icon"></i></button>
                          <button class="ui button" name="jalankan" jns="unsetujui" tbl="dppa" type="button"><i class="edit icon"></i></button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <h3 class="ui dividing header">Jadwal Penganggaran</h3>
                <div class="two fields">
                  <div class="field">
                    <label>Awal Renstra</label>
                    <div class="ui inverted calendar datetime startcal" name="awal_renstra">
                      <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" placeholder="Awal" name="awal_renstra" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="field">
                    <label>Akhir Renstra</label>
                    <div class="ui inverted calendar datetime endcal" name="akhir_renstra">
                      <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" placeholder="Akhir" name="akhir_renstra" readonly>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="two fields">
                  <div class="field">
                    <label>Awal Renja</label>
                    <div class="ui inverted calendar datetime startcal" name="awal_renja">
                      <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" placeholder="Awal" name="awal_renja" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="field">
                    <label>Akhir Renja</label>
                    <div class="ui inverted calendar datetime endcal" name="akhir_renja">
                      <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" placeholder="Akhir" name="akhir_renja" readonly>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="two fields">
                  <div class="field">
                    <label>Awal DPA</label>
                    <div class="ui inverted calendar datetime startcal" name="awal_dpa">
                      <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" placeholder="Awal" name="awal_dpa" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="field">
                    <label>Akhir DPA</label>
                    <div class="ui inverted calendar datetime endcal" name="akhir_dpa">
                      <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" placeholder="Akhir" name="akhir_dpa" readonly>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="two fields">
                  <div class="field">
                    <label>Awal Renja Perubahan</label>
                    <div class="ui inverted calendar datetime startcal" name="awal_renja_p">
                      <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" placeholder="Awal" name="awal_renja_p" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="field">
                    <label>Akhir Renja Perubahan</label>
                    <div class="ui inverted calendar datetime endcal" name="akhir_renja_p">
                      <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" placeholder="Akhir" name="akhir_renja_p" readonly>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="two fields">
                  <div class="field">
                    <label>Awal DPPA</label>
                    <div class="ui inverted calendar datetime startcal" name="awal_dppa">
                      <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" placeholder="Awal" name="awal_dppa" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="field">
                    <label>Akhir DPPA</label>
                    <div class="ui inverted calendar datetime endcal" name="akhir_dppa">
                      <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" placeholder="Akhir" name="akhir_dppa" readonly>
                      </div>
                    </div>
                  </div>
                </div>
                <h4 class="ui horizontal divider header">
                  <i class="tag icon"></i>
                  Peraturan yang digunakan
                </h4>
                <div class="field">
                  <label>Anggaran</label>
                  <div class="ui fluid search selection aturan dropdown" name="aturan_anggaran">
                    <input type="hidden" name="aturan_anggaran">
                    <i class="dropdown icon"></i>
                    <div class="default text">Anggaran</div>
                    <div class="menu">
                    </div>
                  </div>
                </div>
                <div class="field">
                  <label>Pengadaan Barang/Jasa</label>
                  <div class="ui fluid search selection aturan dropdown" name="aturan_pengadaan">
                    <input type="hidden" name="aturan_pengadaan">
                    <i class="dropdown icon"></i>
                    <div class="default text">aturan pengadaan</div>
                    <div class="menu">
                    </div>
                  </div>
                </div>
                <div class="field">
                  <label>Organisasi</label>
                  <div class="ui fluid search selection aturan dropdown" name="aturan_organisasi">
                    <input type="hidden" name="aturan_organisasi">
                    <i class="dropdown icon"></i>
                    <div class="default text">organisasi</div>
                    <div class="menu">
                    </div>
                  </div>
                </div>
                <div class="field">
                  <label>Akun Belanja</label>
                  <div class="ui fluid search selection aturan dropdown" name="aturan_akun">
                    <input type="hidden" name="aturan_akun">
                    <i class="dropdown icon"></i>
                    <div class="default text">Akun Belanja</div>
                    <div class="menu">
                    </div>
                  </div>
                </div>
                <div class="field">
                  <label>Sumber Dana</label>
                  <div class="ui fluid search selection aturan dropdown" name="aturan_sumber_dana">
                    <input type="hidden" name="aturan_sumber_dana">
                    <i class="dropdown icon"></i>
                    <div class="default text">Sumber Dana</div>
                    <div class="menu">
                    </div>
                  </div>
                </div>
                <div class="field">
                  <label>Sub Kegiatan</label>
                  <div class="ui fluid search selection aturan dropdown" name="aturan_sub_kegiatan">
                    <input type="hidden" name="aturan_sub_kegiatan">
                    <i class="dropdown icon"></i>
                    <div class="default text">Sub Kegiatan</div>
                    <div class="menu">
                    </div>
                  </div>
                </div>
                <div class="field">
                  <label>SSH</label>
                  <div class="ui fluid search selection aturan dropdown" name="aturan_ssh">
                    <input type="hidden" name="aturan_ssh">
                    <i class="dropdown icon"></i>
                    <div class="default text">SSH</div>
                    <div class="menu">
                    </div>
                  </div>
                </div>
                <div class="field">
                  <label>HSPK</label>
                  <div class="ui fluid search selection aturan dropdown" name="aturan_hspk">
                    <input type="hidden" name="aturan_hspk">
                    <i class="dropdown icon"></i>
                    <div class="default text">HSPK</div>
                    <div class="menu">
                    </div>
                  </div>
                </div>
                <div class="field">
                  <label>ASB</label>
                  <div class="ui fluid search selection aturan dropdown" name="aturan_asb">
                    <input type="hidden" name="aturan_asb">
                    <i class="dropdown icon"></i>
                    <div class="default text">ASB</div>
                    <div class="menu">
                    </div>
                  </div>
                </div>
                <div class="field">
                  <label>SBU</label>
                  <div class="ui fluid search selection aturan dropdown" name="aturan_sbu">
                    <input type="hidden" name="aturan_sbu">
                    <i class="dropdown icon"></i>
                    <div class="default text">SBU</div>
                    <div class="menu">
                    </div>
                  </div>
                </div>
                <div class="field">
                  <label>Kode OPD Tampilkan</label>
                  <div class="ui fluid search selection dropdown" name="id_opd_tampilkan">
                    <input type="hidden" name="id_opd_tampilkan" non_data>
                    <i class="dropdown icon"></i>
                    <div class="default text">organisasi</div>
                    <div class="menu">
                    </div>
                  </div>
                </div>

                <div class="field"><label>Keterangan</label><textarea name="keterangan" rows="4"></textarea>
                </div>
                <div class="field"><label></label>
                  <div class="ui toggle checkbox"><input type="checkbox" name="disable" non_data=""><label>Non
                      Aktif</label></div>
                </div>
                <div class="ui icon success message"><i class="check icon"></i>
                  <div class="content">
                    <div class="header">Form sudah lengkap</div>
                    <p>anda bisa submit form</p>
                  </div>
                </div>
                <div class="ui error message"></div>
                <button style="display: none;" type="submit" id="form-atur"></button>
              </form>
              <div class="ui segment left actions bottom attached">
                <label class="ui green button" for="form-atur" tabindex="0">Simpan</label>
              </div>
            </div>
            <div class="ui tab basic" data-tab="atur" tbl="">
              <table class="ui table insert">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Name</th>
                    <th>Name</th>
                    <th>Name</th>
                    <th>Name</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td data-label="Name">James</td>
                    <td data-label="Name">James</td>
                    <td data-label="Name">James</td>
                    <td data-label="Name">James</td>
                    <td data-label="Name">James</td>
                  </tr>
                </tbody>
                <tfoot>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- ============== -->
      <!-- wallchat -->
      <!-- ============== -->
      <div class="ui tab basic segment" data-tab="wallchat">
      </div>
      <!-- ============== -->
      <!-- profil -->
      <!-- ============== -->
      <div class="ui tab basic segment" data-tab="profil">
        <div class="ui stackable grid container">
          <div class="four wide column">
            <div class="ui special centered fluid card">
              <div class="content">
                <div class="right floated meta">14h</div>
                <img class="ui avatar image" src="img/avatar/large/elliot.jpg" onerror="imgsrc(this)"><span name="nama">Alwi Mansyur</span>
              </div>
              <div class="blurring dimmable image">
                <div class="ui dimmer">
                  <div class="content">
                    <div class="center">
                      <button for="directupload1" name="direct" type="button" id_row="" jns="upload" tbl="user" dok="photo" class="ui inverted icon button" accept=".jpg,.png,.jpeg,.img">
                        <i class="file icon"></i>ganti profil
                      </button>
                    </div>
                  </div>
                </div>
                <img class="ui medium rounded image" src="img/avatar/large/elliot.jpg" onerror="imgsrc(this)">
              </div>

              <div class="content">
                <span class="right floated">
                  <i class="heart outline like icon"></i>
                  likes
                </span>
                <i class="comment icon"></i>
                comments
              </div>
              <div class="extra content">
                <div class="ui large transparent left icon input">
                  <i class="heart outline icon"></i>
                  <input type="text" placeholder="Add Comment...">
                </div>
              </div>
            </div>
          </div>
          <div class="twelve wide column">
            <form class="ui form profil" name="profil" jns="edit" tbl="profil">
              <div class="two fields">
                <div class="field">
                  <label>Nama Lengkap</label>
                  <input type="text" name="nama" placeholder="Nama Lengkap">
                </div>
                <div class="field">
                  <label>NIP.</label>
                  <input type="text" name="nip" placeholder="NIP">
                </div>
              </div>
              <div class="two fields">
                <div class="field">
                  <label>username</label>
                  <input type="text" name="username" placeholder="username" readonly>
                </div>
                <div class="field">
                  <label>email</label>
                  <input type="text" name="email" placeholder="email">
                </div>
              </div>
              <div class="two fields">
                <div class="field">
                  <label>Tahun Anggaran Aktif</label>
                  <div class="ui calendar year" name="tahun">
                    <div class="ui fluid input left icon">
                      <i class="calendar icon"></i>
                      <input type="text" name="tahun" placeholder="tahun anggaran">
                    </div>
                  </div>
                </div>
                <div class="field">
                  <label>Type User</label>
                  <input type="text" name="type_user" placeholder="Type user" readonly>
                </div>
              </div>
              <div class="two fields">
                <div class="field">
                  <label>Kontak Person</label>
                  <input type="text" name="kontak_person" placeholder="Kontak Person/HP">
                </div>
                <div class="field">
                  <label>Kode OPD</label>
                  <input type="text" name="kd_organisasi" placeholder="Kode OPD" readonly>
                </div>
              </div>
              <div class="field">
                <label>Organisasi</label>
                <input type="text" name="nama_org" placeholder="Nama organisasi" readonly>
              </div>
              <div class="three fields">
                <div class="field">
                  <label>Thema</label>
                  <div class="ui fluid selection dropdown" name="theme">
                    <input type="hidden" name="theme" non_data>
                    <i class="dropdown icon"></i>
                    <div class="default text">theme</div>
                    <div class="menu">
                      <div class="item" value="auto">Auto</div>
                      <div class="item" value="custom">Custom</div>
                    </div>
                  </div>
                </div>
                <div class="field">
                  <label>Warna Tabel</label>
                  <div class="ui fluid search selection dropdown lainnya" name="warna_tbl">
                    <input type="hidden" name="warna_tbl">
                    <div class="default text">warna Tabel</div>
                    <i class="dropdown icon"></i>
                    <div class="menu">
                      <div class="item" value="non">Default</div>
                      <div class="divider"></div>
                      <div class="item" value="red">Merah</div>
                      <div class="item" value="orange">Orange</div>
                      <div class="item" value="yellow">Yellow</div>
                      <div class="item" value="olive">olive</div>
                      <div class="item" value="green">Green</div>
                      <div class="item" value="teal">Teal</div>
                      <div class="item" value="blue">Biru</div>
                      <div class="item" value="violet">Violet</div>
                      <div class="item" value="purple">purple</div>
                      <div class="item" value="pink">Pink</div>
                      <div class="item" value="grey">grey</div>
                      <div class="item" value="black">Hitam</div>
                      <div class="item" value="purple">purple</div>

                    </div>
                  </div>
                </div>
                <div class="field">
                  <label>Font Size</label>
                  <input type="text" name="font_size" readonly>
                </div>
              </div>
              <div class="field">
                <label>Keterangan</label>
                <textarea name="ket" placeholder="Keterangan" rows="2"></textarea>
              </div>
              <div class="ui icon success message"><i class="check icon"></i>
                <div class="content">
                  <div class="header">Form sudah lengkap</div>
                  <p>anda bisa submit form</p>
                </div>
              </div>
              <div class="ui error message"></div>
              <div class="ui hidden divider"></div>
              <button class="ui positive button">
                Simpan
              </button>
            </form>

          </div>
        </div>
      </div>
      <!-- =========================-->
      <!-- =========================-->
      <!-- =========================-->
      <div class="ui basic modal info">
        <div class="ui icon header" id="kop_notifikasi"><i class="archive icon"></i>Archive Old Messages </div>
        <div class="content">
          <div class="ui center aligned stackable container grid" id="conten_notifikasi">
            <p>ini di isi oleh ajax</p>
          </div>
        </div>
        <div class="actions">
          <div class="ui green ok inverted button center aligned"><i class="checkmark icon"></i>OK </div>
        </div>
      </div>
      <!-- modal general -->
      <div class="ui modal couple mdl_general" name="mdl_general">
        <h5 class="ui header dash_header">
          <i class="big icons">
            <i class="teal utensils icon"></i>
            <i class="bottom right white corner add icon"></i>
          </i></i>
          <div class="content">siSendok<div class="sub header" id="header_mdl">Tambah Data</div>
          </div>
        </h5>
        <form class="ui form scrolling content" name="form_modal">form umum ji
          <div class="ui icon success message">
            <i class="check icon"></i>
            <div class="content">
              <div class="header">Form sudah lengkap</div>
              <p>anda bisa submit form</p>
            </div>
          </div>
          <div class="ui error message"></div>
        </form>
        <div class="actions">
          <div class="ui red cancel button"><i class="remove icon"></i>Cancel </div>
          <div class="ui green ok button add"><i class="checkmark icon"></i>OK </div>
        </div>
      </div>
      <!-- modal kedua -->
      <div class="ui kedua couple modal" name="mdl_kedua">
        <h5 class="ui header dash_header"><i class="users cog icon"></i>
          <div class="content">siSendok <div class="sub header">Komponen</div>
          </div>
        </h5>
        <form class="ui form scrolling content" name="form_modal_kedua">
          <div class="ui icon success message">
            <i class="check icon"></i>
            <div class="content">
              <div class="header">Form sudah lengkap</div>
              <p>anda bisa submit form</p>
            </div>
          </div>
          <div class="ui error message"></div>
        </form>
        <div class="actions">
          <div class="ui red cancel button"><i class="remove icon"></i>Cancel </div>
          <div class="ui green ok button add"><i class="checkmark icon"></i>OK </div>
        </div>
      </div>
      <!-- jangan dihapus file button <label>  -->
      <form hidden action="script/writer_xlsx" method="post" id="form_ungguh_dok">
        <input hidden type="text" name="jenis">
        <input hidden type="text" name="tbl">
        <input hidden type="text" name="dok">
        <input hidden type="text" name="nabiila">
      </form>
      <!-- jangan dihapus file button <label>  -->
      <input type="file" id="invisibleupload1" class="ui invisible file input" name="file_invisible">
      <!-- jangan dihapus untuk download hasil  -->
      <a name="tempat_download" hidden href="" target="_blank"></a>
      <!-- jmodal hapus  -->
      <div class="ui basic mini inverted hapus modal">
        <div class="ui icon header" id="kop_notif_hapus"></div>
        <div id="content_notif" class="ui center content aligned"></div>
        <div class="ui actions center aligned container grid">
          <div class="ui green basic cancel button"><i class="remove icon"></i>No </div>
          <div class="ui red basic ok button"><i class="checkmark icon"></i>Yes </div>
        </div>
      </div>
    </div>
  </div>
  <!-- jangan dihapus untuk upload file di dimmer langsung eksekusi  -->
  <form class="ui form" name="form_upload" hidden>
    <input type="file" id="directupload1" class="ui invisible file input">
  </form>

  <!-- <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>-->
  <script src="<?= BASEURL; ?>vendor/jquery-3.7.1.min.js"></script>
  <script src="<?= BASEURL; ?>vendor/node_modules/fomantic-ui/dist/semantic.js"></script>
  <script src="<?= BASEURL; ?>vendor/node_modules/fomantic-ui/dist/components/form.js"></script>
  <script src="<?= BASEURL; ?>js/accounting.js"></script>
  <script src="<?= BASEURL; ?>js/mathbiila.min.js"></script>
  <script src="<?= BASEURL; ?>js/jqmath-etc-0.4.6.min.js"></script>
  <script src="<?= BASEURL; ?>js/xlsx.js"></script>
  <script src="<?= BASEURL; ?>vendor/node_modules/crypto-js/crypto-js.js"></script>
  <script src="<?= BASEURL; ?>js/Encryption.js"></script>
  <script type="text/javascript">
    var theme = '<?= $theme; ?>';
    var warna_tbl = '<?= $_SESSION["user"]["warna_tbl"]; ?>';
    const halamanDefault = '<?= $keyEnc; ?>';
  </script>
  <script>
    const BASEURL = '<?= BASEURL; ?>';
  </script>
  <script src="<?= BASEURL; ?>js/index.js"></script>
</body>

</html>