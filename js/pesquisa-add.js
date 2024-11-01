var imagem = ''

jQuery(document).ready(function ($) {
    $('#upload-btn').click(function (e) {
        e.preventDefault();
        var image = wp.media({
            title: 'Upload Imagem',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
                .on('select', function (e) {
                    // This will return the selected image from the Media Uploader, the result is an object
                    var uploaded_image = image.state().get('selection').first();
                    // We convert uploaded_image to a JSON object to make accessing it easier
                    // Output to the console uploaded_image
                    var image_url = uploaded_image.toJSON().url;
                    // Let's assign the url value to the input field
                    $('#image_url').val(image_url);
                    // Create the var to send to webcourier
                    var name = image_url.substring(image_url.lastIndexOf("/")+1);
                    imagem = {
                        name: name,
                        data: image_url
                    };
                });
    });
});
