const MENUS_API = "https://pun-pun-pun.github.io/punpunroom/menu.json";

// ヘッダを初期化
document.querySelectorAll('header')
	.forEach((ele) => {
		const clone = ele.cloneNode(false);
		ele.parentNode.replaceChild(clone, ele);
	});

// ヘッダをコーディング
document.querySelectorAll('header')
	.forEach((ele) => {
		const waku = document.createElement('div');
		ele.appendChild(waku);

		const navInput = document.createElement('input');
		navInput.setAttribute('id', 'nav_input');
		navInput.setAttribute('type', 'checkbox');
		navInput.setAttribute('class', 'nav_none');

		const navOpen = document.createElement('label');
		navOpen.setAttribute('id', 'nav_open');
		navOpen.setAttribute('for', 'nav_input');
		navOpen.appendChild(document.createElement('span'));

		const navClose = document.createElement('label');
		navClose.setAttribute('id', 'nav_close');
		navClose.setAttribute('for', 'nav_input');
		navClose.setAttribute('class', 'nav_none');

		const nav = document.createElement('nav');
		nav.setAttribute('id', 'nav');

		const navDiv = document.createElement('div');
		navDiv.setAttribute('id', 'nav_div');
		navDiv.appendChild(navInput);
		navDiv.appendChild(navOpen);
		navDiv.appendChild(navClose);
		navDiv.appendChild(nav);

		const menu = document.createElement('menu');
		menu.appendChild(navDiv);
		waku.appendChild(menu);

		const page = document.createElement('div');
		page.setAttribute('class', 'page');
		const icon = document.createElement('a');
		icon.setAttribute('href', 'index.html');
		icon.innerHTML = 'ぷんぷんる～む';
		page.appendChild(icon);
		waku.appendChild(page);

		const sign = document.createElement('div');
		sign.setAttribute('class', 'sign');
		sign.innerHTML = 'signin';
		waku.appendChild(sign);

		const name = document.createElement('div');
		name.setAttribute('class', 'name');
		name.innerHTML = 'name';
		waku.appendChild(name);
	});
