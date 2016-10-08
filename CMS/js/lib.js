function JobFinderLib(settings){
	_this = this;
}

//コードマスタ
JobFinderLib.prototype.codes = null;

//メモリからコードを取得
JobFinderLib.prototype.loadCode = function(){
	if(this.codes == null) {
		var work = localStorage.getItem('CODES');

		if(work == null){
			return null;
		}
		
		this.codes = JSON.parse(work);
	}

	return this.codes;
};

//コードマスタ保存
JobFinderLib.prototype.saveCode = function(data){
	this.codes = data;
	localStorage.setItem('CODES', JSON.stringify(_this.codes));
}


/**
 * コンボボックス生成
 * @param id
 * @param code
 */
JobFinderLib.prototype.buildCombo = function(id, code, isEmpty, val){
	sel = $('#' + id);
	sel.html('');
	if(_this.codes == null){
		_this.loadCode(); //コード
	}	
	
	if(isEmpty){
		$('<option value="">未選択</option>').appendTo(sel);
	}
	
	$.each(_this.codes, function(index, t_code){
		if(t_code.code == code){
			$(String.format('<option value="{0}">{1}</option>', t_code.codeDetail, t_code.codeDetailName)).appendTo(sel);
		}
	});
	
	if(val != undefined){
		sel.val(val)
		if(sel.val() == undefined){
			sel.val('');
		}
		sel.change();
	}
}

/**
 * チェックボックスリスト生成
 * @param id
 * @param code
 */
JobFinderLib.prototype.buildCheckBoxList = function(parentId, name, code, columns, defaultValues){
	parent = $('#' + parentId);
	parent.html('');
	
	if(_this.codes == null){
		_this.loadCode(); //コード
	}
	
	if(columns == undefined || columns == ''){
		codeIndex = 0;
		$.each(_this.codes, function(index, t_code){
			if(t_code.code == code){
				codeIndex++;
				chk = $(String.format('<input type="checkbox" name="{0}[]" id="{0}{1}" value="{2}">', name, codeIndex, t_code.codeDetail)).appendTo(parent);
				$(String.format('<label for="{0}{1}">{2}</label>', name, codeIndex, t_code.codeDetailName)).appendTo(parent);
				if(_this.inList(defaultValues, t_code.codeDetail)){
					chk.attr('checked', 'checked');
				}
			}
		});
	}
	else{
		table = $('<table/>').appendTo(parent);
		
		count = 0;
		var tr;
		$.each(_this.codes, function(index, t_code){
			if(t_code.code == code){
				if(count % columns == 0){
					tr = $('<tr/>').appendTo(table);
				}
				td = $('<td style="border:none;"/>').appendTo(tr);
				chk = $(String.format('<input type="checkbox" name="{0}[]" id="{0}{1}" value="{2}">', name, count + 1, t_code.codeDetail)).appendTo(td);
				$(String.format('<label for="{0}{1}">{2}</label>', name, count + 1, t_code.codeDetailName)).appendTo(td);
				if(_this.inList(defaultValues, t_code.codeDetail)){
					chk.attr('checked', 'checked');
				}
				
				count++;
			}
		});
	}
}

/**
 * 配列にあるチェック
 * @param $lst
 * @param $val
 */
JobFinderLib.prototype.inList = function(lst, val){
	if(lst == undefined || lst == '') return false;
	if(val == undefined || val == '') return false;
	
	vals = lst.split(',');
	if($.inArray(val, vals) >= 0) return true;
	return false;
}

/**
 * ラジオボックスリスト生成
 * @param id
 * @param code
 */
JobFinderLib.prototype.buildRadioList = function(parentId, name, code, defaultValue, isCheck){
	parent = $('#' + parentId);
	parent.html('');
	
	if(_this.codes == null){
		_this.loadCode(); //コード
	}
	
	count = 0;
	$.each(_this.codes, function(index, t_code){
		if(t_code.code == code){
			count++;
			radio = $(String.format('<input type="radio" name="{0}" id="{0}{1}" value="{2}">', name, count, t_code.codeDetail)).appendTo(parent);			
			$(String.format('<label for="{0}{1}">{2}</label>', name, count, t_code.codeDetailName)).appendTo(parent);
			if(defaultValue != undefined && defaultValue == t_code.codeDetail){
				radio.attr('checked', 'checked');
			}
		}
	});
	
	//エラーチェックDIV追加
	if(isCheck != undefined && isCheck){
		parent.append('&nbsp;');
		$(String.format('<span id="err{0}" class="error"></span>', name)).appendTo(parent);
	}
}