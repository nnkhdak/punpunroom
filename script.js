const USERS_API = "https://jsonplaceholder.typicode.com/users";
const MENUS_API = "https://pun-pun-pun.github.io/punpunroom/menu.json";

async function callApi() {
    const res = await window.fetch(USERS_API);
    const users = await res.json();
    console.log(users);
}

$(function () {
    console.log('Hello');

    //    callApi();
    codingNavTag('checkbox').then(tag => {
        $('div div nav').append(tag);
        console.log(tag);
    });
});

async function codingNavTag(type) {
    prefix = 'side';
    if ('checkbox' == type) {
        const result = document.createElement('ul');
        const res = await window.fetch(MENUS_API);
        const rows0 = await res.json();
        rows0.forEach(row0 => {
            const li0 = document.createElement('li');
            result.appendChild(li0);

            const lbl0 = document.createElement('label');
            lbl0.innerHTML = row0.name;
            lbl0.setAttribute('for', prefix + '_' + row0.name);
            li0.appendChild(lbl0);

            const input0 = document.createElement('input');
            input0.setAttribute('id', prefix + '_' + row0.name);
            input0.setAttribute('type', type);
            input0.setAttribute('value', row0.name);
            li0.appendChild(input0);

            const ul1 = document.createElement('ul');
            li0.appendChild(ul1);

            const rows1 = row0.child;
            rows1.forEach(row1 => {
                console.log(row1);

                const li1 = document.createElement('li');
                ul1.appendChild(li1);

                const a1 = document.createElement('a');
                a1.innerHTML = row1.name;
                if (row1.href && row1.href.length > 0) {
                    a1.href = row0.name + '/' + row1.href;
                }
                li1.appendChild(a1);
            });
        });
        return result;
    }
    return 'ddd';
}