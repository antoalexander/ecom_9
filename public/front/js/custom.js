$(document).ready(function () {
    $("#getPrice").change(function () {
        var size = $(this).val();
        var product_id = $(this).attr("product-id");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: "/get-product-price",
            data: { size: size, product_id: product_id },
            type: "post",
            success: function (resp) {
                // alert(resp['final_price']);
                // alert(resp['discount']);
                if (resp["discount"] > 0) {
                    $(".getAttributePrice").html(
                        "<div class='original-price'><span>Original Price:</span><span>Rs." +
                            resp["product_price"] +
                            "</span></div>"
                    );
                } else {
                    $(".getAttributePrice").html(
                        "<div class='price'><h4>Rs." +
                            resp["final_price"] +
                            "</h4></div>"
                    );
                }
            },
            error: function () {
                alert("Error");
            },
        });
    });

    // Update Cart items qty
    $(document).on("click", ".updateCartItem", function () {
        if ($(this).hasClass("plus-a")) {
            // Get qty
            var quantity = $(this).data("qty");
            //alert(quantity);
            // increase the qty by 1
            new_qty = parseInt(quantity) + 1;
            //alert(new_qty);
        }

        if ($(this).hasClass("minus-a")) {
            // Get qty
            var quantity = $(this).data("qty");
            //alert(quantity);
            //check qty is alleast
            if (quantity <= 1) {
                alert("Item quantity must be 1 or greater!");
                return false;
            }
            // increase the qty by 1
            new_qty = parseInt(quantity) - 1;
            //alert(new_qty);
        }

        var cartid = $(this).data("cartid");
        //alert(cartid);

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: { cartid: cartid, qty: new_qty },
            url: "/cart/update",
            type: "post",
            success: function (resp) {
                //alert(resp);
                if (resp.status == false) {
                    alert(resp.message);
                }
                $("#appendCartItems").html(resp.view);
            },
            error: function () {
                alert("Error");
            },
        });
    });

    //delete cart item
    $(document).on("click", ".deleteCartItem", function () {
        var cartid = $(this).data("cartid");
        //alert(cartid);

        var result = confirm("Are you sure to delete this Cart Items?");
        if (result) {
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: { cartid: cartid },
                url: "/cart/delete",
                type: "post",
                success: function (resp) {
                    //alert(resp);
                    $("#appendCartItems").html(resp.view);
                },
                error: function () {
                    alert("Error");
                },
            });
        }
    });

    //register form validation

    $("#registerForm").submit(function () {
        $(".loader").show();

        var formdata = $(this).serialize();
        //alert(formdata);
        //return false;
        $.ajax({
            url: "/user/register",
            type: "POST",
            data: formdata,
            success: function (resp) {
                if (resp.type == "error") {
                    $(".loader").hide();
                    $.each(resp.errors, function (i, error) {
                        $("#register-" + i).attr("style", "color:red");
                        $("#register-" + i).html(error);

                        setTimeout(function () {
                            $("#register-" + i).css({
                                display: "none",
                            });
                        }, 3000);
                    });
                } else if (resp.type == "success") {
                    $(".loader").hide();
                    $("#register-success").attr("style", "color:green");
                    $("#register-success").attr(resp.message);
                }
            },
            error: function () {
                alert("Error");
            },
        });
    });

    //account form validation
    $("#accountForm").submit(function () {
        $(".loader").show();

        var formdata = $(this).serialize();
        //alert(formdata);
        //return false;
        $.ajax({
            url: "/user/account",
            type: "POST",
            data: formdata,
            success: function (resp) {
                if (resp.type == "error") {
                    $(".loader").hide();
                    $.each(resp.errors, function (i, error) {
                        $("#account-" + i).attr("style", "color:red");
                        $("#account-" + i).html(error);

                        setTimeout(function () {
                            $("#account-" + i).css({
                                display: "none",
                            });
                        }, 3000);
                    });
                } else if (resp.type == "success") {
                    $(".loader").hide();
                    $("#account-success").attr("style", "color:green");
                    $("#account-success").html(resp.message);
                    setTimeout(function () {
                        $("#account-success").css({
                            display: "none",
                        });
                    }, 3000);
                }
            },
            error: function () {
                alert("Error");
            },
        });
    });

    //update password form validation
    $("#passwordForm").submit(function () {
        $(".loader").show();

        var formdata = $(this).serialize();
        //alert(formdata);
        //return false;
        $.ajax({
            url: "/user/update-password",
            type: "POST",
            data: formdata,
            success: function (resp) {
                if (resp.type == "error") {
                    $(".loader").hide();
                    $.each(resp.errors, function (i, error) {
                        $("#password-" + i).attr("style", "color:red");
                        $("#password-" + i).html(error);

                        setTimeout(function () {
                            $("#password-" + i).css({
                                display: "none",
                            });
                        }, 3000);
                    });
                } else if (resp.type == "success") {
                    $(".loader").hide();
                    $("#password-success").attr("style", "color:green");
                    $("#password-success").html(resp.message);
                    setTimeout(function () {
                        $("#account-success").css({
                            display: "none",
                        });
                    }, 3000);
                }
            },
            error: function () {
                alert("Error");
            },
        });
    });

    //login form validation

    $("#loginForm").submit(function () {
        var formdata = $(this).serialize();
        //alert(formdata);
        //return false;
        $.ajax({
            url: "/user/login",
            type: "POST",
            data: formdata,
            success: function (resp) {
                if (resp.type == "error") {
                    $.each(resp.errors, function (i, error) {
                        $("#login-" + i).attr("style", "color:red");
                        $("#login-" + i).html(error);

                        setTimeout(function () {
                            $("#login-" + i).css({
                                display: "none",
                            });
                        }, 3000);
                    });
                } else if (resp.type == "incorrect") {
                    // alert(resp.message);
                    //window.location.href = resp.url;
                    $("#login-error").attr("style", "color:red");
                    $("#login-error").html(resp.message);
                } else if (resp.type == "inactive") {
                    $("#login-error").attr("style", "color:red");
                    $("#login-error").html(resp.message);
                } else if (resp.type == "success") {
                    window.location.href = resp.url;
                }
            },
            error: function () {
                alert("Error");
            },
        });
    });

    //forgot password validation
    $("#forgotForm").submit(function () {
        $(".loader").show();
        var formdata = $(this).serialize();
        //alert(formdata);
        //return false;
        $.ajax({
            url: "/user/forgot-password",
            type: "POST",
            data: formdata,
            success: function (resp) {
                if (resp.type == "error") {
                    $(".loader").hide();
                    $.each(resp.errors, function (i, error) {
                        $("#forgot-" + i).attr("style", "color:red");
                        $("#forgot-" + i).html(error);

                        setTimeout(function () {
                            $("#forgot-" + i).css({
                                display: "none",
                            });
                        }, 3000);
                    });
                } else if (resp.type == "success") {
                    $(".loader").hide();
                    $("#forgot-success").attr("style", "color:green");
                    $("#forgot-success").html(resp.message);
                }
            },
            error: function () {
                alert("Error");
            },
        });
    });
});

function get_filter(class_name) {
    var filter = [];
    $("." + class_name + ":checked").each(function () {
        filter.push($(this).val());
    });

    return filter;
}
