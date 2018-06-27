$(document).ready(function () {
    var iframe = $('#xpan_iframe'),
        iframe_src = iframe.attr('src'),
        servername = iframe_src.substr(0, iframe_src.indexOf('/Panopto/Pages/Sessions/EmbeddedUpload.aspx')),
        insert_button = $('#xpan_insert'),
        eventMethod = window.addEventListener ? 'addEventListener' : 'attachEvent',
        eventEnter = window[eventMethod],
        messageEvent = eventMethod === 'attachEvent' ? 'onmessage' : 'message',
        form = $('#form_xpan_embed');
    console.log(servername);
    //Hide insert button initially, until a video is selected
    insert_button.show();

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
            insert_button.show();
        }

        //If no video is chosen, hide the "Insert" button
        if (message.cmd === 'notReady') {
            insert_button.hide();
        }

        //Called when "Insert" is clicked. Creates HTML for embedding each selected video into the editor
        if (message.cmd === 'deliveryList') {
            console.log(message);
            ids = message.ids;
            for (var i = 0; i < ids.length; ++i) {
                thumbnailChunk = "<div style='position: absolute; z-index: -1;'>";
                if (message.playableObjectTypes && (parseInt(message.playableObjectTypes[i]) === PLAYLIST_EMBED_ID)){
                    idChunk = "?pid=" + ids[i];
                } else {
                    idChunk = "?id=" + ids[i];
                }
                if (typeof message.names[i] !== 'undefined') {
                    thumbnailChunk += "<div width='450'><a style='max-width: 450px; display: inline-block;" +
                        "text-overflow: ellipsis; white-space: nowrap; overflow: hidden;'" +
                        "href='" + servername + '/Panopto/Pages/Viewer.aspx' + idChunk +
                        "' target='_blank'>" + names[i] + "</a></div>";
                }
                embedString = "<iframe src='" + servername + "/Panopto/Pages/Embed.aspx" +
                    idChunk + "&v=1' width='250' height='180' frameborder='0' allowfullscreen></iframe><br>";

                form.append('<input type="hidden" name="session_id[]" value="'+ids[i]+'">');
                form.append(embedString);
                // form.append(thumbnailChunk);
            }
            // window.parent.tinyMCE.activeEditor.execCommand('mceInsertContent', 0, embedString);
            console.log(embedString);
            console.log(thumbnailChunk);
            // tinyMCEPopup.close();
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


