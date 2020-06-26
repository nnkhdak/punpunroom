class CanUseEnv {
	checker = new EnvChecker();
	json = null;

	constructor(url) {
		var that = this;
		$.ajax({
			type: "GET",
			url: url,
			cache: false,
			async: false,
			dataType: "json",
		})
			.done(function (response, textStatus, jqXHR) {
				if (response.status === "err") {
					alert("失敗: サーバー内でエラーがありました。\n" + "err: " + response.msg);
				} else {
					that.json = response;
				}
			})
			.fail(function (jqXHR, textStatus, errorThrown) {
				console.log(jqXHR);
				alert("失敗: サーバーとの通信に失敗しました。");
			})
			.always(function (data_or_jqXHR, textStatus, jqXHR_or_errorThrown) {});
	}

	canUseBrowser() {
		console.log(this.json);
		return true;
	}

	canUseCookie() {
		console.log(this.json);
		return this.checker.canUseCookie();
	}

	canUseOs() {
		console.log(this.json);
		return true;
	}

	getOsName() {
		console.log(this.json);
		return this.checker.getOsName();
	}

	getBrowserFullName() {
		console.log(this.json);
		return true;
	}
}
