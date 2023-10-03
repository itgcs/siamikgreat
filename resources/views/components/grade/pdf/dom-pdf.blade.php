<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <title>Print Pdf - {{ $data->name }} {{ $data->class }}</title>
    <style>
        a {
            text-decoration: none !important;
            color: black;
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            font-size: 12pt;
            width: 100%;
        }

        .text-center {
            text-align: center;
            margin: 10px 0px 100px 0px;
        }

        th,
        td {
            text-align: left;
            padding: 5px;
        }

        .colspan {
            width: 15px;
        }

    </style>
</head>

<body>
    <div class="container">
        <div class="text-center my-5">
            <h2>Data on {{strtolower($data->name)}} {{$data->class}}</h2>
            <h2><a target="_blank" href="https://great.sch.id/">GREAT CRYSTAL SCHOOL AND COURSE</a></h2>
        </div>

        <p class="mt-5">Print date: {{date('d/m/Y')}}</p>
        <table>
            <thead>
                <tr>
                    <th class="colspan">No</th>
                    <th>Name</th>
                    <th>Unique ID</th>
                    <th>Gender</th>
                    <th>Religion</th>
                    <th>Place birth</th>
                    <th>Date Birth</th>
                    <th>Age</th>
                    <th>Nationality</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data->student as $el)

                <tr>
                    <td>{{$loop->index + 1}}</td>
                    <td>{{$el->name}}</td>
                    <td>{{$el->unique_id}}</td>
                    <td>{{$el->gender}}</td>
                    <td>{{$el->religion}}</td>
                    <td>{{$el->place_birth}}</td>
                    @php
                    $explode = explode('-', $el->date_birth);
                    $date_birth = $explode[2].'-'.$explode[1].'-'.$explode[0];
                    $currentDate = date('Y-m-d');
                    $dateDiff = date_diff(date_create($el->date_birth), date_create($currentDate));
                    $age = $dateDiff->format('%y');

                    @endphp
                    <td>{{$date_birth}}</td>
                    <td>{{$age}}</td>
                    <td>{{$el->nationality}}</td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </div>

    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script> --}}
</body>

</html>
