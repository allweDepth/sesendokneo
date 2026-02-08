<?php
class Validate
{
	private $_errors = array();
	private $_formMethod = null;

	public function __construct($formMethod)
	{
		$this->_formMethod = $formMethod;
	}
	public function encrypt($formValue)
	{
		// var_dump(isset($_SESSION["user"]["key_encrypt"]));
		if (isset($_SESSION["user"]["key_encrypt"])) {
			$keyEncrypt = $_SESSION["user"]["key_encrypt"];
		} else if (isset($_SESSION["key_encrypt"])) {
			$keyEncrypt = $_SESSION["key_encrypt"];
		} else {
			$real_path = realpath(dirname(__FILE__));
			if (strpos($real_path, 'script')) {
				header("Location: login");
			} else {
				header("Location: login");
			}
		}
		// var_dump($_SESSION);
		// var_dump($formValue);
		if ($formValue != null && $keyEncrypt) {
			require_once 'CryptoUtils.php';
			$crypto = new CryptoUtils();
			// var_dump($keyEncrypt);
			// var_dump($formValue);
			// var_dump($crypto->decrypt($formValue, $keyEncrypt));
			return $crypto->decrypt($formValue, $keyEncrypt);
		} else {
		}
	}
	// 
	public function setRules($item, $itemLabel, $rules)
	{
		// kondisi if isset() diperlukan untuk form checkbox atau radio yang kemungkinan kosong
		if (isset($this->_formMethod[$item])) {
			$formValue = trim($this->_formMethod[$item]);
			// var_dump($formValue);
		} else {
			$formValue = "";
		}
		// jalankan proses hilangkan duble spasi item (jika disyaratkan)
		if (array_key_exists('del_2_spasi', $rules)) {
			$formValue = preg_replace('/(\s\s+|\t|\n)/', ' ', $formValue);
		}
		// jalankan proses hilangkan karakter tertentu item (jika disyaratkan)
		if (array_key_exists('preg_replace', $rules)) {
			$formValue = preg_replace($rules["preg_replace"][0], $rules["preg_replace"][1], $formValue);
		}
		// jalankan proses huruf kecil item (jika disyaratkan)
		if (array_key_exists('strtolower', $rules)) {
			$formValue = strtolower($formValue);
		}
		// jalankan proses huruf kecil item (jika disyaratkan)
		if (array_key_exists('json_decode', $rules)) {
			$formValue = json_decode($formValue);
		}
		// jalankan proses huruf kapital item (jika disyaratkan)
		if (array_key_exists('strtoupper', $rules)) {
			$formValue = strtoupper($formValue);
		}
		// jalankan proses sanitize untuk setiap item (jika disyaratkan)
		if (array_key_exists('sanitize', $rules)) {
			$formValue = Input::runSanitize($formValue, $rules['sanitize']);
		}
		if (array_key_exists('cry', $this->_formMethod)) {
			if ($this->_formMethod['cry']) {
				$formValue = $this->encrypt($formValue);
			}
		}
		// var_dump($formValue);
		foreach ($rules as $rule => $ruleValue) {
			// var_dump($rule);
			// var_dump($ruleValue);
			switch ($rule) {
				case 'sanitizeJSON':
					$formValue = $this->sanitizeJSON($formValue, $ruleValue);
					break;
				case 'sanitizeArray':
					$formValue = $this->sanitizeArray($formValue, $ruleValue);
					break;
				case 'sanitizeValue':
					$formValue = $this->sanitizeValue($formValue, $ruleValue);
					break;
				case 'error': //langsung error
					$this->_errors[$item] = "$itemLabel";
					break;
					//tambahan alwi buat validasi json
				case 'json_enc':
					$formValue = $this->encrypt($formValue);
					if (!@json_decode($formValue)) {
						$this->_errors[$item] = (json_last_error() === JSON_ERROR_NONE) . " Pola $itemLabel format json tidak sesuai";
					}
					break;
				case 'json':
					if (!@json_decode($formValue)) {
						$this->_errors[$item] = (json_last_error() === JSON_ERROR_NONE) . " Pola $itemLabel format json tidak sesuai";
					}
					/*
					The above outputs :
					0 JSON_ERROR_NONE
					1 JSON_ERROR_DEPTH
					2 JSON_ERROR_STATE_MISMATCH
					3 JSON_ERROR_CTRL_CHAR
					4 JSON_ERROR_SYNTAX
					5 JSON_ERROR_UTF8
					6 JSON_ERROR_RECURSION
					7 JSON_ERROR_INF_OR_NAN
					8 JSON_ERROR_UNSUPPORTED_TYPE
					*/
					break;
				case 'json_repair':
					json_decode($formValue);
					if (json_last_error()) {
						$this->_errors[$item] = (json_last_error() === JSON_ERROR_NONE) . " Pola $itemLabel format json tidak sesuai";
					}
					break;
				case 'required':
					if ($ruleValue === TRUE && empty($formValue)) {
						$this->_errors[$item] = "$itemLabel tidak boleh kosong";
					}
					break;

				case 'min_char':
					if (strlen($formValue) < $ruleValue) {
						$this->_errors[$item] = "$itemLabel minimal $ruleValue karakter";
					}
					break;
					// encrypt
				case 'min_char_enc':
					$formValue = $this->encrypt($formValue);
					if (strlen($formValue) < $ruleValue) {
						$this->_errors[$item] = "$itemLabel minimal $ruleValue karakter";
					}
					break;
				case 'max_char':
					if (strlen($formValue) > $ruleValue) {
						$this->_errors[$item] = "$itemLabel maksimal $ruleValue karakter";
					}
					break;
					// encrypt
				case 'numeric_enc':
					$formValue = $this->encrypt($formValue);
					if ($ruleValue === TRUE) { //|| !is_int($formValue)//($ruleValue === TRUE && (!is_numeric($formValue) ))
						if ($formValue != 0) {
							if (is_numeric((float)$formValue)) {
								$formValue = $formValue; // numeric
							} else { // is not numeric 
								$this->_errors[$item] = "$itemLabel harus diisi angka";
							}
						} else {
							$formValue = $formValue; // 0 as numeric
						}
						//var_dump($formValue);

					}
					break;
				case 'numeric':
					if ($ruleValue === TRUE) { //|| !is_int($formValue)//($ruleValue === TRUE && (!is_numeric($formValue) ))
						if ($formValue != 0) {
							if (is_numeric((float)$formValue)) {
								$formValue = $formValue; // numeric
							} else { // is not numeric 
								$this->_errors[$item] = "$itemLabel harus diisi angka";
							}
						} else {
							$formValue = $formValue; // 0 as numeric
						}
						//var_dump($formValue);
					}
					break;
				case 'numeric_zero':
					if ($ruleValue === TRUE) { //|| !is_int($formValue)//($ruleValue === TRUE && (!is_numeric($formValue) ))
						if ($formValue != 0) {
							if (is_numeric((float)$formValue)) {
								$formValue = $formValue; // numeric
							} else { // is not numeric 
								if ($formValue == '') {
									$formValue = 0;
								} else {

									$this->_errors[$item] = "$itemLabel harus diisi angka";
								}
							}
						} else {
							$formValue = $formValue; // 0 as numeric
						}
						//var_dump($formValue);
					}
					break;
				case 'min_value':
					if ($formValue < $ruleValue) {
						$this->_errors[$item] = "$itemLabel minimal $ruleValue";
					}
					break;

				case 'max_value':
					if ($formValue > $ruleValue) {
						$this->_errors[$item] = "$itemLabel maksimal $ruleValue";
					}
					break;

				case 'matches':
					if ($formValue != $this->_formMethod[$ruleValue]) {
						$this->_errors[$item] = "$itemLabel tidak sama";
					}
					break;

				case 'sama_dengan_enc':
					$formValue = $this->encrypt($formValue);
					if ($formValue != $ruleValue) {
						$this->_errors[$item] = "$itemLabel tidak sama dengan";
					}
					break;
				case 'sama_dengan':
					if ($formValue != $ruleValue) {
						$this->_errors[$item] = "$itemLabel tidak sama dengan";
					}
					break;
				case 'email':
					if ($ruleValue === TRUE && !filter_var($formValue, FILTER_VALIDATE_EMAIL)) {
						$this->_errors[$item] = "Format $itemLabel tidak sesuai";
					}
					break;

				case 'url':
					if ($ruleValue === TRUE && !filter_var($formValue, FILTER_VALIDATE_URL)) {
						$this->_errors[$item] = "Format $itemLabel tidak sesuai";
					}
					break;
				case 'url_enc':
					$formValue = $this->encrypt($formValue);
					if ($ruleValue === TRUE && !filter_var($formValue, FILTER_VALIDATE_URL)) {
						$this->_errors[$item] = "Format $itemLabel tidak sesuai";
					}
					break;
					//tambahan alwi
				case 'count_array': //'count_array'=>[';',4]
					$arrayku = explode($ruleValue[0], $formValue);
					if (!count($arrayku) == (int)$ruleValue[1]) {
						$this->_errors[$item] = "jumlah larik $itemLabel != {$ruleValue[0]}";
					}
					break;
					//tambahan alwi
				case 'in_array':
					$formValue = (gettype($formValue) == 'string') ? strtolower($formValue) : $formValue;
					if (!in_array($formValue, $ruleValue, TRUE)) {
						$this->_errors[$item] = "Periksa baris $itemLabel";
					}
					break;

				case 'regexp_enc':
					$formValue = $this->encrypt($formValue);
					if (!preg_match($ruleValue, $formValue)) {
						$this->_errors[$item] = "Pola $itemLabel tidak sesuai";
					}
					break;
				case 'regexp_enc':
					$formValue = $this->encrypt($formValue);
					if (!preg_match($ruleValue, $formValue)) {
						$this->_errors[$item] = "Pola $itemLabel tidak sesuai";
					}
					break;
				case 'regexp':
					if (!preg_match($ruleValue, $formValue)) {
						$this->_errors[$item] = "Pola $itemLabel tidak sesuai";
					}
					break;
				case 'unique_enc':
					$formValue = $this->encrypt($formValue);
					require_once 'DB.php';
					$DB = DB::getInstance();
					if ($DB->check($ruleValue[0], $ruleValue[1], $formValue)) {
						$this->_errors[$item] = "$itemLabel sudah terpakai, silahkan pilih nama lain";
					}
					break;
				case 'unique':
					require_once 'DB.php';
					$DB = DB::getInstance();
					if ($DB->check($ruleValue[0], $ruleValue[1], $formValue)) {
						$this->_errors[$item] = "$itemLabel sudah terpakai, silahkan pilih nama lain";
					}
					break;
				case 'uniqueArray':
					require_once 'DB.php';
					$DB = DB::getInstance();
					//checkArray( $tableName, $columnName, $condition )
					//['user','username',[[ 'id', '=', $id_user][ 'id', '=', $id_user , 'AND']]]
					if ($DB->checkArray($ruleValue[0], $ruleValue[1], $ruleValue[2], $formValue)) {
						$this->_errors[$item] = "$itemLabel sudah terpakai, silahkan pilih nama lain";
					}
					break;
				case 'inDB':
					require_once 'DB.php';
					$DB = DB::getInstance();
					//checkArray( $tableName, $columnName, $condition )
					//['user','username',[[ 'id', '=', $id_user][ 'id', '=', $id_user , 'AND']]]
					if (!$DB->checkArray($ruleValue[0], $ruleValue[1], $ruleValue[2], $formValue)) {
						$this->_errors[$item] = "$itemLabel data tidak ditemukan pilih yang lain";
					}
					break;

				case 'inLikeConcatDB':
					require_once 'DB.php';
					$DB = DB::getInstance();
					//checkArray( $tableName, $columnName, $condition )
					//['user','username',[[ 'id', '= ?', $id_user][ 'id', '= ?', $id_user , 'AND']]]
					if (!$DB->checkArrayLike($ruleValue[0], $ruleValue[1], $ruleValue[2])) {
						$this->_errors[$item] = "$itemLabel data tidak ditemukan pilih yang lain";
					}
					break;
				case 'inLikeConcatDBMultiple':
					require_once 'DB.php';
					$DB = DB::getInstance();

					//['user','username',[[ 'id', '= ?', $id_user][ 'id', '= ?', $id_user , 'AND']]]
					$DB->select($ruleValue[1]);
					$rows_result = $DB->getWhereOnceCustom($ruleValue[0], $ruleValue[2]);
					$var_a = $ruleValue[1];
					$rows_result_explode = explode(',', $rows_result->$var_a);

					$formValueExplode = explode(',', $formValue);
					$DB->select('*');
					foreach ($formValueExplode as $key_row => $row) {
						$key = array_search($row, $rows_result_explode, true);
						if ($key === false) {
							$this->_errors[$item] = "$itemLabel data tidak ditemukan pilih yang lain";
						}
					}
					break;
				case 'inDBJsonMultiple':
					require_once 'DB.php';
					$DB = DB::getInstance();
					$formValueExplode = explode(',', $formValue);
					foreach ($formValueExplode as $key_row => $row) {
						$kolom = $ruleValue[1];
						$keyCellKolom = $ruleValue[2];
						$kondisi = $ruleValue[3];
						$data_klm = $DB->readJSONFieldMultiLevel($ruleValue[0], $kolom, $keyCellKolom, $kondisi);
						$data_klm = json_decode($data_klm, true);
						$key = array_search($row, $data_klm, true);
						if ($key === false) {
							$this->_errors[$item] = "$itemLabel data tidak ditemukan pilih yang lain";
						}
					}
					break;
			}

			// cek jika sudah ada error di item yang sama, langsung keluar dari looping
			if (!empty($this->_errors[$item])) {
				break;
			}
		}
		// kembalikan nilai yang sudah lewat proses sanitize
		return $formValue;
	}

	public function getError()
	{
		return $this->_errors;
	}

	public function passed()
	{
		return empty($this->_errors) ? true : false;
	}
	//tambahan allwe
	// Implementasi metode sanitizeJSON, sanitizeArray, dan sanitizeValue
	public function sanitizeJSON($json, $rules)
	{
		$decodedJSON = json_decode($json, true);

		if ($decodedJSON === null && json_last_error() !== JSON_ERROR_NONE) {
			throw new Exception('JSON tidak valid: ' . json_last_error_msg());
		}

		return $this->sanitizeArray($decodedJSON, $rules);
	}

	private function sanitizeArray($data, $rules)
	{
		foreach ($data as $key => $value) {
			if (isset($rules[$key])) {
				$data[$key] = $this->sanitizeValue($value, $rules[$key]);
			} elseif (is_array($value)) {
				$data[$key] = $this->sanitizeArray($value, $rules);
			}
		}
		return $data;
	}

	private function sanitizeValue($value, $rules)
	{
		foreach ($rules as $rule => $ruleValue) {
			// Implementasi sanitasi sesuai aturan yang diberikan
			// Contoh:
			switch ($rule) {
				case 'strip_tags':
					$value = strip_tags($value);
					break;
				case 'trim':
					$value = trim($value);
					break;
				case 'htmlspecialchars':
					$value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
					break;
					// Tambahkan aturan sanitasi lain sesuai kebutuhan
			}
		}
		return $value;
	}
}
