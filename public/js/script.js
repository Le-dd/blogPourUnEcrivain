

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


  var resultForm1 = "";
  var IndexFirstForm1 = document.querySelector(".indexFirstForm1");
      if(IndexFirstForm1.value ){
        resultForm1 = IndexFirstForm1.value;

      }
  var ResultFirstForm1 = document.querySelector( ".resultFirstForm1" );
  ResultFirstForm1.value = resultForm1;

  var IndexFirstForm1 = document.querySelector(".indexFirstForm1");
    IndexFirstForm1.addEventListener("blur", function( event ) {
        var resultForm1 = this.value;
        var ResultFirstForm1 = document.querySelector( ".resultFirstForm1" );
        ResultFirstForm1.value = resultForm1;
        var resultIndex = {};
        var FormIndex = document.querySelectorAll(".champFormIndex");
        for (var i = 0; i < FormIndex.length; i++) {
              if(FormIndex[i].value ){
                resultIndex[FormIndex[i].name] = FormIndex[i].value;

              }

            }
        resultIndex = JSON.stringify(resultIndex);


        var HiddenIndex = document.querySelectorAll( ".itemSave" );
          for (var i = 0; i < HiddenIndex.length; i++) {
            HiddenIndex[i].value = resultIndex;
            console.log(HiddenIndex[i].value);
          }

          console.log(ResultFirstForm1.value);

      }, true);


  var resultForm2 = "";
  var IndexFirstForm2 = document.querySelector(".indexFirstForm2");
      if(IndexFirstForm2.value ){
        resultForm2 = IndexFirstForm2.value;

      }
  var ResultFirstForm2 = document.querySelector( ".resultFirstForm2" );
  ResultFirstForm2.value = resultForm2;

  var IndexFirstForm2 = document.querySelector(".indexFirstForm2");
    IndexFirstForm2.addEventListener("blur", function( event ) {
        var resultForm2 = this.value;
        var ResultFirstForm2 = document.querySelector( ".resultFirstForm2" );
        ResultFirstForm2.value = resultForm2;
        var resultIndex = {};
        var FormIndex = document.querySelectorAll(".champFormIndex");
        for (var i = 0; i < FormIndex.length; i++) {
              if(FormIndex[i].value ){
                resultIndex[FormIndex[i].name] = FormIndex[i].value;

              }

            }
        resultIndex = JSON.stringify(resultIndex);


        var HiddenIndex = document.querySelectorAll( ".itemSave" );
          for (var i = 0; i < HiddenIndex.length; i++) {
            HiddenIndex[i].value = resultIndex;
          

          }


      }, true);






  var resultIndex = {};
  var FormIndex = document.querySelectorAll(".champFormIndex");
  for (var i = 0; i < FormIndex.length; i++) {
        if(FormIndex[i].value ){
          resultIndex[FormIndex[i].name] = FormIndex[i].value;

        }

      }
  resultIndex = JSON.stringify(resultIndex);


  var HiddenIndex = document.querySelectorAll( ".itemSave" );
    for (var i = 0; i < HiddenIndex.length; i++) {
      HiddenIndex[i].value = resultIndex;

    }

    var FormIndex = document.querySelectorAll( ".champFormIndex" );

    for (var i = 0; i < FormIndex.length; i++) {
            FormIndex[i].addEventListener("blur", function( event ) {
              var HiddenIndex = document.querySelector( ".itemSave" );
                for (var i = 0; i < HiddenIndex.length; i++) {
                  resultIndex = HiddenIndex[i].value ;
                  resultIndex = JSON.parse(resultIndex);
                  resultIndex[this.name] = this.value;
                  resultIndex = JSON.stringify(resultIndex);
                  HiddenIndex[i].value = resultIndex;

                }
            }, true);
          }
