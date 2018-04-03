$(document).ready(function() {
    $("#select_button").on("click", function() {$("#file").click()});

    $("#file").on("change", function() {
        $("#file_indicator").html("File Selected!");
        $("#submit_button").attr("style", "");
    })
});