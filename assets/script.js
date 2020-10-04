const MENUS_API = "https://pun-pun-pun.github.io/punpunroom/menu.json";

// header clear
document.querySelectorAll('header')
	.forEach((ele) => {
		const clone = ele.cloneNode(false);
		ele.parentNode.replaceChild(clone, ele);
	});

// header coding
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

// nav coding
codingNav().then(tag => {
	const nav = document.getElementById('nav');
	nav.appendChild(tag);
});
async function codingNav() {
	const res = await window.fetch(MENUS_API);
	const rows0 = await res.json();

	// ul
	const ul0 = document.createElement('ul');

	rows0.forEach(row0 => {
		// ul li
		const li0 = document.createElement('li');
		li0.innerHTML = row0.name;
		ul0.appendChild(li0);

		// ul li ul
		const ul1 = document.createElement('ul');
		li0.appendChild(ul1);

		const rows1 = row0.child;
		rows1.forEach(row1 => {
			const a = document.createElement('a');
			if (row1.href && row1.href.length > 0) {
				a.href = row0.name + '/' + row1.href;
			}
			ul1.appendChild(a);

			// ul li ul li
			const li1 = document.createElement('li');
			li1.innerHTML = row1.name;
			a.appendChild(li1);
		});
	});
	return ul0;
}

/* main show by javascript */
document.querySelectorAll('main')
	.forEach((ele) => {
		ele.setAttribute('style', 'display: block;');
	});
