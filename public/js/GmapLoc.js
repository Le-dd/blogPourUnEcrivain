var valide =document.getElementById('map')
if(valide ){

function downloadUrl(url,callback) {
 var request = window.ActiveXObject ?
     new ActiveXObject('Microsoft.XMLHTTP') :
     new XMLHttpRequest;

 request.onreadystatechange = function() {
   if (request.readyState == XMLHttpRequest.DONE) {
     if(this.status === 200){
       callback(JSON.parse(this.responseText));

     }else{
       console.log("Statut:" + this.status);
     }
   }
 };

 request.open('GET', url, true);
 request.send(null);
}


function initMap() {
        var Alaska = {lat: 65.324558 , lng: -152.896029,};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 5,
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

        var markers = downloadUrl("http://localhost:8000/request/locationAll",function(response) {
        response = Object.values(response);

        var Markers =[];
        var firstPoint;
        var lastPoint;
        var roadPoints=[];
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0; i < response.length; i++) {

          var latlng ={lat: parseFloat(response[i]["latitude"]) , lng: parseFloat(response[i]["longitude"]),};
          var loc = new google.maps.LatLng(parseFloat(response[i]["latitude"]), parseFloat(response[i]["longitude"]));
          bounds.extend(loc);

          var marker = new google.maps.Marker({
            position: latlng,
            map: map,
            title:response[i]["nameLocality"],
            icon: "http://localhost:8000/images/icone-walker1.png"
            });
          marker['MyValue'] = response[i]["id"];
          Markers.push(marker);

          var lasteId = response.length-1;
          if(response[i] == response[0]){
            firstPoint = loc;
          }else if (response[i] == response[lasteId]) {

            lastPoint = loc;
          }else {
            roadPoints.push({
              location: loc,
              stopover: true
            })
          }

        }

        map.fitBounds(bounds);
        map.panToBounds(bounds);


            var directionsService = new google.maps.DirectionsService();
            var directionsDisplay = new google.maps.DirectionsRenderer({
              map: map
            });


            var request = {
              origin: firstPoint,
              destination: lastPoint,
              waypoints: roadPoints,
              travelMode: google.maps.DirectionsTravelMode.DRIVING,
              unitSystem: google.maps.DirectionsUnitSystem.METRIC
            };


            directionsService.route(request, function(result, status) {

              if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(result);
                directionsDisplay.suppressMarkers = false;
                directionsDisplay.setOptions({
                    polylineOptions: {strokeColor: '#26C64F' },
                    preserveViewport: true

                });
              }


            });

            return  OnClikMarkers(Markers,directionsDisplay);
      });

      function OnClikMarkers(arrayMarkers,directionsDisplay){

        for (var i = 0; i < arrayMarkers.length; i++) {
          arrayMarkers[i].addListener('click', function() {

            var url = "http://localhost:8000/request/locationAll/"+ this['MyValue'];
            directionsDisplay.setMap(null);
            for (var i = 0; i < arrayMarkers.length; i++) {
               arrayMarkers[i].setMap(null);
             }
            var markerPost = downloadUrl(url,function(response) {
              response = Object.values(response);

              var Markers =[];
              var bounds = new google.maps.LatLngBounds();
              for (var i = 0; i < response.length; i++) {

                var latlng ={lat: parseFloat(response[i]["latitude"]) , lng: parseFloat(response[i]["longitude"]),};
                var loc = new google.maps.LatLng(parseFloat(response[i]["latitude"]), parseFloat(response[i]["longitude"]));
                bounds.extend(loc);

                var marker = new google.maps.Marker({
                  position: latlng,
                  map: map,
                  title:response[i]["namePlace"],
                  icon: "http://localhost:8000/images/icone-walker1.png"
                  });
                marker['MyValueId'] = response[i]["id"];
                marker['MyValueSlug'] = response[i]["slug"];
                Markers.push(marker);

                var lasteId = response.length-1;


              }

              map.fitBounds(bounds);
              map.panToBounds(bounds);
              return  RedirectMarker(Markers);
            });
          });
        }
      }
      function RedirectMarker(arrayMarkers){
        for (var i = 0; i < arrayMarkers.length; i++) {
          arrayMarkers[i].addListener('click', function() {

            window.location.href ="http://localhost:8000/posts/"+this['MyValueSlug']+"-"+this['MyValueId'];

          });
        }
      }
}
}
