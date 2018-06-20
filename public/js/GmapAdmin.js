var valide =document.getElementById('mapAdmin');
if(valide ){


function initMap() {

        var Alaska = {lat: 65.324558 , lng: -152.896029,};
        var map3 = new google.maps.Map(document.getElementById('mapAdmin'), {
          zoom: 9,
          center: Alaska,
          disableDefaultUI: true,
          styles: [
                    {
                      "elementType": "geometry",
                      "stylers": [
                        {
                          "color": "#f5f5f5"
                        }
                      ]
                    },
                    {
                      "elementType": "labels.icon",
                      "stylers": [
                        {
                          "visibility": "off"
                        }
                      ]
                    },
                    {
                      "elementType": "labels.text.fill",
                      "stylers": [
                        {
                          "color": "#616161"
                        }
                      ]
                    },
                    {
                      "elementType": "labels.text.stroke",
                      "stylers": [
                        {
                          "color": "#f5f5f5"
                        }
                      ]
                    },
                    {
                      "featureType": "administrative.land_parcel",
                      "elementType": "labels",
                      "stylers": [
                        {
                          "visibility": "off"
                        }
                      ]
                    },
                    {
                      "featureType": "administrative.land_parcel",
                      "elementType": "labels.text.fill",
                      "stylers": [
                        {
                          "color": "#bdbdbd"
                        }
                      ]
                    },
                    {
                      "featureType": "poi",
                      "elementType": "geometry",
                      "stylers": [
                        {
                          "color": "#eeeeee"
                        }
                      ]
                    },
                    {
                      "featureType": "poi",
                      "elementType": "labels.text",
                      "stylers": [
                        {
                          "visibility": "off"
                        }
                      ]
                    },
                    {
                      "featureType": "poi",
                      "elementType": "labels.text.fill",
                      "stylers": [
                        {
                          "color": "#757575"
                        }
                      ]
                    },
                    {
                      "featureType": "poi.park",
                      "elementType": "geometry",
                      "stylers": [
                        {
                          "color": "#62a29a"
                        }
                      ]
                    },
                    {
                      "featureType": "poi.park",
                      "elementType": "labels.text.fill",
                      "stylers": [
                        {
                          "color": "#9e9e9e"
                        }
                      ]
                    },
                    {
                      "featureType": "road",
                      "elementType": "geometry",
                      "stylers": [
                        {
                          "color": "#cbded6"
                        }
                      ]
                    },
                    {
                      "featureType": "road.arterial",
                      "elementType": "labels",
                      "stylers": [
                        {
                          "visibility": "off"
                        }
                      ]
                    },
                    {
                      "featureType": "road.arterial",
                      "elementType": "labels.text.fill",
                      "stylers": [
                        {
                          "color": "#757575"
                        }
                      ]
                    },
                    {
                      "featureType": "road.highway",
                      "elementType": "geometry",
                      "stylers": [
                        {
                          "color": "#a7cdb6"
                        }
                      ]
                    },
                    {
                      "featureType": "road.highway",
                      "elementType": "labels",
                      "stylers": [
                        {
                          "visibility": "off"
                        }
                      ]
                    },
                    {
                      "featureType": "road.highway",
                      "elementType": "labels.text.fill",
                      "stylers": [
                        {
                          "color": "#616161"
                        }
                      ]
                    },
                    {
                      "featureType": "road.local",
                      "stylers": [
                        {
                          "visibility": "off"
                        }
                      ]
                    },
                    {
                      "featureType": "road.local",
                      "elementType": "labels",
                      "stylers": [
                        {
                          "visibility": "off"
                        }
                      ]
                    },
                    {
                      "featureType": "road.local",
                      "elementType": "labels.text.fill",
                      "stylers": [
                        {
                          "color": "#9e9e9e"
                        }
                      ]
                    },
                    {
                      "featureType": "transit.line",
                      "elementType": "geometry",
                      "stylers": [
                        {
                          "color": "#e5e5e5"
                        }
                      ]
                    },
                    {
                      "featureType": "transit.station",
                      "elementType": "geometry",
                      "stylers": [
                        {
                          "color": "#eeeeee"
                        }
                      ]
                    },
                    {
                      "featureType": "water",
                      "elementType": "geometry",
                      "stylers": [
                        {
                          "color": "#b9d1db"
                        }
                      ]
                    },
                    {
                      "featureType": "water",
                      "elementType": "labels.text.fill",
                      "stylers": [
                        {
                          "color": "#9e9e9e"
                        }
                      ]
                    }
                  ]

        });

        var params = document.getElementById('mapAdmin');
        var latitude = parseFloat(params.getAttribute("data-lat")) ;
        var longitude = parseFloat(params.getAttribute('data-lng'));
        var title = params.getAttribute("data-title");

        if(latitude && longitude && title){
          var latlng1 = {lat: latitude , lng: longitude,};
          if(map3['OldMarker']){
            map3['OldMarker'].setMap(null);
          }
          var marker = new google.maps.Marker({
                   position: latlng1,
                   map: map3,
                   title:title,
                   icon: "http://localhost:8000/images/icone-walker1.png"
          });
          map3.panTo(latlng1);
          map3['OldMarker'] = marker;
          map3['Oldlatitude'] = latitude;
          map3['Oldlongitude'] = longitude;
          map3['Oldtitle'] = title;

        }else{
          map3['Oldtitle'] = "pas de titre ";
        }


        map3.addListener('click', function(event) {

          if(map3['OldMarker']){
            map3['OldMarker'].setMap(null);
          }
          var marker = new google.maps.Marker({
                   position: event.latLng,
                   map: map3,
                   title: map3['Oldtitle'],
                   icon: "http://localhost:8000/images/icone-walker1.png"
          });
          map3.panTo(event.latLng);
          map3['OldMarker'] = marker;
          map3['Oldlatitude'] = event.latLng.lat();
          map3['Oldlongitude'] = event.latLng.lng();
          var Latitude2 = document.querySelector(".Latitude");
          var Longitude2 = document.querySelector(".longitude");
          Latitude2.value = map3['Oldlatitude'].toString();
          Longitude2.value = map3['Oldlongitude'].toString();
        });

      var Latitude3 = document.querySelector(".Latitude");
      Latitude3.addEventListener("blur", function( event ) {
          if(map3['OldMarker']){
            map3['OldMarker'].setMap(null);
          }
          var newLatitude = parseFloat(this.value);
          var latlng1 = {lat: newLatitude , lng: map3['Oldlongitude'],};
          var marker = new google.maps.Marker({
                   position: latlng1,
                   map: map3,
                   title: map3['Oldtitle'],
                   icon: "http://localhost:8000/images/icone-walker1.png"
          });
          map3.panTo(latlng1);
          map3['OldMarker'] = marker;
          map3['Oldlatitude'] = newLatitude;

      });
      var Longitude3 = document.querySelector(".longitude");
      Longitude3.addEventListener("blur", function( event ) {
        if(map3['OldMarker']){
          map3['OldMarker'].setMap(null);
        }
        var newlongitude = parseFloat(this.value);
        var latlng1 = {lat: map3['Oldlatitude'] , lng: newlongitude,};
        var marker = new google.maps.Marker({
                 position: latlng1,
                 map: map3,
                 title: map3['Oldtitle'],
                 icon: "http://localhost:8000/images/icone-walker1.png"
        });
        map3.panTo(latlng1);
        map3['OldMarker'] = marker;
        map3['Oldlongitude'] = newlongitude;

      });

      var title3 = document.querySelector(".title");
      title3.addEventListener("blur", function( event ) {
        if(map3['OldMarker']){
          map3['OldMarker'].setMap(null);
        }
        var newlongitude = parseFloat(this.value);
        var latlng1 = {lat: map3['Oldlatitude'] , lng: map3['Oldlongitude'],};
        var marker = new google.maps.Marker({
                 position: latlng1,
                 map: map3,
                 title: this.value,
                 icon: "http://localhost:8000/images/icone-walker1.png"
        });
        map3['OldMarker'] = marker;
        map3['Oldtitle'] = this.value;
      });


}
}else{
  function initMap() {


      }
}
