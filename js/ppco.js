$(document).ready(function () {
    var get_new_form_groups = function(id, index, embedString) {
        var form_groups = '<input type="hidden" class="xpan_form_element" name="session_id[]" value="'+id+'">';

        form_groups += '<div class="form-group xpan_form_element" id="il_prop_cont_embed_'+index+'">';
        form_groups += '<label for="embed_'+index+'" class="col-sm-3 control-label">Video</label>';
        form_groups += '<div class="col-sm-9">';
        form_groups += '<div class="form_inline">';
        form_groups += embedString;
        form_groups += '</div>';
        form_groups += '</div>';
        form_groups += '</div>';

        form_groups += '<div class="form-group xpan_form_element" id="il_prop_cont_height_'+index+'">';
        form_groups += '<label for="height_'+index+'" class="col-sm-3 control-label">Höhe<span class="asterisk">*</span></label>';
        form_groups += '<div class="col-sm-9">';
        form_groups += '<div class="form_inline">';
        form_groups += '<input style="text-align:right;width:auto;" class="form-control" type="text" size="40" id="height_'+index+'" maxlength="200" name="height[]" required="required" value="200">';
        form_groups += '<div class="help-block"></div>';
        form_groups += '</div>';
        form_groups += '</div>';
        form_groups += '</div>';

        form_groups += '<div class="form-group xpan_form_element" id="il_prop_cont_width_'+index+'">';
        form_groups += '<label for="width_'+index+'" class="col-sm-3 control-label">Breite<span class="asterisk">*</span></label>';
        form_groups += '<div class="col-sm-9">';
        form_groups += '<div class="form_inline">';
        form_groups += '<input style="text-align:right;width:auto;" class="form-control" type="text" size="40" id="width_'+index+'" maxlength="200" name="width[]" required="required" value="350">';
        form_groups += '<div class="help-block"></div>';
        form_groups += '</div>';
        form_groups += '</div>';
        form_groups += '</div>';


        return form_groups;
    };

    var iframe = $('#xpan_iframe'),
        iframe_src = iframe.attr('src'),
        servername = iframe_src.substr(0, iframe_src.indexOf('/Panopto/Pages/Sessions/EmbeddedUpload.aspx')),
        insert_button = $('#xpan_insert'),
        eventMethod = window.addEventListener ? 'addEventListener' : 'attachEvent',
        eventEnter = window[eventMethod],
        messageEvent = eventMethod === 'attachEvent' ? 'onmessage' : 'message',
        choose_videos_link = $('#il_prop_cont_xpan_choose_videos_link');

    //Hide insert button initially, until a video is selected
    insert_button.prop('disabled', true);

    // Listen to message from child iframe
    eventEnter(messageEvent, function (e) {
        var message = JSON.parse(e.data),
            thumbnailChunk = '',
            idChunk = '',
            embedString = '',
            ids = message.ids,
            names = message.names,
            VIDEO_EMBED_ID = 0,
            PLAYLIST_EMBED_ID = 1;

        //If a video is chosen, show the "Insert" button
        if (message.cmd === 'ready') {
            insert_button.prop('disabled', false);
        }

        //If no video is chosen, hide the "Insert" button
        if (message.cmd === 'notReady') {
            insert_button.prop('disabled', true);
        }

        //Called when "Insert" is clicked. Creates HTML for embedding each selected video into the editor
        if (message.cmd === 'deliveryList') {
            // remove existing form elements
            $('.xpan_form_element').remove();

            ids = message.ids;
            for (var i = (ids.length - 1); i >= 0; --i) {
                if (message.playableObjectTypes && (parseInt(message.playableObjectTypes[i]) === PLAYLIST_EMBED_ID)){
                    idChunk = "?pid=" + ids[i];
                } else {
                    idChunk = "?id=" + ids[i];
                }

                embedString = "<iframe class='xpan_form_element' id='iframe_"+i+"' src='" + servername + "/Panopto/Pages/Embed.aspx" +
                    idChunk + "&v=1' width='350' height='200' frameborder='0' allowfullscreen></iframe>";

                // add new form elements (iframe + height + width)
                $(get_new_form_groups(ids[i], i, embedString)).insertAfter(choose_videos_link);

                // index = i.valueOf();
                // $('input#height_'+index).focusout(function() {
                //    $('iframe#iframe_'+index).height($('input#height_'+index).val());
                // });
                //
                // $('input#width_'+index).focusout(function() {
                //    $('iframe#iframe_'+index).width($('input#width_'+index).val());
                // });
            }
            $('#xpan_modal').modal('hide')
        }
    }, false);

    insert_button.click(function () {
        var win = document.getElementById('xpan_iframe').contentWindow,
            message = {
                cmd: 'createEmbeddedFrame'
            };
        win.postMessage(JSON.stringify(message), servername);
    });

    // $('#cancel').click(function () {
    //     tinyMCEPopup.close();
    // });
});


