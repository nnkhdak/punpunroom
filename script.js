const USERS_API = "https://jsonplaceholder.typicode.com/users";

async function callApi() {
    const res = await window.fetch(USERS_API);
    console.log(res);

    const users = await res.json();
    console.log(users);
}

$(function () {
    console.log('Hello');

    callApi();
});
