// const quantity = document.querySelector(".quantity_number");

// if (quantity) {

// const add_quantity = document.querySelector(".add_quantity");
// const sub_quantity = document.querySelector(".sub_quantity");
const submit = document.getElementById("submit");
const summary = document.querySelector(".summary");

// add_quantity.onclick = (e) => {
//     e.preventDefault();
//     let n = Number(quantity.innerHTML) + 1;
//     quantity.innerHTML = n;
// };

// sub_quantity.onclick = (e) => {
//     e.preventDefault();
//     let n = Number(quantity.innerHTML) - 1;
//     quantity.innerHTML = quantity.innerHTML > 1 ? n : 1;
// };

submit.onclick = (e) => {
    // e.preventDefault();
};

summary.onclick = () => {
    document.querySelector(".order-details").classList.toggle("active");
};
// }
