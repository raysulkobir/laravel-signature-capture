<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}">
    <title>Signature Pad</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <style>
        .signature-container {
            text-align: center;
        }

        .signature-pad {
            border: 2px solid #000;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="jumbotron mt-5 text-center">
            <h1 class="display-6 text-center">Signature Pad</h1>
            <p class="lead text-center">Please provide your signature using the canvas below. Once you're satisfied, click 'Save' to submit your signature. If needed, click 'Clear' to reset the canvas and start over. This conveys the instructions clearly and is user-friendly. Let me know if you'd like to adjust the wording further!</p>

            <div class="alert alert-success"></div>

            <div class="signature-container">
                <canvas id="signature-pad" class="signature-pad" width="400" height="200"></canvas>
            </div>

            <div class="btn-group">
                <button class="btn btn-primary text-green-400" id="save">Save</button>
                <button class="btn btn-secondary btn btn-info" id="clear">Clear</button>
            </div>
        </div>
    </div>

    <!-- JS dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>

    <script>
        $(function () {
            // Setup CSRF Token for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            var canvas = document.getElementById('signature-pad');
            var signaturePad = new SignaturePad(canvas);

            // Save button functionality
            $('#save').on('click', function () {
                if (signaturePad.isEmpty()) {
                    alert('Please provide a signature first.');
                    return;
                }

                $.ajax({
                    url: "{{ url('/signature') }}",
                    method: 'POST',
                    data: {
                        signature: signaturePad.toDataURL('image/png')
                    },
                    success: function (response) {
                        $('.alert-success').text(response.success).fadeIn();
                    },
                    error: function (error) {
                        alert('Something went wrong. Please try again.');
                    }
                });
            });

            // Clear button functionality
            $('#clear').on('click', function () {
                signaturePad.clear();
                $('.alert-success').fadeOut();
            });
        });
    </script>

</body>

</html>
