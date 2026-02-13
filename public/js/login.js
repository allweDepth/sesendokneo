var dok = '';
$(document).ready(function () {
	"use strict";
	$('.ui.vertical.stripe.segment .ui.container').visibility({
		once: false,
		// update size when new content loads
		observeChanges: true,
		onTopVisible: function (calculations) {
			// top is on screen
			console.log(`onTopVisible = `);
			console.log(calculations);
		},
		onTopPassed: function (calculations) {
			// top of element passed
			console.log(`onTopPassed =`);
			console.log(calculations);
		},
		onUpdate: function (calculations) {
			// do something whenever calculations adjust
			// console.log(`onUpdate =`);
			// console.log(calculations);

		},
		// load content on bottom edge visible
		onBottomVisible: function (calculations) {
			console.log(`onBottomVisible =`);
			console.log(calculations);
		}
	});
	// fix menu when passed
	$('.masthead')
		.visibility({
			once: false,
			onBottomPassed: function () {
				$('.fixed.menu').transition('fade in');
			},
			onBottomPassedReverse: function () {
				$('.fixed.menu').transition('fade out');
			}
		})
		;
	let handler = {
		activate: function () {
			if (!$(this).hasClass('dropdown browse')) {
				$(this)
					.addClass('active')
					.closest('.ui.menu')
					.find('.item')
					.not($(this))
					.removeClass('active')
					;
			}
		}
	}
	$(".menu .item.inayah").on('click', handler.activate);
	// create sidebar and attach to menu open
	$('.ui.sidebar')
		.sidebar('attach events', '.toc.item')
		;
	$(".ui.accordion").accordion();
	$(".ui.accordion.menu_utama").accordion({
		exclusive: false
	});
	$(".ui.dropdown").dropdown();
	//Sticking to Own Context
	$('.ui.sticky')
		.sticky({
			context: '.pusher',
			pushing: true
		});

	var keyEncryption = halamanDefault;
	let encryption = new Encryption();

	function modal_notif(kop, conten) {

		$('#kop_notifikasi').html(kop);
		$('#conten_notifikasi').html('<p>' + conten + '</p>');
		$('.ui.basic.modal.info').modal('show');
	}
	$(document).on('click', "a[name='modal']", function (event) {
		event.preventDefault();
		$('.ui.modal.login')
			.modal('show')
			;
	})
	$(document).on('click', "a[name='modal-register']", function (event) {
		event.preventDefault();
		$('.ui.modal.register')
			.modal('show');
		let MyForm = $(`.ui.form[name="form_modal"]`);
		MyForm.attr('jns', 'register').attr('tbl', 'register');
		let modalGeneral = new ModalConstructor(`.ui.modal.register`);
		modalGeneral.globalModal();
		let form = new FormGlobal(`.ui.form[name="form_modal"]`);
		form.run();
		form.addRulesForm();
	})
	$(document).on('click', "button[name='login']", function (event) {
		event.preventDefault();
		dok = $(this).attr('value');
		$('.ui.form.login').form('submit');
		return false;
	})
	$('.ui.form.login').form({
		fields: {
			username: {
				identifier: 'username',
				rules: [{
					type: 'empty',
					prompt: 'Please enter your username or e-mail'
				}]
			},
			password: {
				identifier: 'password',
				rules: [{
					type: 'empty',
					prompt: 'Please enter your password'
				}]
			}
		},
		onSuccess: function (event) {
			event.preventDefault();
			//$(this).serialize();

			var dataku = $(this).serializeArray();
			var username = dataku[0].value;
			var password = dataku[1].value;
			username = encryption.encrypt(username, keyEncryption);
			password = encryption.encrypt(password, keyEncryption);

			const url = BASEURL + halamandok + "/masuk";
			let data = {
				username: username,
				password: password,
				login: 'login',
				dok: 'dok',
				cry: true
			}
			$.ajax({
				type: "POST",
				data: data,
				url: url,
				dataType: 'JSon',
				success: function (result) {
					if (parseInt(result) == 1) {
						window.location.href = BASEURL + "home"; //admin
					} else if (parseInt(result) == 2) {
						window.location.href = BASEURL + "home";
					} else if (parseInt(result) == 6) {
						modal_notif(
							'<i class="info icon"></i>Akun belum aktif',
							'Hubungi admin untuk mengaktifkan akun anda'
						);
					} else if (parseInt(result) == 7) {
						modal_notif(
							'<i class="info icon"></i>Gagal Login',
							'Kombinasi akun anda salah'
						);
					}
				},
				error: function (jqXHR, status, err) {
				}
			});
			(async () => {
			})();
		}
	});
	//=======================================
	//===============FORM GLOBAL=============
	//=======================================
	class FormGlobal {//@audit-ok Form
		constructor(form) {
			this.form = $(form); //element;
		}
		run() {
			let MyForm = this.form;
			MyForm.form({
				autoCheckRequired: true,
				onSuccess: function (e) {
					e.preventDefault();
					loaderShow();
					const [node] = $(MyForm);
					const attrs = {}
					$.each(node.attributes, (index, attribute) => {
						attrs[attribute.name] = attribute.value;
						let attrName = attribute.name;
						//membuat variabel
						let myVariable = attrName + 'Attr';
						window[myVariable] = attribute.value;
					});
					let jalankanAjax = false;
					let ini = $(this);
					let tbl = ini.attr("tbl");
					let dataType = "Json";
					let jenis = ini.attr("jns");
					let nama_form = ini.attr("name");
					let cryptos = false;
					//loaderShow();
					let formData = new FormData(this);
					//ubah angka indonesia menjadi standar
					let elmRms = MyForm.find('input[rms]');
					Object.keys(elmRms).forEach((key) => {
						let element = $(elmRms[key]);
						let namaAttr = element.attr("name");
						if (namaAttr !== undefined) {
							formData.set(namaAttr, accounting.unformat(formData.get(namaAttr), ","));
						}
					});
					formData.set("jenis", jenis);
					formData.set("tbl", tbl);
					let url = "script/post_data";
					// formData.set("cari", cari(jenis));
					// formData.set("rows", countRows());
					let id_row = ini.attr("id_row");
					if (typeof id_row === "undefined") {
						id_row = ini.closest("tr").attr("id_row");
						if (typeof id_row === "undefined") {
							id_row = ini.closest("div.item").attr("id_row");
						}
					}
					if (typeof id_row !== "undefined") {
						formData.set("id_row", id_row);
					}
					// global mencari element date dan checkbox
					let property = ini.find(`.ui.toggle.checkbox input[name]`);
					if (property.length > 0) {
						for (const key of property) {
							let namaAttr = $(key).attr("name");
							(formData.has(namaAttr) === false)
								? formData.set(namaAttr, 'off')
								: formData.set(namaAttr, 'on'); // Returns false
						}
					}
					property = ini.find(".ui.calendar.date");
					if (property.length > 0) {
						for (const key of property) {
							let nameAttr = $(key).find("[name]").attr("name");
							let tanggal = $(key).calendar("get date");
							if (tanggal) {
								tanggal = `${tanggal.getFullYear()}-${tanggal.getMonth() + 1
									}-${tanggal.getDate()}`; //local time
								formData.set(nameAttr, tanggal);
							}
						}
					}
					property = ini.find(".ui.calendar.year");
					if (property.length > 0) {
						for (const key of property) {
							let nameAttr = $(key).find("[name]").attr("name");
							let tanggal = $(key).calendar("get date");
							if (tanggal) {
								tanggal = `${tanggal.getFullYear()}`; //local time
								formData.set(nameAttr, tanggal);
							}
						}
					}
					property = ini.find(".ui.calendar.datetime");
					if (property.length > 0) {
						for (const key of property) {
							let nameAttr = $(key).find("[name]").attr("name");
							let tanggal = $(key).calendar("get date");
							if (tanggal) {
								tanggal = new Date(tanggal).toISOString().slice(0, 19).replace('T', ' ');
								formData.set(nameAttr, tanggal);
							}
						}
					}
					switch (nama_form) {
						// =================
						// UNTUK FORM MODAL
						// =================
						case "form_modal":
							switch (jenis) {
								case 'register':
									switch (tbl) {
										case 'register':
											cryptos = true;
											jalankanAjax = true;
											url = BASEURL + halamandok + "/register";
											// const url = BASEURL + halamandok + "/masuk";
											formData.set('register', 'register');
											break;

										default:
											break;
									}
									break;

								default:
									break;
							}
							break;
						// =================
						// UNTUK FORM FLYOUT
						// =================
						case "form_flyout":

							break;
					}
					if (cryptos) {
						let keyEncryption = halamanDefault;
						let encryption = new Encryption();
						formData.forEach((value, key) => {
							switch (key) {
								case 'jenis':
								case 'tbl':
									break;
								default:
									formData.set(key, encryption.encrypt(value, keyEncryption));
									break;
							}
						});
						formData.set('cry', true);

					}
					if (jalankanAjax) {
						suksesAjax["ajaxku"] = function (result) {
							let kelasToast = "success";
							let list = '';
							if (result.success === true) {

							} else {
								kelasToast = "warning"; //'success'
								let dataresul = result.data;
								if (Object.keys(dataresul).length > 0) {

									let liOl = '';
									for (let x in dataresul) {
										liOl += `<li>${dataresul[x]}</li>`;
									}
									list = `<ul class="ui list">${liOl}</ul>`;
									result.error.message = result.error.message + list
								}
							}
							showToast(result.error.message, {
								class: kelasToast,
								icon: "check circle icon",
							});
							loaderHide();
						};
						runAjax(
							url,
							"POST",
							formData,
							dataType,
							false,
							false,
							"ajaxku",
							cryptos
						);
						//runAjax("script/master_read_xlsx", "POST", formData, false, false, false, 'upload_renja');// untuk type file
						//runAjax("script/load_data", "POST", data, 'text', undefined, undefined, "draft_renstra");// type text
					} else {
					}
					switch (nama_form) {
						case "form_modal":
							// MyForm.form('reset')
							// $(".ui.modal.mdl_general").modal("hide");
							break;
						case "form_flyout":
							//$('.ui.flyout').flyout('hide');
							break;
						case "form_modal_kedua":
							$('div[name="mdl_kedua"]').modal("hide");
							break;
						default:
							break;
					}
				},
				onDirty: function (e) {
					//return true
					return false;
				},
				onFailure: function (e) {
					loaderHide();
					return false;
				},
			});
		}
		addRulesForm() {
			let MyForm = this.form;
			MyForm.form("set auto check");
			let elmtInputTextarea = MyForm.find($("input[name],textarea[name]"));
			for (const iterator of elmtInputTextarea) {
				let atribut = $(iterator).attr("name");
				let lbl = $(iterator).attr("placeholder");
				if (lbl === undefined) {
					lbl = $(iterator).closest(".field").find("label").text();
					if (lbl === undefined || lbl === "") {
						lbl = $(iterator).closest(".field").find("div.sub.header").text();
					}
					if (lbl === undefined || lbl === "") {
						lbl = atribut.replaceAll(/_/g, " ");
					}
				}
				let non_data = $(iterator).attr("non_data");
				if (typeof non_data === 'undefined' || non_data === false) {
					MyForm.form("add rule", atribut, {
						rules: [
							{
								type: "empty",
								prompt: "Lengkapi Data " + lbl,
							},
						],
					});
				} else {
					MyForm.form("remove field", atribut);
				}
			}
		}
		removeRulesForm(formku) {
			let MyForm = this.form;

			var attrName = MyForm.find($("input[name],textarea[name]"));
			var i = 0;
			for (i = 0; i < attrName.length; i++) {
				var atribut = MyForm.find(attrName[i]).attr("name");
				var lbl = MyForm.find(attrName[i]).attr("placeholder");
				if (lbl === undefined) {
					lbl = MyForm.find(attrName[i]).closest(".field").find("label").text();
					if (lbl === undefined || lbl === "") {
						lbl = MyForm.find(attrName[i]).closest(".field").find("div.sub.header").text();
					}
					if (lbl === undefined || lbl === "") {
						lbl = atribut.replaceAll(/_/g, " ");
					}
				}
				var non_data = formku.find(attrName[i]).attr("non_data");
				if (typeof non_data === "undefined" || non_data === false) {
					if (atribut) {
						MyForm.form("remove field", atribut);
					}
				}
			}
			$(formku).form("set auto check");
		}
	}
	//============================================================
	// . FUNGSI ADD RULE UNTUK SEMUA INPUT DAN TEXT AREA
	//============================================================
	//=======================================
	//===============MODAL GLOBAL=============
	//=======================================
	class ModalConstructor {//@audit-ok ModalConstructor
		constructor(modal) {
			this.modal = $(modal); //element;
		}
		globalModal() {
			let MyModal = this.modal;
			MyModal.modal({
				allowMultiple: true,
				//observeChanges: true,
				closable: false,
				transition: "zoom", //slide down,'slide up','browse right','browse','swing up','vertical flip','fly down','drop','zoom','scale'
				onDeny: function () {
				},
				onApprove: function () {
					// jika di tekan yes
					$(this).find("form").trigger("submit");
					return false;
				},
				onShow: function () {
				},
				onHidden: function () {
					// loaderHide();
					$(this).find("form").form("reset");
				}
			});
		}
	}
	//
	//===================================
	//=========== class dropdown ========
	//===================================
	class DropdownConstructor {
		jenis = '';
		tbl = '';
		ajax = false;
		result_ajax = {};
		url = BASEURL + "/register/wilayah";
		constructor(element) {
			this.element = $(element); //element;
			// this.methodConstructor = new MethodConstructor();
		}
		returnList(jenis = "list_dropdown", tbl = "satuan", minCharacters = 3) {
			let get = this.element.dropdown("get query");
			let elm = this.element;
			this.element.dropdown({
				minCharacters: minCharacters,
				maxResults: countRows(),
				searchDelay: 600,
				throttle: 600,
				cache: false,
				apiSettings: {
					// this url just returns a list of tags (with API response expected above)
					cache: false,
					method: "POST",
					url: "script/register/wilayah",
					throttle: 600,
					//throttle: 1000,//delay perintah
					// passed via POST
					data: {
						jenis: jenis,
						tbl: tbl,
						cari: function (value) {
							return elm.dropdown("get query");
						},
						rows: countRows(), //"all",
						halaman: 1,
					}, fields: {
						results: "results",
					},
					// filterRemoteData: true,
				},

				// filterRemoteData: true,
				// saveRemoteData: false,
			});
		}
		returnListOnChange(jenis = "list_dropdown", tbl = "satuan", minCharacters = 3, allField = {}) {
			let get = this.element.dropdown("get query");
			let elm = this.element;
			let dataSend = Object.assign({
				jenis: jenis,
				tbl: tbl,
				cari: function (value) {
					return elm.dropdown("get query");
				},
				rows: '10', //"all",
				halaman: 1
			}, allField);
			switch (jenis) {
				case 'list_dropdown':
					switch (tbl) {
						case 'wilayah'://url = BASEURL + halamandok + "/register";
							this.url = BASEURL + halamandok + "/wilayah";
							break;
						case 'organisasi':
							this.url = BASEURL + halamandok + "/organisasi";
							break;
						default:
							break;
					}
					break;
				case 'value1':
					break;
				default:
					break;
			};
			this.element.dropdown({
				minCharacters: minCharacters,
				maxResults: 10,
				searchDelay: 600,
				throttle: 600,
				cache: false,
				apiSettings: {
					// this url just returns a list of tags (with API response expected above)
					cache: false,
					method: "POST",
					url: this.url,
					throttle: 600,
					//throttle: 1000,//delay perintah
					// passed via POST
					data: dataSend,
					fields: {
						results: "results",
					},
					// filterRemoteData: true,
				},
				onChange: function (value, text, $choice) {
					let dataChoice = $($choice).find('span.description').text();
					let ajaxSend = false;
					switch (jenis) {
						case 'list_dropdown':
							switch (tbl) {
								case 'wilayah'://tujuan sasaran renstra
									ajaxSend = true;
									break;
								default:
									break;
							}
							break;
						case 'value1':
							break;
						default:
							break;
					};
					if (ajaxSend == true) {
						let data = {
							jenis: jenis,
							tbl: tbl
						};
						let url = 'script/register_akun';
						let cryptos = false;
						switch (jenis) {
							case 'list_dropdown':
								switch (tbl) {
									case 'wilayah'://tujuan sasaran renstra
										let elementDrop = $(`.ui.organisasi.dropdown.ajx`);
										data.kd_wilayah = $('.ui.wilayah.dropdown.ajx').dropdown('get value');
										url = BASEURL + halamandok + "/organisasi";
										break;
									default:
										break;
								}
								break;
							case 'value1':
								break;
							default:
								break;
						};
						suksesAjax["ajaxku"] = function (result) {
							let kelasToast = "success";
							if (result.success === true) {
								switch (jenis) {
									case 'list_dropdown':
										switch (tbl) {
											case 'wilayah'://tujuan sasaran renstra
												$(`.ui.organisasi.dropdown.ajx`).dropdown({ values: result.results });

												break;
											default:
												break;
										}
										break;
									case 'value1':
										break;
									default:
										break;
								};
							} else {
								kelasToast = "warning"; //'success'
							}
							showToast(result.error.message, {
								class: kelasToast,
								icon: "check circle icon",
							});

						};
						runAjax(url, "POST", data, "Json", undefined, undefined, "ajaxku", cryptos);
					}
				},
			});
		}
		setSelected(val) {
			this.element.dropdown("set selected", val);
		}
		setValue(val) {
			this.element.dropdown("set value", val);
		}
		restore() {
			this.element.dropdown("restore defaults");
		}

		setVal(val) {
			//this.element.dropdown('preventChangeTrigger', true);
			this.element.dropdown("set selected", val);
		}
		onChange(jenis = "list_dropdown", tbl = "satuan", ajax = false) {
			let ajaxSend = ajax;
			this.element.dropdown({
				onChange: function (value, text, $choice) {
					let dataChoice = $($choice).find('span.description').text();
					switch (jenis) {
						case 'getJsonRows':
							switch (tbl) {
								case 'tujuan_renstra'://tujuan sasaran renstra

									break;
								default:
									break;
							}
							break;
						case 'value1':
							break;
						default:
							break;
					};
					if (ajaxSend == true) {
						let data = {
							cari: cari(jenis),
							rows: countRows(),
							jenis: jenis,
							tbl: tbl,
							halaman: halaman,
						};
						let url = 'script/register_akun';
						let cryptos = false;

						suksesAjax["ajaxku"] = function (result) {
							var kelasToast = "success";
							if (result.success === true) {
								switch (jenis) {
									case 'getJsonRows':

										break;
									case 'value1':
										break;
									default:
										break;
								};
							} else {
								kelasToast = "warning"; //'success'
							}
							showToast(result.error.message, {
								class: kelasToast,
								icon: "check circle icon",
							});
							loaderHide();
						};
						runAjax(url, "POST", data, "Json", undefined, undefined, "ajaxku", cryptos);
					}
				},
				saveRemoteData: true,
				filterRemoteData: true
			});
		}
		valuesDropdown(values) {
			this.element.dropdown({
				values: values
			})
		}
	}
	let dropdownWilayah = new DropdownConstructor('.ui.wilayah.dropdown.ajx');
	dropdownWilayah.returnListOnChange("list_dropdown", "wilayah", 3);


	//=============================
	// ini general Ajax parameter.
	//=============================
	function ajaxParams(
		url,
		type,
		data,
		dataType,
		contentType,
		processData,
		callback
	) {
		this.url = url;
		this.type = type;
		this.formData = data;
		this.dataType = dataType;
		this.contentType = contentType;
		this.processData = processData;
		this.callback = callback;
	}
	//The global suksesAjax object, to be used for all scripts.
	var suksesAjax = [];
	// The general Ajax function.
	function generalAjax(params) {
		$.ajax({
			url: params.url,
			type: params.type,
			data: params.formData,
			dataType: params.dataType,
			contentType: params.contentType,
			processData: params.processData,
		})
			.done(function (data) {
				var callback = suksesAjax[params.callback](data);
			})
			.fail(function (jqXHR, textStatus, err) {
				try {
					var resultObject = JSON.parse(jqXHR.responseText, reviver);
					modal_notif(
						'<i class="huge info circle icon"></i>' + textStatus,
						jqXHR.responseText.split('"')[1]
					);
				} catch (e) {
					modal_notif('<i class="info icon"></i>' + textStatus, err);
				}
			});
	}
	function runAjax(
		url,
		type,
		formData,
		dataType,
		contentType,
		processData,
		callback,
		cryptos = false
	) {
		if (type === undefined) {
			type = "POST";
		}
		if (dataType === undefined) {
			dataType = "Json";
		}
		if (contentType === undefined) {
			contentType = "application/x-www-form-urlencoded; charset=UTF-8";
		}
		if (processData === undefined) {
			processData = true;
		}
		if (dataType === "Json" || dataType === "json") {
			const start = new Date().getTime();
			if (cryptos) {
				Object.keys(formData).forEach((key) => {
					const value = formData[key].toString();
					if (value.toString().length > 0) {
						formData[key] = enc.encrypt(value, halAwal);
					}
					formData.cry = cryptos;
					//formData.set(key, enc.encrypt(value, halAwal));
				});
			}
			const end = new Date().getTime();
			const diff = end - start;
			const seconds = diff / 1000; //Math.floor(diff / 1000 % 60);
		}

		var params = new ajaxParams(
			url,
			type,
			formData,
			dataType,
			contentType,
			processData,
			callback
		);
		generalAjax(params);
	}
	function showToast(message, settings) {
		settings.title = settings.title === undefined ? "info !" : settings.title;
		settings.position =
			settings.position === undefined ? "top center" : settings.position;
		settings.class = settings.class === undefined ? "info" : settings.class; //warna
		settings.icon = settings.icon === undefined ? false : settings.icon;
		settings.showProgress =
			settings.showProgress === undefined ? "bottom" : settings.showProgress;
		settings.displayTime =
			settings.displayTime === undefined ? 4500 : settings.displayTime; //0 jika ingin tampil terus sebelum diklik
		settings.showMethod =
			settings.showMethod === undefined ? "zoom" : settings.showMethod;
		settings.showDuration =
			settings.showDuration === undefined ? 1000 : settings.showDuration;
		settings.hideMethod =
			settings.hideMethod === undefined ? "fade" : settings.hideMethod;
		settings.hideDuration =
			settings.hideDuration === undefined ? 1000 : settings.hideDuration;
		$("body").toast({
			title: settings.title,
			position: settings.position,
			class: settings.class,
			showIcon: settings.icon,
			message: message,
			showProgress: settings.showProgress,
			displayTime: settings.displayTime,
			transition: {
				showMethod: settings.showMethod,
				showDuration: settings.showDuration,
				hideMethod: settings.hideMethod,
				hideDuration: settings.hideDuration,
			},
		});
	}
	// fungsi notifikasi
	function loaderShow() {
		var tinggi = $(window).height();
		//$('body > .demo.page.dimmer').dimmer('setting', {closable:true, debug:false}).dimmer('show');
		$(".demo.page.dimmer:first")
			.css("height", tinggi)
			.dimmer({
				closable: false,
				debug: false,
				"set opacity": 0,
			})
			.dimmer("show");
		//jika lebih dari 120 detik otomatis hilang sendiri
		// setTimeout(function () {
		// 	$(".demo.page.dimmer:first").dimmer("hide");
		// }, 120000);
	}
	function loaderHide() {
		$(".demo.page.dimmer:first").dimmer("hide");
	}

});
function changePassView() {
	let getPwdView = $("input[name='password']");
	const type = getPwdView.attr("type") === "password" ? "text" : "password";
	getPwdView.attr("type", type)
}