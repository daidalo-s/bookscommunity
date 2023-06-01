import * as pwUtil from '/tweb-progetto/js/utils/password-check.js';

$(function () {

    $("#pw-requirements").hide();

    $("#login-button").on("click", function () {
        $("#register-form-div").toggleClass("hidden");
        $("#login-form-div").toggleClass("hidden");
    })

    $("#register-button2").on("click", function () {
        $("#register-form-div").toggleClass("hidden");
        $("#login-form-div").toggleClass("hidden");
    })

    $("#password-register").on("focus", function () {
        $("#pw-requirements").slideDown(300);
    })

    $("#password-register").on("focusout", function () {
        $("#pw-requirements").slideUp(300);
    })

    $("#pw-confirmation-register").on("keyup", function () {
        if (!pwUtil.passwordEqual($("#password-register").val(), $("#pw-confirmation-register").val())) {
            $(".password-error").removeClass("hidden");
        } else {
            $(".password-error").addClass("hidden");
        }
    })

    $("#register").on("click", function () {
        $.ajax({
            url: "../php/user/register.php",
            type: "post",
            data: $("#register-form").serialize(),
            success: function (response) {
                $(window.location).attr("href", "./" + response.message);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $(".password-error").text(jqXHR.responseJSON.message);
                $(".password-error").removeClass("hidden");
            }
        })
    })

    $("#login").on("click", function () {
        $.ajax({
            url: "../php/user/login.php",
            type: "post",
            data: $("#login-form").serialize(),
            success: function (response) {
                $(window.location).attr("href", "./" + response.message);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $(".login-error").text(jqXHR.responseJSON.message);
                $(".login-error").removeClass("hidden");
            }
        })
    })

});