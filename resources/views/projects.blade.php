<html>
    <head>
        <title>Projects</title>
    </head>

    <body>
        @foreach ($projects as $project)
            <li>{{ $project->title }}</li>
        @endforeach
    </body>
</html>

