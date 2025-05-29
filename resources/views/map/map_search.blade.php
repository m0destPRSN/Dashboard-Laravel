@include('head.head_doc')
<body xmlns="">
@include('header.header', ['icon' => 'browser', 'iconLink' => url('/main'), 'query' => request('query')])


<div id="map" style="height: calc(100vh - 80px)"></div>

<script>
    let map;
    let infoWindow;
    const locationsData = @json($locations ?? []);
    const centerCoordinates = ("{{ $centerMapOn ?? '50.4501,30.5234' }}").split(',').map(Number);

    window.initMap = function initMap() {
        const mapOptions = {
            zoom: locationsData.length > 0 ? 10 : 6,
            center: { lat: centerCoordinates[0], lng: centerCoordinates[1] }
        };
        map = new google.maps.Map(document.getElementById("map"), mapOptions);
        infoWindow = new google.maps.InfoWindow();

        if (locationsData && locationsData.length > 0) {
            locationsData.forEach(location => {
                let position;
                if (location.latitude && location.longitude) {
                    position = { lat: parseFloat(location.latitude), lng: parseFloat(location.longitude) };
                } else if (location.location && typeof location.location === 'string' && location.location.includes(',')) {
                    const coords = location.location.split(',').map(Number);
                    if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
                        position = { lat: coords[0], lng: coords[1] };
                    }
                }

                if (position) {
                    const marker = new google.maps.Marker({
                        position: position,
                        map: map,
                        title: location.title || 'Маркер'
                    });

                    marker.addListener('click', () => {
                        // Використовуємо Bootstrap класи для стилізації
                        let content = '<div class="p-2" style="max-width: 250px;">'; // Додаємо обгортку з Bootstrap padding

                        // Додаємо картинку, якщо photo_path існує
                        if (location.photo_path) {
                            content += `<img src="{{ asset('storage/') }}/${location.photo_path}" alt="${location.title || 'Фото'}" class="img-fluid rounded mb-2 d-block mx-auto" style="max-height: 150px;"><br>`;
                        }

                        // Додаємо заголовок як посилання
                        if (location.id) {
                            const locationUrl = `/locations/${location.id}`;
                            content += `<h6 class="mb-1"><h5 class="text-primary" style="text-decoration: none;">${location.title || 'Деталі'}</h5></h6>`;
                        } else {
                            content += `<h6 class="mb-1">${location.title || 'Деталі'}</h6>`;
                        }

                        // Додаємо опис, якщо він існує
                        if (location.description) {
                            let descriptionText = location.description;
                            if (descriptionText.length > 100) {
                                descriptionText = descriptionText.substring(0, 100) + '...';
                            }
                            content += `<p class="small text-muted mb-0 mt-1">${descriptionText}</p>`;
                        }

                        // Додаємо кнопку "Детальніше", якщо є ID
                        if (location.id) {
                            const locationUrl = `/locations/${location.id}`;
                            content += `<a href="${locationUrl}" class="btn btn-sm btn-outline-primary mt-2 d-block" style="box-shadow: none; !important;">Детальніше</a>`;
                        }

                        content += '</div>'; // Закриваємо обгортку

                        infoWindow.setContent(content);
                        infoWindow.open(map, marker);
                    });
                }
            });
        }
    }
</script>

@include('footer.footer')




</body>
