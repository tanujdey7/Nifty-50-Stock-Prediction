<html>
<head>
    <title>lol</title>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>
async function f() {

const { value: password } = await Swal.fire({
                        title: 'Enter your password',
                        input: 'password',
                        inputPlaceholder: 'Enter your password',   
                        inputAttributes: {
                            autocapitalize: 'off',
                            autocorrect: 'off'
                        }
                        })

if (password) {
Swal.fire(`Your IP address is ${password}`)
}
}

f();
</script>
lol
</body>
</html>