function validatePassword(field) {
    if (field == "") return "Не введен пароль.\n";
    else if (field.length < 6)
        return "В пароле должно быть не менее 6 символов.\n";
    else if (!/[a-z]/.test(field) || ! /[A-Z]/.test(field) || !/[0-9]/.test(field))
        return "Пароль требует по одному символу из каждого набора a-z, A-Z и 0-9.\n";
    return "";
}

function repeatPassword(field1, field2) {
    if (field2 != field1) return "Пароли не совпадают! Будьте внимательны!\n";
    else return "";
}

function validateEmail(field) {
    if (field == "") return "Не введен адрес электронной почты.\n";
    else if (!((field.indexOf(".") > 0) && (field.indexOf("@") > 0)) ||/[^a-zA-Z0-9.@_-]/.test(field))
        return "Электронный адрес имеет неверный формат.\n";
    return "";
}

function validate_signup(form) {
    fail = validatePassword(form.password.value);
    fail += repeatPassword(form.password.value, form.passwordRepeat.value);
    fail += validateEmail(form.email.value);
    if (fail == "")
    {
        return true;
    }
    else
    {
        document.getElementById('jsWarnings').innerText = fail+"\n";
        return false;
    }
}

//function validate_registration(form) {
//    fail = validatePassword(form.password.value);
//    fail += repeatPassword(form.password.value, form.passwordRepeat.value);
//    fail += validateEmail(form.email.value);
//    if (fail == "")
//    {
//        return true;
//    }
//    else
//    {
//        document.getElementById('jsWarnings').innerText = fail+"\n";
//        return false;
//    }
//}