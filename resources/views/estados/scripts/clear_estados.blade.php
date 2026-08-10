window.runEstadosClear = function (dt, button) {
    const originalText = button.text();
    button.text("Eliminando...");
    button.prop("disabled", true);

    $.ajax({
        url: "/api/estados",
        type: "DELETE",
        success: function(response) {
            alert(`${response.count || 0} registros eliminados.`);
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
