<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
  <title>Leaflet Map</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <style>
    #map {
      height: 400px;
    }
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
    }
    #save {
      background: none padding-box rgb(255, 255, 255);
      display: table-cell;
      padding: 16px;
      color: rgb(86, 86, 86);
      height: 40px;
      font-family: Roboto, Arial, sans-serif;
      font-size: 18px;
      border-radius: 3px;
      box-shadow: rgb(0 0 0 / 30%) 0px 1px 4px -1px;
      border: 0px;
      cursor: pointer;
      font-weight: 500;
      line-height: 10px;
      width: 170px;
    }
    #save:hover {
      background-color: #EBEBEB;
    }
  </style>
<div x-data="{ state: $wire.entangle('{{ $getStatePath('latt_long') }}') }">
       <input value="{{ $getRecord()->latitude }}" id="latt" hidden>
       <input value="{{ $getRecord()->longitude }}" id="long" hidden>
  <div wire:ignore>
    <div id="map"></div>
  </div>
 
  <div style="bottom: 10%; left: 0.7%;">
    <input id="save" value="Get Coordinates" type="button">
  </div>
  <input x-model="state" id="resultInput" hidden/>

  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
  <script src="https://unpkg.com/leaflet-geosearch/dist/bundle.min.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch/dist/geosearch.css" />
  <script>
    function initializeMap() {
      var long = parseFloat(document.getElementById('long').value);
      var latt = parseFloat(document.getElementById('latt').value);
      var map, marker;

      // Initialize the map
      if (latt !== 0 && long !== 0) {
        map = L.map('map').setView([latt, long], 13);
        marker = L.marker([latt, long]).addTo(map);
        console.log("ok");
      } else {
        map = L.map('map').setView([34.4164665682985, 35.83093564206633], 13);
      }

      // Add OpenStreetMap tiles
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
      }).addTo(map);

      // Click event to place a marker
      map.on('click', function(e) {
        if (marker) {
          map.removeLayer(marker);
        }
        marker = L.marker(e.latlng).addTo(map);
      });

      // Save coordinates of the marker
      document.getElementById('save').onclick = function() {
        if (marker) {
          const latLng = marker.getLatLng();
          const resultInput = document.getElementById('resultInput');
          resultInput.value = JSON.stringify(latLng);
          resultInput.dispatchEvent(new Event('input'));
          alert(`Latitude: ${latLng.lat}, Longitude: ${latLng.lng}`);
        } else {
          alert('No marker placed. Click on the map to place a marker first.');
        }
      };

      // Use Leaflet GeoSearch for search suggestions
      const provider = new window.GeoSearch.OpenStreetMapProvider();
      const searchControl = new window.GeoSearch.GeoSearchControl({
        provider: provider,
        style: 'bar',
        showMarker: true,
        retainZoomLevel: false,
        animateZoom: true,
        autoClose: true,
        searchLabel: 'Enter address',
        keepResult: true
      });

      map.addControl(searchControl);

      // Automatically add marker for search results
      searchControl.on('result', function(data) {
        if (marker) {
          map.removeLayer(marker);
        }
        marker = L.marker(data.location).addTo(map);
        map.setView(data.location, 13);
      });
    }

    document.addEventListener('DOMContentLoaded', initializeMap);
  </script>
</div>
</x-dynamic-component>
