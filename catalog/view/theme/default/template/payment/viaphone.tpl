<form id="viaphone_first" action="<?php echo $first_url; ?>" method="post">
    <input type="hidden" name="cart_order_id" value="<?php echo $cart_order_id; ?>"/>

    <div class="col-md-12">
        <?php echo $phone_label; ?>: <input type="text" name="phone"/><br><br>
    </div>
    <div class="col-md-12">
        <div class="buttons">
            <input type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary"/>
        </div>
    </div>
</form>
<form style="display:none" id="viaphone_second" action="<?php echo $second_url; ?>" method="post">
    <input type="hidden" id="payment_id" name="payment" value=""/>
    <input type="hidden" name="cart_order_id" value="<?php echo $cart_order_id; ?>"/>

    <div class="col-md-12">
        <?php echo $text_smscode; ?>: <input type="text" name="code" value=""/>
    </div>
    <div class="col-md-12">
        <div class="buttons">
            <input type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary"/>
        </div>
    </div>
</form>
<div class="col-md-12" id="viaphone_error" style="font-size: 16px;color: red; "></div>
<script>
    $("#viaphone_first").submit(function (e) {
        $("#viaphone_error").html('');
        var url = "<?php echo $first_url; ?>";
        $.ajax({
            type: "POST",
            url: url,
            data: $("#viaphone_first").serialize(),
            success: function (data) {
                data = $.parseJSON(data);
                if (data['status'] != "OK") {
                    if (data['error']) {
                        $("#viaphone_error").html('<?php echo $text_error; ?>: ' + data['error']);
                    } else {
                        $("#viaphone_error").html('<?php echo $text_error; ?>');
                    }
                } else {
                    $("#payment_id").val(data['payment']);
                    $("#viaphone_first").hide();
                    $("#viaphone_second").show();
                }
            }
        });
        e.preventDefault();
    });
    $("#viaphone_second").submit(function (e) {
        $("#viaphone_error").html('');
        var url = "<?php echo $second_url; ?>";
        $.ajax({
            type: "POST",
            url: url,
            data: $("#viaphone_second").serialize(),
            success: function (data) {
                data = $.parseJSON(data);
                if (data['status'] != "OK") {
                    if (data['error']) {
                        $("#viaphone_error").html('<?php echo $text_error; ?>: ' + data['error']);
                    } else {
                        $("#viaphone_error").html('<?php echo $text_error; ?>');
                    }
                } else {
                    location.replace("<?php echo $success_url; ?>");
                }
            }
        });
        e.preventDefault();
    });
</script>