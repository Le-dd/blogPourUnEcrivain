

  flatpickr('.datepicker', {
    altInput: true,
    altFormat: 'j F Y',
    dateFormat: 'Y-m-d',
    locale: 'fr',

  })
  flatpickr('.timepicker', {
    enableTime: true,
    noCalendar: true,
    altInput: true,
    altFormat: 'H:i',
    dateFormat: 'H:i:S',
    time_24hr: true

  })

  var btnTog = document.querySelectorAll(".btnTog");


  for (var i = 0; i < btnTog.length; i++) {
        btnTog[i].addEventListener('click', function(event) {
            var nameEvent = ".tog"+ $(this).attr('data-id');
            document.querySelector( nameEvent ).classList.toggle("toggedisplay");
            event.preventDefault();
          });
  }
tinymce.init({ selector:'textarea' });



var result = {};
var ImageValueBase = document.querySelectorAll(".champValue");
for (var i = 0; i < ImageValueBase.length; i++) {
      if(ImageValueBase[i].value ){
        result[ImageValueBase[i].name] = ImageValueBase[i].value;

      }

    }
result = JSON.stringify(result);


var ImageHidden = document.querySelector( ".itemSave" );
ImageHidden.value = result;

  var ImageValue = document.querySelectorAll( ".champValue" );

  for (var i = 0; i < ImageValue.length; i++) {
          ImageValue[i].addEventListener("blur", function( event ) {
            var ImageHidden = document.querySelector( ".itemSave" );
            result = ImageHidden.value ;
            result = JSON.parse(result);
            result[this.name] = this.value;
            result = JSON.stringify(result);
            ImageHidden.value = result;
          }, true);
        }
