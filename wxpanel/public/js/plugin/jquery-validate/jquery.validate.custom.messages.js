jQuery.extend(jQuery.validator.messages, {
    required: "Este campo es obligatorio.",
    remote: "Por favor corrija este campo.",
    email: "Por favor ingrese una dirección de email válida",
    url: "Por favor ingrese una URL válida.",
    date: "Por favor ingrese una fecha válida.",
    dateISO: "Por favor ingrese una fecha válida (ISO).",
    number: "Por favor ingrese un número válido.",
    digits: "Por favor ingrese solamente dígitos.",
    creditcard: "Por favor ingrese un número de tarjeta de crédito válido.",
    equalTo: "Please enter the same value again.",
    accept: "Por favor ingrese un valor con extensión válida.",
    maxlength: jQuery.validator.format("Por favor ingrese no más que {0} caracteres."),
    minlength: jQuery.validator.format("Por favor ingrese al menos {0} caracteres."),
    rangelength: jQuery.validator.format("Por favor ingrese un valor entre {0} y {1} caracteres."),
    range: jQuery.validator.format("Por favor ingrese un valor entre {0} y {1}."),
    max: jQuery.validator.format("Por favor ingrese un valor menor o igual a {0}."),
    min: jQuery.validator.format("Por favor ingrese un valor mayor o igual a {0}.")
});

