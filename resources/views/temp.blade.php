


<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  

    @if (session('pyamentSuccess'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'payment successful !',
            text: '{{ session('pyamentSuccess ') }}',
            confirmButtonText: 'OK'
        });
    </script>
@endif
</body>
</html>
