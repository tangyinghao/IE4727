document.addEventListener("DOMContentLoaded", () => {
    const JavaNode1 = document.getElementById("Qty_Java_Single");
    const JavaNode2 = document.getElementById("Qty_Java_Double");
    const JavaNode3 = document.getElementById("QtyJava");

    const CafeNode1 = document.getElementById("Qty_Cafe_Single");
    const CafeNode2 = document.getElementById("Qty_Cafe_Double");
    const CafeNode3 = document.getElementById("QtyCafe");

    const CappNode1 = document.getElementById("Qty_Capp_Single");
    const CappNode2 = document.getElementById("Qty_Capp_Double");
    const CappNode3 = document.getElementById("QtyCapp");

    const totalField = document.getElementById("Price_Total");

    JavaNode1.addEventListener("change", Cal_Java, false);
    JavaNode2.addEventListener("change", Cal_Java, false);
    JavaNode3.addEventListener("change", Cal_Java, false);

    CafeNode1.addEventListener("change", Cal_Cafe, false);
    CafeNode2.addEventListener("change", Cal_Cafe, false);
    CafeNode3.addEventListener("change", Cal_Cafe, false);

    CappNode1.addEventListener("change", Cal_Capp, false);
    CappNode2.addEventListener("change", Cal_Capp, false);
    CappNode3.addEventListener("change", Cal_Capp, false);

    function validateQuantity(field) {
        const value = field.value.trim();
        const validNumberPattern = /^[0-9]+$/;

        if (value === "") {
            field.value = "0";
            return 0;
        }

        if (!validNumberPattern.test(value)) {
            alert("You entered (" + field.value + ") is not correct. \n" +
                "Only NUMBER are allowed.");
            field.value = "0";
            field.focus();
            field.select();
            return 0;
        }

        return parseInt(value, 10);
    }

    function Cal_Java() {
        const quantity = validateQuantity(JavaNode3);
        const price = JavaNode1.checked ? parseFloat(JavaNode1.value) : parseFloat(JavaNode2.value);
        const total = quantity * price;

        document.getElementById("Price_Java").value = total.toFixed(2);
        Cal_total();
    }

    function Cal_Cafe() {
        const quantity = validateQuantity(CafeNode3);
        const price = CafeNode1.checked ? parseFloat(CafeNode1.value) : parseFloat(CafeNode2.value);
        const total = quantity * price;

        document.getElementById("Price_Cafe").value = total.toFixed(2);
        Cal_total();
    }

    function Cal_Capp() {
        const quantity = validateQuantity(CappNode3);
        const price = CappNode1.checked ? parseFloat(CappNode1.value) : parseFloat(CappNode2.value);
        const total = quantity * price;

        document.getElementById("Price_Capp").value = total.toFixed(2);
        Cal_total();
    }

    function Cal_total() {
        const java = parseFloat(document.getElementById("Price_Java").value) || 0;
        const cafe = parseFloat(document.getElementById("Price_Cafe").value) || 0;
        const capp = parseFloat(document.getElementById("Price_Capp").value) || 0;

        const totalAmount = java + cafe + capp;
        totalField.value = totalAmount.toFixed(2);
    }

    // Initialize all totals
    Cal_Java();
    Cal_Cafe();
    Cal_Capp();
});
