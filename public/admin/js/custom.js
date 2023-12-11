$(document).ready(function () {
    //call the datatable
    $("#sections").DataTable();
    $("#categories").DataTable();
    $("#brands").DataTable();
    $("#products").DataTable();
    $("#banners").DataTable();
    $("#filters").DataTable();
    $("#pages").DataTable();

    // check admin password is correct or not
    $("#current_password").keyup(function () {
        var current_password = $("#current_password").val();
        /* alert(current_pwd);*/
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/check-admin-password",
            data: { current_password: current_password },
            success: function (resp) {
                /* alert(resp);*/
                if (resp == "false") {
                    $("#check_password").html(
                        "<font color='red'>Current Password is Incorrect</font>"
                    );
                } else if (resp == "true") {
                    $("#check_password").html(
                        "<font color='green'>Current Password is Correct</font>"
                    );
                }
            },
            error: function () {
                alert("Error");
            },
        });
    });

    //update admin status

    $(document).on("click", ".updateAdminStatus", function () {
        var status = $(this).children("i").attr("status");
        var admin_id = $(this).attr("admin_id");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/update-admin-status",
            data: { status: status, admin_id: admin_id },
            success: function (resp) {
                if (resp["status"] == 0) {
                    $("#admin-" + resp["admin_id"]).html(
                        "<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='InActive'></i>"
                    );
                } else if (resp["status"] == 1) {
                    $("#admin-" + resp["admin_id"]).html(
                        "<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>"
                    );
                }
            },
            error: function () {
                alert("error");
            },
        });
    });

    //update page status
    $(document).on("click", ".updatePageStatus", function () {
        var status = $(this).children("i").attr("status");
        var page_id = $(this).attr("page_id");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/update-cms-page-status",
            data: { status: status, page_id: page_id },
            success: function (resp) {
                if (resp["status"] == 0) {
                    $("#page-" + resp["page_id"]).html(
                        "<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='InActive'></i>"
                    );
                } else if (resp["status"] == 1) {
                    $("#page-" + resp["page_id"]).html(
                        "<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>"
                    );
                }
            },
            error: function () {
                alert("error");
            },
        });
    });

    //update admin status

    $(document).on("click", ".updateSectionStatus", function () {
        var status = $(this).children("i").attr("status");
        var section_id = $(this).attr("section_id");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/update-section-status",
            data: { status: status, section_id: section_id },
            success: function (resp) {
                if (resp["status"] == 0) {
                    $("#section-" + resp["section_id"]).html(
                        "<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='InActive'></i>"
                    );
                } else if (resp["status"] == 1) {
                    $("#section-" + resp["section_id"]).html(
                        "<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>"
                    );
                }
            },
            error: function () {
                alert("error");
            },
        });
    });

    //update categories status
    $(document).on("click", ".updateCategoryStatus", function () {
        var status = $(this).children("i").attr("status");
        var category_id = $(this).attr("category_id");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/update-categories-status",
            data: { status: status, category_id: category_id },
            success: function (resp) {
                if (resp["status"] == 0) {
                    $("#category-" + resp["category_id"]).html(
                        "<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='InActive'></i>"
                    );
                } else if (resp["status"] == 1) {
                    $("#category-" + resp["category_id"]).html(
                        "<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>"
                    );
                }
            },
            error: function () {
                alert("error");
            },
        });
    });

    //update updateBrandStatus
    $(document).on("click", ".updateBrandStatus", function () {
        var status = $(this).children("i").attr("status");
        var brand_id = $(this).attr("brand_id");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/update-brand-status",
            data: { status: status, brand_id: brand_id },
            success: function (resp) {
                if (resp["status"] == 0) {
                    $("#brand-" + resp["brand_id"]).html(
                        "<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='InActive'></i>"
                    );
                } else if (resp["status"] == 1) {
                    $("#brand-" + resp["brand_id"]).html(
                        "<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>"
                    );
                }
            },
            error: function () {
                alert("error");
            },
        });
    });

    //update update product Status
    $(document).on("click", ".updateProductStatus", function () {
        var status = $(this).children("i").attr("status");
        var product_id = $(this).attr("product_id");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/update-product-status",
            data: { status: status, product_id: product_id },
            success: function (resp) {
                if (resp["status"] == 0) {
                    $("#product-" + resp["product_id"]).html(
                        "<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='InActive'></i>"
                    );
                } else if (resp["status"] == 1) {
                    $("#product-" + resp["product_id"]).html(
                        "<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>"
                    );
                }
            },
            error: function () {
                alert("error");
            },
        });
    });

    //update attribute Status
    $(document).on("click", ".updateAttrStatus", function () {
        var status = $(this).children("i").attr("status");
        var attr_id = $(this).attr("attr_id");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/update-attr-status",
            data: { status: status, attr_id: attr_id },
            success: function (resp) {
                if (resp["status"] == 0) {
                    $("#attr-" + resp["attr_id"]).html(
                        "<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='InActive'></i>"
                    );
                } else if (resp["status"] == 1) {
                    $("#attr-" + resp["attr_id"]).html(
                        "<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>"
                    );
                }
            },
            error: function () {
                alert("error");
            },
        });
    });

    //update image status

    $(document).on("click", ".updateImageStatus", function () {
        var status = $(this).children("i").attr("status");
        var image_id = $(this).attr("image_id");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/update-image-status",
            data: { status: status, image_id: image_id },
            success: function (resp) {
                if (resp["status"] == 0) {
                    $("#image-" + resp["image_id"]).html(
                        "<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='InActive'></i>"
                    );
                } else if (resp["status"] == 1) {
                    $("#image-" + resp["image_id"]).html(
                        "<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>"
                    );
                }
            },
            error: function () {
                alert("error");
            },
        });
    });

    //update banner status
    $(document).on("click", ".updateBannerStatus", function () {
        var status = $(this).children("i").attr("status");
        var banner_id = $(this).attr("banner_id");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/update-banner-status",
            data: { status: status, banner_id: banner_id },
            success: function (resp) {
                if (resp["status"] == 0) {
                    $("#banner-" + resp["banner_id"]).html(
                        "<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='InActive'></i>"
                    );
                } else if (resp["status"] == 1) {
                    $("#banner-" + resp["banner_id"]).html(
                        "<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>"
                    );
                }
            },
            error: function () {
                alert("error");
            },
        });
    });

    //update filter status
    $(document).on("click", ".updateFilterStatus", function () {
        var status = $(this).children("i").attr("status");
        var filter_id = $(this).attr("filter_id");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/update-filter-status",
            data: { status: status, filter_id: filter_id },
            success: function (resp) {
                if (resp["status"] == 0) {
                    $("#filter-" + resp["filter_id"]).html(
                        "<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='InActive'></i>"
                    );
                } else if (resp["status"] == 1) {
                    $("#filter-" + resp["filter_id"]).html(
                        "<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>"
                    );
                }
            },
            error: function () {
                alert("error");
            },
        });
    });

    //update filter status
    $(document).on("click", ".updateFilterValueStatus", function () {
        var status = $(this).children("i").attr("status");
        var filter_id = $(this).attr("filter_id");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/update-filter-value-status",
            data: { status: status, filter_id: filter_id },
            success: function (resp) {
                if (resp["status"] == 0) {
                    $("#filter-" + resp["filter_id"]).html(
                        "<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='InActive'></i>"
                    );
                } else if (resp["status"] == 1) {
                    $("#filter-" + resp["filter_id"]).html(
                        "<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>"
                    );
                }
            },
            error: function () {
                alert("error");
            },
        });
    });

    //confirm delele(simple javascript)
    /* $(".confirmDelete").click(function(){
      var title = $(this).attr("title");
      if(confirm("Are you sure to delete this "+title+"?")){
        return true;
       }else{
         return false;
      }
    }); */

    //confirm delele(sweet alert 2)
    // $(".confirmDelete").click(function(){
    $(document).on("click", ".confirmDelete", function () {
        // alert(1);
        var module = $(this).attr("module");
        var moduleid = $(this).attr("moduleid");
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire("Deleted!", "Your file has been deleted.", "success");
                window.location = "/admin/delete-" + module + "/" + moduleid;
            }
        });
    });

    //append categories level
    $("#section_id").change(function () {
        var section_id = $(this).val();
        //alert(section_id);
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "get",
            url: "/admin/append-categories-level",
            data: { section_id: section_id },
            success: function (resp) {
                $("#appendCategoriesLevel").html(resp);
            },
            error: function () {
                alert("Error");
            },
        });
    });

    // product attributes add/remove script
    var maxField = 10; //Input fields increment limitation
    var addButton = $(".add_button"); //Add button selector
    var wrapper = $(".field_wrapper"); //Input field wrapper
    var fieldHTML =
        '<div style="margin-top:10px;"><input type="text" name="size[]" placeholder="Size" style="width:90px;" required />  <input type="text" name="sku[]" placeholder="sku" style="width:90px" required /> <input type="text" name="price[]" placeholder="price" style="width:90px" required /><input type="text" name="stock[]" placeholder="stock" style="width:90px" required /><a href="javascript:void(0);" class="remove_button">Remove</a></div>'; //New input field html
    var x = 1; //Initial field counter is 1

    //Once add button is clicked
    $(addButton).click(function () {
        //Check maximum number of input fields
        if (x < maxField) {
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
        }
    });

    //Once remove button is clicked
    $(wrapper).on("click", ".remove_button", function (e) {
        e.preventDefault();
        $(this).parent("div").remove(); //Remove field html
        x--; //Decrement field counter
    });

    // Show filters on selection of category
    $("#category_id").on("change", function () {
        var category_id = $(this).val();
        // alert(category_id);
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "category-filters",
            data: { category_id: category_id },
            success: function (resp) {
                $(".loadFilters").html(resp.view);
            },
        });
    });
});
