var RegCheck = {
  	/**
	 * 正規表示式
	 */
	_reg:{
		// Email正規表示式
		email: /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/,
		// 非「英文大小寫」和「數字」出現過一次
		not_en_digital_least1: /[^a-zA-Z0-9]+/,
		// 「英文大小寫」和「數字」各出現過一次(a1,b2...)
		en_digital_least1: /[a-zA-Z]+[0-9]+|[0-9]+[a-zA-Z]+/,
		// 英文或數字
		en_digital : /[a-zA-Z0-9]+/,
		// 網址
		url : /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/,
		// 信用卡
		credit_card : /^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/,
		// 電話
		phone : /^[0-9\-()+]{3,20}$/,
		// 日期(YYYY-MM-DD / YYYY/MM/DD)
		date : /^(\d{4}(\/|\-)\d{1,2}(\/|\-)\d{1,2}){1}$/
	},
	/*
	 * 檢測Email
	 */
	email : function(email) {
		return this._reg.email.test(email);
	},
	/*
	 * 檢測信用卡
	 */
	credit_card : function(credit_card_number) {
		return this._reg.credit_card.test(credit_card_number);
	},
	/*
	 * 檢測電話
	 */
	phone : function(phone_number) {
		return this._reg.phone.test(phone_number);
	},
	/*
	 * 檢測日期
	 */
	date : function(date) {
		return this._reg.date.test(date);
	},
	/*
	 * 非「英文大小寫」和「數字」出現過一次
	 */
	not_en_digital_least1:function(str) {
		return this._reg.not_en_digital_least1.test(str);
	},
	/*
	 * 「英文大小寫」和「數字」各出現過一次(a1,b2...)
	 */
	en_digital_least1:function(str) {
		return this._reg.en_digital_least1.test(str);
	},
	/*
	 * 「英文大小寫」和「數字」各出現過一次(a1,b2...)
	 */
	en_digital:function(str) {
		return this._reg.en_digital.test(str);
	},
	/*
	 * 檢測網址格式
	 */
	url:function(str) {
		return this._reg.url.test(str);
	}
};