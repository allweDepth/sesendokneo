<?php
class DB
{
    // Property untuk koneksi ke database mysql
    private $_host = 'localhost';
    private $_dbname = 'sesendokneo_db';
    private $_username = 'root';
    private $_password = 'rydcat-xYgrav-5jofto';
    // Property internal dari class DB
    private static $_instance = null;
    private $_pdo;
    private $_columnName = "*";
    private $_orderBy = "";
    private $_tambahQuery = "";
    private $_count = 0;
    private $_lastInserId = 0;
    private $_limit = '';
    private $_running;
    private $_hasilServer = hasilServer;
    // Constructor untuk pembuatan PDO Object
    private
    function __construct()
    {
        try {
            $this->_pdo = new PDO(
                'mysql:host=' . $this->_host . ';dbname=' . $this->_dbname,
                $this->_username,
                $this->_password
            );
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            //die("Koneksi / DB bermasalah constr: " . $e->getMessage() . " (" . $e->getCode() . ")");
            $pesan['koneksi'] = "Koneksi / DB bermasalah: " . $e->getMessage() . " (" . $e->getCode() . ")";
            $code = 49;
            $message = $this->_hasilServer[$code];
            $item = array('code' => $code, 'message' => $message);
            $json = array('success' => false, 'data' => $pesan, 'error' => $item);
            echo json_encode($json);
        }
    }
    // Singleton pattern untuk membuat class DB
    public static
    function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }
    // Method dasar untuk menjalankan prepared statement query
    //runQuery( $query, $bindValue = [], [offset,batas])
    public function runQuery($query, $bindValue = [], $limit = [])
    {
        try {
            if (count($limit) > 0) {
                $query .= " LIMIT ?, ?";
            }
            $stmt = $this->_pdo->prepare($query);
            $jumlahArray = count($bindValue);
            // var_dump( 'jumlah arary : ' . $jumlahArray );
            $nilai = 0;
            //  var_dump($query);
            for ($x = 0; $x < $jumlahArray; $x++) {
                $nilai = $x + 1;
                // var_dump($nilai);
                // var_dump($bindValue[$x]);
                $stmt->bindValue($nilai, $bindValue[$x], PDO::PARAM_STR);
            }
            //bindvalue limit jika ada
            if (count($limit) > 0) {
                $nilai = $nilai + 1;
                //var_dump( 'nilai2='.$nilai );
                $stmt->bindValue($nilai, $limit[0], PDO::PARAM_INT); //batas
                $nilai = $nilai + 1;
                if (count($limit) == 2) {
                    $stmt->bindValue($nilai, $limit[1], PDO::PARAM_INT); //offset
                } else {
                    $stmt->bindValue($nilai, 0, PDO::PARAM_INT); //offset
                }
            }
            // var_dump( $query );
            // var_dump( $bindValue);
            // var_dump( $limit );
            $stmt->execute();
            // var_dump( $stmt );
        } catch (PDOException $e) {
            //die( "Koneksi / Query bermasalah: " . $e->getMessage() . " (" . $e->getCode() . ")" );
            $pesan['koneksi'] = "Koneksi / Query bermasalah: " . $e->getMessage() . " (" . $e->getCode() . ")(" . $query . ")(" . json_encode($bindValue) . ")";
            // echo $query;
            //echo json_encode($pesan);
            //coba versi baru
            $code = 49;
            $message = $this->_hasilServer[$code];
            $item = array('code' => $code, 'message' => $message);
            $json = array('success' => false, 'data' => $pesan, 'error' => $item);

            echo json_encode($json);
        }
        return $stmt;
    }
    //run query alternatif tanpa bind
    function runQuery2($query)
    {
        try {
            //var_dump($query);
            //$stmt = $this->_pdo->exec($query);// exec digunakan jika tidak ada bind
            $stmt = $this->_pdo->query($query); // alternatif 2 digunakan jika tidak ada bind dan datanya ditampilkan
            $rows = $stmt->fetchAll(); //data hasil sudah menjadi array
            //var_dump($rows);
            return $rows;
            //$this->closeCursor();
        } catch (PDOException $e) {
            //die( "Koneksi / Query bermasalah: " . $e->getMessage() . " (" . $e->getCode() . ")" );
            $pesan['koneksi'] = "Koneksi / Query2 bermasalah: " . $e->getMessage() . " (" . $e->getCode() . ")(" . $query . ")";
            //echo json_encode($pesan);
            //coba versi baru
            $code = 49;
            $message = $this->_hasilServer[$code];
            $item = array('code' => $code, 'message' => $message);
            $json = array('success' => false, 'data' => $pesan, 'error' => $item);
            echo json_encode($json);
        }
        //return $rows;
        //return $stmt;
        //return true;
    }
    //RESET TABEL 
    public function resetTabel($tableName)
    {
        $query = "TRUNCATE TABLE $tableName";
        return $this->runQuery2($query);
    }
    // Method untuk menampilkan hasil query SELECT sebagai fetchAll (object) tambahan fasilitas limit
    public
    function getQuery($query, $bindValue = [], $limit = [])
    {
        return $this->runQuery($query, $bindValue, $limit)->fetchAll(PDO::FETCH_OBJ);
    }
    // Method untuk menentukan kolom yang akan ditampilkan
    public
    function select($columnName)
    {
        $this->_columnName = $columnName;
        return $this;
    }
    public function select_array($tableName, $condition, $columnName = "*", $limit = [])
    {
        $query = "SELECT $columnName FROM {$tableName} WHERE";
        $dataValues = [];
        foreach ($condition as $key => $val) {
            if (strpos($val[1], "LIKE") !== false) {
                $query .= " {$val[3]} {$val[0]} {$val[1]}";
            } else {
                if (count($val) === 3 && $key === 0) {
                    $query .= " {$val[0]} {$val[1]} ? ";
                } else if (count($val) === 4 && $key > 0) {
                    $query .= " {$val[3]} {$val[0]} {$val[1]} ?";
                }
            }
            array_push($dataValues, $val[2]);
        }
        // var_dump($query);
        // var_dump($dataValues);
        //array $condition[[ 'id', '=', $id_user ],[ 'id', '=', $id_user , 'AND'],...]array pertama 3larik, array selanjutnya 4 larik
        return $this->runQuery($query, $dataValues, $limit)->fetchAll(PDO::FETCH_OBJ);
        // true;
    }
    // Method untuk menentukan urutan hasil tabel (query ORDER BY)
    public
    function orderBy($columnName, $sortType = 'ASC')
    {
        $this->_orderBy = "ORDER BY {$columnName} {$sortType}";
        //var_dump($this->_orderBy);
        return $this;
    }
    public
    function orderByString($string)
    {
        $this->_orderBy = "ORDER BY {$string}";
        //var_dump($this->_orderBy);
        return $this;
    }
    //[[kolomA,ASC],[kolomC,DSC],[kolomC,ASC]]
    public
    function orderByCustom($columnNames)
    {
        $sortType = 'ASC';
        $columSort = '';
        $rowCount = 1;
        $jumlahCount = count($columnNames);
        foreach ($columnNames as $value) {
            if (array_key_exists(1, $value)) {
                $sortType = $value[1];
            } else {
                $sortType = 'ASC';
            }
            if ($rowCount > 1 && $rowCount < $jumlahCount - 1) {
                $columSort += ",";
            }
            if (count($value) > 1) {
                $columSort += " {$value[0]} {$sortType}";
            } else {
                $columSort += " {$value[0]} {$sortType}";
            }
            $rowCount++;
        }
        $this->_orderBy = "ORDER BY {$columSort}";
        return $this;
    }
    // Method utama untuk mengambil isi tabel
    public
    function get($tableName, $condition = "", $bindValue = [])
    {
        //SELECT nama_kolom FROM nama_tabel LIMIT offset, jumlah_baris
        $query = "SELECT {$this->_columnName} FROM {$tableName} {$condition} {$this->_orderBy} {$this->_limit}";
        $this->_columnName = "*";
        $this->_orderBy = "";
        $this->_limit = "";
        // var_dump($query);
        // var_dump($bindValue);
        return $this->getQuery($query, $bindValue);
    }
    // Method untuk mengambil isi tabel dengan kondisi WHERE
    public
    function getWhere($tableName, $condition)
    {
        $queryCondition = "WHERE {$condition[0]} {$condition[1]} ? ";
        return $this->get($tableName, $queryCondition, [$condition[2]]);
    }
    public
    function getWhereCustom($tableName, $condition = [])
    {
        $queryGet = "";
        $jumlahArray = count($condition);
        $dataValues = [];
        for ($x = 0; $x < $jumlahArray; $x++) {
            if (count($condition[$x]) === 3 && $x == 0) { //array pertama
                $queryGet .= "WHERE {$condition[$x][0]} {$condition[$x][1]} ? ";
            } else if (count($condition[$x]) === 4 && $x > 0) {
                $queryGet .= " {$condition[$x][3]} {$condition[$x][0]} {$condition[$x][1]} ? ";
            }
            $dataValues[] = $condition[$x][2];
        }
        //var_dump($queryGet);
        $queryGet .= " {$this->_tambahQuery}";
        return $this->get($tableName, $queryGet, $dataValues);
    }
    public
    function tambahQuery($query)
    {
        $this->_tambahQuery = $query;
        return $this;
    }
    // Method utama untuk mengambil isi tabel where dengen beberapa kondisi
    public
    function getWhereArray($tableName, $condition = [])
    {
        //kondisi ['username', '=', $username]['username', '=', $username,'AND']['username', '=', $username,'OR']...
        $queryGet = "";
        $jumlahArray = count($condition);
        $dataValues = [];
        for ($x = 0; $x < $jumlahArray; $x++) {
            if (count($condition[$x]) === 3 && $x == 0) { //array pertama
                $queryGet .= "WHERE {$condition[$x][0]} {$condition[$x][1]} ? ";
            } else if (count($condition[$x]) === 4 && $x > 0) {
                $queryGet .= " {$condition[$x][3]} {$condition[$x][0]} {$condition[$x][1]} ? ";
            }
            $dataValues[] = $condition[$x][2];
        }
        $query = "SELECT {$this->_columnName} FROM {$tableName} {$queryGet} {$this->_orderBy}";
        //$this->_columnName = "*";
        //$this->_orderBy = "";
        //var_dump($query);
        return $this->getQuery($query, $dataValues);
    }
    // Method untuk mengambil isi tabel dengan kondisi WHERE dan hanya baris pertama saja
    //kita tidak harus menulis index [0] setiap kali ingin menampilkan hasil tabel.
    public
    function getWhereOnce($tableName, $condition)
    {
        $result = $this->getWhere($tableName, $condition);
        if (!empty($result)) {
            return $result[0];
        } else {
            return false;
        }
    }
    public
    function getWhereOnceCustom($tableName, $condition = [])
    {
        $result = $this->getWhereCustom($tableName, $condition);
        if (!empty($result)) {
            return $result[0];
        } else {
            return false;
        }
    }
    // Method untuk mengambil isi tabel dengan pencarian (query LIKE)
    public
    function getLike($tableName, $columnLike, $search)
    {
        $queryLike = "WHERE {$columnLike} LIKE ?";
        return $this->get($tableName, $queryLike, [$search]);
    }
    // Method untuk mengambil isi tabel dengan pencarian (query LIKE) array columnLike multiple kolom
    public
    //[[ 'id' ],[ 'nama kolom', 'AND']]
    function getLike_array($tableName, $search, $limit, $condition)
    { //[]
        $queryLike = "WHERE ";
        $jumlahArray = count($condition);
        $dataValues = [];
        for ($x = 0; $x < $jumlahArray; $x++) {
            if (count($condition[$x]) === 3 && $x == 0) {
                $queryLike .= " {$condition[$x][0]} LIKE ? ";
            } else if (count($condition[$x]) === 4 && $x > 0) {
                $queryLike .= " {$condition[$x][1]} {$condition[$x][0]} LIKE ? ";
            }
            $dataValues[] = $search;
        }
        return $this->get($tableName, $queryLike, $dataValues, $limit);
    }
    // [['id', "LIKE CONCAT('%',?,'%')", $_POST['komponen']], ['kd_wilayah', '= ?', $kd_wilayah, 'AND'],['tahun', '= ?', $tahun, 'AND']]
    function getArrayLike($tableName, $condition)
    {
        $queryArray = "WHERE ";
        $jumlahArray = count($condition);
        $dataValuesCheck = [];
        for ($x = 0; $x < $jumlahArray; $x++) {
            if (count($condition[$x]) === 3 && $x <= 0) {
                $queryArray .= " {$condition[$x][0]} {$condition[$x][1]}";
            } else if (count($condition[$x]) === 4 && $x > 0) {
                $queryArray .= " {$condition[$x][3]} {$condition[$x][0]} {$condition[$x][1]} ";
            }
            $dataValuesCheck[] = $condition[$x][2];
        }
        // $query = "SELECT {$columnName} FROM {$tableName} {$queryArray} ";
        // var_dump($queryArray);
        // var_dump($dataValuesCheck);
        // return $this->runQuery($query, $dataValuesCheck);
        $result = $this->get($tableName, $queryArray, $dataValuesCheck);
        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }
    public
    function getWhereOnceArrayLike($tableName, $condition = [])
    {
        $result = $this->getArrayLike($tableName, $condition);
        if (!empty($result)) {
            return $result[0];
        } else {
            return false;
        }
    }
    //Perintah DISTINCT digunakan untuk select data yang sama dari hasil tampilan query SELECT.
    public
    function getDistinct($tableName, $columnName, $condition)
    {
        $queryArray = "";
        $jumlahArray = count($condition);
        for ($x = 0; $x < $jumlahArray; $x++) {
            if (count($condition[$x]) === 3 && $x <= 0) {
                $queryArray .= " {$condition[$x][0]} {$condition[$x][1]} ? ";
            } else if (count($condition[$x]) === 4 && $x > 0) {
                $queryArray .= " {$condition[$x][3]} {$condition[$x][0]} {$condition[$x][1]} ? ";
            }
            $dataValuesCheck[] = $condition[$x][2];
        }
        $query = "SELECT DISTINCT {$columnName} FROM {$tableName} WHERE {$queryArray} ";
        //var_dump($query);
        return $this->getQuery($query, $dataValuesCheck);
    }
    // Method untuk check nilai unik, akan berguna untuk form
    public
    function check($tableName, $columnName, $dataValues)
    {
        $query = "SELECT {$columnName} FROM {$tableName} WHERE {$columnName} = ? ";
        //var_dump($query);
        return $this->runQuery($query, [$dataValues])->rowCount();
    }
    public
    function quote($string)
    {
        return $this->_pdo->quote($string);
    }
    // Method untuk check nilai unik dengan beberapa ketentuan, untuk beberapa colum name[ 'id', '=', $id_user , 'AND']
    public
    function checkArray($tableName, $columnName, $condition, $dataValues)
    {
        $queryArray = "WHERE {$columnName} = ? AND ";
        $jumlahArray = count($condition);
        $dataValuesCheck = [];
        $dataValuesCheck[] = $dataValues;
        for ($x = 0; $x < $jumlahArray; $x++) {
            if (count($condition[$x]) === 3 && $x <= 0) {
                $queryArray .= " {$condition[$x][0]} {$condition[$x][1]} ? ";
            } else if (count($condition[$x]) === 4 && $x > 0) {
                $queryArray .= " {$condition[$x][3]} {$condition[$x][0]} {$condition[$x][1]} ? ";
            }
            $dataValuesCheck[] = $condition[$x][2];
        }
        $query = "SELECT {$columnName} FROM {$tableName} {$queryArray} ";
        return $this->runQuery($query, $dataValuesCheck)->rowCount();
    }
    // Method untuk check nilai unik dengan beberapa ketentuan, untuk beberapa colum name[ 'id', '= ?', $id_user , 'AND']
    public
    function checkArrayLike($tableName, $columnName, $condition)
    {
        $queryArray = "WHERE ";
        $jumlahArray = count($condition);
        $dataValuesCheck = [];
        for ($x = 0; $x < $jumlahArray; $x++) {
            if (count($condition[$x]) === 3 && $x <= 0) {
                $queryArray .= " {$condition[$x][0]} {$condition[$x][1]}";
            } else if (count($condition[$x]) === 4 && $x > 0) {
                $queryArray .= " {$condition[$x][3]} {$condition[$x][0]} {$condition[$x][1]} ";
            }
            $dataValuesCheck[] = $condition[$x][2];
        }
        $query = "SELECT {$columnName} FROM {$tableName} {$queryArray} ";
        // var_dump($query);
        // var_dump($dataValuesCheck);
        return $this->runQuery($query, $dataValuesCheck)->rowCount();
    }
    // Ambil nilai kolom, hasil dari rowCount()
    public
    function count()
    {
        return $this->_count;
    }
    // Ambil nilai kolom, hasil dari laast insert id()
    public
    function lastInsertId()
    {
        return $this->_lastInserId;
    }
    // LIMIT diikuti oleh 2 angka, angka pertama berfungsi sebagai offset, yakni jumlah baris yang dilompati. Sedangkan angka kedua berfungsi sebagai jumlah baris.
    // tambahan [offset,batas] limit
    public
    function limit($limit = [])
    {
        if (count($limit) > 0) {
            if (count($limit) == 2) {
                $offset = (int)$limit[0];
                $batas = (int)$limit[1];
                $this->_limit = " LIMIT $offset, $batas";
            } else {
                $batas = (int)$limit[0];
                $this->_limit = " LIMIT $batas";
            }
        }
        return $this->_limit;
    }
    public
    function posisilimit($limit, $get_data, $halaman = 1)
    {
        $jumlahArray = is_array($get_data) ? count($get_data) : 0;
        if ($jumlahArray) {
            if ($limit !== "all") {
                $jmldata = sizeof($get_data); //$get_data->fetchColumn();
                $jmlhalaman = ceil($jmldata / $limit);
            } else {
                $jmlhalaman = 1;
            }
            if ($halaman > $jmlhalaman) {
                $halaman = $jmlhalaman;
            }
            if (empty($halaman)) {
                $posisi = 0;
                $halaman = 1;
            } else {
                $posisi = ((int)$halaman - 1) * (int)$limit;
            }
        } else {
            $posisi = 0;
        }
        return $posisi;
    }
    // Method untuk menginput data tabel (query INSERT)
    public
    function insert($tableName, $data)
    {
        $dataKeys = array_keys($data);
        $dataValues = array_values($data);
        $placeholder = '(' . str_repeat('?,', count($data) - 1) . '?)';
        $query = "INSERT INTO {$tableName} (" . implode(', ', $dataKeys) . ") VALUES {$placeholder}";
        //var_dump($query);
        //var_dump($dataValues);
        $run = $this->runQuery($query, $dataValues);
        //var_dump($this->_pdo->lastInsertId());//ok lah
        $this->_count = $run->rowCount(); //ok
        $this->_lastInserId = $this->_pdo->lastInsertId(); //error
        return true;
    }
    // Method untuk menginput data tabel $condition = [[ 'id', '= ?', $id_user ],[ 'id', '= ?', $id_user , 'AND'],...]
    public
    function insert_select($tableName, $tablePosting, $columnName, $columnSelect, $condition = []) //@audit insert select mysql
    {
        $query_where = "";
        $dataValues = [];
        if (count($condition)  > 0) {
            $query_where = " WHERE ";
            foreach ($condition as $key => $val) {
                $dataValues[] = $val[2];
                $query_where .= (count($val) == 4) ? "{$val[3]} {$val[0]} {$val[1]} " : "{$val[0]} {$val[1]} ";
            }
        }

        $query = "INSERT INTO {$tablePosting} ($columnName) SELECT $columnSelect FROM {$tableName} $query_where";
        // var_dump($query);
        // var_dump($dataValues);
        $run = $this->runQuery($query, $dataValues);
        //var_dump($this->_pdo->lastInsertId());//ok lah
        $this->_count = $run->rowCount(); //ok
        $this->_lastInserId = $this->_pdo->lastInsertId(); //error
        return true;
    }
    // Method untuk mengupdate data tabel (query UPDATE)
    public
    function update($tableName, $data, $condition)
    {
        $query = "UPDATE {$tableName} SET ";
        foreach ($data as $key => $val) {
            $query .= "$key = ?, ";
        }
        $query = substr($query, 0, -2);
        $query .= " WHERE {$condition[0]} {$condition[1]} ?";
        $dataValues = array_values($data);
        array_push($dataValues, $condition[2]);
        //var_dump($query);
        $this->_count = $this->runQuery($query, $dataValues)->rowCount();
        return true;
    }
    // Method untuk mengupdate data tabel multi kondisi (query UPDATE)
    public
    function update_array($tableName, $data, $condition)
    {
        //kondisi=[[ 'id', '=', $id_user ],[ 'id', '=', $id_user , 'AND']]
        $query = "UPDATE {$tableName} SET ";
        foreach ($data as $key => $val) {
            $query .= "$key = ?, ";
        }
        $query = substr($query, 0, -2);
        $query .= " WHERE ";
        $jumlahArray = count($condition);
        $dataValues = array_values($data);
        foreach ($condition as $key => $val) {
            if (strpos($val[1], "LIKE") !== false) {
                $query .= " {$val[3]} {$val[0]} {$val[1]}";
            } else {
                if (count($val) === 3 && $key === 0) {
                    $query .= " {$val[0]} {$val[1]} ? ";
                } else if (count($val) === 4 && $key > 0) {
                    $query .= " {$val[3]} {$val[0]} {$val[1]} ? ";
                }
            }
            array_push($dataValues, $val[2]);
        }
        // var_dump($dataValues);
        // var_dump($query);
        $this->_count = $this->runQuery($query, $dataValues)->rowCount();
        return true;
    }
    // Method untuk menghapus data tabel (query DELETE)
    public function delete($tableName, $condition)
    {
        $query = "DELETE FROM {$tableName} WHERE {$condition[0]} {$condition[1]} ? ";
        //var_dump($condition);
        $this->_count = $this->runQuery($query, [$condition[2]])->rowCount();
        return true;
    }
    // Method untuk menghapus data tabel condition array (query DELETE)
    public
    function delete_array($tableName, $condition)
    {
        $query = "DELETE FROM {$tableName} WHERE";
        $dataValues = [];
        foreach ($condition as $key => $val) {
            if (strpos($val[1], "LIKE") !== false) {
                $query .= " {$val[3]} {$val[0]} {$val[1]}";
            } else {
                if (count($val) === 3 && $key === 0) {
                    $query .= " {$val[0]} {$val[1]} ? ";
                } else if (count($val) === 4 && $key > 0) {
                    $query .= " {$val[3]} {$val[0]} {$val[1]} ?";
                }
            }
            array_push($dataValues, $val[2]);
        }
        //var_dump($query);
        //var_dump($dataValues);
        //array $condition[[ 'id', '=', $id_user ],[ 'id', '=', $id_user , 'AND'],...]array pertama 3larik, array selanjutnya 4 larik
        $this->_count = $this->runQuery($query, $dataValues)->rowCount();
        return true;
    }
    public function backup_tables($tables = '*')
    {  /* backup the db OR just a table */
        $data = "";
        //get all of the tables
        if ($tables == '*') {
            $tables = array();
            $result = $this->_pdo->prepare('SHOW TABLES');
            $result->execute();
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }
        } else {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }
        //cycle through
        foreach ($tables as $table) {
            $resultcount = $this->_pdo->prepare('SELECT count(*) FROM ' . $table);
            $resultcount->execute();
            $num_fields = $resultcount->fetch(PDO::FETCH_NUM);
            $num_fields = $num_fields[0];
            $result = $this->_pdo->prepare('SELECT * FROM ' . $table);
            $result->execute();
            $data .= 'DROP TABLE ' . $table . ';';
            $result2 = $this->_pdo->prepare('SHOW CREATE TABLE ' . $table);
            $result2->execute();
            $row2 = $result2->fetch(PDO::FETCH_NUM);
            $data .= "\n\n" . $row2[1] . ";\n\n";
            for ($i = 0; $i < $num_fields; $i++) {
                while ($row = $result->fetch(PDO::FETCH_NUM)) {
                    $data .= 'INSERT INTO ' . $table . ' VALUES(';
                    for ($j = 0; $j < $num_fields; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = str_replace("\n", "\\n", $row[$j]);
                        if (isset($row[$j])) {
                            $data .= '"' . $row[$j] . '"';
                        } else {
                            $data .= '""';
                        }
                        if ($j < ($num_fields - 1)) {
                            $data .= ',';
                        }
                    }
                    $data .= ");\n";
                }
            }
            $data .= "\n\n\n";
        }
        //save filename
        $filename = 'db-backup-' . time() . '-' . (implode(",", $tables)) . '.sql';
        $this->writeUTF8filename($filename, $data);
        /*USE EXAMPLE
       $connection = new MySql(SERVERHOST,"your_db_name",DBUSER, DBPASS);
       $connection->backup_tables(); //OR backup_tables("posts");
       $connection->closeConnection();
    */
    } /*end function*/
    private function writeUTF8filename($filenamename, $content)
    {  /* save as utf8 encoding */
        $f = fopen($filenamename, "w+");
        # Now UTF-8 - Add byte order mark 
        fwrite($f, pack("CCC", 0xef, 0xbb, 0xbf));
        fwrite($f, $content);
        fclose($f);
        /*USE EXAMPLE this is only used by public function above...
        $this->writeUTF8filename($filename,$data);
    */
    } /*end function*/
    public function recoverDB($file_to_load)
    {
        echo "write some code to load and proccedd .sql file in here ...";
        /*USE EXAMPLE this is only used by public function above...
        recoverDB("some_buck_up_file.sql");
    */
    } /*end function*/
    //==================
    //====   JSON ======
    //==================
    // Method untuk menambahkan data array JSON ke dalam kolom mysql> UPDATE $tableName SET $columnName=JSON_ARRAY_APPEND($columnName,"$[$jsonKey]","*");
    public function appendArrayJSONField($tableName, $columnName, $jsonData, $jsonKey, $condition)
    {
        $query = "UPDATE {$tableName} SET {$columnName} = JSON_ARRAY_APPEND({$columnName}, '$.[$jsonKey]', ?)";
        $query .= " WHERE";
        $dataValues = [$jsonData];
        foreach ($condition as $key => $val) {
            if (strpos($val[1], "LIKE") !== false) {
                $query .= " {$val[3]} {$val[0]} {$val[1]}";
            } else {
                if (count($val) === 3 && $key === 0) {
                    $query .= " {$val[0]} {$val[1]} ? ";
                } else if (count($val) === 4 && $key > 0) {
                    $query .= " {$val[3]} {$val[0]} {$val[1]} ?";
                }
            }
            array_push($dataValues, $val[2]);
        }
        return $this->runQuery($query, $dataValues)->rowCount();
    }
    // Method untuk menambahkan data JSON ke dalam kolom
    public function insertJSONField($tableName, $columnName, $jsonData, $jsonKey, $condition)
    {
        $query = "UPDATE {$tableName} SET {$columnName} = JSON_INSERT({$columnName}, '$.{$jsonKey}', ?)";
        $query .= " WHERE";
        $dataValues = [$jsonData];
        foreach ($condition as $key => $val) {
            if (strpos($val[1], "LIKE") !== false) {
                $query .= " {$val[3]} {$val[0]} {$val[1]}";
            } else {
                if (count($val) === 3 && $key === 0) {
                    $query .= " {$val[0]} {$val[1]} ? ";
                } else if (count($val) === 4 && $key > 0) {
                    $query .= " {$val[3]} {$val[0]} {$val[1]} ?";
                }
            }
            array_push($dataValues, $val[2]);
        }
        return $this->runQuery($query, $dataValues)->rowCount();
    }
    // Method untuk membaca data JSON dari kolom untuk level satu
    public function readJSONField($tableName, $columnName, $jsonKey, $condition)
    {
        // $query = "SELECT JSON_UNQUOTE(JSON_EXTRACT({$columnName}, '$.{$jsonKey}')) AS {$jsonKey} FROM {$tableName}";
        // antisipasi ada titik di $jsonKey
        $query = "SELECT JSON_UNQUOTE(JSON_EXTRACT(`{$columnName}`, CONCAT('$.', JSON_QUOTE('{$jsonKey}')))) AS `{$jsonKey}` FROM `{$tableName}`";
        $query .= " WHERE";
        $dataValues = [];
        foreach ($condition as $key => $val) {
            if (strpos($val[1], "LIKE") !== false) {
                $query .= " {$val[3]} {$val[0]} {$val[1]}";
            } else {
                if (count($val) === 3 && $key === 0) {
                    $query .= " {$val[0]} {$val[1]} ? ";
                } else if (count($val) === 4 && $key > 0) {
                    $query .= " {$val[3]} {$val[0]} {$val[1]} ?";
                }
            }
            array_push($dataValues, $val[2]);
        }
        return $this->runQuery($query, $dataValues)->fetch(PDO::FETCH_ASSOC)[$jsonKey];
    }
    // Method untuk membaca data JSON dari kolom untuk level satu
    public function readJSONFieldMultiLevel($tableName, $columnName, $jsonKey, $condition)
    {
        $query = "SELECT JSON_UNQUOTE(JSON_EXTRACT({$columnName}, '$.{$jsonKey}')) AS {$jsonKey} FROM {$tableName}";

        $query .= " WHERE";
        $dataValues = [];
        foreach ($condition as $key => $val) {
            if (strpos($val[1], "LIKE") !== false) {
                $query .= " {$val[3]} {$val[0]} {$val[1]}";
            } else {
                if (count($val) === 3 && $key === 0) {
                    $query .= " {$val[0]} {$val[1]} ? ";
                } else if (count($val) === 4 && $key > 0) {
                    $query .= " {$val[3]} {$val[0]} {$val[1]} ?";
                }
            }
            array_push($dataValues, $val[2]);
        }
        // var_dump($query);
        // var_dump($dataValues);
        return $this->runQuery($query, $dataValues)->fetch(PDO::FETCH_ASSOC)[$jsonKey];
    }
    // Method untuk memperbarui data JSON di dalam kolom
    public function updateJSONField($tableName, $columnName, $jsonData, $jsonKey, $condition)
    {
        $query = "UPDATE {$tableName} SET {$columnName} = JSON_SET({$columnName}, '$.{$jsonKey}', ?)";
        $query .= " WHERE";
        $dataValues = [$jsonData];
        foreach ($condition as $key => $val) {
            if (strpos($val[1], "LIKE") !== false) {
                $query .= " {$val[3]} {$val[0]} {$val[1]}";
            } else {
                if (count($val) === 3 && $key === 0) {
                    $query .= " {$val[0]} {$val[1]} ? ";
                } else if (count($val) === 4 && $key > 0) {
                    $query .= " {$val[3]} {$val[0]} {$val[1]} ?";
                }
            }
            array_push($dataValues, $val[2]);
        }
        return $this->runQuery($query, $dataValues)->rowCount();
    }
    // $condition = [['id', '=', $id_sub_keg], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
    // Method untuk menghapus field dari data JSON di kolom
    public function deleteJSONField($tableName, $columnName, $jsonKey, $condition)
    {
        $query = "UPDATE {$tableName} SET {$columnName} = JSON_REMOVE({$columnName}, '$.{$jsonKey}')";
        $query .= " WHERE";
        $dataValues = [];
        foreach ($condition as $key => $val) {
            if (strpos($val[1], "LIKE") !== false) {
                $query .= " {$val[3]} {$val[0]} {$val[1]}";
            } else {
                if (count($val) === 3 && $key === 0) {
                    $query .= " {$val[0]} {$val[1]} ? ";
                } else if (count($val) === 4 && $key > 0) {
                    $query .= " {$val[3]} {$val[0]} {$val[1]} ?";
                }
            }
            array_push($dataValues, $val[2]);
        }
        return $this->runQuery($query, $dataValues)->rowCount();
    }
}
