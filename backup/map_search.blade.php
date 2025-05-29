@include('head.head_doc')
<body>
@include('map.header')

<gmp-map
        center="{{ $center ?? '50.4501, 30.5234' }}"
        zoom="10"
        map-id="DEMO_MAP_ID"
        style="height: calc(100vh - 80px)"
>
    @if(!empty($locations) && count($locations) > 0)
        @foreach($locations as $location)
            <gmp-advanced-marker
                    position="{{ $location->location }}"
                    title="{{ $location->name ?? 'Локація' }}"
            ></gmp-advanced-marker>
        @endforeach
    @endif
</gmp-map>


@include('footer.footer')

</body>
