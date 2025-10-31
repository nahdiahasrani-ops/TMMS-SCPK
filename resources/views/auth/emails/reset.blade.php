<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        /* Tailwind CSS styles */
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f3f4f6; /* Tailwind gray-100 */
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #067214; /* Tailwind blue-500 */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #063f15; /* Tailwind blue-600 */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-2xl font-bold text-center">Reset Password</h1>
        <p class="mt-4 text-gray-600">Kami menerima permintaan untuk mereset password Anda. Klik tombol di bawah ini untuk melanjutkan:</p>
        <a href="{{ route('reset.password', $token) }}" class="button">Reset Password</a>
        <p class="mt-4 text-gray-600">Jika Anda tidak meminta reset password, abaikan email ini.</p>
        <p class="mt-4 text-gray-600">Terima kasih,<br>Tim Kami</p>
    </div>
</body>
</html>
