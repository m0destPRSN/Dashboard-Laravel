@include('head.head_doc')
<body xmlns="">
@include('header.header', ['icon' => 'browser', 'iconLink' => url('/main'), 'query' => request('query')])

<div id="map" style="height: calc(100vh - 80px)"></div>

<script>
    let map;
    let infoWindow;
    const locationsData = @json($locations ?? []);
    const centerCoordinates = ("{{ $centerMapOn ?? '50.4501,30.5234' }}").split(',').map(Number);
    const mapLinksData = @json($mapLinks ?? []); // <--- ДОДАНО: Отримання даних посилань

    /**
     * Створює кастомну кнопку для карти.
     * @param {string} text - Текст на кнопці.
     * @param {string} title - Підказка, що з'являється при наведенні.
     * @param {function} onClickHandler - Функція, що викликається при кліку.
     * @return {HTMLButtonElement} - Створений елемент кнопки.
     */
    function createCustomRedirectButton(text, title, onClickHandler) {
        const controlButton = document.createElement("button");

        // Встановлюємо CSS для кнопки
        controlButton.style.backgroundColor = "#fff";
        controlButton.style.border = "1px solid #ccc";
        controlButton.style.borderRadius = "3px";
        controlButton.style.boxShadow = "0 1px 4px rgba(0,0,0,.2)";
        controlButton.style.color = "rgb(55,55,55)";
        controlButton.style.cursor = "pointer";
        controlButton.style.fontFamily = "Roboto,Arial,sans-serif";
        controlButton.style.fontSize = "14px";
        controlButton.style.lineHeight = "30px";
        controlButton.style.margin = "0";
        controlButton.style.padding = "0 10px";
        controlButton.style.textAlign = "center";
        controlButton.textContent = text;
        controlButton.title = title;
        controlButton.type = "button";
        controlButton.style.display = "block";
        controlButton.style.width = "100%";

        controlButton.addEventListener("click", onClickHandler);

        return controlButton;
    }

    window.initMap = function initMap() {
        const mapStyles = [
            { featureType: "poi", elementType: "labels", stylers: [{ visibility: "off" }] },
            { featureType: "poi", elementType: "geometry", stylers: [{ visibility: "off" }] },
            { featureType: "poi.attraction", elementType: "labels", stylers: [{ visibility: "off" }] },
            { featureType: "poi.attraction", elementType: "geometry", stylers: [{ visibility: "off" }] },
            { featureType: "poi.business", elementType: "labels", stylers: [{ visibility: "off" }] },
            { featureType: "poi.business", elementType: "geometry", stylers: [{ visibility: "off" }] },
            { featureType: "poi.government", elementType: "labels", stylers: [{ visibility: "off" }] },
            { featureType: "poi.government", elementType: "geometry", stylers: [{ visibility: "off" }] },
            { featureType: "poi.medical", elementType: "labels", stylers: [{ visibility: "off" }] },
            { featureType: "poi.medical", elementType: "geometry", stylers: [{ visibility: "off" }] },
            { featureType: "poi.park", elementType: "labels", stylers: [{ visibility: "off" }] },
            { featureType: "poi.park", elementType: "geometry", stylers: [{ visibility: "off" }] },
            { featureType: "poi.place_of_worship", elementType: "labels", stylers: [{ visibility: "off" }] },
            { featureType: "poi.place_of_worship", elementType: "geometry", stylers: [{ visibility: "off" }] },
            { featureType: "poi.school", elementType: "labels", stylers: [{ visibility: "off" }] },
            { featureType: "poi.school", elementType: "geometry", stylers: [{ visibility: "off" }] },
            { featureType: "poi.sports_complex", elementType: "labels", stylers: [{ visibility: "off" }] },
            { featureType: "poi.sports_complex", elementType: "geometry", stylers: [{ visibility: "off" }] },
        ];
        const mapOptions = {
            zoom: locationsData.length > 0 ? 10 : 6,
            center: { lat: centerCoordinates[0], lng: centerCoordinates[1] },
            mapTypeControl: false,
            mapTypeId: 'roadmap',
            styles: mapStyles,
        };
        map = new google.maps.Map(document.getElementById("map"), mapOptions);
        infoWindow = new google.maps.InfoWindow();

        // --- Початок додавання кастомних кнопок ---

        const customControlsContainer = document.createElement("div");
        customControlsContainer.style.margin = "10px";
        customControlsContainer.style.display = "flex";
        customControlsContainer.style.flexDirection = "row";
        customControlsContainer.style.gap = "5px";

        // <--- ЗМІНЕНО: Динамічне створення кнопок з mapLinksData ---
        if (mapLinksData && mapLinksData.length > 0) {
            mapLinksData.forEach(linkItem => {
                const redirectButton = createCustomRedirectButton(
                    linkItem.name, // Текст кнопки
                    linkItem.name, // Підказка
                    () => {
                        // linkItem.link вже містить шлях типу /map/search?type=1
                        window.location.href = `{{ url('') }}${linkItem.link}`;
                    }
                );
                customControlsContainer.appendChild(redirectButton);
            });
        }
        // <--- КІНЕЦЬ ЗМІНИ ---

        map.controls[google.maps.ControlPosition.TOP_LEFT].push(customControlsContainer);

        // --- Кінець додавання кастомних кнопок ---


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
                        let content = '<div class="p-2" style="max-width: 250px;">';
                        if (location.photo_paths) {
                            content += `<img src="{{ asset('storage/') }}/${location.photo_paths[0]}" alt="${location.title || 'Фото'}" class="img-fluid rounded mb-2 d-block mx-auto" style="max-height: 150px;"><br>`;
                        }
                        if (location.id) {
                            content += `<h6 class="mb-1"><h5 class="text-primary" style="text-decoration: none;">${location.title || 'Деталі'}</h5></h6>`;
                        } else {
                            content += `<h6 class="mb-1">${location.title || 'Деталі'}</h6>`;
                        }
                        if (location.description) {
                            let descriptionText = location.description;
                            if (descriptionText.length > 100) {
                                descriptionText = descriptionText.substring(0, 100) + '...';
                            }
                            content += `<p class="small text-muted mb-0 mt-1">${descriptionText}</p>`;
                        }
                        if (location.id) {
                            const locationUrl = `/locations/${location.id}`;
                            content += `<a href="${locationUrl}" class="btn btn-sm btn-outline-primary mt-2 d-block" style="box-shadow: none !important;">Детальніше</a>`;
                        }
                        content += '</div>';
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
