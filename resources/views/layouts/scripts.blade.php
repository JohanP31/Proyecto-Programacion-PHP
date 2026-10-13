<!-- Vendor JS Files -->
<script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.j') }}s"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
<script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

<!-- Template Main JS File -->
<script src="{{ asset('assets/js/main.js') }}"></script>

<script src="{{ asset('libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('libs/sweetalert2/sweetalert2.js') }}"></script>

@if (session()->has('message'))
    <script>
        const message = @json(session('message'));

        Swal.fire({

            title: message.content,
            icon: message.type,
        });
    </script>
@endif


@if ($errors->any())
    <script>
        const errors = @json($errors->all());

        Swal.fire({

            title: "Error",
            icon: "error",
            html: `<ul> @foreach ($errors->all() as $error) <li class="alert alert-danger"> {{ $error }}</li> @endforeach </ul>`
        });
    </script>
@endif
