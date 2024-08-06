<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>الخدمات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
        html,
        body,
        .intro {
            height: 100%;
        }

        table td,
        table th {
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }

        .card {
            border-radius: .5rem;
        }

        .mask-custom {
            background: rgba(24, 24, 16, .2);
            border-radius: 2em;
            backdrop-filter: blur(25px);
            border: 2px solid rgba(255, 255, 255, 0.05);
            background-clip: padding-box;
            box-shadow: 10px 10px 10px rgba(46, 54, 68, 0.03);
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">تطبيق عمق</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    @if (Auth()->user()->role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('userProvider') }}">مقدمين الخدمات</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">الخدمات</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products') }}">المنتجات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('signout') }}">
                            <button type="button" class="btn btn-outline-danger">تسجيل الخروج</button>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="intro">
        <div class="bg-image h-100" style="background-color: #6095F0;">
            <div class="mask d-flex align-items-center h-100">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <div class="card shadow-2-strong" style="background-color: #f5f7fa;">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col">
                                                        <button type="button" class="btn btn-primary"
                                                            data-toggle="modal" data-target="#addServiceModal">
                                                            اضافة خدمة جديدة
                                                        </button>
                                                    </th>
                                                    {{-- model --}}
                                                    <div class="modal fade" id="addServiceModal" tabindex="-1"
                                                        role="dialog" aria-labelledby="addServiceModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="addServiceModalLabel">
                                                                        اضافة خدمة جديدة</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <!-- Your form goes here -->
                                                                    <form id="addServiceForm"
                                                                        action="{{ route('addServices') }}"
                                                                        method="POST" enctype="multipart/form-data">
                                                                        @csrf
                                                                        <!-- Form fields go here -->
                                                                        <div class="form-group">
                                                                            <label for="serviceName">اسم الخدمة</label>
                                                                            <input type="text" class="form-control"
                                                                                id="serviceName" name="name" required
                                                                                placeholder="ادخل اسم الخدمة">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="serviceName">وصف الخدمة</label>
                                                                            <input type="text" class="form-control"
                                                                                id="serviceName" name="description"
                                                                                required placeholder="ادخل وصف الخدمة">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="serviceName">صورة الخدمة</label>
                                                                            <input type="file" name="image"
                                                                                required class="form-control"
                                                                                id="serviceName">
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-dismiss="modal">اغلاق</button>
                                                                            <button type="submit"
                                                                                class="btn btn-primary">حفظ</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </tr>
                                                <tr>
                                                    <th scope="col">الاسم</th>
                                                    <th scope="col">الوصف</th>
                                                    <th scope="col">الصورة</th>
                                                    <th scope="col">العمليات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($services as $service)
                                                    <tr>
                                                        <td>{{ $service->name }}</td>
                                                        <td>{{ $service->description }}</td>
                                                        <td>
                                                            <img width="100px" height="100px"
                                                                style="object-fit: cover"
                                                                src="{{ asset('uploads/' . $service->image) }}"
                                                                alt="Service Image">
                                                        </td>
                                                        <td>
                                                            <form action="{{ route('deleteService', $service->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-danger btn-sm px-3">
                                                                    مسح
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
