<?php
// define('LOCALURL', 'http://localhost/sesendokneo/');
// define('BASEURL', 'http://localhost/sesendokneo/public/');
// define('pathURL', 'http://localhost/sesendokneo/public/');
define('LOCALURL', 'http://192.168.1.6:8085/');
define('BASEURL', 'http://192.168.1.6:8085/public/');
define('pathURL', 'http://192.168.1.6:8085/public/');
$msx = rand(32, 64);
$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ()<>?{}#$&=-*^@';
$keyEnc = substr(str_shuffle($permitted_chars), 0, $msx);
define('KEY_ENCRYPT', $keyEnc);
define('PAJAK', 11);
define('hasilServer', [
    1 => 'berhasil dijalankan',
    2 => 'berhasil tambah data',
    3 => 'berhasil update',
    4 => 'berhasil delete',
    5 => 'berhasil select',
    6 => 'berhasil insert/data ganda(berhasil update)',
    7 => 'berhasil impor file',
    8 => 'berhasil import file dengan catatan',
    9 => 'data sudah ada',
    10 => 'berhasil validasi',
    11 => 'berhasil data posting',
    12 => 'berhasil data jenis tabel',
    29 => 'gagal validasi',
    30 => 'gagal tambah data/data ganda',
    31 => 'gagal tambah data/berhasil update',
    32 => 'gagal tambah data',
    33 => 'gagal update',
    34 => 'gagal update/berhasil tambah data',
    35 => 'gagal delete',
    36 => 'gagal select/tidak ditemukan',
    37 => 'gagal tambah data/data ganda',
    38 => 'gagal import file',
    39 => 'gagal menentukan jenis data',
    40 => 'proses anda tidak dikenali',
    41 => 'data tidak ditemukan',
    45 => 'tabel yang digunakan tidak ditemukan',
    46 => 'gagal run',
    47 => 'data telah ada',
    48 => 'data telah ada dan telah diupdate',
    49 => 'data telah diproses kembali',
    50 => 'kode bisa digunakan',
    55 => 'telah melewati validasi',
    56 => 'belum ada dokumen pekerjaan yang aktif',
    70 => 'data belum lengkap',
    701 => 'File Tidak Lengkap',
    702 => 'file yang ada terlalu besar',
    703 => 'type file tidak sesuai',
    704 => 'Gagal Upload',

    100 => 'Continue', #server belum menolak header permintaan dan meminta klien untuk mengirim body (isi/konten) pesan
    101  => 'Switching protocols',
    //File
    
    200  => 'Ok',
    201  => 'Created',
    202  => 'Accepted',
    203  => 'Non-Authoritative Information',
    204  => 'No Content',
    205  => 'Reset Content',
    206  => 'Partial Content',
    300  => 'Multiple Choices',
    301  => 'Moved Permanently',
    302  => 'Found',
    303  => 'See Other',
    304  => 'Not Modified',
    305  => 'Use Proxy',
    307  => 'Temporary Redirect',
    400  => 'Bad Request',
    401  => 'Unauthorized', #pengunjung website tidak memiliki hak akses untuk file / folder yang diproteksi oleh password (kata kunci).
    402  => 'Payment Required',
    403  => 'Forbidden', #pengunjung sama sekali tidak dapat mengakses ke folder tujuan. Angka 403 muncul disebabkan oleh kesalahan pengaturan hak akses pada folder.
    404  => 'Not Found', #bahwa file / folder yang diminta, tidak ditemukan didalam database pada suatu website.
    405  => 'Method Not Allowed',
    406  => 'Not Acceptable', #pernyataan bahwa permintaan dari browser tidak dapat dipenuhi oleh server.
    407  => 'Proxy Authentication Required',
    408  => 'Request Timeout',
    409  => 'Conflict',
    410  => 'Gone',
    411  => 'Length Required',
    412  => 'Precondition Failed',
    413  => 'Request Entity Too Large',
    414  => 'Request-URI Too Long',
    415  => 'Unsupported Media Type',
    416  => 'Requested Range Not Suitable',
    417  => 'Expectation Failed',
    500  => 'Internal Server Error', #menyatakan bahwa ada kesalahan konfigurasi pada akun hosting.
    501  => 'Not Implemented',
    502  => 'Bad Gateway',
    503  => 'Service Unavailable',
    504  => 'Gateway Timeout',
    505  => 'HTTP Version Not Supported',
    509  => 'Bandwidth Limit Exceeded', #penggunaan bandwidth pada account hosting sudah melebihi quota yang ditetapkan untuk akun hosting Anda
    //Bahasa Gaul

    530  => 'I Miss You', #I Miss You dalam bahasa Mandarin adalah Wo Xiang Ni
    831  => 'I Love You', #Memiliki jumlah 8 huruf dalam kalimat "I Love You",Kemudian ada 3 jumlah total kata dalam frasa "I Love You",Dan 1 memiliki satu makna, yaitu "Aku Cinta Kamu"
    24434   => 'Sudahkah anda sholat', #diambil dari jumlah rakaat di setiap Sholat lima waktu atau shalat fardhu
    1432  => 'I Love You Too', #1 artinya I, 4 artinya Love, 3 artinya You, 2 artinya Too. bisa diberikan untuk pasangan kekasih.
    224  => 'I Love You Too' #Artinya adalah Today, Tomorrow dan Forever.Angka 2 artinya two yang artinya twoday,today,
]);

