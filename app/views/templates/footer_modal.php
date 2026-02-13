<div class="ui inverted vertical footer segment">
    <div class="ui container">
        <div class="ui stackable inverted divided equal height stackable grid">
            <div class="three wide column">
                <h4 class="ui inverted header">About</h4>
                <div class="ui inverted link list">
                    <a href="#" class="item">Sitemap</a>
                    <a href="#" class="item">Contact Us</a>
                    <a href="#" class="item">Ceremonies</a>
                </div>
            </div>
            <div class="three wide column">
                <h4 class="ui inverted header">Services</h4>
                <div class="ui inverted link list">
                    <a href="#" class="item">Pelayanan Publik</a>
                    <a href="#" class="item">DNA FAQ</a>
                    <a href="#" class="item">How To Access</a>
                </div>
            </div>
            <div class="seven wide column">
                <h4 class="ui inverted header">Footer Header</h4>
                <p>Extra space for a call to action inside the footer that could help re-engage users.</p>
            </div>
        </div>
    </div>
</div>
</div>
<!-- modal pemberitahuan notif-->
<div class="ui basic modal info">
    <div class="ui icon header" id="kop_notifikasi">
        <i class="archive icon"></i>
        Archive Old Messages
    </div>
    <div class="content">
        <div class="ui center aligned stackable container grid" id="conten_notifikasi">
            <p></p>
        </div>
    </div>
    <div class="actions">
        <div class="ui green ok inverted button center aligned">
            <i class="checkmark icon"></i>
            OK
        </div>
    </div>
</div>

<!-- modal login-->
<div class="ui basic tiny modal login">
    <div class="ui middle aligned center aligned grid">
        <div class="column">
            <form class="ui large form login content">
                <div class="ui stacked segment">
                    <h2 class="ui teal image header"><i class="home icon"></i>
                        <div class="content"> Login seSendok
                            <div class="sub header"> Manajemen Anggaran</div>
                        </div>
                    </h2>
                    <div class="field">
                        <div class="ui left icon input">
                            <i class="user icon"></i>
                            <input type="text" name="username" placeholder="username or E-mail address">
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui right labeled left icon input">
                            <i class="lock icon"></i>
                            <input type="password" name="password" placeholder="Password">
                            <a onclick="changePassView()" class="ui tag label">
                                <i class="eye icon"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ui error message"></div>
                </div>
            </form>
            <div class="ui message"> Buat akun baru ? <a href="register" target=”_blank”>Buat akun</a></div>
            <div class="actions">
                <button class="ui black deny button">
                    Batal
                </button>
                <button class="ui positive right labeled icon button" type="submit" name="login" value="anggaran">
                    Login
                    <i class="checkmark icon"></i>
                </button>
            </div>

        </div>
    </div>
</div>
<!-- modal register-->
<div class="ui basic modal tiny register">
    <div class="ui middle aligned center aligned grid">
        <div class="column">
            <form class="ui form register" name="form_modal">
                <div class="ui stacked segment">
                    <h2 class="ui teal image header"><i class="home icon"></i>
                        <div class="content"> Register seSendok
                            <div class="sub header"> Manage project </div>
                        </div>
                    </h2>
                    <div class="field">
                        <div class="ui left icon input"> <i class="user icon"></i>
                            <input type="text" name="username" placeholder="username">
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui left icon input"> <i class="envelope open icon"></i>
                            <input type="text" name="email" placeholder="E-mail address">
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui left icon input"> <i class="user icon"></i>
                            <input type="text" name="nama" placeholder="Nama Lengkap">
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui left icon input"> <i class="list icon"></i>
                            <input type="text" name="nip" placeholder="NIP">
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui left icon input"> <i class="fax icon"></i>
                            <input type="text" name="kontak_person" placeholder="kontak person">
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui left icon input"> <i class="home icon"></i>
                            <input type="text" name="alamat" placeholder="alamat">
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui right labeled left icon input">
                            <i class="lock icon"></i>
                            <input type="password" name="password" placeholder="Password">
                            <a onclick="changePassView()" class="ui tag label">
                                <i class="eye icon"></i>
                            </a>
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui fluid search selection wilayah dropdown ajx">
                            <input type="hidden" name="kd_wilayah">
                            <i class="dropdown icon"></i>
                            <div class="default text"><i class="fax icon"></i>Wilayah</div>
                            <div class="menu">

                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui fluid search selection organisasi dropdown ajx">
                            <input type="hidden" name="organisasi">
                            <i class="dropdown icon"></i>
                            <div class="default text"><i class="fax icon"></i>Organisasi</div>
                            <div class="menu">

                            </div>
                        </div>
                    </div>

                    <div class="inline field">
                        <div class="ui checkbox">
                            <input type="checkbox" name="setuju" checked>
                            <label href="dok/Basic Price 1 Header.xlsx" target="_blank">Saya setuju dengan <a href="dok/syarat_dan_ketentuan_AHSP.pdf" target="_blank">ketentuan</a> penggunaan aplikasi ini</label>
                        </div>
                    </div>

                </div>
                <div class="ui error message"></div>
                <div class="actions">
                    <div class="ui red basic cancel inverted button">
                        <i class="remove icon"></i>
                        Batal
                    </div>
                    <div class="ui green ok inverted button" name="register" value="Daftar">
                        <i class="checkmark icon"></i>
                        Register
                    </div>
                </div>
            </form>

        </div>
    </div>

</div>