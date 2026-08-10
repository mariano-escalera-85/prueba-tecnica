window.runEstadosImport = function (dt, button) {
    const originalText = button.text();
    button.text("Importando...");
    button.prop("disabled", true);

    $.ajax({
        url: "/api/estados",
        type: "POST",
        success: function(response) {
            alert(`${response.count || 0} registros nuevos importados.`);
            dt.ajax.reload(null, false);
        },
        error: function(xhr) {
            console.log(xhr);
            alert(`Ha sucedido un error: ${xhr.statusText} (${xhr.status})`);
        },
        complete: function() {
            button.text(originalText);
            button.prop("disabled", false);
        }
    });
}
