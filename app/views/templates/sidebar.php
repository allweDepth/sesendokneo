<!-- sidebar dan tab home -->
<div class="ui inverted left fixed vertical sidebar menu">
        <div class="item">
            <h2 class="ui inverted center red aligned icon header dash_header"><i class="circular colored blue building icon"></i>
                <div class="content">AHSP <span id="kopku" style="color: darkcyan!important;font-style: italic"></span>
                    <div class="sub header">Manage Project</div>
                </div>
            </h2>
        </div>
        <div class="item">
            <form class="ui form" style="margin: 0;" name="cari_data">
                <div class="ui search suggestion fluid transparent icon inverted input"><input type="text" placeholder="Cari..."><i class="search icon"></i>
                    <div class="results"></div>
                </div><button class="ui button submit" style="display: none;"></button>
            </form>
        </div>
        <a class="item" href="#" data-tab="home"><i class="home icon"></i>Home</a><a class="item" href="#" data-tab="proyek" tbl="get_all_proyek"><i class="folder icon"></i>Dokumen Pekerjaan</a>
        <div class="ui accordion inverted item menu_utama analisa">
            <div class="title item"><i class="dropdown icon"></i><span></span>Analisa AHSP </div>
            <div class="content">
                <a class="item" href="#" data-tab="informasi_umum" tbl="list"><span><i class="toggle on blue icon"></i></span><i class="purple sitemap icon"></i>Informasi Umum</a>
                <a class="item" href="#" data-tab="harga_satuan" tbl="list"><span><i class="toggle on blue icon"></i></span><i class="violet users cog icon"></i>Harga Satuan</a>
                <a class="item" href="#" data-tab="analisa_alat" tbl="list"><span><i class="toggle on blue icon"></i></span><i class="yellow snowplow icon"></i>Analisa Peralatan</a>
                <a class="item" href="#" data-tab="analisa_quarry" tbl="list"><span><i class="toggle on blue icon"></i></span><i class="olive truck monster icon"></i>Analisa Quarry (bahan)</a>
                <a class="item" href="#" data-tab="analisa_bm" tbl="list"><span><i class="toggle on blue icon"></i></span><i class="teal road icon"></i>Analisa Pek. Binamarga</a>
                <a class="item" href="#" data-tab="analisa_ck" tbl="list"><span><i class="toggle on blue icon"></i></span><i class="green city icon"></i>Analisa Pek. Ciptakarya</a>
                <a class="item" href="#" data-tab="analisa_sda" tbl="list"><span><i class="toggle on blue icon"></i></span><i class="blue water icon"></i>Analisa Pek. SDA</a>
            </div>
        </div>
        <a class="item" href="#" data-tab="rab" tbl="get_list"><i class="money icon"></i>Rencana Anggaran Biaya</a>
        <a class="item" href="#" data-tab="schedule" tbl="get_list"><i class="chart bar outline icon"></i>Schedule</a>
        <a class="item" href="#" data-tab="lokasi" tbl="get_list"></i></span><i class="map icon"></i>Lokasi</a>
        <a class="item" href="#" data-tab="monev"><i class="chartline icon"></i>Monev</a>
        <div class="ui accordion inverted item menu_utama">
            <div class="title item"><i class="dropdown icon"></i>Data Umum</div>
            <div class="content">
                <a class="item" href="#" data-tab="satuan" tbl="list"><span><i class="toggle on blue icon"></i></span><i class="user plus icon"></i>Satuan</a>
                <a class="item" href="#" data-tab="rekanan" tbl="get_list"><span><i class="toggle on blue icon"></i></span><i class="users icon"></i>Rekanan</a>
                <a class="item" href="#" data-tab="divisi"><span><i class="toggle on blue icon"></i></span><i class="outdent icon"></i>Divisi</a>
                <a class="item" href="#" data-tab="template"><span><i class="toggle on blue icon"></i></span><i class="file icon"></i>Template</a>
                <a class="item" href="#" data-tab="peraturan"><span><i class="toggle on blue icon"></i></span><i class="book reader icon"></i>Peraturan</a>
            </div>
        </div>
        <a class="item" href="#" data-tab="reset"><i class="erase icon"></i>Reset Tabel</a>
        <?php
        if ($type_user == 'admin') {
            echo '<a class="item" href="#" data-tab="user" tbl="list"><i class="users icon"></i>Users Aplikasi</a>';
        }
        ?>
        <a class="item" href="#" data-tab="wallchat"><i class="comments outline icon"></i>Pesan</a>
        <a class="item" href="#" data-tab="profil" tbl="list"><i class="user icon"></i>Profil</a>
    </div>
    <div class="context example pushable pusher">
        <!-- compact disc,toggle off outline,toggle off,toggle on -->
        <div class="ui basic top attached demo red inverted menu">
            <div class="ui dropdown icon item" id="toggle"><i class="th icon"></i></div>
            <div class="right menu">
                <div class="item">
                    <div class="ui transparent icon inverted input"><input type="text" name="cari_data" id="cari_data" placeholder="Search..."><i class="search link icon"></i></div>
                </div>
                <div class="right red inverted menu">
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
                </div>
                <div class="right red inverted menu">
                    <div class="ui dropdown item lain"><span><i class="inverted teal user icon"></i></span><i class="dropdown icon"></i>
                        <div class="menu"><a class="item" data-tab="wallchat"><i class="circular inverted teal comments outline icon"></i>Pesan</a><a class="item" data-tab="profil"><i class="circular inverted teal qrcode icon"></i>Pengaturan</a><a class="item" onclick="window.location.href='script/logout.php'"><i class="circular inverted teal sign out alternate icon"></i>Log Out</a></div>
                    </div>
                </div>
            </div>

        </div>
        <!-- sticky-->
        <div class="ui sticky">
            <div class="ui icon message dashboard"><i class="home icon"></i>
                <div class="content">
                    <div class="header">DASHBOARD </div>
                    <p>AHSP Pekerjaan Konstruksi</p>
                </div>
            </div>
        </div>
        <!--  stretched masuk pusher <div class="pusher" style="height: calc(100vh - 45px - 70px); overflow-y: auto;">-->
        <div class="ui bottom attached segment pushable" style="height: calc(100vh - 45px - 70px) !important; overflow-y: auto;">
            <!-- flyout -->
            <div class="ui right flyout" style="overflow-y: auto;">
                <i class="close icon"></i>
                <div class="ui header"><i class="folder icon" name="icon_flyout"></i>
                    <div class="content" name="content_flyout">Lengkapi Data </div>
                </div>
                <form class="ui form content" name="form_flyout">
                </form>
                <div class="left actions">
                    <div class="ui red cancel button"><i class="remove icon"></i>Tutup </div>
                    <div class="ui green ok button"><i class="checkmark icon"></i>Submit </div>
                </div>
            </div>

            <!-- pusher <div class="pusher">-->
            <div class="flyout pusher">
                <div class="basic segment">
                    <div class="ui demo page dimmer light">
                        <div class="ui massive text blue elastic loader">Loading...</div>
                    </div>
                </div>
                <!-- tab home -->