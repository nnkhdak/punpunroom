function getCookie(key, tmp1, tmp2, xx1, xx2, xx3) {
	tmp1 = " " + document.cookie + ";";
	console.log(tmp1);
	xx1 = xx2 = 0;
	len = tmp1.length;
	while (xx1 < len) {
		console.log("aaaaaa");
		xx2 = tmp1.indexOf(";", xx1);
		tmp2 = tmp1.substring(xx1 + 1, xx2);
		xx3 = tmp2.indexOf("=");
		if (tmp2.substring(0, xx3) == key) {
			return unescape(tmp2.substring(xx3 + 1, xx2 - xx1 - 1));
		}
		xx1 = xx2 + 1;
	}
	return "";
}

class EnvChecker {
	canUseCookie() {
		/*
		const k = "cookiecheck";
		const s = k + "=" + escape("true") + "; ";
		console.log(s);
		document.cookie = s;
		const v = getCookie(k);
		console.log(v);
		*/
		return false;
	}

	canUseFlash() {
		return false;
	}

	getBrowser() {
		var ua = navigator.userAgent + " ";
		if (ua.match(/Trident\/([0-9.]+)/i)) {
			// IE 8-11
			return { name: "Internet Explorer", ver: parseInt(RegExp.$1) + 4 };
		} else if (ua.match(/Edg[e]{0,1}([0-9.]+)\//i)) {
			// Edge(HTML,Chromium)
			return { name: "Microsoft Edge", ver: RegExp.$1 };
		} else if (ua.match(/Chrome\/([0-9.]+)/i)) {
			// Chrome
			return { name: "Chrome", ver: RegExp.$1 };
		} else if (ua.match(/MSIE ([0-9.]+)/i)) {
			// IE ?-7
			return { name: "Internet Explorer", ver: RegExp.$1 };
		} else if (ua.match(/Firefox\/([0-9.]+)/i)) {
			// Firefox
			return { name: "Firefox", ver: RegExp.$1 };
		} else if (ua.match(/Safari\/([0-9.]+)/i)) {
			// Firefox
			return { name: "Safari", ver: RegExp.$1 };
		} else if (ua.match(/Opera\/([0-9.]+)/i)) {
			// Firefox
			return { name: "Opera", ver: RegExp.$1 };
		}
		return null;
	}

	getOsName() {
		const ua = navigator.userAgent;
		if (ua.match(/Win(dows )?NT 10\.0/)) {
			return "Windows 10"; // Windows 10
		} else if (ua.match(/Win(dows )?NT 6\.3/)) {
			return "Windows 8.1"; // Windows 8.1
		} else if (ua.match(/Win(dows )?NT 6\.2/)) {
			return "Windows 8"; // Windows 8
		} else if (ua.match(/Win(dows )?NT 6\.1/)) {
			return "Windows 7"; // Windows 7
		} else if (ua.match(/Win(dows )?NT 6\.0/)) {
			return "Windows Vista"; // Windows Vista
		} else if (ua.match(/Win(dows )?NT 5\.2/)) {
			return "Windows Server 2003"; // Windows Server 2003
		} else if (ua.match(/Win(dows )?(NT 5\.1|XP)/)) {
			return "Windows XP"; // Windows XP
		} else if (ua.match(/Win(dows)? (9x 4\.90|ME)/)) {
			return "Windows ME"; // Windows ME
		} else if (ua.match(/Win(dows )?(NT 5\.0|2000)/)) {
			return "Windows 2000"; // Windows 2000
		} else if (ua.match(/Win(dows )?98/)) {
			return "Windows 98"; // Windows 98
		} else if (ua.match(/Win(dows )?NT( 4\.0)?/)) {
			return "Windows NT"; // Windows NT
		} else if (ua.match(/Win(dows )?95/)) {
			return "Windows 95"; // Windows 95
		} else if (ua.match(/Android/)) {
			ua.match(/Android ([0-9.]+)/g);
			var v = RegExp.$1;
			return "Android " + v; // Android
		} else if (ua.match(/iPhone/)) {
			ua.match(/iPhone OS ([0-9._]+)/g);
			var v = RegExp.$1;
			return "iOS " + v.replace(/_/g, "."); // iPhone
		} else if (ua.match(/iPad/)) {
			ua.match(/CPU OS ([0-9._]+)/g);
			var v = RegExp.$1;
			return "iOS " + v.replace(/_/g, "."); // iOS
		} else if (ua.match(/Mac OS/)) {
			ua.match(/Mac OS (\S+)/g);
			var v = RegExp.$1;
			return "MacOS " + v; // Macintosh
		} else if (ua.match(/Mac|PPC/)) {
			return "Mac OS X"; // Macintosh
		} else if (ua.match(/CrOS/)) {
			ua.match(/CrOS [\w]+ ([0-9._]+)/g);
			var v = RegExp.$1;
			return "ChromeOS " + v; // Chrome OS
		} else if (ua.match(/Linux/)) {
			return "Linux"; // Linux
		} else if (ua.match(/(Free|Net|Open)BSD/)) {
			return RegExp.$1 + "BSD"; // BSD系
		} else if (ua.match(/SunOS/)) {
			return "Solaris"; // Solaris
		}
		return null;
	}
}
