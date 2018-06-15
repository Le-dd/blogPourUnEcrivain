

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
            console.log(nameEvent);
            document.querySelector( nameEvent ).classList.toggle("toggedisplay");
            event.preventDefault();
          });
  }
tinymce.init({ selector:'textarea' });
