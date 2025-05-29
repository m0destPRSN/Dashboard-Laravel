@include('head.head_doc')
<body class="bg-light">
@include('header.header', ['icon' => 'map', 'iconLink' => url('/map'), 'query' => request('query')])





@include('footer.footer')
</body>
</html>
