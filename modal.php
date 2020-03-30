<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <button onclick="nice()" class="btn1"><i class="fa fa-trash"></i> </button>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function nice() {
            swal({
                    title: 'Please Check Your Mail!',
                    text: 'Enter Your One Time Password (OTP)',
                    content: "input",
                    button: {
                        text: "Confirm!",
                        closeModal: false,
                    },
                })
                .then(otp => {
                    if (otp != 1234) {
                        swal("Please Enter Correct OTP", {
                            icon: "warning",
                            title: 'Please Check Your Mail!',
                            text: 'Enter Your One Time Password (OTP)',
                            content: "input",
                            button: {
                                text: "Confirm!",
                                closeModal: false,
                            },
                        }).then(otp => {
                            if (otp != 1234) {
                                swal("Unsuccessful", "Please Try Again!", "error");
                            }
                            if (otp == 1234) {
                                swal("Successful!", {
                                    icon: "success"
                                });
                            }
                        })
                    }
                    if (otp == 1234) {
                        swal("Successful!", {
                            icon: "success"
                        });
                    }
                })
            // ${otp}
        }
    </script>
</body>

</html>