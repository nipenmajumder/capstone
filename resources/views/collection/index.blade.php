<x-app-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css"/>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="max-w-7xl mx-auto">
                        <div id="map" style="width: 1000px; height: 600px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
        function getRandomCoordinate($min, $max) {
            return $min + mt_rand() / mt_getrandmax() * ($max - $min);
        }

        $dustbins = [];
        for ($i = 1; $i <= 5; $i++) {
            $dustbins[] = [
                'id' => $i,
                'latitude' => getRandomCoordinate(23.7000, 23.9000),
                'longitude' => getRandomCoordinate(90.3000, 90.5000)
            ];
        }
    @endphp
    <script>
        let map, userLocation, routingControl;

        initializeMap();
        getUserLocation();
        markDustbins();

        function initializeMap() {
            map = L.map('map').setView([23.8118, 90.3571], 10);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            map.on('click', e => {
                L.popup()
                    .setLatLng(e.latlng)
                    .setContent(`You clicked the map at ${e.latlng.toString()}`)
                    .openOn(map);
            });
        }

        function getUserLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    position => {
                        const {latitude, longitude} = position.coords;
                        userLocation = L.latLng(latitude, longitude);
                        map.setView([latitude, longitude], 13);
                        L.marker([latitude, longitude]).addTo(map)
                            .bindPopup('You are here!')
                            .openPopup();
                    },
                    error => console.log(getGeolocationError(error.code))
                );
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function getGeolocationError(code) {
            const errors = {
                1: "User denied the request for Geolocation.",
                2: "Location information is unavailable.",
                3: "The request to get user location timed out.",
                0: "An unknown error occurred."
            };
            return errors[code];
        }

        function markDustbins() {
            @foreach($dustbins as $dustbin)
            L.marker([{{ $dustbin['latitude'] }}, {{ $dustbin['longitude'] }}]).addTo(map)
                .bindPopup(`Dustbin ID: {{ $dustbin['id'] }}`)
                .on('popupopen', () => showRoute(L.latLng({{ $dustbin['latitude'] }}, {{ $dustbin['longitude'] }})));
            @endforeach
    }

    function showRoute(destination) {
        if (!userLocation) {
            console.log("User location not available.");
            return;
        }

        if (routingControl) {
            map.removeControl(routingControl);
        }

        routingControl = L.Routing.control({
            waypoints: [userLocation, destination],
            routeWhileDragging: true
        }).addTo(map);
    }
</script>
</x-app-layout>
