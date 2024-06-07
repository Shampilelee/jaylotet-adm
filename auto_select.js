
const item_type = document.getElementById("item_type");

const container = document.getElementById("choices");

const hair = document.getElementById("hair");
const spray = document.getElementById("spray");



setInterval(() => {

    if (item_type.value == "Hair") {
        console.log("entered hair");
        // TURN DISPLAY ON
        container.style.display = "flex";
        container.style.justifyContent = "center";
        container.style.alignItems = "center";

        // HIDE THIS
        spray.style.display = "none";
        spray.required = false;
        spray.name = "asd";
        spray.value = "";

        hair.required = true;
        hair.name = "item_category";
        hair.style.display = "flex";
        console.log("passed hair");
    
    } else if (item_type.value == "Perfume") {
        console.log("entered perfume");
        container.style.display = "flex";
        container.style.justifyContent = "center";
        container.style.alignItems = "center";

        hair.style.display = "none";
        hair.required = false;
        hair.name = "dsa";
        hair.value = "";

        spray.required = true;
        spray.name = "item_category";
        spray.style.display = "flex";
        console.log("passed perfume");
    } else {
        // TURN DISPLAY OFF
        container.style.display = "none";
        console.log("container display off");
    }

}, 500);








