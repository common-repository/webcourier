jQuery(document).on('click', '#submit-config', function (e) {
    e.preventDefault();
    var data = {
      'displayType': sendForm.option.value,  
      'listType': sendForm.option2.value, 
      'api': api,
    };
    jQuery.ajax({
            'url': 'https://app.webcourier.com.br/api/survey/saveconfig',
            'type': 'POST',
            'data': data
            }).done(function(response)
            {
                location.reload();
            });
        });

