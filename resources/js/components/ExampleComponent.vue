<template>
  <div>
    
 <input  style="width:300px" ref="autocomplete" 
        placeholder="Search" 
        class="search-location"
        onfocus="value = ''" 
        type="text" />

    <div class="google-map" ref="googleMap"></div>
    
    <template v-if="Boolean(this.google) && Boolean(this.map)">
      <slot
        :google="google"
        :map="map"
      />
      
    </template>

    <div v-if="errorStr">
    Sorry, but the following error
    occurred: {{errorStr}}
  </div>
  
  <div v-if="gettingLocation">
    <i>Getting your location...</i>
  </div>
  
  <div v-if="location">
    Your location data is {{ location.coords.latitude }}, {{ location.coords.longitude}}
  </div>

  </div>
  
</template>


 <style>
    .google-map{
        height: 1000px;
        width: 1000px;
    }
    </style>

<script>   

import GoogleMapsApiLoader from 'google-maps-api-loader'

export default {

  data() {
    return {
      google: null,
      map: null,
      apiKey: "AIzaSyB67nVOFrwFuONhS_4P_fyCpF5JyIw9CcQ",
       location:null,
       gettingLocation: false,
      errorStr:null,
      origin:null,
      destination:null,
      search:null,

    }
  },
  created() {
    this.Actual();
  },
  async mounted() { 
    // Inicialiar Map
    const googleMapApi = await GoogleMapsApiLoader({
      apiKey: this.apiKey,
      libraries: ["places"]
      })
    this.google = googleMapApi
    this.initializeMap()
    // Iniciar autocomplete
     this.autocomplete = new google.maps.places.Autocomplete(
      (this.$refs.autocomplete),
      {types: ['geocode']}
    );
    // Metodo para dibujar el 2 marcador y trazar la ruta
    this.To()
    
  },

  methods: {
    // Ubicacion actual
    Actual(){
//do we support geolocation
    if(!("geolocation" in navigator)) {
      this.errorStr = 'Geolocation is not available.';
      return;
    }
     this.gettingLocation = true;
    // get position
    navigator.geolocation.getCurrentPosition(pos => {
      this.gettingLocation = false;
      this.location = pos;


 var myLatLng = {
        lat: this.location.coords.latitude,
         lng: this.location.coords.longitude
         }
         var marker = new google.maps.Marker({
         position: myLatLng,
         map: this.map,
         title: "Raul!",
         icon: {
          url: "http://maps.google.com/mapfiles/kml/shapes/play.png"
         }
         })
    // this.drawRoute(myLatLng)
    }, err => {
      this.gettingLocation = false;
      this.errorStr = err.message;
    })

    //
    },
    // Trazar ruta y segundo marcador
   To()
   {
    this.autocomplete.addListener('place_changed', () => {
  let place = this.autocomplete.getPlace();
  let ac = place.address_components;
  let lat = place.geometry.location.lat();
  let lon = place.geometry.location.lng();
  let city = ac[0]["short_name"];

  console.log(`Ciudad ${city} latitud ${lat}, longitud: ${lon}`);
  console.log(`Si funca`);

  var myLatLng2 = {
        lat: lat,
         lng: lon
         }

         console.log(myLatLng2)
     this.marker(myLatLng2);

     var myLatLng = {
        lat: this.location.coords.latitude,
         lng: this.location.coords.longitude
         }
     this.drawRoute(myLatLng,myLatLng2)
});
   },
  //  Iniciar Mapa
    initializeMap() {
      const mapContainer = this.$refs.googleMap
      this.map = new this.google.maps.Map(mapContainer,{
          center:{lat:23.6345005, lng: -102.5527878},
          zoom:6
      });
      
    },
    // Marcadores
    marker(position){
         var marker = new google.maps.Marker({
         position: position,
         map: this.map,
         title: "Raul!",
         icon: {
      url: "http://maps.google.com/mapfiles/kml/shapes/poi.png"
    }
       });
     
     },
    //  Pintar
   drawRoute(position,position2){
      var directionsService = new google.maps.DirectionsService();
      // var directionsRenderer = new google.maps.DirectionsRenderer();
      var polylineOptionsActual = new google.maps.Polyline({
      strokeColor: '#572364 ',
      strokeOpacity: 1.0,
      strokeWeight: 10
      });
      var directionsRenderer = new google.maps.DirectionsRenderer({polylineOptions: polylineOptionsActual});    

      directionsRenderer.setMap(this.map);
      directionsService.route(
      {
        origin: position,
        destination: position2,
        travelMode: 'DRIVING'
      },
      function(response, status) {
        if (status === 'OK') {
          directionsRenderer.setDirections(response);
        } else {
          window.alert('Directions request failed due to ' + status);
        }
      });
    },
    // Autocompletado
    // Autocomplete(){
    //   // Cree el objeto de autocompletado, restringiendo la búsqueda
    // var autocomplete = new google.maps.places.Autocomplete(
    //  (this.$refs.infowindowc),
    //   { types: ['geocode'] });
    //     console.log(this.search);
    // }
  }
};

</script>