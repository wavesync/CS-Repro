/*
 *  Validate.js, version 1.7
 *  LastUpDate 2013/08/13
 *  LastUpDate 2014/02/12	v1.3 Add isValue by H.Yanagawa 
 *  LastUpDate 2014/02/14	v1.4 Add endsWith by H.Yanagawa 
 *  LastUpDate 2014/02/18	v1.5 Expand decimal option to check inte, dec option by M.Yokokawa
 *  LastUpDate 2014/02/27	v1.6 set var for local variable like [for (i = 0] to [for (var i = 0]
 *  LastUpDate 2014/07/02	v1.7 Fixed isDate function for Invalid Date
 *  LastUpDate 2015/01/01	v1.9 Fixed decimal errmsg bug. by H.Yanagawa
 *  LastUpDate 2015/02/03	v2.0 Add errBox option to show / hide. by H.Yanagawa
 *  LastUpDate 2015/07/31	v2.1 Fixed isChars function for check.
 * 
 **/


/* 変換
--------------------------------------------------------------------------*/
// 空白を取り除く
function trim(str) {
	if (str) {
		return str.replace(/^[\s　]+|[\s　]+$/g, '');
	} else {
		return "";
	}
}
// 全角英数 → 半角英数へ変換
function hankaku(str) {
	if (str) {
		return str.replace(/[！-～]/g, function(s) {
		    return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
		});
	} else {
		return "";
	}
}
// 大文字 → 小文字へ変換
function toLower(str) {
	if (str) {
		return str.toLowerCase();
	} else {
		return "";
	}
}
// 小文字 → 大文字へ変換
function toUpper(str) {
	if (str) {
		return str.toUpperCase();
	} else {
		return "";
	}
}
//数値3桁区切り
function digitSeparator(str, type) {
	var num = notDigitSeparator(str, type);  //[,]または[']が入ってる場合は一度外す
	while(num != (num = num.replace(/^(-?\d+)(\d{3})/, "$1" + type + "$2")));
	return num;
}
//数値3桁区切り外し
function notDigitSeparator(str, type) {
	if (str == null) return "";
	var separator = new RegExp(type, "g");
	return str.toString().replace(separator, '');
}
//日付フォーマット変換 (dateformat.jsと併用して使用する)
function getDateFormatText(str, from, to) {
	if (!str) return "";
	var date = comDateParse(str, from);
	return comDateFormat(date, to);
}
//マルチバイト除去（英数字記号のみ)
function multiByteDelete(str) {
	tmp = str.split("");
	for(var i = 0; i < tmp.length; i++){
		if(tmp[i].match(/^[a-zA-Z0-9!-/:-@¥[-`{-~]+$/) == null){
			tmp[i] = "";
		}
	}
	return tmp.join("");
}
//指定した文字を置換
function charsReplace(str, from, to) {
	return str.replace(new RegExp(from, "g"), to);
}




/* 値の取得
--------------------------------------------------------------------------*/
//入力値の取得
function getValue(s){
	if (document.getElementById(s)) {
		return document.getElementById(s).value;
	}
}
//選択項目取得（multiple)
function selectValues(s){
	var value_array = [];
	for (var i = 0; i < document.getElementById(s).options.length; i++) {
		if (document.getElementById(s).options[i].selected) 
			value_array.push(document.getElementById(s).options[i].value);
	}
	return value_array;
}
//チェック項目取得
function checkValues(name){
	var value_array = [];
	for (var i = 0; i < document.getElementsByName(name).length; i++) {
		if (document.getElementsByName(name)[i].checked == true) 
			value_array.push(document.getElementsByName(name)[i].value);
	}
	return value_array;
}

//値をセット
function setValue(s, str){
	if (document.getElementById(s)) {
		document.getElementById(s).value = str;
	}
}

/* バリデーション
--------------------------------------------------------------------------*/
var Class = {
	create: function() {
		return function() {
			this.initialize.apply(this, arguments);
		}
	}
}

var Validate = Class.create();
Validate.prototype = {
	initialize:function(){
		this.conv_num = 0;
		this.error_msg = [];
		this.supports_array = [];
		this.check_array = [];
		this.messages = [];
		this.prepare_array = [];
		this.e = true;
	},
	/*--------------------------------------------------------------------------*/
    defaultMsg: {
		"errmsg": [{
		    'required': "【%s】は必須項目です。",
		    'selected':  "【%s】は必須項目です。",
		    'multiSelected':  "【%s】は必須項目です。",
		    'numSelected': "【%s】は{0}個選択してください。",
		    'checked': "【%s】は必須項目です。",
		    'numChecked': "【%s】は{0}個選択してください。",
		    'isValue': "【%s】の値が不正です。",
		    'notValue': "【%s】の値が不正です。",
		    'length': "【%s】は{0}文字で入力してください。",
		    'byteLength': "【%s】は{0}バイトで入力してください。",
		    'number': "【%s】は半角数字で入力してください。",
		    'integer': "【%s】は数値ではありません。",
		    'integerRange': "【%s】は{0} ～ {1}の範囲内の数値で入力してください。",
		    'alpha': "【%s】は半角英数字で入力してください。",
		    'hiragana': "【%s】はひらがなで入力してください。",
		    'katakana': "【%s】は全角カタカナで入力してください。",
		    'email': "【%s】のメールアドレス表記が正しくありません。",
		    'mobileMail': "【%s】の携帯アドレス表記が正しくありません。",
		    'url': "【%s】のURL表記が正しくありません。",
		    'Tel': "【%s】の電話番号表記が正しくありません。",
		    'zipCode': "【%s】の郵便番号表記が正しくありません。",
		    'date': "【%s】は{0}形式で入力してください。",
		    'dateRnage': "【%s】は{0} ～ {1}の範囲内で入力してください。",
		    'dateCompare': "【%s】の終了が開始より前の日付になっています。",
		    'isChar': "【%s】に{0}は以外含まないでください。",
		    'notChar': "【%s】に{0}は含まないでください。",
			'startsWith': "【%s】の先頭文字は「{0}」で開始してください。",
			'endsWith': "【%s】の終了文字は「{0}」で開始してください。",
			'decimal': "【%s】は小数（{0}）で入力してください。"
		}, {
		    'numSelected': "【%s】は{0}個以上選択してください。",
		    'numChecked': "【%s】は{0}個以上選択してください。",
		    'length': "【%s】は{0}文字以上で入力してください。",
		    'integerRange': "【%s】は{0}以上の数値で入力してください。"
		}, {
		    'numSelected': "【%s】は{1}個以下選択してください。",
		    'numChecked': "【%s】は{1}個以下選択してください。",
		    'length': "【%s】は{1}文字以下で入力してください。",
		    'integerRange': "【%s】は{1}以下の数値で入力してください。"
		}, {
		    'numSelected': "【%s】は{0}～{1}個選択してください。",
		    'numChecked': "【%s】は{0}～{1}個選択してください。",
		    'length': "【%s】は{0}～{1}文字で入力してください。"
		}]
    },
	/*--------------------------------------------------------------------------*/
	setMessage : function(d) {
		this.messages = (d) ? $.extend(true, this.defaultMsg.errmsg, d) : this.defaultMsg.errmsg;
	},
	/*--------------------------------------------------------------------------*/
	escapeReplace:function(string){
		string = string.replace(new RegExp("[\\\\]]", "g"), "]");
		return string.replace(/\\\\/, "\\");
	},
	/*--------------------------------------------------------------------------*/
	selectorEscape:function(string) {
		return string.replace(/[ !"#$%&'()*+,.\/:;<=>?@\[\\\]^`{|}~]/g, '\\$&');
	},
	/* 空ならTrue
	--------------------------------------------------------------------------*/
	isEmpty:function(s){
		return !/\S/.test(s);
	},
	/* 空じゃなければTrue
	--------------------------------------------------------------------------*/
	isNotEmpty:function (string){
		return /\S/.test(string);
	},
	/* 指定した値じゃなければTrue
	--------------------------------------------------------------------------*/
	isNotValue:function(string, arr){
		if (!string) return true;
		arr = arr.split(",");
		for (var i = 0; i < arr.length; i++) {
			if(string == arr[i]) {
				return false;
				break;
			}
		}
		return true;
	},
	/* slectbox(multiple)が選択されていればTrue
	--------------------------------------------------------------------------*/
	isMultipleSelected:function(arr){
		if (arr.length == 0) {
			return false;
		} else {
			return true;
		}
	},
	/* slectbox(multiple)の選択が指定した個数、または範囲内の個数であればTrue
	--------------------------------------------------------------------------*/
	isNumSelected:function(arr, min, max){
		return this.isIntegerRange(arr.length, min, max);
	},
	/* radio,checoboxが選択されていればTrue
	--------------------------------------------------------------------------*/
	isChecked:function(arr){
		if (arr.length == 0) {
			return false;
		} else {
			return true;
		}
	},
	/* radio,checkboxの選択が指定個数、または範囲内の個数であればTrue
	--------------------------------------------------------------------------*/
	isNumChecked:function(arr, min, max){
		return this.isIntegerRange(arr.length, min, max);
	},
	/* 指定した文字数、または範囲内であればTrue
	--------------------------------------------------------------------------*/
	isCharLength:function(s, min, max){
		if (!s) return true;
		return this.isIntegerRange(s.length, min, max);
	},
	/* 指定したバイト数であればTrue
	--------------------------------------------------------------------------*/
	isByteLength:function(s, byte){
		if (!s) return true;
		var flg = true;
	    s = escape(s);
	  	var len = 1;
	    for(var i = 0; i < s.length; i++){
	        if(s.charAt(i) == "%"){
	            if(s.charAt(++i) == "u"){
	                i += 3;
	                len = 2;
	            }
	            i++;
	        }
			if (len != byte) {
				flg = false; break;
			}
			len = 1;
	    }
		return flg;
	},
	/* 入力した値が0-9の数値であればTrue
	--------------------------------------------------------------------------*/
	isNum:function(number){
		if(!number) return true;
		numRegExp = /^[0-9]+$/;
		return numRegExp.test(number);
	},
	/* 入力した値が0-9または-であればTrue
	--------------------------------------------------------------------------*/
	isInteger:function(str){
		if (!str) return true;
		numRegExp = /^[0-9-]+$/;
		return numRegExp.test(str);
	},
	/* 入力した数値が指定した範囲内であればTrue
	--------------------------------------------------------------------------*/
	isIntegerRange:function(n, Nmin, Nmax){
		if (n == "") return true;
		if (n == parseFloat(n)) {
			if (Nmin && Nmax) {
				if (Nmin == Nmax) {
					return (n == parseFloat(Nmin));
				} else {
					var flg = false;
					if (Nmin.match(/\*/)) {
						flg = (n > parseFloat(Nmin.replace(/\*/, '')));
					} else {
						flg = (n >= parseFloat(Nmin));
					}
					if (flg == false) { return false; }
					if (Nmax.match(/\*/)) {
						flg = (n < parseFloat(Nmax.replace(/\*/, '')));
					} else {
						flg = (n <= parseFloat(Nmax));
					}
					return flg;
				}
			} else if (Nmin && !Nmax) {
				if (Nmin.match(/\*/)) {
					return (n > parseFloat(Nmin.replace(/\*/, '')));
				} else {
					return (n >= parseFloat(Nmin));
				}
			} else if (!Nmin && Nmax) {
				if (Nmax.match(/\*/)) {
					return (n < parseFloat(Nmax.replace(/\*/, '')));
				} else {
					return (n <= parseFloat(Nmax));
				}
			} else {
				return true;
			}
		}
	},
	/* 0-9a-zA-Zの半角英数字であればTrue
	--------------------------------------------------------------------------*/
	isAlpha:function(string){
		if(!string) return true;
		alphaRegExp = /^[0-9a-zA-Z]+$/i
		return alphaRegExp.test(string);
	},
	/* ひらがなならTrue
	--------------------------------------------------------------------------*/
    isHiragana:function(string) {
		if(!string) return true;
		hiraganaRegExp = /^[ぁ-ゞ]+$/g;
    	return hiraganaRegExp.test(string);
    },
	/* 全角カタカナならTrue
	--------------------------------------------------------------------------*/
    isKatakana:function(string) {
		if(!string) return true;
		katakanaRegExp = /^[ァ-ヾ]+$/g;
    	return katakanaRegExp.test(string);
    },
	/* メールアドレスの形式であればTrue
	--------------------------------------------------------------------------*/
	isEMailAddr:function(string){
		if(!string) return true;
		emailRegExp = /^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9_\-]+(\.[a-zA-Z0-9_\-]+)+$/
		return emailRegExp.test(string);
	},
	/* 携帯ドメインで終了していればTrue
	--------------------------------------------------------------------------*/
	isMobileMailAddr:function (string) {
		if(!string) return true;
		//携帯ドメイン
		var Domain = [
			"docomo.ne.jp",
			"softbank.ne.jp",
			"vodafone.ne.jp",
			"disney.ne.jp",
			"i.softbank.jp",
			"ezweb.ne.jp",
			"biz.ezweb.ne.jp",
			"ido.ne.jp",
			"emnet.ne.jp",
			"emobile.ne.jp",
			"pdx.ne.jp",
			"willcom.com",
			"wcm.ne.jp"
		];
		var flg = false;
		for(var i = 0; i < Domain.length; i++){
			var mMailRegExp = new RegExp("^[a-zA-Z0-9_\\-\\.]+@" + Domain[i] + "+$", "i");
			if (mMailRegExp.test(string)) {
				flg = true;
				break;
			}
		}
		return flg;
	},
	/* http://,https://,ftp://,ftps://で始まっていればTrue
	--------------------------------------------------------------------------*/
	isURL:function(string){
		if(!string) return true;
		urlRegExp = /^(((ht|f)tp(s?))\:\/\/)(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/\S*)?$/
		return urlRegExp.test(string);
	},
	/* 0-9+-()のみで記載されていればTrue
	--------------------------------------------------------------------------*/
	isTEL:function(string){
		if(!string) return true;
		telRegExp = /^[\(\)0-9+-]+$/;
		return telRegExp.test(string);
	},
	/* 指定した国の表記であればTrue（JP:日本、US:米、GLOBAL：その他の国）
	--------------------------------------------------------------------------*/
	isZipCode:function(zipcode,country){
		if(!zipcode) return true;
		if(!country) country = 'GLOBAL';
		switch(country){
			case'JP': zpcRegExp = /^\d{7}$|^\d{3}-\d{4}$|^\d{3}$/; break;
			case'US': zpcRegExp = /^\d{5}$|^\d{5}-\d{4}$/; break;
			case'GLOBAL': zpcRegExp = /^[a-zA-Z0-9\-]+$/; break;
		}
		return zpcRegExp.test(zipcode);
	},
	/* 指定した日付表記で記載されていればTrue
	--------------------------------------------------------------------------*/
	isDate:function(date, format){
		if(!date) return true;
		if(!format) format = 'yyyy/MM/dd';
		if (comDate = comDateParse(date, format)) {
			if (Object.prototype.toString.call(comDate) === "[object Date]" ) {
				if (isNaN(comDate.getTime())) {
					return false;
				} else {
					comDate = comDateFormat(comDate, format);
					if (date == comDate) return true;
					else return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	},
	/* 指定した日付範囲内であればTrue
	--------------------------------------------------------------------------*/
	isDateInRange:function(date, from, to, format){
		if(!date) return true;
		if(!from) return true;
		if(!to) return true;
		if(!format) format = 'yyyy/MM/dd';
		if (date = comDateParse(date, format)) {
			from = comDateParse(from, format);
			to = comDateParse(to, format);
		    if (date < from || date > to) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	},
	/* 終了日が開始日より前になっていなければTrue
	--------------------------------------------------------------------------*/
	isDateCompare:function(date, cf, format){
		if(!date) return true;
		if(!cf) return true;
		if(!format) format = 'yyyy/MM/dd';
		if (date = comDateParse(date, format)) {
			cf = comDateParse(cf, format);
		    if (date > cf) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	},
	/* flg:Trueの場合　指定文字のみ使用されていればTrue
	 * flg:Falseの場合　指定文字が含まれていなかったらTrue
	--------------------------------------------------------------------------*/
	isChars:function(s, characters, flg){
		if (!s) return true;
		if (flg) {
			return !new RegExp("[^" + characters + "]+", "g").test(s);
		} else {
			return !new RegExp("[" + characters + "]+", "g").test(s);
		}
	},
	/* 開始文字が指定した文字ならばTrue
	--------------------------------------------------------------------------*/
	isStartsWith:function(s, val){
		if (!s) return true;
		val = val.replace(/\,/g, "|");
		startRegExp = new RegExp("^(" + this.selectorEscape(val) + ")");
		return startRegExp.test(s);
	},
	/* 終了文字が指定した文字ならばTrue
	--------------------------------------------------------------------------*/
	isEndsWith:function(s, val){
		if (!s) return true;
		val = val.replace(/\,/g, "|");
		endRegExp = new RegExp("(" + this.selectorEscape(val) + ")$");
		return endRegExp.test(s);
	},
	/* 小数ならTrue
	 * inte:整数の桁、dec:小数の桁 桁数があっていればTrue
	--------------------------------------------------------------------------*/
	isDecimal:function(number, type, inte, dec){
		if(!number) return true;
		type = (!type) ? "." : type;
		decimalRegExp = new RegExp("^-?(0|[1-9]{1}\\d{0,})\\" + type + "(\\d{1})(\\d{0,})?$");
		if(decimalRegExp.test(number)) {
			num = number.split(type);
			num[0] = num[0].replace(/-/g, "");
			if (inte.match(/\</)) {
				inte = inte.replace(/\</g, "");
				if (inte > num[0].length)  return false;
			} else if (inte.match(/\>/)) {
				inte = inte.replace(/\>/g, "");
				if (inte < num[0].length)  return false;
			} else {
				if (inte != num[0].length)  return false;
			}
			if (dec.match(/\</)) {
				dec = dec.replace(/\</g, "");
				if (dec > num[1].length)  return false;
			} else if (dec.match(/\>/)) {
				dec = dec.replace(/\>/g, "");
				if (dec < num[1].length)  return false;
			} else {
				if (dec != num[1].length)  return false;
			}
			return true;
		} else {
			if (!this.isInteger(number)) {
				return false;
			} else {
				number = number.replace(/-/g, "");
				if (inte.match(/\</)) {
					inte = inte.replace(/\</g, "");
					if (inte > number.length)  return false;
				} else if (inte.match(/\>/)) {
					inte = inte.replace(/\>/g, "");
					if (inte < number.length)  return false;
				} else {
					if (inte != number.length)  return false;
				}
				if (dec.match(/\</)) {
					dec = dec.replace(/\</g, "");
					if (dec > 0)  return false;
				} else if (dec.match(/\>/)) {
					return true;
				} else {
					return false;
				}
				return true;
			}
		}
	},
	/* チェック実行
	--------------------------------------------------------------------------*/
	addChecks:function(chk){
		this.check_array = this.check_array.concat(chk);
	},
	/*--------------------------------------------------------------------------*/
	conversion:function(id, conv){
		for(var i=0; i < conv.length; i++){
			switch(conv[i].option) {
				/*--------------------------------------------------------------------------*/
				case'trim' :
					 val = trim(getValue(id));
					
				break;
				/*--------------------------------------------------------------------------*/
				case'hankaku' :
					 val = hankaku(getValue(id));
				break;
				/*--------------------------------------------------------------------------*/
				case'lower' :
					 val = toLower(getValue(id));
				break;
				/*--------------------------------------------------------------------------*/
				case'upper' :
					 val = toUpper(getValue(id));
				break;
				/*--------------------------------------------------------------------------*/
				case'digit':
					val = digitSeparator(getValue(id), conv[i].type);
				break;
				/*--------------------------------------------------------------------------*/
				case'notdigit':
					val = notDigitSeparator(getValue(id), conv[i].type);
				break;
				/*--------------------------------------------------------------------------*/
				case'deteformat':
					val = getDateFormatText(getValue(id), conv[i].from, conv[i].to);
				break;
				case'multibyteDel':
					val = multiByteDelete(getValue(id));
				break;
				case'charsReplace':
					val = charsReplace(getValue(id), conv[i].from, conv[i].to);
				break;
				default :
					val = getValue(id);
				break;
			}
			setValue(id, val);
		}
	},
	/*--------------------------------------------------------------------------*/
	check:function(){
		this.error_msg = [];
		this.e = true;
		for(var i=0; i < this.check_array.length; i++){
			if (this.check_array[i].conv) this.conversion(this.check_array[i].id, this.check_array[i].conv);   //変換を実行
			var chk = this.check_array[i].chk;
			if (this.check_array[i].el) $("#" + this.check_array[i].el).empty(); //エラー表示を空にする
			if (this.check_array[i].errBox) $("#" + this.check_array[i].errBox).hide(); //エラー表示を非表示にする
			var fieldLable = (this.check_array[i].fieldLabel) ?	this.check_array[i].fieldLabel : this.check_array[i].id;
			for (var j = 0; j < chk.length; j++) {
				switch(chk[j].option){
					/*--------------------------------------------------------------------------*/
					case'required':
						if (this.isEmpty(getValue(this.check_array[i].id))){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var errMsg = this.messages[0][chk[j].option];
							}
							errMsg = errMsg.replace('%s', fieldLable);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'selected':
						if(!this.isNotValue(getValue(this.check_array[i].id), chk[j].val)){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var errMsg = this.messages[0][chk[j].option];
							}
							errMsg = errMsg.replace('%s', fieldLable);
							errMsg = errMsg.replace('{0}', chk[j].val);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'multiSelected':
						if(!this.isMultipleSelected(selectValues(this.check_array[i].id))){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var errMsg = this.messages[0][chk[j].option];
							}
							errMsg = errMsg.replace('%s', fieldLable);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'numSelected':
						if(!this.isNumSelected(selectValues(this.check_array[i].id), chk[j].min, chk[j].max)){
							var min; var max;
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								if (chk[j].min && chk[j].max) {
									min = chk[j].min.replace(/\*/, '');
									max = chk[j].max.replace(/\*/, '');
									if (chk[j].min == chk[j].max) {
										var errMsg = this.messages[0][chk[j].option];
									} else {
										var errMsg = this.messages[3][chk[j].option];
									}
								} else if (chk[j].min) {
									var errMsg = this.messages[1][chk[j].option];
									min = chk[j].min.replace(/\*/, '');
								} else if (chk[j].max) {
									var errMsg = this.messages[2][chk[j].option];
									max = chk[j].max.replace(/\*/, '');
								}
							}
							errMsg = errMsg.replace('%s', fieldLable);
							errMsg = errMsg.replace('{0}', min);
							errMsg = errMsg.replace('{1}', max);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'checked':
						if(!this.isChecked(checkValues(this.check_array[i].id))){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								var errMsg = this.messages[0][chk[j].option];
							}
							errMsg = errMsg.replace('%s', fieldLable);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'numChecked':
						if(!this.isNumChecked(checkValues(this.check_array[i].id), chk[j].min, chk[j].max)){
							var min; var max;
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								if (chk[j].min && chk[j].max) {
									min = chk[j].min.replace(/\*/, '');
									max = chk[j].max.replace(/\*/, '');
									if (chk[j].min == chk[j].max) {
										var errMsg = this.messages[0][chk[j].option];
									} else {
										var errMsg = this.messages[3][chk[j].option];
									}
								} else if (chk[j].min) {
									var errMsg = this.messages[1][chk[j].option];
									min = chk[j].min.replace(/\*/, '');
								} else if (chk[j].max) {
									var errMsg = this.messages[2][chk[j].option];
									max = chk[j].max.replace(/\*/, '');
								}
							}
							errMsg = errMsg.replace('%s', fieldLable);
							errMsg = errMsg.replace('{0}', min);
							errMsg = errMsg.replace('{1}', max);

							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'isValue':
						if(this.isNotValue(getValue(this.check_array[i].id), chk[j].val)){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								var errMsg = this.messages[0][chk[j].option];

							}
							errMsg = errMsg.replace('%s', fieldLable);
							errMsg = errMsg.replace('{0}', chk[j].val);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'notValue':
						if(!this.isNotValue(getValue(this.check_array[i].id), chk[j].val)){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								var errMsg = this.messages[0][chk[j].option];

							}
							errMsg = errMsg.replace('%s', fieldLable);
							errMsg = errMsg.replace('{0}', chk[j].val);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'length':
						if(!this.isCharLength(getValue(this.check_array[i].id), chk[j].min, chk[j].max)){
							var min; var max;
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								if (chk[j].min && chk[j].max) {
									min = chk[j].min.replace(/\*/, '');
									max = chk[j].max.replace(/\*/, '');
									if (chk[j].min == chk[j].max) {
										var errMsg = this.messages[0][chk[j].option];
									} else {
										var errMsg = this.messages[3][chk[j].option];
									}
								} else if (chk[j].min) {
									var errMsg = this.messages[1][chk[j].option];
									min = chk[j].min.replace(/\*/, '');
								} else if (chk[j].max) {
									var errMsg = this.messages[2][chk[j].option];
									max = chk[j].max.replace(/\*/, '');
								}
							}
							errMsg = errMsg.replace('%s', fieldLable);
							errMsg = errMsg.replace('{0}', min);
							errMsg = errMsg.replace('{1}', max);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'byteLength':
						if(!this.isByteLength(getValue(this.check_array[i].id), chk[j].byte)){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								var errMsg = this.messages[0][chk[j].option];

							}
							errMsg = errMsg.replace('%s', fieldLable);
							errMsg = errMsg.replace('{0}', chk[j].byte);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'number':
						if (!this.isNum(getValue(this.check_array[i].id))){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								var errMsg = this.messages[0][chk[j].option];
							}
							errMsg = errMsg.replace('%s', fieldLable);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'integer':
						if (!this.isInteger(getValue(this.check_array[i].id))){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								var errMsg = this.messages[0][chk[j].option];
							}
							errMsg = errMsg.replace('%s', fieldLable);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'integerRange':
						if (!this.isIntegerRange(getValue(this.check_array[i].id),chk[j].min,chk[j].max)){
							var min; var max;
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								if (chk[j].min && chk[j].max) {
									min = chk[j].min.replace(/\*/, '');
									max = chk[j].max.replace(/\*/, '');
									var errMsg = this.messages[0][chk[j].option];
								} else if (chk[j].min) {
									var errMsg = this.messages[1][chk[j].option];
									min = chk[j].min.replace(/\*/, '');
								} else if (chk[j].max) {
									var errMsg = this.messages[2][chk[j].option];
									max = chk[j].max.replace(/\*/, '');
								}
							}
							errMsg = errMsg.replace('%s', fieldLable);
							errMsg = errMsg.replace('{0}', min);
							errMsg = errMsg.replace('{1}', max);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'alpha':
						if(!this.isAlpha(getValue(this.check_array[i].id))){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								var errMsg = this.messages[0][chk[j].option];
							}
							errMsg = errMsg.replace('%s', fieldLable);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'hiragana':
						if (!this.isHiragana(getValue(this.check_array[i].id))){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								var errMsg = this.messages[0][chk[j].option];
							}
							errMsg = errMsg.replace('%s', fieldLable);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'katakana':
						if (!this.isKatakana(getValue(this.check_array[i].id))){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								var errMsg = this.messages[0][chk[j].option];
							}
							errMsg = errMsg.replace('%s', fieldLable);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'email':
						if (!this.isEMailAddr(getValue(this.check_array[i].id))){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								var errMsg = this.messages[0][chk[j].option];
							}
							errMsg = errMsg.replace('%s', fieldLable);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'mobileMail':
						if (!this.isMobileMailAddr(getValue(this.check_array[i].id))){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								var errMsg = this.messages[0][chk[j].option];
							}
							errMsg = errMsg.replace('%s', fieldLable);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'url':
						if(!this.isURL(getValue(this.check_array[i].id))){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								var errMsg = this.messages[0][chk[j].option];
							}
							errMsg = errMsg.replace('%s', fieldLable);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'Tel':
						if (!this.isTEL(getValue(this.check_array[i].id))){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								var errMsg = this.messages[0][chk[j].option];
							}
							errMsg = errMsg.replace('%s', fieldLable);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'zipCode':
						if (!this.isZipCode(getValue(this.check_array[i].id),chk[j].country)){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								var errMsg = this.messages[0][chk[j].option];
							}
							errMsg = errMsg.replace('%s', fieldLable);
							errMsg = errMsg.replace('{0}', chk[j].country);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'date':
						var format = (!chk[j].format) ? "yyyy/MM/dd" : chk[j].format;
						if(!this.isDate(getValue(this.check_array[i].id), format)){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								var errMsg = this.messages[0][chk[j].option];
							}
							errMsg = errMsg.replace('%s', fieldLable);
							errMsg = errMsg.replace('{0}', format);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'dateRnage':
						if(!this.isDateInRange(getValue(this.check_array[i].id), chk[j].from, chk[j].to, chk[j].format)){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								var errMsg = this.messages[0][chk[j].option];
							}
							errMsg = errMsg.replace('%s', fieldLable);
							errMsg = errMsg.replace('{0}', chk[j].from);
							errMsg = errMsg.replace('{1}', chk[j].to);
							errMsg = errMsg.replace('{2}', chk[j].format);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'dateCompare':
						if(!this.isDateCompare(getValue(this.check_array[i].id), getValue(chk[j].cf), chk[j].format)){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								var errMsg = this.messages[0][chk[j].option];
							}
							errMsg = errMsg.replace('%s', fieldLable);
							errMsg = errMsg.replace('{0}', getValue(chk[j].cf));
							errMsg = errMsg.replace('{1}', chk[j].format);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'isChar':
						if(!this.isChars(getValue(this.check_array[i].id),chk[j].char,true)){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								var errMsg = this.messages[0][chk[j].option];
							}
							errMsg = errMsg.replace('%s', fieldLable);
							errMsg = errMsg.replace('{0}', this.escapeReplace(chk[j].char));
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'notChar':
						if(!this.isChars(getValue(this.check_array[i].id),chk[j].char,false)){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								var errMsg = this.messages[0][chk[j].option];
							}
							errMsg = errMsg.replace('%s', fieldLable);
							errMsg = errMsg.replace('{0}', this.escapeReplace(chk[j].char));
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'startsWith':
						if(!this.isStartsWith(getValue(this.check_array[i].id), chk[j].initial)){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								var errMsg = this.messages[0][chk[j].option];
							}
							errMsg = errMsg.replace('%s', fieldLable);
							errMsg = errMsg.replace('{0}', chk[j].initial);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'endsWith':
						if(!this.isEndsWith(getValue(this.check_array[i].id), chk[j].initial)){
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								var errMsg = this.messages[0][chk[j].option];
							}
							errMsg = errMsg.replace('%s', fieldLable);
							errMsg = errMsg.replace('{0}', chk[j].initial);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
					/*--------------------------------------------------------------------------*/
					case'decimal':
						if(!this.isDecimal(getValue(this.check_array[i].id), chk[j].type, chk[j].inte, chk[j].dec)){
							number = "";
							if (chk[j].errmsg) {
								var errMsg = chk[j].errmsg;
							} else {
								var type = (this.check_array[i].type) ? this.check_array[i].type : ".";
								if (chk[j].inte || chk[j].dec) {
									for(var l = 0; l < parseInt(chk[j].inte); l++) {
										number += "#";
									}
									number += type;
									for(var l = 0; l < parseInt(chk[j].dec); l++) {
										number += "#";
									}
								}
								var fieldLable = (this.check_array[i].fieldLabel) ?
									this.check_array[i].fieldLabel : this.check_array[i].id;
								var errMsg = this.messages[0][chk[j].option];
							}
							errMsg = errMsg.replace('%s', fieldLable);
							errMsg = errMsg.replace('{0}', number);
							errMsg = errMsg.replace('{1}', chk[j].inte);
							errMsg = errMsg.replace('{2}', chk[j].dec);
							if (this.check_array[i].el) {
								$("#" + this.check_array[i].el).append(errMsg);
							}
							if (this.check_array[i].errBox)  {
								$("#" + this.check_array[i].errBox).html(errMsg);
								$("#" + this.check_array[i].errBox).show();
							}
							this.error_msg.push(errMsg);
							this.e = false;
						}
					break;
				}
			}
		}
	},
	/*--------------------------------------------------------------------------*/
	getJsonData:function(json, parm) {
		var d = [];
		$.ajaxSetup({async: false});
		$.getJSON(json, parm, function(data){
			d = (data.errmsg);
		});
		return d;
		$.ajaxSetup({async: true});
	},
	/*--------------------------------------------------------------------------*/
	convert:function(){
		for(var i=0; i < this.check_array.length; i++){
			var intVal = getValue(this.check_array[i].id);  //変換前の値
			if (this.check_array[i].conv) {
				this.conversion(this.check_array[i].id, this.check_array[i].conv);   //変換を実行
			}
			var val = getValue(this.check_array[i].id);  //変換後の値
			if (intVal != val) this.conv_num++;

		}
		return this.conv_num;
	},
	/*--------------------------------------------------------------------------*/
	apply:function(flg, el, json, parm){
		flg = (flg == undefined) ? true: flg;
		var d = (json) ?  this.getJsonData(json, parm) : [];
		this.setMessage(d);
		this.check();
		if(this.e) {
			return true;
		}else{
			if(flg) {
				alert(this.error_msg.join("\n"));
			}
			if (el) {
				document.getElementById(el).innerHTML = this.error_msg.join("<br />");
			}
			return false;
		}
	},
	/* チェック実行
	--------------------------------------------------------------------------*/
	prepareCheck:function(op, txt, pos){
		var num = 0;
		for(var i=0; i < this.check_array.length; i++){
			var chk = this.check_array[i].chk;
			for (var n = 0; n < chk.length; n++) {
				if (chk[n].option == op) {
					this.prepareEv(this.check_array[i].id, txt, pos);
					num++;
				}
			}
		}
		return num;
	},
	/*-------------------------------------------------------------------------- */
	prepareEv:function(id, txt, pos) {
		pos = pos.split(" ");
		if (this.isStartsWith(pos[0], "#")) {
			$(pos[0]).append(txt);
		} else {
			if (pos.length > 1) {
				elm = $("#" + id).parents(pos[0]);
				pos.shift();
				$(elm).find(pos.join(" ")).append(txt);
			} else {
				$("#" + id).siblings(pos[0]).append(txt);
			}
		}
	},
	/*--------------------------------------------------------------------------*/
	prepare:function(){
		for(var i=0; i < this.check_array.length; i++){
			if (this.check_array[i].prepare) {
				var pre = this.check_array[i].prepare;
				for (var n = 0; n < pre.length; n++) {
					this.prepareEv(this.check_array[i].id, pre[n].text, pre[n].pos);
				}
			}
		}
	}
}
